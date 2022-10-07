<?php
/**
 * Lógica del negocio de RegistroSgcModelo
 *
 * Este archivo se complementa con el archivo RegistroSgcControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-10-18
 * @uses RegistroSgcLogicaNegocio
 * @package RegistroControlDocumentos
 * @subpackage Modelos
 */
namespace Agrodb\RegistroControlDocumentos\Modelos;

use Agrodb\RegistroControlDocumentos\Modelos\IModelo;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Agrodb\Core\Constantes;

class RegistroSgcLogicaNegocio implements IModelo{

	private $modeloRegistroSgc = null;

	private $excelPhp = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloRegistroSgc = new RegistroSgcModelo();
		$this->excelPhp = new ReportesExcelModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new RegistroSgcModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdRegistroSgc() != null && $tablaModelo->getIdRegistroSgc() > 0){
			return $this->modeloRegistroSgc->actualizar($datosBd, $tablaModelo->getIdRegistroSgc());
		}else{
			unset($datosBd["id_registro_sgc"]);
			return $this->modeloRegistroSgc->guardar($datosBd);
		}
	}

	/**
	 * Borra el registro actual
	 *
	 * @param
	 *        	string Where|array $where
	 * @return int
	 */
	public function borrar($id){
		$this->modeloRegistroSgc->borrar($id);
	}

	public function borrarPorParametro($param, $value){
		$this->modeloRegistroSgc->borrarPorParametro($param, $value);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return RegistroSgcModelo
	 */
	public function buscar($id){
		return $this->modeloRegistroSgc->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloRegistroSgc->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloRegistroSgc->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarRegistroSgc(){
		$consulta = "SELECT * FROM " . $this->modeloRegistroSgc->getEsquema() . ". registro_sgc";
		return $this->modeloRegistroSgc->ejecutarSqlNativo($consulta);
	}

	public function buscarDivisionEstruc($areaPadre, $clasificacion = "Planta Central"){
		$consulta = "select
										*
								from
										g_estructura.area
								where
										id_area_padre = '$areaPadre' and estado=1 and clasificacion='" . $clasificacion . "'
										
							UNION
							
								select
										*
								from
										g_estructura.area
								where
										id_area = '$areaPadre' and estado=1 and id_area not in ('DE') and clasificacion='" . $clasificacion . "'
								order by
								id_area asc;";
		return $this->modeloRegistroSgc->ejecutarSqlNativo($consulta);
	}
	
	public function buscarSubProcesoEstructura($areaPadre, $clasificacion = "Planta Central"){
		
		$consulta = "select
							*
					from
							g_estructura.area
					where
							id_area_padre = '$areaPadre' and estado=1 and clasificacion='" . $clasificacion . "';";
		
		return $this->modeloRegistroSgc->ejecutarSqlNativo($consulta);
	}

	public function buscarNombreArea($area){
		$consulta = "select
										nombre
								from
										g_estructura.area
								where
										id_area = '" . $area . "'
								limit 1 ;";
		return $this->modeloRegistroSgc->ejecutarSqlNativo($consulta);
	}

	public function guardarEnlace(Array $datos){
		try{
			$this->modeloRegistroSgc = new RegistroSgcModelo();
			$proceso = $this->modeloRegistroSgc->getAdapter()
				->getDriver()
				->getConnection();
			if (! $proceso->beginTransaction()){
				throw new \Exception('No se pudo iniciar la transacción: Guardar enlace');
			}

			$tablaModelo = new RegistroSgcModelo($datos);
			$datosBd = $tablaModelo->getPrepararDatos();
			// print_r($datosBd);
			if ($tablaModelo->getIdRegistroSgc() != null && $tablaModelo->getIdRegistroSgc() > 0){
				$this->modeloRegistroSgc->actualizar($datosBd, $tablaModelo->getIdRegistroSgc());
				$idRegistro = $datosBd["id_registro_sgc"];
			}else{
				unset($datosBd["id_registro_sgc"]);
				$idRegistro = $this->modeloRegistroSgc->guardar($datosBd);
			}

			if (! $idRegistro){
				throw new \Exception('No se registo los datos en la tabla registr SGC');
			}
			// *************guadar detalle de enlace*************
			if (isset($datos['enlace_socializar'])){
				$lnegocioDetalleRegistroSgc = new DetalleRegistroSgcLogicaNegocio();
				$datos = array(
					'id_registro_sgc' => $idRegistro,
					'enlace_socializar' => $datos['enlace_socializar']);
				$statement = $this->modeloRegistroSgc->getAdapter()
					->getDriver()
					->createStatement();
				$sqlInsertar = $this->modeloRegistroSgc->guardarSql('detalle_registro_sgc', $this->modeloRegistroSgc->getEsquema());
				$sqlInsertar->columns($lnegocioDetalleRegistroSgc->columnas());
				$sqlInsertar->values($datos, $sqlInsertar::VALUES_MERGE);
				$sqlInsertar->prepareStatement($this->modeloRegistroSgc->getAdapter(), $statement);
				$statement->execute();
			}else{
				throw new \Exception('Enlace vacio..!!');
			}
			$proceso->commit();
			return $idRegistro;
		}catch (\Exception $ex){
			$proceso->rollback();
			throw new \Exception($ex->getMessage());
			return 0;
		}
	}

	public function guardarDestinatario(Array $datos){
		try{
			$this->modeloRegistroSgc = new RegistroSgcModelo();
			$proceso = $this->modeloRegistroSgc->getAdapter()
				->getDriver()
				->getConnection();
			if (! $proceso->beginTransaction()){
				throw new \Exception('No se pudo iniciar la transacción: Guardar destinatario');
			}

			$tablaModelo = new RegistroSgcModelo($datos);
			$datosBd = $tablaModelo->getPrepararDatos();
			// print_r($datosBd);
			if ($tablaModelo->getIdRegistroSgc() != null && $tablaModelo->getIdRegistroSgc() > 0){
				$this->modeloRegistroSgc->actualizar($datosBd, $tablaModelo->getIdRegistroSgc());
				$idRegistro = $datosBd["id_registro_sgc"];
			}else{
				unset($datosBd["id_registro_sgc"]);
				$idRegistro = $this->modeloRegistroSgc->guardar($datosBd);
			}

			if (! $idRegistro){
				throw new \Exception('No se registo los datos en la tabla registr SGC');
			}
			// *************guadar detalle de destinatario*************
			if (isset($datos['funcionarios'])){

				$arrayEliminar = array();
				$arrayGuardar = array();
				$arrayDatos = array();
				$lnegocioDetalleDestinatario = new DetalleDestinatarioLogicaNegocio();
				$verificarElemento = $lnegocioDetalleDestinatario->buscarLista("id_registro_sgc = " . $idRegistro);
				foreach ($datos['funcionarios'] as $value){
					$subtip = explode("-", $value);
					$datosDestinatario[] = [
						$subtip[0],
						$subtip[1],
						$subtip[2],
						$subtip[3]];
				}
				foreach ($verificarElemento as $valor1){
					$arrayDatos[] = $valor1->identificador;
					$ban = 1;
					foreach ($datosDestinatario as $valor2){
						if ($valor1->identificador == $valor2[0]){
							$ban = 0;
						}
					}
					if ($ban){
						$arrayEliminar[] = $valor1->id_detalle_destinatario;
					}
				}

				foreach ($datosDestinatario as $valor2){
					$ban = 1;
					foreach ($arrayDatos as $valor1){
						if ($valor1 == $valor2[0]){
							$ban = 0;
						}
					}
					if ($ban){
						$arrayGuardar[] = $valor2;
					}
				}
				// foreach ($arrayEliminar as $value){
				// $statement = $this->modeloRegistroSgc->getAdapter()
				// ->getDriver()
				// ->createStatement();
				// $sqlActualizar = $this->modeloRegistroSgc->borrarSql('detalle_destinatario', $this->modeloRegistroSgc->getEsquema());
				// $sqlActualizar->where(array(
				// 'id_detalle_destinatario' => $value));
				// $sqlActualizar->prepareStatement($this->modeloRegistroSgc->getAdapter(), $statement);
				// $statement->execute();
				// }
				foreach ($arrayGuardar as $value){
					$arrayElemento = array(
						'id_registro_sgc' => $idRegistro,
						'nombre' => $value[1],
						'identificador' => $value[0],
						'id_area' => $value[2],
						'nombre_area' => $value[3]);
					$statement = $this->modeloRegistroSgc->getAdapter()
						->getDriver()
						->createStatement();
					$sqlInsertar = $this->modeloRegistroSgc->guardarSql('detalle_destinatario', $this->modeloRegistroSgc->getEsquema());
					$sqlInsertar->columns($lnegocioDetalleDestinatario->columnas());
					$sqlInsertar->values($arrayElemento, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloRegistroSgc->getAdapter(), $statement);
					$statement->execute();
				}
			}else{
				throw new \Exception('Funcionarios vacio..!!');
			}
			$proceso->commit();
			return $idRegistro;
		}catch (\Exception $ex){
			$proceso->rollback();
			throw new \Exception($ex->getMessage());
			return 0;
		}
	}

	// -------------------------------obtener funcionarios de estructura-------------------------------------------------------------------------
	public function filtroObtenerFuncionarios($arrayParemetros){
		$busqueda = $areaSubproceso = '';
		// $busque = 'are.id_area = res.id_area and res.identificador=res.identificador and res.responsable = true and
		// res.activo=1 and';
		// $tablas = ',g_estructura.responsables res';
		if (array_key_exists('clasificacion', $arrayParemetros)){
			$busqueda .= "and are.clasificacion='" . $arrayParemetros['clasificacion'] . "'";
		}
		if (array_key_exists('area', $arrayParemetros)){
			$areaProceso = $this->buscarDivisionEstruc($arrayParemetros['area']);
			foreach ($areaProceso as $value){
				if (strcmp($value['clasificacion'], 'Oficina Técnica') == 0){
					$areaProceso2 = $this->buscarDivisionEstruc($value['id_area']);
					foreach ($areaProceso2 as $item){
						// $areaSubproceso .= "'" . $item['id_area'] . "',";
					}
				}else{
					$areaSubproceso .= "'" . $value['id_area'] . "',";
				}
			}

			$areaSubproceso = "(" . rtrim($areaSubproceso, ',') . ")";
			$busqueda .= ' and res.id_area IN ' . $areaSubproceso;
		}
		if (array_key_exists('tipoB', $arrayParemetros)){
			$busqueda .= "and res.id_area like 'D%'";
		}
		if (array_key_exists('jefatura', $arrayParemetros)){
			$busqueda .= "and res.id_area like 'J%'";
		}

		$consulta = "SELECT
							res.identificador,
							are.id_area as area,
							are.id_area_padre as padre,
							fe.apellido ||' '||fe.nombre as nombre,
							are.nombre as nombrearea
					FROM
						g_estructura.responsables res 
                    	inner join g_estructura.area are on res.id_area=are.id_area and res.activo=1 
                    	inner join g_uath.ficha_empleado fe on fe.identificador= res.identificador
							
					WHERE
							fe.estado_empleado='activo'
							" . $busqueda . " order by 2;";

		return $this->modeloRegistroSgc->ejecutarSqlNativo($consulta);
	}

	public function filtrarRevisarRegistros(Array $arrayParemetros = array()){
		$busqueda = '';

		if (array_key_exists('fecha_aprobacion_desde', $arrayParemetros)){
			if ($arrayParemetros['fecha_aprobacion_desde'] != ''){
				$busqueda .= "and fecha_aprobacion >= '" . $arrayParemetros['fecha_aprobacion_desde'] . "'";
			}
		}
		if (array_key_exists('fecha_aprobacion_hasta', $arrayParemetros)){
			if ($arrayParemetros['fecha_aprobacion_hasta'] != ''){
				$busqueda .= "and fecha_aprobacion <= '" . $arrayParemetros['fecha_aprobacion_hasta'] . "'";
			}
		}
		if (array_key_exists('fecha_aprobacion_desde', $arrayParemetros) && array_key_exists('fecha_aprobacion_hasta', $arrayParemetros)){
			if ($arrayParemetros['fecha_aprobacion_desde'] != '' && $arrayParemetros['fecha_aprobacion_hasta'] != ''){
				$busqueda = "and fecha_aprobacion Between '" . $arrayParemetros['fecha_aprobacion_desde'] . "' and '" . $arrayParemetros['fecha_aprobacion_hasta'] . "'";
			}
		}

		if (array_key_exists('fecha_notificacion_desde', $arrayParemetros) && array_key_exists('fecha_notificacion_hasta', $arrayParemetros)){
			if ($arrayParemetros['fecha_notificacion_desde'] != '' && $arrayParemetros['fecha_notificacion_hasta'] != ''){
				$busqueda .= "and fecha_notificacion Between '" . $arrayParemetros['fecha_notificacion_desde'] . "' and '" . $arrayParemetros['fecha_notificacion_hasta'] . "'";
			}
		}

		if (array_key_exists('numero_memorando_busq', $arrayParemetros)){
			if ($arrayParemetros['numero_memorando_busq'] != ''){
				$busqueda .= "and numero_memorando = '" . $arrayParemetros['numero_memorando_busq'] . "'";
			}
		}
		if (array_key_exists('numero_glpi_busq', $arrayParemetros)){
			if ($arrayParemetros['numero_glpi_busq'] != ''){
				$busqueda .= "and numero_glpi = '" . $arrayParemetros['numero_glpi_busq'] . "'";
			}
		}
		if (array_key_exists('coordinacion_busq', $arrayParemetros)){
			if ($arrayParemetros['coordinacion_busq'] != ''){
				$busqueda .= "and coordinacion = '" . $arrayParemetros['coordinacion_busq'] . "'";
			}
		}
		if (array_key_exists('estadoSocializar', $arrayParemetros)){
			if ($arrayParemetros['estadoSocializar'] != ''){
				if ($arrayParemetros['estadoSocializar'] != 'Todos'){
					$busqueda .= "and dd.estado = '" . $arrayParemetros['estadoSocializar'] . "'";
				}
			}
		}
		if (array_key_exists('formato_busq', $arrayParemetros)){
			if ($arrayParemetros['formato_busq'] != ''){
				$busqueda .= "and formato = '" . $arrayParemetros['formato_busq'] . "'";
			}
		}
		if (array_key_exists('identificador', $arrayParemetros)){
			$busqueda .= " and dd.identificador = '" . $arrayParemetros['identificador'] . "'";
		}
		if (array_key_exists('estado', $arrayParemetros)){
			$busqueda .= " and rs.estado = '" . $arrayParemetros['estado'] . "'";
		}
		if (array_key_exists('estado_socializacion', $arrayParemetros)){
			$busqueda .= " and dd.estado_socializacion = '" . $arrayParemetros['estado_socializacion'] . "'";
		}
		
		$consulta = "SELECT 
						rs.id_registro_sgc, identificador, subproceso,fecha_aprobacion, formato, numero_glpi, numero_memorando, nombre_documento,
						fecha_notificacion, nombre_area, coordinacion, fecha_vigencia, dd.estado, edicion, resolucion, observacion , rs.estado_registro, id_detalle_destinatario   
					FROM 
						g_registro_control_documentos.detalle_destinatario dd 
						inner join g_registro_control_documentos.registro_sgc rs on dd.id_registro_sgc = rs.id_registro_sgc
					where 
						rs.socializar = 'Si' and rs.estado not in ('temporal')
						" . $busqueda . "
					ORDER BY id_detalle_destinatario ASC";
		
		return $this->modeloRegistroSgc->ejecutarSqlNativo($consulta);
	}
	
	public function filtrarRevisarRegistrosCompleto(Array $arrayParemetros = array()){
	    $busqueda = '';
	    
	    if (array_key_exists('fecha_aprobacion_desde', $arrayParemetros)){
	        if ($arrayParemetros['fecha_aprobacion_desde'] != ''){
	            $busqueda .= "and fecha_aprobacion >= '" . $arrayParemetros['fecha_aprobacion_desde'] . "'";
	        }
	    }
	    if (array_key_exists('fecha_aprobacion_hasta', $arrayParemetros)){
	        if ($arrayParemetros['fecha_aprobacion_hasta'] != ''){
	            $busqueda .= "and fecha_aprobacion <= '" . $arrayParemetros['fecha_aprobacion_hasta'] . "'";
	        }
	    }
	    if (array_key_exists('fecha_aprobacion_desde', $arrayParemetros) && array_key_exists('fecha_aprobacion_hasta', $arrayParemetros)){
	        if ($arrayParemetros['fecha_aprobacion_desde'] != '' && $arrayParemetros['fecha_aprobacion_hasta'] != ''){
	            $busqueda = "and fecha_aprobacion Between '" . $arrayParemetros['fecha_aprobacion_desde'] . "' and '" . $arrayParemetros['fecha_aprobacion_hasta'] . "'";
	        }
	    }
	    
	    if (array_key_exists('fecha_notificacion_desde', $arrayParemetros) && array_key_exists('fecha_notificacion_hasta', $arrayParemetros)){
	        if ($arrayParemetros['fecha_notificacion_desde'] != '' && $arrayParemetros['fecha_notificacion_hasta'] != ''){
	            $busqueda .= "and fecha_notificacion Between '" . $arrayParemetros['fecha_notificacion_desde'] . "' and '" . $arrayParemetros['fecha_notificacion_hasta'] . "'";
	        }
	    }
	    
	    if (array_key_exists('numero_memorando_busq', $arrayParemetros)){
	        if ($arrayParemetros['numero_memorando_busq'] != ''){
	            $busqueda .= "and numero_memorando = '" . $arrayParemetros['numero_memorando_busq'] . "'";
	        }
	    }
	    if (array_key_exists('numero_glpi_busq', $arrayParemetros)){
	        if ($arrayParemetros['numero_glpi_busq'] != ''){
	            $busqueda .= "and numero_glpi = '" . $arrayParemetros['numero_glpi_busq'] . "'";
	        }
	    }
	    if (array_key_exists('coordinacion_busq', $arrayParemetros)){
	        if ($arrayParemetros['coordinacion_busq'] != ''){
	            $busqueda .= "and coordinacion = '" . $arrayParemetros['coordinacion_busq'] . "'";
	        }
	    }
	    if (array_key_exists('estadoSocializar', $arrayParemetros)){
	        if ($arrayParemetros['estadoSocializar'] != ''){
	            if ($arrayParemetros['estadoSocializar'] != 'Todos'){
	                $busqueda .= "and dd.estado = '" . $arrayParemetros['estadoSocializar'] . "'";
	            }
	        }
	    }
	    if (array_key_exists('formato_busq', $arrayParemetros)){
	        if ($arrayParemetros['formato_busq'] != ''){
	            $busqueda .= "and formato = '" . $arrayParemetros['formato_busq'] . "'";
	        }
	    }
	    if (array_key_exists('identificador', $arrayParemetros)){
	        $busqueda .= " and dd.identificador = '" . $arrayParemetros['identificador'] . "'";
	    }
	    if (array_key_exists('estado', $arrayParemetros)){
	        $busqueda .= " and rs.estado = '" . $arrayParemetros['estado'] . "'";
	    }
	    if (array_key_exists('estado_socializacion', $arrayParemetros)){
	        $busqueda .= " and dd.estado_socializacion = '" . $arrayParemetros['estado_socializacion'] . "'";
	    }
	    if (array_key_exists('socializar', $arrayParemetros)){
	        if ($arrayParemetros['socializar'] != ''){
	            $busqueda .= " and rs.socializar = '" . $arrayParemetros['socializar'] . "'";
	        }
	    }
	    
	    $consulta = "SELECT
						rs.id_registro_sgc, identificador, subproceso,fecha_aprobacion, formato, numero_glpi, numero_memorando, nombre_documento,
						fecha_notificacion, nombre_area, coordinacion, fecha_vigencia, dd.estado, edicion, resolucion, observacion , rs.estado_registro, 
                        id_detalle_destinatario, rs.socializar
					FROM
						g_registro_control_documentos.registro_sgc rs
						left join g_registro_control_documentos.detalle_destinatario dd on dd.id_registro_sgc = rs.id_registro_sgc
					where
						rs.estado not in ('temporal')
						" . $busqueda . "
					ORDER BY id_detalle_destinatario ASC";
	    
	    //echo $consulta;
	    return $this->modeloRegistroSgc->ejecutarSqlNativo($consulta);
	}

	public function filtrarRevisarRegistrosReporte(Array $arrayParemetros = array()){
		$busqueda = '';

		if (array_key_exists('fecha_aprobacion_desde', $arrayParemetros)){
			if ($arrayParemetros['fecha_aprobacion_desde'] != ''){
				$busqueda .= "and fecha_aprobacion >= '" . $arrayParemetros['fecha_aprobacion_desde'] . "'";
			}
		}
		if (array_key_exists('fecha_aprobacion_hasta', $arrayParemetros)){
			if ($arrayParemetros['fecha_aprobacion_hasta'] != ''){
				$busqueda .= "and fecha_aprobacion <= '" . $arrayParemetros['fecha_aprobacion_hasta'] . "'";
			}
		}
		if (array_key_exists('fecha_aprobacion_desde', $arrayParemetros) && array_key_exists('fecha_aprobacion_hasta', $arrayParemetros)){
			if ($arrayParemetros['fecha_aprobacion_desde'] != '' && $arrayParemetros['fecha_aprobacion_hasta'] != ''){
				$busqueda = "and fecha_aprobacion Between '" . $arrayParemetros['fecha_aprobacion_desde'] . "' and '" . $arrayParemetros['fecha_aprobacion_hasta'] . "'";
			}
		}

		if (array_key_exists('fecha_notificacion_desde', $arrayParemetros) && array_key_exists('fecha_notificacion_hasta', $arrayParemetros)){
			if ($arrayParemetros['fecha_notificacion_desde'] != '' && $arrayParemetros['fecha_notificacion_hasta'] != ''){
				$busqueda .= "and fecha_notificacion Between '" . $arrayParemetros['fecha_notificacion_desde'] . "' and '" . $arrayParemetros['fecha_notificacion_hasta'] . "'";
			}
		}

		if (array_key_exists('numero_memorando_busq', $arrayParemetros)){
			if ($arrayParemetros['numero_memorando_busq'] != ''){
				$busqueda .= "and numero_memorando = '" . $arrayParemetros['numero_memorando_busq'] . "'";
			}
		}
		if (array_key_exists('numero_glpi_busq', $arrayParemetros)){
			if ($arrayParemetros['numero_glpi_busq'] != ''){
				$busqueda .= "and numero_glpi = '" . $arrayParemetros['numero_glpi_busq'] . "'";
			}
		}
		if (array_key_exists('coordinacion_busq', $arrayParemetros)){
			if ($arrayParemetros['coordinacion_busq'] != ''){
				$busqueda .= "and rs.coordinacion = '" . $arrayParemetros['coordinacion_busq'] . "'";
			}
		}
		if (array_key_exists('estadoSocializar', $arrayParemetros)){
			if ($arrayParemetros['estadoSocializar'] != ''){
				if ($arrayParemetros['estadoSocializar'] != 'Todos'){
					$busqueda .= "and dd.estado = '" . $arrayParemetros['estadoSocializar'] . "'";
				}
			}
		}
		if (array_key_exists('formato_busq', $arrayParemetros)){
			if ($arrayParemetros['formato_busq'] != ''){
				$busqueda .= "and formato = '" . $arrayParemetros['formato_busq'] . "'";
			}
		}

		if (array_key_exists('identificador', $arrayParemetros)){
			$busqueda .= " and dd.identificador = '" . $arrayParemetros['identificador'] . "'";
		}
		if (array_key_exists('estado', $arrayParemetros)){
			$busqueda .= " and rs.estado = '" . $arrayParemetros['estado'] . "'";
		}

		$consulta = "SELECT
						rs.id_registro_sgc, numero_glpi, subproceso, fecha_aprobacion,numero_memorando, fecha_notificacion, nombre_area, rs.coordinacion, fecha_vigencia, dd.estado, formato,
 						ds.nombre_socializar, ds.estado_socializar, ds.documento_socializar, ds.fecha_socializacion
					FROM
						g_registro_control_documentos.detalle_destinatario dd
						inner join g_registro_control_documentos.registro_sgc rs on dd.id_registro_sgc = rs.id_registro_sgc
                        left join g_registro_control_documentos.detalle_socializacion ds on ds.id_registro_sgc = rs.id_registro_sgc
					where
						socializar='Si' and rs.estado not in ('temporal')
						" . $busqueda . "
					ORDER BY id_detalle_destinatario ASC";
		return $this->modeloRegistroSgc->ejecutarSqlNativo($consulta);
	}

	public function filtrarRevisarRegistrosSocializar(Array $arrayParemetros = array()){
		$busqueda = '';
		if (array_key_exists('identificador', $arrayParemetros)){
			$busqueda .= " and t.identificador = '" . $arrayParemetros['identificador'] . "'";
		}

		$consulta = "SELECT rs.id_registro_sgc, numero_memorando, fecha_notificacion, nombre_area, rs.coordinacion, 
						fecha_vigencia, dd.estado, dd.id_detalle_destinatario 
						FROM 
						g_registro_control_documentos.registro_sgc rs
						inner join g_registro_control_documentos.detalle_destinatario dd   on dd.id_registro_sgc = rs.id_registro_sgc
						inner join g_registro_control_documentos.detalle_socializacion ds on ds.id_detalle_destinatario = dd.id_detalle_destinatario 
						inner join g_registro_control_documentos.tecnico t on t.id_tecnico = ds.id_tecnico 
						where dd.identificador= ds.identifcador_asignante and socializar='Si' and rs.estado not in ('temporal')
							and rs.estado in ('socializar')  and dd.estado_socializacion = 'registrado' and dd.estado = 'No atendido'
						" . $busqueda . "
					ORDER BY dd.id_detalle_destinatario ASC";
		return $this->modeloRegistroSgc->ejecutarSqlNativo($consulta);
	}

	public function filtrarTecnicoRegistrado(Array $arrayParemetros = array()){
		$busqueda = 'true ';
		if (array_key_exists('identifcador_asignante', $arrayParemetros)){
			$busqueda .= "  and ds.identifcador_asignante = '" . $arrayParemetros['identifcador_asignante'] . "'";
		}
		if (array_key_exists('id_registro_sgc', $arrayParemetros)){
			$busqueda .= "  and rs.id_registro_sgc = " . $arrayParemetros['id_registro_sgc'];
		}
		if (array_key_exists('id_detalle_destinatario', $arrayParemetros)){
			$busqueda .= "  and dd.id_detalle_destinatario = " . $arrayParemetros['id_detalle_destinatario'];
		}
		$consulta = "SELECT ds.id_detalle_socializacion, 
						numero_memorando, t.nombre, fecha_socializacion, nombre_socializar, documento_socializar
						FROM 
                            g_registro_control_documentos.registro_sgc rs
						inner join g_registro_control_documentos.detalle_destinatario dd on dd.id_registro_sgc = rs.id_registro_sgc 
						inner join g_registro_control_documentos.detalle_socializacion ds on ds.id_detalle_destinatario=dd.id_detalle_destinatario 
						inner join g_registro_control_documentos.tecnico t on t.id_tecnico = ds.id_tecnico 
						where 
						" . $busqueda . "
					ORDER BY id_detalle_socializacion ASC";
		
		return $this->modeloRegistroSgc->ejecutarSqlNativo($consulta);
	}

	public function filtrarInfoTecnicoCatastro(Array $arrayParemetros = array()){
		$busqueda = '';
		if (array_key_exists('identificador', $arrayParemetros)){
			$busqueda .= "and t.identificador = '" . $arrayParemetros['identificador'] . "'";
		}
		$consulta = "SELECT
									id_tecnico, t.nombre, provincia, oficina, coordinacion, direccion
								FROM
								g_registro_control_documentos.tecnico t
								inner join g_uath.datos_contrato c on t.identificador = c.identificador
								where
									c.estado=1 
									" . $busqueda . "
								ORDER BY id_tecnico ASC";
		return $this->modeloRegistroSgc->ejecutarSqlNativo($consulta);
	}

	public function filtrarRegistroSgc(Array $arrayParemetros = array()){
		$busqueda = '';
		if (array_key_exists('fecha_notificacion_desde', $arrayParemetros)){
			if ($arrayParemetros['fecha_notificacion_desde'] != ''){
				$busqueda .= "and fecha_notificacion >= '" . $arrayParemetros['fecha_notificacion_desde'] . "'";
			}
		}
		if (array_key_exists('fecha_notificacion_hasta', $arrayParemetros)){
			if ($arrayParemetros['fecha_notificacion_hasta'] != ''){
				$busqueda .= "and fecha_notificacion <= '" . $arrayParemetros['fecha_notificacion_hasta'] . "'";
			}
		}
		if (array_key_exists('fecha_notificacion_desde', $arrayParemetros) && array_key_exists('fecha_notificacion_hasta', $arrayParemetros)){
			if ($arrayParemetros['fecha_notificacion_desde'] != '' && $arrayParemetros['fecha_notificacion_hasta'] != ''){
				$busqueda = "and fecha_notificacion Between '" . $arrayParemetros['fecha_notificacion_desde'] . "' and '" . $arrayParemetros['fecha_notificacion_hasta'] . "'";
			}
		}
		if (array_key_exists('numero_memorando_busq', $arrayParemetros)){
			if ($arrayParemetros['numero_memorando_busq'] != ''){
				$busqueda .= "and numero_memorando = '" . $arrayParemetros['numero_memorando_busq'] . "'";
			}
		}
		if (array_key_exists('numero_glpi_busq', $arrayParemetros)){
			if ($arrayParemetros['numero_glpi_busq'] != ''){
				$busqueda .= "and numero_glpi = '" . $arrayParemetros['numero_glpi_busq'] . "'";
			}
		}
		if (array_key_exists('coordinacion_busq', $arrayParemetros)){
			if ($arrayParemetros['coordinacion_busq'] != ''){
				$busqueda .= "and coordinacion = '" . $arrayParemetros['coordinacion_busq'] . "'";
			}
		}
		if (array_key_exists('estado_registro_busq', $arrayParemetros)){
			if ($arrayParemetros['estado_registro_busq'] != ''){
				$busqueda .= "and estado_registro = '" . $arrayParemetros['estado_registro_busq'] . "'";
			}
		}
		if (array_key_exists('formato_busq', $arrayParemetros)){
			if ($arrayParemetros['formato_busq'] != ''){
				$busqueda .= "and formato = '" . $arrayParemetros['formato_busq'] . "'";
			}
		}
		$consulta = "SELECT
									*
								FROM
								g_registro_control_documentos.registro_sgc rs
								where
								    estado not in('temporal')
									and fecha_aprobacion between '" . $arrayParemetros['fecha_aprobacion_desde'] . "' and '" . $arrayParemetros['fecha_aprobacion_hasta'] . "'
									" . $busqueda . "
								ORDER BY id_registro_sgc DESC";
		return $this->modeloRegistroSgc->ejecutarSqlNativo($consulta);
	}

	// *****************************************generar excel********************************************************
	public function crearExcel($arrayDatos, $arrayParametros){
		$documento = new ReportesExcelModelo();
		$documento->getProperties()
			->setCreator("GUIA")
			->setLastModifiedBy('GUIA')
			->
		// sss
		setTitle('Reporte socialización gestión documental')
			->setSubject('Reporte')
			->setDescription('Este documento fue creado por el sistema GUIA')
			->setKeywords('')
			->setCategory('');

		$hoja = $documento->getActiveSheet();
		$hoja->setTitle("hoja 1");

		$documento->cuerpoDinamicoHorizontal(5, 'ID', '95A5A6', 1, 10, 0, 1, 0);
		$documento->cuerpoDinamicoHorizontal(5, 'No. Memorando', '95A5A6', 1, 10, 0, 2, 0);
		$documento->cuerpoDinamicoHorizontal(5, 'No. GLPI', '95A5A6', 1, 10, 0, 3, 0);
		$documento->cuerpoDinamicoHorizontal(5, 'COORDINACIÓN/DIRECCIÓN SOLICITANTE', '95A5A6', 1, 10, 0, 4, 0);
		$documento->cuerpoDinamicoHorizontal(5, 'SUBPROCESO', '95A5A6', 1, 10, 0, 5, 0);
		$documento->cuerpoDinamicoHorizontal(5, 'FORMATO', '95A5A6', 1, 10, 0, 6, 0);
		$documento->cuerpoDinamicoHorizontal(5, 'NOMBRE DEL DOCUMENTO', '95A5A6', 1, 10, 0, 7, 0);
		$documento->cuerpoDinamicoHorizontal(5, 'FECHA APROBACIÓN', '95A5A6', 1, 10, 0, 8, 0);
		$documento->cuerpoDinamicoHorizontal(5, 'FECHA NOTIFICACIÓN.', '95A5A6', 1, 10, 0, 9, 0);
		$documento->cuerpoDinamicoHorizontal(5, 'COORDINACIÓN/DIRECCIÓN NOTIFICADA', '95A5A6', 1, 10, 0, 10, 0);
		$documento->cuerpoDinamicoHorizontal(5, 'FECHA SOCIALIZACIÓN', '95A5A6', 1, 10, 0, 11, 0);
		$documento->cuerpoDinamicoHorizontal(5, 'ESTADO SOCIALIZACIÓN', '95A5A6', 1, 10, 0, 12, 0);
		$documento->cuerpoDinamicoHorizontal(5, 'EVIDENCIA SOCIALIZACIÓN', '95A5A6', 1, 10, 0, 13, 0);

		$arrayResultadoDocumentos = array();
		foreach ($arrayParametros as $items){
			$parametros = array(
				'identifcador_asignante' => $items['identificador'],
				'id_registro_sgc' => $items['id_registro_sgc']);
			
			$verificarocializacion = $this->filtrarTecnicoRegistrado($parametros);
			
			$coordinacion = $this->buscarNombreArea($items['coordinacion']);
			$subproceso = $this->buscarNombreArea($items['subproceso']);
			
			if ($verificarocializacion->count()){
			    //$url = Constantes::RUTA_DOMINIO . '/' . Constantes::RUTA_APLICACION . '/' . $verificarocializacion->current()->documento_socializar;
			    if($verificarocializacion->current()->fecha_socializacion != null){
				    $url = Constantes::RUTA_DOMINIO . '/' . Constantes::RUTA_APLICACION . '/' . $verificarocializacion->current()->documento_socializar;
			    }else{
			        $url="";
			    }
				
				$arrayResultadoDocumentos[] = array(
					'id_registro' => $items['id_registro_sgc'],
				    'numero_memorando' => $items['numero_memorando'],
				    'numero_glpi' => $items['numero_glpi'],
					'fecha_socializacion' => $verificarocializacion->current()->fecha_socializacion,
					'nombre_socializar' => $items['nombre_documento'],
					'documento_socializar' => $url,
					'coordinacion' => $coordinacion->current()->nombre,
					'subproceso' => $subproceso->current()->nombre,
					'formato' => $items['formato'],
					'fecha_aprobacion' => $items['fecha_aprobacion'],
					'fecha_notificacion' => $items['fecha_notificacion'],
					'nombre_area' => $items['nombre_area'],
					'estado' => $items['estado']);
			}else{
				$arrayResultadoDocumentos[] = array(
				    'id_registro' => $items['id_registro_sgc'],
				    'numero_memorando' => $items['numero_memorando'],
				    'numero_glpi' => $items['numero_glpi'],
					'fecha_socializacion' => '',
				    'nombre_socializar' => $items['nombre_documento'],
					'documento_socializar' => '',
					'coordinacion' => $coordinacion->current()->nombre,
					'subproceso' => $subproceso->current()->nombre,
					'formato' => $items['formato'],
					'fecha_aprobacion' => $items['fecha_aprobacion'],
					'fecha_notificacion' => $items['fecha_notificacion'],
					'nombre_area' => $items['nombre_area'],
					'estado' => $items['estado']);
			}
		}
		$contador = 6;
		$item = 1;
		
		//$arrayResultadoDocumentos = array_unique($arrayResultadoDocumentos, SORT_REGULAR);
		
		foreach ($arrayResultadoDocumentos as $value){
		    $documento->cuerpoDinamicoHorizontal($contador, $value['id_registro'], 'ffffff', 1, 10, 0, 1, 0);
		    $documento->cuerpoDinamicoHorizontal($contador, $value['numero_memorando'], 'ffffff', 1, 10, 0, 2, 0);
		    $documento->cuerpoDinamicoHorizontal($contador, $value['numero_glpi'], 'ffffff', 1, 10, 0, 3, 0);
			$documento->cuerpoDinamicoHorizontal($contador, $value['coordinacion'], 'ffffff', 1, 10, 0, 4, 0);
			$documento->cuerpoDinamicoHorizontal($contador, $value['subproceso'], 'ffffff', 1, 10, 0, 5, 0);
			$documento->cuerpoDinamicoHorizontal($contador, $value['formato'], 'ffffff', 1, 10, 0, 6, 0);
			$documento->cuerpoDinamicoHorizontal($contador, $value['nombre_socializar'], 'ffffff', 1, 10, 0, 7, 0);
			$documento->cuerpoDinamicoHorizontal($contador, $value['fecha_aprobacion'], 'ffffff', 1, 10, 0, 8, 0);
			$documento->cuerpoDinamicoHorizontal($contador, $value['fecha_notificacion'], 'ffffff', 1, 10, 0, 9, 0);
			$documento->cuerpoDinamicoHorizontal($contador, $value['nombre_area'], 'ffffff', 1, 10, 0, 10, 0);
			$documento->cuerpoDinamicoHorizontal($contador, $value['fecha_socializacion'], 'ffffff', 1, 10, 0, 11, 0);
			$documento->cuerpoDinamicoHorizontal($contador, $value['estado'], 'ffffff', 1, 10, 0, 12, 0);
			$documento->cuerpoDinamicoHorizontal($contador, $value['documento_socializar'], 'ffffff', 1, 10, 0, 13, 0);
			$contador ++;
			$item ++;
		}

		$documento->crearCabeceraExcel(3, $arrayDatos['titulo'], 'ffffff', 0, 12, 13);
		$documento->getActiveSheet()
			->getRowDimension(6)
			->setRowHeight(50);
		$documento->getActiveSheet()
			->getColumnDimension('D')
			->setAutoSize(true);
		$documento->getActiveSheet()
			->getColumnDimension('E')
			->setAutoSize(true);
		$documento->getActiveSheet()
			->getColumnDimension('F')
			->setAutoSize(true);
		$documento->getActiveSheet()
			->getColumnDimension('G')
			->setAutoSize(true);
		$documento->getActiveSheet()
			->getColumnDimension('H')
			->setAutoSize(true);
		$documento->getActiveSheet()
			->getColumnDimension('I')
			->setAutoSize(true);
		$documento->getActiveSheet()
			->getColumnDimension('J')
			->setAutoSize(true);
		$documento->getActiveSheet()
			->getColumnDimension('K')
			->setAutoSize(true);
		$documento->getActiveSheet()
			->getColumnDimension('L')
			->setAutoSize(true);
		$documento->getActiveSheet()
			->getColumnDimension('M')
			->setAutoSize(true);

		for ($i = 7; $i <= 11; $i ++){
			$documento->getActiveSheet()
				->getRowDimension($i)
				->setRowHeight(20);
		}
		$writer = new Xlsx($documento);
		$nombreArchivo = REG_CTR_DOC_SGC_RAIZ . "reporteExcel/" . $arrayDatos['nombreArchivo'] . ".xlsx";
		$writer->save($nombreArchivo);
	}

	// *****************************************generar excel reportes documentos SGC********************************************************
	public function crearExcelSGC($arrayDatos, $arrayParametros){
		$documento = new ReportesExcelModelo();
		$lnegocioDocumentoAdjunto = new DocumentoAdjuntoLogicaNegocio();
		$documento->getProperties()
			->setCreator("GUIA")
			->setLastModifiedBy('GUIA')
			->
		// sss
		setTitle('Matriz SGC ' . date('Y-m-d'))
			->setSubject('Reporte')
			->setDescription('Este documento fue creado por el sistema GUIA')
			->setKeywords('')
			->setCategory('');

		$hoja = $documento->getActiveSheet();
		$hoja->setTitle("hoja 1");

		$documento->cuerpoDinamicoHorizontal(5, 'ID', '95A5A6', 1, 10, 0, 1, 0);
		$documento->cuerpoDinamicoHorizontal(5, 'COORDINACIÓN/DIRECCIÓN SOLICITANTE', '95A5A6', 1, 10, 0, 2, 0);
		$documento->cuerpoDinamicoHorizontal(5, 'FORMATO', '95A5A6', 1, 10, 0, 3, 0);
		$documento->cuerpoDinamicoHorizontal(5, 'SUBPROCESO', '95A5A6', 1, 10, 0, 4, 0);
		$documento->cuerpoDinamicoHorizontal(5, 'NOMBRE DEL DOCUMENTO', '95A5A6', 1, 10, 0, 5, 0);
		$documento->cuerpoDinamicoHorizontal(5, 'FECHA APROBACIÓN', '95A5A6', 1, 10, 0, 6, 0);
		$documento->cuerpoDinamicoHorizontal(5, 'EDICIÓN', '95A5A6', 1, 10, 0, 7, 0);
		$documento->cuerpoDinamicoHorizontal(5, 'RESOLUCIÓN', '95A5A6', 1, 10, 0, 8, 0);
		$documento->cuerpoDinamicoHorizontal(5, 'OBSERVACIONES', '95A5A6', 1, 10, 0, 9, 0);
		$documento->cuerpoDinamicoHorizontal(5, 'ESTADO REGISTRO', '95A5A6', 1, 10, 0, 10, 0);
		$documento->cuerpoDinamicoHorizontal(5, 'NECESITA SOCIALIZACIÓN', '95A5A6', 1, 10, 0, 11, 0);
		$documento->cuerpoDinamicoHorizontal(5, 'ENLACES ARCHIVOS', '95A5A6', 1, 10, 0, 12, 0);

		$arrayResultadoDocumentos = array();
		foreach ($arrayParametros as $items){
			$nombreDocumento = '';
			$rutaArchivo = '';
			$verificarDocumentos = $lnegocioDocumentoAdjunto->buscarLista("id_registro_sgc=" . $items['id_registro_sgc']);
			$coordinacion = $this->buscarNombreArea($items['coordinacion']);
			$subproceso = $this->buscarNombreArea($items['subproceso']);
			if ($verificarDocumentos->count()){
				foreach ($verificarDocumentos as $value){
					$nombreDocumento .= $value->nombre_archivo . ' - ';
					$rutaArchivo .= Constantes::RUTA_DOMINIO . '/' . Constantes::RUTA_APLICACION . '/' . $value->ruta_archivo . ' - ';
				}
				
				$rutaArchivo = rtrim($rutaArchivo, ' - ');
				
				$arrayResultadoDocumentos[] = array(
				    'id_registro' => $items['id_registro_sgc'],
				    'documento_socializar' => $rutaArchivo,
					'nombre_area' => $coordinacion->current()->nombre,
					'formato' => $items['formato'],
					'subproceso' => $subproceso->current()->nombre,
					'nombre_documento' => $items['nombre_documento'],
					'fecha_aprobacion' => $items['fecha_aprobacion'],
					'edicion' => $items['edicion'],
					'resolucion' => $items['resolucion'],
					'observacion' => $items['observacion'],
				    'estado_registro' => $items['estado_registro'],
				    'socializar' => $items['socializar']
				);
			}else{
				$arrayResultadoDocumentos[] = array(
				    'id_registro' => $items['id_registro_sgc'],
				    'documento_socializar' => '',
					'nombre_area' => $coordinacion->current()->nombre,
					'formato' => $items['formato'],
					'subproceso' => $subproceso->current()->nombre,
					'nombre_documento' => $items['nombre_documento'],
					'fecha_aprobacion' => $items['fecha_aprobacion'],
					'edicion' => $items['edicion'],
					'resolucion' => $items['resolucion'],
					'observacion' => $items['observacion'],
					'estado_registro' => $items['estado_registro'],
				    'socializar' => $items['socializar']
					);
			}
		}
		$contador = 6;
		$item = 1;
		
		$arrayResultadoDocumentos = array_unique($arrayResultadoDocumentos, SORT_REGULAR);
		
		foreach ($arrayResultadoDocumentos as $value){
		    $documento->cuerpoDinamicoHorizontal($contador, $value['id_registro'], 'ffffff', 1, 10, 0, 1, 0);
		    $documento->cuerpoDinamicoHorizontal($contador, $value['nombre_area'], 'ffffff', 1, 10, 0, 2, 0);
			$documento->cuerpoDinamicoHorizontal($contador, $value['formato'], 'ffffff', 1, 10, 0, 3, 0);
			$documento->cuerpoDinamicoHorizontal($contador, $value['subproceso'], 'ffffff', 1, 10, 0, 4, 0);
			$documento->cuerpoDinamicoHorizontal($contador, $value['nombre_documento'], 'ffffff', 1, 10, 0, 5, 0);
			$documento->cuerpoDinamicoHorizontal($contador, $value['fecha_aprobacion'], 'ffffff', 1, 10, 0, 6, 0);
			$documento->cuerpoDinamicoHorizontal($contador, $value['edicion'], 'ffffff', 1, 10, 0, 7, 0);
			$documento->cuerpoDinamicoHorizontal($contador, $value['resolucion'], 'ffffff', 1, 10, 0, 8, 0);
			$documento->cuerpoDinamicoHorizontal($contador, $value['observacion'], 'ffffff', 1, 10, 0, 9, 0);
			$documento->cuerpoDinamicoHorizontal($contador, $value['estado_registro'], 'ffffff', 1, 10, 0, 10, 0);
			$documento->cuerpoDinamicoHorizontal($contador, $value['socializar'], 'ffffff', 1, 10, 0, 11, 0);
			$documento->cuerpoDinamicoHorizontal($contador, $value['documento_socializar'], 'ffffff', 1, 10, 0, 12, 0);
			$contador ++;
			$item ++;
		}

		$documento->crearCabeceraExcel(3, $arrayDatos['titulo'], 'ffffff', 0, 12, 12);
		$documento->getActiveSheet()
			->getRowDimension(6)
			->setRowHeight(80);
		$documento->getActiveSheet()
			->getColumnDimension('D')
			->setAutoSize(true);
		$documento->getActiveSheet()
			->getColumnDimension('B')
			->setAutoSize(true);
		$documento->getActiveSheet()
			->getColumnDimension('E')
			->setAutoSize(true);
		$documento->getActiveSheet()
			->getColumnDimension('F')
			->setAutoSize(true);
		$documento->getActiveSheet()
			->getColumnDimension('I')
			->setAutoSize(true);
		$documento->getActiveSheet()
			->getColumnDimension('H')
			->setAutoSize(true);
		$documento->getActiveSheet()
			->getColumnDimension('J')
			->setAutoSize(true);
		$documento->getActiveSheet()
			->getColumnDimension('K')
			->setAutoSize(true);
		$documento->getActiveSheet()
			->getColumnDimension('L')
			->setAutoSize(true);
			
		for ($i = 7; $i <= 12; $i ++){
			$documento->getActiveSheet()
				->getRowDimension($i)
				->setRowHeight(20);
		}
		$writer = new Xlsx($documento);
		$nombreArchivo = REG_CTR_DOC_SGC_RAIZ . "reporteExcel/" . $arrayDatos['nombreArchivo'] . ".xlsx";
		$writer->save($nombreArchivo);
	}

	public function buscarFuncionarioSocializar(){
		$consulta = "
			select 
				up.identificador, fe.nombre ||' '||fe.apellido as funcionario from 
			g_usuario.usuarios_perfiles up 
			inner join g_uath.ficha_empleado fe on fe.identificador=up.identificador 
			inner join g_uath.datos_contrato dc on dc.identificador=fe.identificador and dc.estado=1
			where  
	  			id_perfil= (SELECT id_perfil FROM g_usuario.perfiles WHERE codificacion_perfil ='PFL_RES_SOC') and
                dc.provincia = '".$_SESSION['nombreProvincia']."'
			order by 1";
		return $this->modeloRegistroSgc->ejecutarSqlNativo($consulta);
	}
}

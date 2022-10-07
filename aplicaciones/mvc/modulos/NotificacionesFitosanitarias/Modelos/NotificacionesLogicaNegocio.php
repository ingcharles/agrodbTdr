<?php

/**
 * Lógica del negocio de NotificacionesModelo
 *
 * Este archivo se complementa con el archivo NotificacionesControlador.
 *
 * @author AGROCALIDAD
 * @date    2019-09-09
 * @uses NotificacionesLogicaNegocio
 * @package NotificacionesFitosanitarias
 * @subpackage Modelos
 */
namespace Agrodb\NotificacionesFitosanitarias\Modelos;

use Agrodb\NotificacionesFitosanitarias\Modelos\IModelo;
use Agrodb\NotificacionesFitosanitarias\Modelos\NotificacionPorPaisAfectadoModelo;
use Agrodb\NotificacionesFitosanitarias\Modelos\NotificacionPorPaisAfectadoLogicaNegocio;
use Agrodb\NotificacionesFitosanitarias\Modelos\RespuestaNotificacionLogicaNegocio;
use Agrodb\NotificacionesFitosanitarias\Modelos\ListaNotificacionLogicaNegocio;
use Agrodb\Catalogos\Modelos\LocalizacionLogicaNegocio;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\NotificacionesFitosanitarias\Modelos\AreaTematicaNotificacionLogicaNegocio;

class NotificacionesLogicaNegocio implements IModelo{

	private $modeloNotificaciones = null;

	private $modeloNotificacionPorPaisAfectado = null;

	private $lNegocioNotificacionPorPaisAfectado = null;

	private $lNegocioListaNotificacion = null;

	private $lNegocioLocalizacion = null;

	private $lNegocioRespuestaNotificacion = null;
	
	private $lNegocioAreaTematica = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloNotificaciones = new NotificacionesModelo();
		$this->modeloNotificacionPorPaisAfectado = new NotificacionPorPaisAfectadoModelo();
		$this->lNegocioNotificacionPorPaisAfectado = new NotificacionPorPaisAfectadoLogicaNegocio();
		$this->lNegocioListaNotificacion = new ListaNotificacionLogicaNegocio();
		$this->lNegocioLocalizacion = new LocalizacionLogicaNegocio();
		$this->lNegocioRespuestaNotificacion = new RespuestaNotificacionLogicaNegocio();
		$this->lNegocioAreaTematica = new AreaTematicaNotificacionLogicaNegocio();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new NotificacionesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdNotificacion() != null && $tablaModelo->getIdNotificacion() > 0){
			return $this->modeloNotificaciones->actualizar($datosBd, $tablaModelo->getIdNotificacion());
		}else{
			unset($datosBd["id_notificacion"]);
			return $this->modeloNotificaciones->guardar($datosBd);
		}
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardarRegistros(Array $datos){
		try{
			$this->modeloNotificaciones = new NotificacionesModelo();
			$proceso = $this->modeloNotificaciones->getAdapter()
				->getDriver()
				->getConnection();
			if (! $proceso->beginTransaction()){
				throw new \Exception('No se pudo iniciar la transacción: actualizar notificación ');
			}
			$tablaModelo = new NotificacionesModelo($datos);
			$datosBd = $tablaModelo->getPrepararDatos();
			if ($tablaModelo->getIdNotificacion() != null && $tablaModelo->getIdNotificacion() > 0){
				$this->modeloNotificaciones->actualizar($datosBd, $tablaModelo->getIdNotificacion());
				$id = $datosBd["id_notificacion"];
			}else{
				unset($datosBd["id_notificacion"]);
				$id = $this->modeloNotificaciones->guardar($datosBd);
			}

			// *****************area tematica*********************************************
			if (isset($datos['area_tematica'])){
				$arrayEliminar = array();
				$arrayGuardar = array();
				$arrayDatos = array();
				$lnegocioAreaTematica = new AreaTematicaNotificacionLogicaNegocio();
				$verificarAreaTematica = $lnegocioAreaTematica->buscarLista("id_notificacion = " . $id);
				if ($verificarAreaTematica->count()){

					foreach ($verificarAreaTematica as $valor1){
						$arrayDatos[] = $valor1->area_tematica;
						$ban = 1;
						foreach ($datos['area_tematica'] as $valor2){
							if ($valor1->area_tematica == $valor2){
								$ban = 0;
							}
						}
						if ($ban){
							$arrayEliminar[] = $valor1->id_area_tematica_notificacion;
						}
					}
					foreach ($datos['area_tematica'] as $valor2){
						$ban = 1;
						foreach ($arrayDatos as $valor1){
							if ($valor1 == $valor2){
								$ban = 0;
							}
						}
						if ($ban){
							$arrayGuardar[] = $valor2;
						}
					}
					foreach ($arrayEliminar as $value){
						$statement = $this->modeloNotificaciones->getAdapter()
							->getDriver()
							->createStatement();
						$sqlActualizar = $this->modeloNotificaciones->borrarSql('area_tematica_notificacion', $this->modeloNotificaciones->getEsquema());
						$sqlActualizar->where(array(
							'id_area_tematica_notificacion' => $value));
						$sqlActualizar->prepareStatement($this->modeloNotificaciones->getAdapter(), $statement);
						$statement->execute();
					}
					foreach ($arrayGuardar as $value){
						$statement = $this->modeloNotificaciones->getAdapter()
							->getDriver()
							->createStatement();
						$arrayAreaTema = array(
							'id_notificacion' => $id,
							'area_tematica' => $value);
						$sqlInsertar = $this->modeloNotificaciones->guardarSql('area_tematica_notificacion', $this->modeloNotificaciones->getEsquema());
						$sqlInsertar->columns($lnegocioAreaTematica->columnas());
						$sqlInsertar->values($arrayAreaTema, $sqlInsertar::VALUES_MERGE);
						$sqlInsertar->prepareStatement($this->modeloNotificaciones->getAdapter(), $statement);
						$statement->execute();
					}
				}else{

					foreach ($datos['area_tematica'] as $value){
						$arrayAreaTema = array(
							'id_notificacion' => $id,
							'area_tematica' => $value);
						$statement = $this->modeloNotificaciones->getAdapter()
							->getDriver()
							->createStatement();
						$sqlInsertar = $this->modeloNotificaciones->guardarSql('area_tematica_notificacion', $this->modeloNotificaciones->getEsquema());
						$sqlInsertar->columns($lnegocioAreaTematica->columnas());
						$sqlInsertar->values($arrayAreaTema, $sqlInsertar::VALUES_MERGE);
						$sqlInsertar->prepareStatement($this->modeloNotificaciones->getAdapter(), $statement);
						$statement->execute();
					}
				}
			}else{
				throw new \Exception('No se pudo iniciar la transacción: Verificar los campos obligatorios ');
			}
			// **********************************************************************************************
			$proceso->commit();
			return $id;
		}catch (\Exception $ex){
			$proceso->rollback();
			throw new \Exception($ex->getMessage());
			// Mensajes::fallo(Constantes::ERROR_AL_GUARDAR);
			return false;
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
		$this->modeloNotificaciones->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return NotificacionesModelo
	 */
	public function buscar($id){
		return $this->modeloNotificaciones->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloNotificaciones->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloNotificaciones->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarNotificaciones(){
		$consulta = "SELECT * FROM " . $this->modeloNotificaciones->getEsquema() . ". notificaciones";
		return $this->modeloNotificaciones->ejecutarSqlNativo($consulta);
	}

	public function leerArchivoExcelNotificaciones($datos, $idListaNotificacion){
		$rutaArchivo = $datos['archivo'];
		$extension = explode('.', $rutaArchivo);

		switch (strtolower(end($extension))) {
			case 'xls':
				$tipo = 'Xls'; // Requiere formato Xls
			break;
			case 'xlsx':
				$tipo = 'Xlsx'; // Requiere formato Xlsx
			break;
			default:
				$tipo = 'Xls'; // Requiere formato Xls
			break;
		}

		try{
			$lnegocioAreaTematica = new AreaTematicaNotificacionLogicaNegocio();
			$proceso = $this->modeloNotificaciones->getAdapter()
			->getDriver()
			->getConnection();
			
			if (! $proceso->beginTransaction()){
				throw new \Exception('No se pudo iniciar la transacción en: Guardar notificación');
			}
			$reader = IOFactory::createReader($tipo);
			$reader->setReadDataOnly(true);
			$reader->setLoadSheetsOnly(0);

			$documento = $reader->load(Constantes::RUTA_SERVIDOR_OPT . '/' . Constantes::RUTA_APLICACION . '/' . $rutaArchivo);
			$hojaActual = $documento->getActiveSheet()->toArray(null, true, true, true);

			$archivoVacio = $documento->getActiveSheet()
				->getCell('A2')
				->getValue();
			if ($archivoVacio){
				$datoExceso = $documento->getActiveSheet()
					->getCell('K2')
					->getValue();
				if (! $datoExceso){

					$tipoDeDocumento = array(
						"Adiciones de urgencia",
						"Adiciones ordinarias",
						"Correcciones de urgencia",
						"Correcciones ordinarias",
						"Notificación de medidas de urgencia",
						"Notificación ordinaria",
						"Reconocimiento de equivalencia",
						"Addenda emergency",
						"Addenda regular",
						"Corrigenda emergency",
						"Corrigenda regular",
						"Emergency notification",
						"Recognition of equivalence",
						"Regular notification");
					for ($i = 2; $i <= count($hojaActual); $i ++){
						if (trim($hojaActual[$i]['A']) !== '' && trim($hojaActual[$i]['B']) !== '' && trim($hojaActual[$i]['C']) !== '' && trim($hojaActual[$i]['D']) !== ''){
							$fechaNotificacion = trim($hojaActual[$i]['D']);
							$fecha_notifica = date('Y-m-d', strtotime($fechaNotificacion));
							$fechaCierre = strtotime($fecha_notifica . '+59 days');
							$fechaCierre = date('Y-m-d', $fechaCierre);
							$fechaCierre = "'" . $fechaCierre . "'";
							$nombrePais = trim($hojaActual[$i]['B']); // País que notifica

							$localizacionPais = $this->lNegocioLocalizacion->buscarVariosPaisesPorNombre($nombrePais);

							if ($localizacionPais->count() > 0){
								$datoPais = $localizacionPais->current();
								$idPaisN = $datoPais->id_localizacion;
							}else{
								continue;
							}

							if (in_array($hojaActual[$i]['C'], $tipoDeDocumento)){
								$tipoDocumento = $hojaActual[$i]['C'];
								$documento = '';
								switch ($tipoDocumento) {
									case "Addenda emergency":
										$documento = "Adiciones de urgencia";
									break;
									case "Addenda regular":
										$documento = "Adiciones ordinarias";
									break;
									case "Corrigenda emergency":
										$documento = "Correcciones de urgencia";
									break;
									case "Corrigenda regular":
										$documento = "Correcciones ordinarias";
									break;
									case "Emergency notification":
										$documento = "Notificación de medidas de urgencia";
									break;
									case "Recognition of equivalence":
										$documento = "Notificación ordinaria";
									break;
									case "Regular notification":
										$documento = "Reconocimiento de equivalencia";
									break;
									default:
										$documento = $tipoDocumento;
								}
							}else{
								continue;
							}
							
							
							$datosExcel = array(
								'id_lista_notificacion' => $idListaNotificacion,
								'codigo_documento' => $hojaActual[$i]['A'],
								'id_pais_notifica' => $idPaisN,
								'nombre_pais_notifica' => $hojaActual[$i]['B'],
								'tipo_documento' => $documento,
								'fecha_notificacion' => $fecha_notifica,
								'fecha_cierre' => $fechaCierre,
								'producto' => $hojaActual[$i]['E'],
								'palabra_clave' => $hojaActual[$i]['F'],
								'descripcion' => $hojaActual[$i]['G'],
								'enlace' => $hojaActual[$i]['I']);
							
// 							$tablaModelo = new NotificacionesModelo($datosExcel);
// 							$datosBd = $tablaModelo->getPrepararDatos();
// 							$id = $this->modeloNotificaciones->guardar($datosBd);
							
							$id = $this->guardar($datosExcel);
							$arrayPaisesAfectados = null;
							$arrayAreaTematica = null;
							
							if (trim($hojaActual[$i]['H']) !== ''){
								$arrayPaisesAfectados = explode(',', trim($hojaActual[$i]['H']));

								for ($j = 0; $j < count($arrayPaisesAfectados); $j ++){
									$localizacionPais = $this->lNegocioLocalizacion->buscarVariosPaisesPorNombre(trim($arrayPaisesAfectados[$j]));

									if ($localizacionPais->count() > 0){
										$datoPaisAfectado = $localizacionPais->current();
										$datosExcelPaises = array(
											'id_notificacion' => $id,
											'id_localizacion' => $datoPaisAfectado->id_localizacion,
											'nombre_pais' => $datoPaisAfectado->nombre);

										$this->lNegocioNotificacionPorPaisAfectado->guardar($datosExcelPaises);
									}
								}
							}
							if (trim($hojaActual[$i]['J']) !== ''){
								
								$arrayAreaTematica = explode(';', trim($hojaActual[$i]['J']));
								foreach ($arrayAreaTematica as $value) {
									$arrayAreaTema = array(
										'id_notificacion' => $id,
										'area_tematica' => trim($value));
									$this->lNegocioAreaTematica->guardar($arrayAreaTema);
// 									$statement = $this->modeloNotificaciones->getAdapter()
// 									->getDriver()
// 									->createStatement();
// 									$sqlInsertar = $this->modeloNotificaciones->guardarSql('area_tematica_notificacion', $this->modeloNotificaciones->getEsquema());
// 									$sqlInsertar->columns($lnegocioAreaTematica->columnas());
// 									$sqlInsertar->values($arrayAreaTema, $sqlInsertar::VALUES_MERGE);
// 									$sqlInsertar->prepareStatement($this->modeloNotificaciones->getAdapter(), $statement);
// 									$statement->execute();
									
								}
							}
						}else{
							continue;
							Mensajes::fallo(Constantes::ARCHIVO_MAL_CONSTRUIDO);
						}
					}
					$proceso->commit();
					Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
				}else{
					Mensajes::fallo(Constantes::ARCHIVO_MAL_CONSTRUIDO);
				}
			}else{
				Mensajes::fallo(Constantes::ARCHIVO_VACIO);
			}
		}catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e){
			$proceso->rollback();
			Mensajes::fallo(Constantes::ERROR_AL_GUARDAR);
		}
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 * Buscar notificaciones usando filtros.
	 *
	 * @return array|ResultSet
	 */
	public function buscarNotificacionesRespondidas($idProceso){
		$busqueda = '';
		$busqueda .= " and now() <= n.fecha_cierre
                        and n.id_notificacion in (
                        select distinct id_notificacion 
                        from g_notificaciones_fitosanitarias.respuesta_notificacion 
                        where estado_respuesta = 'f' 
                        and tipo = 'operador') ";

		$consulta = " select n.* from g_notificaciones_fitosanitarias.notificaciones n
                        where n.id_lista_notificacion = '" . $idProceso . "'" . $busqueda . "
                        order by n.id_notificacion asc;";

		return $this->modeloNotificaciones->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 * Buscar notificaciones usando filtros.
	 *
	 * @return array|ResultSet
	 */
	public function buscarNotificacionesXFiltroReporte($arrayParametros){
		$busqueda = '';

		if (isset($arrayParametros['fecha_notificacion_inicio']) && ($arrayParametros['fecha_notificacion_inicio'] != '')){
			$busqueda .= " and n.fecha_notificacion >= '" . $arrayParametros['fecha_notificacion_inicio'] . " 00:00:00' ";
		}

		if (isset($arrayParametros['fecha_notificacion_fin']) && ($arrayParametros['fecha_notificacion_fin'] != '')){
			$busqueda .= " and n.fecha_notificacion <= '" . $arrayParametros['fecha_notificacion_fin'] . " 24:00:00' ";
		}

		if (isset($arrayParametros['fecha_cierre']) && ($arrayParametros['fecha_cierre'] != '')){
			$busqueda .= " and n.fecha_cierre = '" . $arrayParametros['fecha_cierre'] . " 24:00:00' ";
		}

		if (isset($arrayParametros['fecha_revision']) && ($arrayParametros['fecha_revision'] != '')){
			$busqueda .= " and rn.fecha_revision = '" . $arrayParametros['fecha_revision'] . " 24:00:00' ";
		}

		if (isset($arrayParametros['id_pais_notifica']) && ($arrayParametros['id_pais_notifica'] != '')){
			$busqueda .= " and n.id_pais_notifica = '" . $arrayParametros['id_pais_notifica'] . "' ";
		}

		if (isset($arrayParametros['tipo_documento']) && ($arrayParametros['tipo_documento'] != '') && ($arrayParametros['tipo_documento'] != 'Todos')){
			$busqueda .= " and n.tipo_documento = '" . $arrayParametros['tipo_documento'] . "' ";
		}

		if (isset($arrayParametros['producto']) && ($arrayParametros['producto'] != '')){
			$busqueda .= " and upper(n.producto) ilike upper('%" . $arrayParametros['producto'] . "%') ";
		}

		if (array_key_exists('area_tematica', $arrayParametros)){
			if ($arrayParametros['area_tematica'] != ''){
				$busqueda .= " and upper(atn.area_tematica) ilike upper('%" . $arrayParametros['area_tematica'] . "%') ";
			}
		}
// 		if (array_key_exists('estado_respuesta', $arrayParametros)){
// 			if ($arrayParametros['estado_respuesta'] != ''){
// 				$busqueda .= " and estado_respuesta ilike '%" . $arrayParametros['estado_respuesta'] . "%' ";
// 			}
// 		}
		if (isset($arrayParametros['estado_respuesta']) && ($arrayParametros['estado_respuesta'] != '')){
			if($arrayParametros['estado_respuesta'] == 'Vigente'){
				$fecha_actual = date("Y-m-d");
				$busqueda .= " and n.fecha_notificacion::date BETWEEN '" . date("Y-m-d",strtotime($fecha_actual."- 60 days")) . "' and '".$fecha_actual."'";
			}else if($arrayParametros['estado_respuesta'] == 'Respondido'){
				$busqueda .= " and rn.estado_respuesta = 'true' ";
			}else if(rtrim($arrayParametros['estado_respuesta']) == 'No respondido'){
				$busqueda .= " and rn.estado_respuesta = 'false' ";
			}
		}
	 $consulta = "SELECT 
                            distinct n.*,
                            (select string_agg(distinct area_tematica,', ') from g_notificaciones_fitosanitarias.area_tematica_notificacion where id_notificacion = n.id_notificacion order by 1 ) as area_tematica
                            ,estado_respuesta
                        FROM 
                            g_notificaciones_fitosanitarias.notificaciones n inner join
                            g_notificaciones_fitosanitarias.respuesta_notificacion rn on  n.id_notificacion  = rn.id_notificacion  inner join 
                            g_notificaciones_fitosanitarias.area_tematica_notificacion atn on atn.id_notificacion = n.id_notificacion
                        WHERE 
                            rn.id_padre is null
                            " . $busqueda . "
                        ORDER BY id_notificacion ASC;";

		return $this->modeloNotificaciones->ejecutarSqlNativo($consulta);
	}

	public function exportarArchivoExcel($datos){
		$hoja = new Spreadsheet();
		$documento = $hoja->getActiveSheet();
		$i = 3;
		$j = 3;
		$k = 3;

		$documento->setCellValueByColumnAndRow(1, 1, 'Reporte de Trámites ');

		$documento->setCellValueByColumnAndRow(1, 2, 'Código de documento');
		$documento->setCellValueByColumnAndRow(2, 2, 'País que notifica');
		$documento->setCellValueByColumnAndRow(3, 2, 'Tipo de documento');
		$documento->setCellValueByColumnAndRow(4, 2, 'Fecha de notificación');
		$documento->setCellValueByColumnAndRow(5, 2, 'Productos');
		$documento->setCellValueByColumnAndRow(6, 2, 'Palabra clave');
		$documento->setCellValueByColumnAndRow(7, 2, 'Descripción');
		$documento->setCellValueByColumnAndRow(8, 2, 'Enlace');
		$documento->setCellValueByColumnAndRow(9, 2, 'Paises afectados');
		$documento->setCellValueByColumnAndRow(10, 2, 'Operador');
		$documento->setCellValueByColumnAndRow(11, 2, 'Respuesta');
		$documento->setCellValueByColumnAndRow(12, 2, 'Fecha respuesta');
		$documento->setCellValueByColumnAndRow(13, 2, 'Área temática');
		$documento->setCellValueByColumnAndRow(14, 2, 'Estado');

		foreach ($datos as $fila){
			$query = "id_notificacion = '" . $fila['id_notificacion'] . "'";
			$order = "id_notificacion = '" . $fila['id_notificacion'] . "' order by id_respuesta_notificacion, fecha_respuesta ASC";

			$documento->setCellValueByColumnAndRow(1, $i, $fila['codigo_documento']);
			$documento->setCellValueByColumnAndRow(2, $i, $fila['nombre_pais_notifica']);
			$documento->setCellValueByColumnAndRow(3, $i, $fila['tipo_documento']);
			$documento->setCellValueByColumnAndRow(4, $i, ($fila['fecha_notificacion'] != null ? date('Y-m-d', strtotime($fila['fecha_notificacion'])) : ''));
			$documento->setCellValueByColumnAndRow(5, $i, $fila['producto']);
			$documento->setCellValueByColumnAndRow(6, $i, $fila['palabra_clave']);
			$documento->setCellValueByColumnAndRow(7, $i, $fila['descripcion']);
			$documento->setCellValueByColumnAndRow(8, $i, $fila['enlace']);
			$documento->setCellValueByColumnAndRow(13, $i, $fila['area_tematica']);
			$documento->setCellValueByColumnAndRow(14, $i, ($fila['estado_respuesta'] == 'true' ) ? "Respondido":"No respondido");

			$paises = $this->lNegocioNotificacionPorPaisAfectado->buscarLista($query);
			
			foreach ($paises as $filaDoc){
				$documento->setCellValueByColumnAndRow(9, $j, $filaDoc['nombre_pais']);
				$j ++;
			}

			$respuestas = $this->lNegocioRespuestaNotificacion->buscarLista($order);

			foreach ($respuestas as $filaRes){
				$documento->setCellValueByColumnAndRow(10, $k, $filaRes['tipo']);
				$documento->setCellValueByColumnAndRow(11, $k, $filaRes['respuesta']);
				$documento->setCellValueByColumnAndRow(12, $k, ($filaRes['fecha_respuesta'] != null ? date('Y-m-d', strtotime($filaRes['fecha_respuesta'])) : ''));
				$k ++;
			}

			/*
			 * foreach ($respuestas as $filaRC) {
			 * $respuestasC = $this->lNegocioRespuestaNotificacion->buscarRegistrosXCampo($filaRC['id_respuesta_notificacion']);
			 *
			 * print_r($respuestasC);
			 * foreach ($respuestasC as $filaRes) {
			 * $documento->setCellValueByColumnAndRow(10, $k, $filaRes['tipo']);
			 * $documento->setCellValueByColumnAndRow(11, $k, $filaRes['respuesta']);
			 * $documento->setCellValueByColumnAndRow(12, $k, ($filaRes['fecha_respuesta'] != null ? date('Y-m-d', strtotime($filaRes['fecha_respuesta'])) : ''));
			 * $k++;
			 * }
			 * }
			 */

			if ($j >= $k){
				$i = $j;
				$k = $j;
			}else{
				$i = $k;
				$j = $k;
			}
		}

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="excelNotificacionesAdmin.xlsx"');
		header("Pragma: no-cache");
		header("Expires: 0");

		$writer = IOFactory::createWriter($hoja, 'Xlsx');
		$writer->save('php://output');
		exit();
	}

	/* * ******* */
	/**
	 * Método para listar notificaciones registradas
	 */

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 * Buscar notificaciones usando filtros.
	 *
	 * @return array|ResultSet
	 */
	public function buscarNotificacionesXFiltro($arrayParametros){
		$busqueda = '';
		if (isset($arrayParametros['codigo_documento']) && ($arrayParametros['codigo_documento'] != '')){
			$busqueda .= " and n.codigo_documento = '" . $arrayParametros['codigo_documento'] . "' ";
		}
		if (isset($arrayParametros['fecha_notificacion']) && ($arrayParametros['fecha_notificacion'] != '')){
			$busqueda .= " and n.fecha_notificacion = '" . $arrayParametros['fecha_notificacion'] . "' ";
		}
		if (isset($arrayParametros['id_pais_notifica']) && ($arrayParametros['id_pais_notifica'] != '')){
			$busqueda .= " and n.id_pais_notifica = '" . $arrayParametros['id_pais_notifica'] . "' ";
		}
		if (isset($arrayParametros['tipo_documento']) && ($arrayParametros['tipo_documento'] != '') && ($arrayParametros['tipo_documento'] != 'Todos')){
			$busqueda .= " and n.tipo_documento = '" . $arrayParametros['tipo_documento'] . "' ";
		}
		if (isset($arrayParametros['producto']) && ($arrayParametros['producto'] != '')){
			$busqueda .= " and upper(n.producto) ilike upper('%" . $arrayParametros['producto'] . "%') ";
		}
		if (isset($arrayParametros['area_tematica']) && ($arrayParametros['area_tematica'] != '')){
			$busqueda .= " and upper(atn.area_tematica) ilike upper('%" . $arrayParametros['area_tematica'] . "%') ";
		}

		$consulta = " SELECT n.id_notificacion, n.codigo_documento,n.nombre_pais_notifica, n.producto, n.fecha_notificacion, n.fecha_cierre,
                             (select string_agg(distinct area_tematica,', ') from g_notificaciones_fitosanitarias.area_tematica_notificacion where id_notificacion = n.id_notificacion order by 1 ) as area_tematica
                      
                      FROM 
                           g_notificaciones_fitosanitarias.notificaciones n inner join 
                           g_notificaciones_fitosanitarias.area_tematica_notificacion atn on atn.id_notificacion = n.id_notificacion
                      WHERE
                            n.id_lista_notificacion = '" . $arrayParametros['id_lista_notificacion'] . "'" . $busqueda . " 
                      GROUP BY 1,2,3,4 ORDER BY n.fecha_notificacion DESC;";

		return $this->modeloNotificaciones->ejecutarSqlNativo($consulta);
	}

	public function buscarNotificacionesXFiltroOperador($arrayParametros){
		$busqueda = '';
		if (isset($arrayParametros['codigo_documento']) && ($arrayParametros['codigo_documento'] != '')){
			$busqueda .= " and n.codigo_documento ilike '%" . $arrayParametros['codigo_documento'] . "%' ";
		}
		if (isset($arrayParametros['fecha_notificacion']) && ($arrayParametros['fecha_notificacion'] != '')){
			$busqueda .= " and n.fecha_notificacion = '" . $arrayParametros['fecha_notificacion'] . "' ";
		}
		if (isset($arrayParametros['id_pais_notifica']) && ($arrayParametros['id_pais_notifica'] != '')){
			$busqueda .= " and n.id_pais_notifica = '" . $arrayParametros['id_pais_notifica'] . "' ";
		}
		if (isset($arrayParametros['tipo_documento']) && ($arrayParametros['tipo_documento'] != '') && ($arrayParametros['tipo_documento'] != 'Todos')){
			$busqueda .= " and n.tipo_documento = '" . $arrayParametros['tipo_documento'] . "' ";
		}
		if (isset($arrayParametros['producto']) && ($arrayParametros['producto'] != '')){
			$busqueda .= " and upper(n.producto) ilike upper('%" . $arrayParametros['producto'] . "%') ";
		}
		if (isset($arrayParametros['area_tematica']) && ($arrayParametros['area_tematica'] != '')){
			$busqueda .= " and upper(atn.area_tematica) ilike upper('%" . $arrayParametros['area_tematica'] . "%') ";
		}
		
	$consulta = " SELECT n.id_notificacion, n.codigo_documento,n.nombre_pais_notifica, n.producto, n.fecha_notificacion, n.fecha_cierre,
						(select string_agg(distinct area_tematica,', ') from g_notificaciones_fitosanitarias.area_tematica_notificacion where id_notificacion = n.id_notificacion order by 1 ) as area_tematica

                      FROM g_notificaciones_fitosanitarias.notificaciones n inner join  
                           g_notificaciones_fitosanitarias.area_tematica_notificacion atn on atn.id_notificacion = n.id_notificacion
                      WHERE
                            n.id_lista_notificacion = '" . $arrayParametros['id_lista_notificacion'] . "'" . $busqueda . " 
                      GROUP BY 1,2,3,4 ORDER BY n.fecha_notificacion DESC;";

		return $this->modeloNotificaciones->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 * Buscar notificaciones usando filtros.
	 *
	 * @return array|ResultSet
	 */
	public function buscarNotificacionesTecnicoXFiltro($arrayParametros){
		$busqueda = '';
		if (isset($arrayParametros['codigo_documento']) && ($arrayParametros['codigo_documento'] != '')){
			$busqueda .= " and n.codigo_documento ilike '%" . $arrayParametros['codigo_documento'] . "%' ";
		}
		if (isset($arrayParametros['fecha_notificacion']) && ($arrayParametros['fecha_notificacion'] != '')){
			$busqueda .= " and n.fecha_notificacion = '" . $arrayParametros['fecha_notificacion'] . "' ";
		}
		if (isset($arrayParametros['id_pais_notifica']) && ($arrayParametros['id_pais_notifica'] != '')){
			$busqueda .= " and n.id_pais_notifica = '" . $arrayParametros['id_pais_notifica'] . "' ";
		}
		if (isset($arrayParametros['tipo_documento']) && ($arrayParametros['tipo_documento'] != '') && ($arrayParametros['tipo_documento'] != 'Todos')){
			$busqueda .= " and n.tipo_documento = '" . $arrayParametros['tipo_documento'] . "' ";
		}
		if (isset($arrayParametros['producto']) && ($arrayParametros['producto'] != '')){
			$busqueda .= " and upper(n.producto) ilike upper('%" . $arrayParametros['producto'] . "%') ";
		}
		if (isset($arrayParametros['estado_respuesta']) && ($arrayParametros['estado_respuesta'] != '')){
			if($arrayParametros['estado_respuesta'] == 'vigente'){
				$fecha_actual = date("Y-m-d");
				$busqueda .= " and n.fecha_notificacion::date BETWEEN '" . date("Y-m-d",strtotime($fecha_actual."- 60 days")) . "' and '".$fecha_actual."'";
			}else{
				$busqueda .= " and rn.estado_respuesta = '" . $arrayParametros['estado_respuesta'] . "' ";
			}
		}
		if (isset($arrayParametros['area_tematica']) && ($arrayParametros['area_tematica'] != '')){
			$busqueda .= " and upper(atn.area_tematica) ilike upper('%" . $arrayParametros['area_tematica'] . "%') ";
		}
		if (isset($arrayParametros['fecha_inicio_notificacion']) && ($arrayParametros['fecha_inicio_notificacion'] != '')){
			$busqueda .= " and n.fecha_notificacion >= '" . $arrayParametros['fecha_inicio_notificacion'] . "' ";
		}
		if (isset($arrayParametros['fecha_fin_notificacion']) && ($arrayParametros['fecha_fin_notificacion'] != '')){
			$busqueda .= " and n.fecha_notificacion <= '" . $arrayParametros['fecha_fin_notificacion'] . "' ";
		}

	 $consulta = " SELECT distinct n.id_notificacion, n.codigo_documento,n.nombre_pais_notifica, n.producto, n.fecha_notificacion, n.fecha_cierre,--, rn.estado_respuesta
						     (select string_agg(distinct area_tematica,', ') from g_notificaciones_fitosanitarias.area_tematica_notificacion where id_notificacion = n.id_notificacion order by 1 ) as area_tematica
                      FROM g_notificaciones_fitosanitarias.notificaciones n inner join  
                           g_notificaciones_fitosanitarias.area_tematica_notificacion atn on atn.id_notificacion = n.id_notificacion
                      INNER JOIN g_notificaciones_fitosanitarias.respuesta_notificacion rn ON rn.id_notificacion = n.id_notificacion
            
                      WHERE
                            rn.tipo = 'operador' and
                            --rn.estado_respuesta = 'false' and
                            rn.finalizar_respuesta = 'false' and
                            n.id_lista_notificacion = '" . $arrayParametros['id_lista_notificacion'] . "'" . $busqueda . "
                      ORDER BY 	n.fecha_notificacion DESC;";

		// print_r($consulta);
		return $this->modeloNotificaciones->ejecutarSqlNativo($consulta);
	}

	public function buscarNotificacionesXMes($arrayParametros){
		$busqueda='';
		if (array_key_exists('comentadas', $arrayParametros)){
			$busqueda =' inner join g_notificaciones_fitosanitarias.respuesta_notificacion r  on r.id_notificacion=n.id_notificacion';
		}
		 $consulta = "SELECT 
							distinct(n.id_notificacion), n.codigo_documento, n.nombre_pais_notifica, n.producto, n.fecha_notificacion, n.fecha_cierre,
							(select string_agg(distinct area_tematica,', ') from g_notificaciones_fitosanitarias.area_tematica_notificacion where id_notificacion = n.id_notificacion order by 1 ) as area_tematica
                    FROM 
					g_notificaciones_fitosanitarias.notificaciones n inner join  
                    g_notificaciones_fitosanitarias.area_tematica_notificacion atn on atn.id_notificacion = n.id_notificacion
					".$busqueda."
                    WHERE n.id_lista_notificacion = '" . $arrayParametros['id_lista_notificacion'] . "' 
                    AND now() <= fecha_cierre
                  
                    ";

		/*
		 * "SELECT * FROM g_notificaciones_fitosanitarias.notificaciones n, g_notificaciones_fitosanitarias.respuesta_notificacion r
		 * WHERE n.id_lista_notificacion = '" . $arrayParametros['id_lista_notificacion'] . "'
		 * AND n.id_notificacion = r.id_notificacion
		 * AND r.tipo = 'operador'
		 * AND r.id_padre is null
		 * AND now() <= fecha_cierre"
		 */

		return $this->modeloNotificaciones->ejecutarSqlNativo($consulta);
	}
	
	public function buscarNotificacionesComentadas($arrayParametros){
		$consulta = "SELECT
							distinct(n.id_notificacion), n.codigo_documento, n.nombre_pais_notifica, n.producto, n.fecha_notificacion, n.fecha_cierre,
							(select string_agg(distinct area_tematica,', ') from g_notificaciones_fitosanitarias.area_tematica_notificacion where id_notificacion = n.id_notificacion order by 1 ) as area_tematica
                    FROM
					g_notificaciones_fitosanitarias.notificaciones n inner join
                    g_notificaciones_fitosanitarias.area_tematica_notificacion atn on atn.id_notificacion = n.id_notificacion
					inner join g_notificaciones_fitosanitarias.respuesta_notificacion r on r.id_notificacion = n.id_notificacion
                    WHERE n.id_lista_notificacion = '" . $arrayParametros['id_lista_notificacion'] . "'
                    --AND n.id_notificacion = r.id_notificacion
                    --AND r.tipo = 'operador'
                    AND now() <= fecha_cierre
                    --AND r.estado_respuesta = 'false'
                    ";
		
		return $this->modeloNotificaciones->ejecutarSqlNativo($consulta);
	}
	
	

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 * Buscar notificaciones usando filtros.
	 *
	 * @return array|ResultSet
	 */
	public function buscarNotificacionesTecnicoXFiltroTodos($arrayParametros){
		$busqueda = '';
		if (isset($arrayParametros['codigo_documento']) && ($arrayParametros['codigo_documento'] != '')){
			$busqueda .= " and n.codigo_documento ilike '%" . $arrayParametros['codigo_documento'] . "%' ";
		}
		if (isset($arrayParametros['fecha_notificacion']) && ($arrayParametros['fecha_notificacion'] != '')){
			$busqueda .= " and n.fecha_notificacion = '" . $arrayParametros['fecha_notificacion'] . "' ";
		}
		if (isset($arrayParametros['id_pais_notifica']) && ($arrayParametros['id_pais_notifica'] != '')){
			$busqueda .= " and n.id_pais_notifica = '" . $arrayParametros['id_pais_notifica'] . "' ";
		}
		if (isset($arrayParametros['tipo_documento']) && ($arrayParametros['tipo_documento'] != '') && ($arrayParametros['tipo_documento'] != 'Todos')){
			$busqueda .= " and n.tipo_documento = '" . $arrayParametros['tipo_documento'] . "' ";
		}
		if (isset($arrayParametros['producto']) && ($arrayParametros['producto'] != '')){
			$busqueda .= " and upper(n.producto) ilike upper('%" . $arrayParametros['producto'] . "%') ";
		}
		if (isset($arrayParametros['area_tematica']) && ($arrayParametros['area_tematica'] != '')){
			$busqueda .= " and upper(atn.area_tematica) ilike upper('%" . $arrayParametros['area_tematica'] . "%') ";
		}

		$consulta = " SELECT 
                            distinct n.id_notificacion, n.codigo_documento,n.nombre_pais_notifica, n.producto, n.fecha_notificacion, n.fecha_cierre,
                           (select string_agg(distinct area_tematica,', ') from g_notificaciones_fitosanitarias.area_tematica_notificacion where id_notificacion = n.id_notificacion order by 1 ) as area_tematica
                      FROM 
                            g_notificaciones_fitosanitarias.notificaciones n inner join  
                            g_notificaciones_fitosanitarias.area_tematica_notificacion atn on atn.id_notificacion = n.id_notificacion
                      WHERE
                            n.id_lista_notificacion = '" . $arrayParametros['id_lista_notificacion'] . "'" . $busqueda . "
                      ORDER BY 	
                            n.fecha_notificacion DESC;";

		// print_r($consulta);
		return $this->modeloNotificaciones->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 * Buscar notificaciones usando filtros.
	 *
	 * @return array|ResultSet
	 */
	public function buscarNotificacionesAreasTematicas($arrayParametros){
		$busqueda = '';

		$consulta = " SELECT
                            distinct n.id_notificacion, n.codigo_documento,n.nombre_pais_notifica, n.producto, n.fecha_notificacion, n.fecha_cierre,
                            (select string_agg(distinct area_tematica,', ') from g_notificaciones_fitosanitarias.area_tematica_notificacion where id_notificacion = n.id_notificacion order by 1 ) as area_tematica
                      FROM
                            g_notificaciones_fitosanitarias.notificaciones n
                      WHERE
                            n.id_lista_notificacion = '" . $arrayParametros['id_lista_notificacion'] . "'" . $busqueda . "
                      ORDER BY
                            n.fecha_notificacion DESC;";

		// print_r($consulta);
		return $this->modeloNotificaciones->ejecutarSqlNativo($consulta);
	}
}

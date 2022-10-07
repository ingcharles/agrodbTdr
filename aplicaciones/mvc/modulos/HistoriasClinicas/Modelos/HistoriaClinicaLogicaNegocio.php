<?php
/**
 * Lógica del negocio de HistoriaClinicaModelo
 *
 * Este archivo se complementa con el archivo HistoriaClinicaControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses HistoriaClinicaLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class HistoriaClinicaLogicaNegocio implements IModelo{

	private $modeloHistoriaClinica = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloHistoriaClinica = new HistoriaClinicaModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new HistoriaClinicaModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdHistoriaClinica() != null && $tablaModelo->getIdHistoriaClinica() > 0){
			return $this->modeloHistoriaClinica->actualizar($datosBd, $tablaModelo->getIdHistoriaClinica());
		}else{
			unset($datosBd["id_historia_clinica"]);
			return $this->modeloHistoriaClinica->guardar($datosBd);
		}
	}

	public function guardarRegistros(Array $datos){
		try{
			$this->modeloHistoriaClinica = new HistoriaClinicaModelo();
			$proceso = $this->modeloHistoriaClinica->getAdapter()
				->getDriver()
				->getConnection();
			if (! $proceso->beginTransaction()){
				throw new \Exception('No se pudo iniciar la transacción: actualizar historia clinica ');
			}
			foreach ($datos['descripcion_concepto'] as $value){
				$descripConcep = $value;
			}
			$datos['descripcion_concepto'] = $descripConcep;
			$datos['estado'] = 'Registrado';
			$datos['identificador_medico'] = $_SESSION['usuario'];
			$tablaModelo = new HistoriaClinicaModelo($datos);
			$datosBd = $tablaModelo->getPrepararDatos();
			$this->modeloHistoriaClinica->actualizar($datosBd, $tablaModelo->getIdHistoriaClinica());

			$lnegocioAusentismoMedico = new AusentismoMedicoLogicaNegocio();
			$verificarAusentismo = $lnegocioAusentismoMedico->buscarLista("id_historia_clinica = " . $tablaModelo->getIdHistoriaClinica());

			foreach ($datos['ausentismo'] as $value){
				$ausentismo = $value;
			}
			$arrayAusentismo = array(
				'id_historia_clinica' => $datos['id_historia_clinica'],
				'ausentismo' => $ausentismo,
				'causa' => $datos['causa'],
				'tiempo' => ($datos['tiempo'] != '') ? $datos['tiempo'] : null);
			$statement = $this->modeloHistoriaClinica->getAdapter()
				->getDriver()
				->createStatement();
			if ($verificarAusentismo->count()){
				$sqlActualizar = $this->modeloHistoriaClinica->actualizarSql('ausentismo_medico', $this->modeloHistoriaClinica->getEsquema());
				$sqlActualizar->set($arrayAusentismo);
				$sqlActualizar->where(array(
					'id_ausentismo_medico' => $verificarAusentismo->current()->id_ausentismo_medico));
				$sqlActualizar->prepareStatement($this->modeloHistoriaClinica->getAdapter(), $statement);
				$statement->execute();
			}else{
				$sqlInsertar = $this->modeloHistoriaClinica->guardarSql('ausentismo_medico', $this->modeloHistoriaClinica->getEsquema());
				$sqlInsertar->columns($lnegocioAusentismoMedico->columnas());
				$sqlInsertar->values($arrayAusentismo, $sqlInsertar::VALUES_MERGE);
				$sqlInsertar->prepareStatement($this->modeloHistoriaClinica->getAdapter(), $statement);
				$statement->execute();
			}
			// *****************elementos de proteccion*********************************************
			if (isset($datos['elementoProteccion'])){
				$arrayEliminar = array();
				$arrayGuardar = array();
				$arrayDatos = array();
				$lnegocioElementoProteccion = new ElementoProteccionLogicaNegocio();
				$verificarElemento = $lnegocioElementoProteccion->buscarLista("id_historia_clinica = " . $tablaModelo->getIdHistoriaClinica());
				$lnegocioProcedimiento = new ProcedimientoMedicoLogicaNegocio();
				$procedi = $lnegocioProcedimiento->buscarLista("nombre ='Elementos de protección'");
				if ($verificarElemento->count()){
					foreach ($verificarElemento as $valor1){
						$arrayDatos[] = $valor1->id_tipo_procedimiento_medico;
						$ban = 1;
						foreach ($datos['elementoProteccion'] as $valor2){
							if ($valor1->id_tipo_procedimiento_medico == $valor2){
								$ban = 0;
							}
						}
						if ($ban){
							$arrayEliminar[] = $valor1->id_elemento_proteccion;
						}
					}

					foreach ($datos['elementoProteccion'] as $valor2){
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
						$statement = $this->modeloHistoriaClinica->getAdapter()
							->getDriver()
							->createStatement();
						$sqlActualizar = $this->modeloHistoriaClinica->borrarSql('elemento_proteccion', $this->modeloHistoriaClinica->getEsquema());
						$sqlActualizar->where(array(
							'id_elemento_proteccion' => $value));
						$sqlActualizar->prepareStatement($this->modeloHistoriaClinica->getAdapter(), $statement);
						$statement->execute();
					}
					foreach ($arrayGuardar as $value){
						$arrayElemento = array(
							'id_historia_clinica' => $datos['id_historia_clinica'],
							'id_procedimiento_medico' => $procedi->current()->id_procedimiento_medico,
							'id_tipo_procedimiento_medico' => $value);
						$statement = $this->modeloHistoriaClinica->getAdapter()
							->getDriver()
							->createStatement();
						$sqlInsertar = $this->modeloHistoriaClinica->guardarSql('elemento_proteccion', $this->modeloHistoriaClinica->getEsquema());
						$sqlInsertar->columns($lnegocioElementoProteccion->columnas());
						$sqlInsertar->values($arrayElemento, $sqlInsertar::VALUES_MERGE);
						$sqlInsertar->prepareStatement($this->modeloHistoriaClinica->getAdapter(), $statement);
						$statement->execute();
					}
				}else{
					foreach ($datos['elementoProteccion'] as $value){
						$arrayElemento = array(
							'id_historia_clinica' => $datos['id_historia_clinica'],
							'id_procedimiento_medico' => $procedi->current()->id_procedimiento_medico,
							'id_tipo_procedimiento_medico' => $value);
						$statement = $this->modeloHistoriaClinica->getAdapter()
							->getDriver()
							->createStatement();
						$sqlInsertar = $this->modeloHistoriaClinica->guardarSql('elemento_proteccion', $this->modeloHistoriaClinica->getEsquema());
						$sqlInsertar->columns($lnegocioElementoProteccion->columnas());
						$sqlInsertar->values($arrayElemento, $sqlInsertar::VALUES_MERGE);
						$sqlInsertar->prepareStatement($this->modeloHistoriaClinica->getAdapter(), $statement);
						$statement->execute();
					}
				}
			}
			// *****************enfermedad profecional*********************************************

			$lnegocioEnfermedadProfecional = new EnfermedadProfesionalLogicaNegocio();
			$verificarEnfermedadP = $lnegocioEnfermedadProfecional->buscarLista("id_historia_clinica = " . $tablaModelo->getIdHistoriaClinica());

			$arrayEnfermedadP = array(
				'id_historia_clinica' => $datos['id_historia_clinica'],
				'tiene_enfermedad' => $datos['tiene_enfermedad'],
				'fecha_diagnostico' => ($datos['fecha_diagnostico'] != '') ? $datos['fecha_diagnostico'] : null,
				'descripcion' => $datos['descripcion']);
			$statement = $this->modeloHistoriaClinica->getAdapter()
				->getDriver()
				->createStatement();
			if ($verificarEnfermedadP->count()){
				$sqlActualizar = $this->modeloHistoriaClinica->actualizarSql('enfermedad_profesional', $this->modeloHistoriaClinica->getEsquema());
				$sqlActualizar->set($arrayEnfermedadP);
				$sqlActualizar->where(array(
					'id_enfermedad_profesional' => $verificarEnfermedadP->current()->id_enfermedad_profesional));
				$sqlActualizar->prepareStatement($this->modeloHistoriaClinica->getAdapter(), $statement);
				$statement->execute();
			}else{
				$sqlInsertar = $this->modeloHistoriaClinica->guardarSql('enfermedad_profesional', $this->modeloHistoriaClinica->getEsquema());
				$sqlInsertar->columns($lnegocioEnfermedadProfecional->columnas());
				$sqlInsertar->values($arrayEnfermedadP, $sqlInsertar::VALUES_MERGE);
				$sqlInsertar->prepareStatement($this->modeloHistoriaClinica->getAdapter(), $statement);
				$statement->execute();
			}

			// *****************examen físico*******************************************************
			$lnegocioExamenFisico = new ExamenFisicoLogicaNegocio();
			$verificarExamenFisico = $lnegocioExamenFisico->buscarLista("id_historia_clinica = " . $tablaModelo->getIdHistoriaClinica());

			$arrayExamenFisico = array(
				'id_historia_clinica' => $datos['id_historia_clinica'],
				'tension_arterial' => $datos['tension_arterial'],
				'saturacion_oxigeno' => $datos['saturacion_oxigeno'],
				'frecuencia_cardiaca' => $datos['frecuencia_cardiaca'],
				'frecuencia_respiratoria' => $datos['frecuencia_respiratoria'],
				'talla_mts' => $datos['talla_mts'],
				'temperatura_c' => $datos['temperatura_c'],
				'peso_kg' => $datos['peso_kg'],
				'imc' => $datos['imc'],
				'interpretacion_imc' => $datos['interpretacion_imc']);
			$statement = $this->modeloHistoriaClinica->getAdapter()
				->getDriver()
				->createStatement();
			if ($verificarExamenFisico->count()){
				$sqlActualizar = $this->modeloHistoriaClinica->actualizarSql('examen_fisico', $this->modeloHistoriaClinica->getEsquema());
				$sqlActualizar->set($arrayExamenFisico);
				$sqlActualizar->where(array(
					'id_examen_fisico' => $verificarExamenFisico->current()->id_examen_fisico));
				$sqlActualizar->prepareStatement($this->modeloHistoriaClinica->getAdapter(), $statement);
				$statement->execute();
			}else{
				$sqlInsertar = $this->modeloHistoriaClinica->guardarSql('examen_fisico', $this->modeloHistoriaClinica->getEsquema());
				$sqlInsertar->columns($lnegocioExamenFisico->columnas());
				$sqlInsertar->values($arrayExamenFisico, $sqlInsertar::VALUES_MERGE);
				$sqlInsertar->prepareStatement($this->modeloHistoriaClinica->getAdapter(), $statement);
				$statement->execute();
			}
			// *****************************evaluacion primaria********************************************
			if (isset($datos['evaluacionPrimaria'])){
				$evaluacionPrimaria = array();
				$evaluacionPrimariaTxt = array();
				$combinar = array();
				$subtiposArray = array();
				$subtiposArrayTxt = array();
				if (isset($datos['evaluacionPrimaria'])){
					foreach ($datos['evaluacionPrimaria'] as $value){
						$subtip = explode("-", $value);
						$datosIndi[] = $subtip[1];
						$evaluacionPrimaria[] = [
							$subtip[1] => [
								$subtip[2],
								$subtip[0]]];
						$subtiposArray[] = [
							$subtip[2],
							$subtip[0],
							$subtip[1]];
					}
				}
				if (isset($datos['evaluacionPrimariatxt'])){
					foreach ($datos['evaluacionPrimariatxt'] as $value){
						$subtipTxt = explode("-", $value);
						$datosIndi[] = $subtipTxt[0];
						$evaluacionPrimariaTxt[] = [
							$subtipTxt[0] => [
								$subtipTxt[1],
								$subtipTxt[2]]];
						$subtiposArrayTxt[] = [
							$subtipTxt[1],
							$subtipTxt[2],
							$subtipTxt[0]];
					}
				}
				$datosIndi = array_unique($datosIndi);
				foreach ($datosIndi as $value){
					foreach ($evaluacionPrimaria as $item){
						$ban = 1;
						if (isset($item[$value][0])){
							foreach ($evaluacionPrimariaTxt as $item2){
								if (isset($item2[$value][0])){
									if ($item2[$value][0] == $item[$value][0]){
										$combinar[] = [
											$value => [
												$item[$value][0],
												$item[$value][1],
												$item2[$value][1]]];
										$ban = 0;
									}
								}
							}
							if ($ban){
								$combinar[] = [
									$value => [
										$item[$value][0],
										$item[$value][1],
										'']];
							}
						}
					}
				}
				$combinadoSub = array();
				foreach ($subtiposArray as $sub1){
					$ban = 1;
					foreach ($subtiposArrayTxt as $sub2){
						if ($sub1[0] == $sub2[0]){
							$ban = 0;
							$combinadoSub[] = [
								$sub1[0],
								$sub1[1],
								$sub2[1],
								$sub1[2]];
						}
					}
					if ($ban){
						$combinadoSub[] = [
							$sub1[0],
							$sub1[1],
							'',
							$sub1[2]];
					}
				}
				$arrayEliminarTodo = array();
				$arrayGuardarTodo = array();
				$arrayGuardar = array();
				$arrayDatos = array();
				$arrayExiste = array();
				$lnegocioEvaluacionPrimaria = new EvaluacionPrimariaLogicaNegocio();
				$lnegocioDetalleEvaluacionPrimaria = new DetalleEvaluacionPrimariaLogicaNegocio();
				$verificarElemento = $lnegocioEvaluacionPrimaria->buscarLista("id_historia_clinica = " . $tablaModelo->getIdHistoriaClinica());
				$lnegocioProcedimiento = new ProcedimientoMedicoLogicaNegocio();
				$procedi = $lnegocioProcedimiento->buscarLista("nombre ='Examen físico'");
				if ($verificarElemento->count()){
					foreach ($verificarElemento as $valor1){
						$arrayDatos[] = $valor1->id_tipo_procedimiento_medico;
						$ban = 1;
						foreach ($combinar as $valor2){
							if (isset($valor2[$valor1->id_tipo_procedimiento_medico][0])){
								$ban = 0;
							}
						}
						if ($ban){
							$arrayEliminarTodo[] = $valor1->id_evaluacion_primaria;
						}else{
							$arrayExiste[] = $valor1->id_evaluacion_primaria;
						}
					}

					foreach ($combinar as $valor2){
						$ban = 1;
						foreach ($arrayDatos as $valor1){
							if (isset($valor2[$valor1][0])){
								$ban = 0;
							}
						}
						if ($ban){
							$arrayGuardarTodo[] = $valor2;
						}
					}
					$arrayExiste = array_unique($arrayExiste);
					$arrayDetalle = array();
					$arrayEliminarDetalle = array();
					foreach ($arrayExiste as $value){
						$detalleEvaPri = $lnegocioDetalleEvaluacionPrimaria->buscarLista("id_evaluacion_primaria=" . $value);
						if ($detalleEvaPri->count()){
							foreach ($detalleEvaPri as $valor){
								$arrayDetalle[] = [
									$valor->id_subtipo_proced_medico,
									$valor->id_evaluacion_primaria];
								$ban = 1;
								foreach ($combinadoSub as $val){
									if ($valor->id_subtipo_proced_medico == $val[0]){
										$ban = 0;
									}
								}
								if ($ban){
									$arrayEliminarDetalle[] = [
										$valor->id_subtipo_proced_medico,
										$value];
								}
							}
						}
					}

					$arrayGuardarDetalle = array();
					$arrayActualizarDetalle = array();
					foreach ($combinadoSub as $val1){
						$ban = 1;
						foreach ($arrayDetalle as $val2){
							if ($val1[0] == $val2[0]){
								$ban = 0;
							}
						}
						if ($ban){
							$arrayGuardarDetalle[] = [
								$val1[0],
								$val1[1],
								$val1[2],
								$val1[3]];
						}else{
							$arrayActualizarDetalle[] = [
								$val1[0],
								$val1[1],
								$val1[2],
								$val1[3]];
						}
					}

					foreach ($arrayEliminarTodo as $value){
						$this->borrarDetalleEvaluacion($value);
						$this->borrarEvaluacionPrimaria($value);
					}

					$indice = array();
					foreach ($arrayGuardarTodo as $value){
						$indice[] = key($value);
					}
					$indice = array_unique($indice);
					foreach ($indice as $value){
						$arrayEvaluacionPrimaria = array(
							'id_historia_clinica' => $datos['id_historia_clinica'],
							'id_procedimiento_medico' => $procedi->current()->id_procedimiento_medico,
							'id_tipo_procedimiento_medico' => $value);

						$idEvaluacionPrimaria = $this->guardarEvaluacionPrimaria($arrayEvaluacionPrimaria);
						if (! $idEvaluacionPrimaria){
							throw new \Exception('Error al guardar los datos en evaluacion_primaria');
						}
						foreach ($arrayGuardarTodo as $item){
							if (isset($item[$value])){
								$detalleEvaluacion = array(
									'id_evaluacion_primaria' => $idEvaluacionPrimaria,
									'id_subtipo_proced_medico' => intval($item[$value][0]),
									'normal' => $item[$value][1],
									'observaciones' => $item[$value][2]);

								$this->guardarDetalleEvaluacion($detalleEvaluacion);
							}
						}
					}

					foreach ($arrayEliminarDetalle as $value){
						$this->borrarDetalleEvaluacionUnico($value[1], $value[0]);
					}

					foreach ($arrayGuardarDetalle as $value){
						if (isset($value[3])){
							$idEvaPri = $lnegocioEvaluacionPrimaria->buscarLista("id_tipo_procedimiento_medico = " . $value[3] . " and id_historia_clinica=" . $datos['id_historia_clinica']);
							if ($idEvaPri->count()){
								$detalleEvaluacion = array(
									'id_evaluacion_primaria' => $idEvaPri->current()->id_evaluacion_primaria,
									'id_subtipo_proced_medico' => intval($value[0]),
									'normal' => $value[1],
									'observaciones' => $value[2]);
								$this->guardarDetalleEvaluacion($detalleEvaluacion);
							}
						}
					}

					foreach ($arrayActualizarDetalle as $value){
						if (isset($value[3])){
							$idEvaPri = $lnegocioEvaluacionPrimaria->buscarLista("id_tipo_procedimiento_medico = " . $value[3] . " and id_historia_clinica=" . $datos['id_historia_clinica']);
							if ($idEvaPri->count()){
								$detalleEvaluacion = array(
									'normal' => $value[1],
									'observaciones' => $value[2]);
								$this->actualizarDetalleEvaluacion($detalleEvaluacion, $idEvaPri->current()->id_evaluacion_primaria, $value[0]);
							}
						}
					}
				}else{
					// ****************************************************************************************************************************
					foreach ($datosIndi as $value){
						$arrayEvaluacionPrimaria = array(
							'id_historia_clinica' => $datos['id_historia_clinica'],
							'id_procedimiento_medico' => $procedi->current()->id_procedimiento_medico,
							'id_tipo_procedimiento_medico' => $value);

						$idEvaluacionPrimaria = $this->guardarEvaluacionPrimaria($arrayEvaluacionPrimaria);
						if (! $idEvaluacionPrimaria){
							throw new \Exception('Error al guardar los datos en evaluacion_primaria');
						}

						foreach ($combinar as $item){
							if (isset($item[$value][0])){
								$detalleEvaluacion = array(
									'id_evaluacion_primaria' => $idEvaluacionPrimaria,
									'id_subtipo_proced_medico' => intval($item[$value][0]),
									'normal' => $item[$value][1],
									'observaciones' => $item[$value][2]);

								$this->guardarDetalleEvaluacion($detalleEvaluacion);
							}
						}
					}
				}
			}

			// ****************************recomendaciones***************************************************
			$lnegocioRecomendaciones = new RecomendacionesLogicaNegocio();
			$verificarRecomendacion = $lnegocioRecomendaciones->buscarLista("id_historia_clinica = " . $tablaModelo->getIdHistoriaClinica());

			foreach ($datos['reubicacion_laboral'] as $value){
				$reubicacion_laboral = $value;
			}
			$arrayRecomendacion = array(
				'id_historia_clinica' => $datos['id_historia_clinica'],
				'descripcion' => $datos['descripcion_recomendaciones'],
				'reubicacion_laboral' => $reubicacion_laboral);
			$statement = $this->modeloHistoriaClinica->getAdapter()
				->getDriver()
				->createStatement();
			if ($verificarRecomendacion->count()){
				$sqlActualizar = $this->modeloHistoriaClinica->actualizarSql('recomendaciones', $this->modeloHistoriaClinica->getEsquema());
				$sqlActualizar->set($arrayRecomendacion);
				$sqlActualizar->where(array(
					'id_recomendaciones' => $verificarRecomendacion->current()->id_recomendaciones));
				$sqlActualizar->prepareStatement($this->modeloHistoriaClinica->getAdapter(), $statement);
				$statement->execute();
			}else{
				$sqlInsertar = $this->modeloHistoriaClinica->guardarSql('recomendaciones', $this->modeloHistoriaClinica->getEsquema());
				$sqlInsertar->columns($lnegocioRecomendaciones->columnas());
				$sqlInsertar->values($arrayRecomendacion, $sqlInsertar::VALUES_MERGE);
				$sqlInsertar->prepareStatement($this->modeloHistoriaClinica->getAdapter(), $statement);
				$statement->execute();
			}
			// ****************************registrar cambios***************************************************
			$lnegocioLog = new LogLogicaNegocio();
			$arrayLog = array(
				'identificador' => $_SESSION['usuario'],
				'id_historia_clinica' => $datos['id_historia_clinica'],
				'accion' => 'Actualizacion');
			$statement = $this->modeloHistoriaClinica->getAdapter()
				->getDriver()
				->createStatement();
			$sqlInsertar = $this->modeloHistoriaClinica->guardarSql('log', $this->modeloHistoriaClinica->getEsquema());
			$sqlInsertar->columns($lnegocioLog->columnas());
			$sqlInsertar->values($arrayLog, $sqlInsertar::VALUES_MERGE);
			$sqlInsertar->prepareStatement($this->modeloHistoriaClinica->getAdapter(), $statement);
			$statement->execute();
			// **********************************************************************************************

			$proceso->commit();
			return true;
		}catch (\Exception $ex){
			$proceso->rollback();
			throw new \Exception($ex->getMessage());
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
		$this->modeloHistoriaClinica->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return HistoriaClinicaModelo
	 */
	public function buscar($id){
		return $this->modeloHistoriaClinica->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloHistoriaClinica->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloHistoriaClinica->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarHistoriaClinica(){
		$consulta = "SELECT * FROM " . $this->modeloHistoriaClinica->getEsquema() . ". historia_clinica";
		return $this->modeloHistoriaClinica->ejecutarSqlNativo($consulta);
	}

	/**
	 * consulta personalizada para consultar información personal del paciente
	 */
	public function buscarInformacionPaciente($arrayParametros){
		$consulta = "SELECT 
						identificador,nombre ||' '||apellido as funcionario,genero, estado_civil, fecha_nacimiento, edad, 
       					tipo_sangre,(SELECT nombre FROM g_catalogos.localizacion WHERE id_localizacion=canton_nacimiento) as canton, convencional,tiene_discapacidad, carnet_conadis_empleado, representante_familiar_discapacidad,  
       					tiene_enfermedad_catastrofica, nombre_enfermedad_catastrofica,carnet_conadis_familiar, mail_personal,
				        mail_institucional,religion,lateralidad,orientacion_sexual,(SELECT nivel_instruccion
						FROM g_uath.datos_academicos where identificador = fe.identificador and estado = 'Aceptado'  ORDER BY id_datos_academicos DESC LIMIT 1) as nivel_instruccion

  					FROM 
						g_uath.ficha_empleado fe 
					WHERE
						fe.identificador ='" . $arrayParametros['identificador_paciente'] . "' and estado_empleado ='activo' ";
		return $this->modeloHistoriaClinica->ejecutarSqlNativo($consulta);
	}

	/**
	 * obtener datos de contrato
	 */
	public function obtenerDatosContrato($arrayParametros){
		$consulta = "SELECT 
						oficina, direccion, coordinacion,nombre_puesto,(SELECT fecha_inicial FROM g_certificados_uath.devolver_fecha_inicial(dc.identificador) order by 1 ASC limit 1) as fecha_inicial,
						(SELECT jornada_laboral FROM g_uath.ficha_empleado fe WHERE fe.identificador=dc.identificador and estado_empleado ='activo') as jornada_laboral 
					FROM
						g_uath.datos_contrato dc
					WHERE 
						identificador='" . $arrayParametros['identificador_paciente'] . "' and estado = 1;";
		return $this->modeloHistoriaClinica->ejecutarSqlNativo($consulta);
	}

	/**
	 * obtener datos de firma
	 */
	public function obtenerDatosFirma($arrayParametros){
		$consulta = "SELECT 
						identificador, nombre ||' '||apellido as funcionario, 
						(SELECT nombre_puesto FROM g_uath.datos_contrato dc 
					WHERE 
						identificador=fe.identificador and estado = 1) as cargo
  					FROM 
						g_uath.ficha_empleado fe where fe.identificador ='" . $arrayParametros['identificador'] . "' and estado_empleado ='activo'";
		return $this->modeloHistoriaClinica->ejecutarSqlNativo($consulta);
	}

	/**
	 * contar elemntos de un array
	 */
	public function contarElementosArray(Array $datos){
		$cantidad = 0;

		foreach ($datos as $item){
			$cantidad ++;
		}

		return $cantidad;
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para borrar detalles
	 *
	 * @return array
	 */
	public function borrarDetalleEvaluacion($idEvaluacionPrimaria){
		$statement = $this->modeloHistoriaClinica->getAdapter()
			->getDriver()
			->createStatement();
		$sqlActualizar = $this->modeloHistoriaClinica->borrarSql('detalle_evaluacion_primaria', $this->modeloHistoriaClinica->getEsquema());
		$sqlActualizar->where(array(
			'id_evaluacion_primaria' => $idEvaluacionPrimaria));
		$sqlActualizar->prepareStatement($this->modeloHistoriaClinica->getAdapter(), $statement);
		$statement->execute();
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para borrar detalles
	 *
	 * @return array
	 */
	public function borrarDetalleEvaluacionUnico($idEvaluacionPrimaria, $idSubTipo){
		$statement = $this->modeloHistoriaClinica->getAdapter()
			->getDriver()
			->createStatement();
		$sqlActualizar = $this->modeloHistoriaClinica->borrarSql('detalle_evaluacion_primaria', $this->modeloHistoriaClinica->getEsquema());
		$sqlActualizar->where(array(
			'id_evaluacion_primaria' => $idEvaluacionPrimaria,
			'id_subtipo_proced_medico' => $idSubTipo));
		$sqlActualizar->prepareStatement($this->modeloHistoriaClinica->getAdapter(), $statement);
		$statement->execute();
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para guardar
	 *
	 * @return array
	 */
	public function guardarDetalleEvaluacion($datos){
		$lnegocioDetalleEvaluacionPrimaria = new DetalleEvaluacionPrimariaLogicaNegocio();
		$statement = $this->modeloHistoriaClinica->getAdapter()
			->getDriver()
			->createStatement();
		$sqlInsertar = $this->modeloHistoriaClinica->guardarSql('detalle_evaluacion_primaria', $this->modeloHistoriaClinica->getEsquema());
		$sqlInsertar->columns($lnegocioDetalleEvaluacionPrimaria->columnas());
		$sqlInsertar->values($datos, $sqlInsertar::VALUES_MERGE);
		$sqlInsertar->prepareStatement($this->modeloHistoriaClinica->getAdapter(), $statement);
		$statement->execute();
		$id = $this->modeloHistoriaClinica->adapter->driver->getLastGeneratedValue($this->modeloHistoriaClinica->getEsquema() . '.detalle_evaluacion_primaria_id_detalle_eval_primaria_seq');
		return $id;
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para actualizar
	 *
	 * @return array
	 */
	public function actualizarDetalleEvaluacion($datos, $idEvaluacionPrimaria, $idSubTipo){
		$statement = $this->modeloHistoriaClinica->getAdapter()
			->getDriver()
			->createStatement();
		$sqlActualizar = $this->modeloHistoriaClinica->actualizarSql('detalle_evaluacion_primaria', $this->modeloHistoriaClinica->getEsquema());
		$sqlActualizar->set($datos);
		$sqlActualizar->where(array(
			'id_evaluacion_primaria' => $idEvaluacionPrimaria,
			'id_subtipo_proced_medico' => $idSubTipo));
		$sqlActualizar->prepareStatement($this->modeloHistoriaClinica->getAdapter(), $statement);
		$statement->execute();
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para borrar evaluacion primaria
	 *
	 * @return array
	 */
	public function borrarEvaluacionPrimaria($idEvaluacionPrimaria){
		$statement = $this->modeloHistoriaClinica->getAdapter()
			->getDriver()
			->createStatement();
		$sqlActualizar = $this->modeloHistoriaClinica->borrarSql('evaluacion_primaria', $this->modeloHistoriaClinica->getEsquema());
		$sqlActualizar->where(array(
			'id_evaluacion_primaria' => $idEvaluacionPrimaria));
		$sqlActualizar->prepareStatement($this->modeloHistoriaClinica->getAdapter(), $statement);
		$statement->execute();
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para guardar
	 *
	 * @return array
	 */
	public function guardarEvaluacionPrimaria($datos){
		$lnegocioEvaluacionPrimaria = new EvaluacionPrimariaLogicaNegocio();
		$statement = $this->modeloHistoriaClinica->getAdapter()
			->getDriver()
			->createStatement();
		$sqlInsertar = $this->modeloHistoriaClinica->guardarSql('evaluacion_primaria', $this->modeloHistoriaClinica->getEsquema());
		$sqlInsertar->columns($lnegocioEvaluacionPrimaria->columnas());
		$sqlInsertar->values($datos, $sqlInsertar::VALUES_MERGE);
		$sqlInsertar->prepareStatement($this->modeloHistoriaClinica->getAdapter(), $statement);
		$statement->execute();
		$idEvaluacionPrimaria = $this->modeloHistoriaClinica->adapter->driver->getLastGeneratedValue($this->modeloHistoriaClinica->getEsquema() . '.evaluacion_primaria_id_evaluacion_primaria_seq');
		return $idEvaluacionPrimaria;
	}

	/**
	 * obtener datos por apellido
	 */
	public function obtenerDatosPorApellido($arrayParametros){
		$consulta = "SELECT id_historia_clinica, identificador_paciente, identificador_medico, 
                           observaciones_revision_organos, documento_adjunto_examenes_clinicos, 
                           descripcion_concepto, tipo_restriccion_limitacion, estado, fecha_creacion
                            FROM g_historias_clinicas.historia_clinica hc 
                            INNER JOIN g_uath.ficha_empleado fe ON hc.identificador_paciente = fe.identificador
                            WHERE fe.apellido ilike '%" . $arrayParametros['identificador_paciente'] . "%' order by 1;";
		return $this->modeloHistoriaClinica->ejecutarSqlNativo($consulta);
	}

	/**
	 * VERIFICAR TIPO DE PERFIL DEL OPERADOR
	 */
	public function verificarPerfil($identificador){
		$sql = "SELECT
					p.nombre, p.codificacion_perfil
			  FROM
					g_usuario.usuarios_perfiles up
					INNER JOIN g_usuario.perfiles p ON up.id_perfil = p.id_perfil
					INNER JOIN g_programas.aplicaciones ap ON ap.id_aplicacion = p.id_aplicacion
			  WHERE
					identificador in ('" . $identificador . "') AND
					ap.codificacion_aplicacion='PRG_HIST_CLINI';";
		return $this->modeloHistoriaClinica->ejecutarSqlNativo($sql);
	}
}

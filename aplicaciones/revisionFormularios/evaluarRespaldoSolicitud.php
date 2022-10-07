<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorReportes.php';
require_once '../../clases/ControladorVigenciaDocumentos.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';
require_once '../../clases/ControladorEstructuraFuncionarios.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorFirmaDocumentos.php';

try{
	$inspector = htmlspecialchars($_POST['inspector'], ENT_NOQUOTES, 'UTF-8');
	$idSolicitud = htmlspecialchars($_POST['idSolicitud'], ENT_NOQUOTES, 'UTF-8');
	$tipoSolicitud = htmlspecialchars($_POST['tipoSolicitud'], ENT_NOQUOTES, 'UTF-8');
	$tipoInspector = htmlspecialchars($_POST['tipoInspector'], ENT_NOQUOTES, 'UTF-8');
	$resultado = htmlspecialchars($_POST['resultado'], ENT_NOQUOTES, 'UTF-8');
	$observaciones = htmlspecialchars($_POST['observacion'], ENT_NOQUOTES, 'UTF-8');
	$tipoElemento = htmlspecialchars($_POST['tipoElemento'], ENT_NOQUOTES, 'UTF-8');
	$idOperadorTipoOperacion = htmlspecialchars($_POST['idOperadorTipoOperacion'], ENT_NOQUOTES, 'UTF-8');
	$idHistoricoOperacion = htmlspecialchars($_POST['idHistoricoOperacion'], ENT_NOQUOTES, 'UTF-8');
	$provinciaSitio = htmlspecialchars($_POST['provinciaSitio'], ENT_NOQUOTES, 'UTF-8');

	$idOperadorTipoOperacion = ($idOperadorTipoOperacion == '' ? 0 : $idOperadorTipoOperacion);
	$idHistoricoOperacion = ($idHistoricoOperacion == '' ? 0 : $idHistoricoOperacion);

	$observacionesAreas = $_POST['observacionAreas'];
	$idAreas = $_POST['idAreas'];
	$idOperaciones = $_POST['idOperaciones'];
	$idGrupoSolicitudes = explode(",", $idSolicitud);

	$archivo = htmlspecialchars($_POST['archivo'], ENT_NOQUOTES, 'UTF-8');
	$idOperador = htmlspecialchars($_POST['identificadorOperador'], ENT_NOQUOTES, 'UTF-8');

	$tipoTiempoVigencia = null;
	$generarDocumento = true;

	$fechaActual = date("Y-m-d h:m:s");
	$actualizacionFechas = true;

	$arrayResultados = array(
		'noHabilitado',
		'subsanacion',
		'subsanacionRepresentanteTecnico',
		'subsanacionProducto'); // Verificar si existe subsanacion

	

	try{
		$conexion = new Conexion();
		$cu = new ControladorUsuarios();
		$cc = new ControladorCatalogos();
		$jru = new ControladorReportes();
		$cvd = new ControladorVigenciaDocumentos();
		$crs = new ControladorRevisionSolicitudesVUE();
		$cef = new ControladorEstructuraFuncionarios();
		$cfd = new ControladorFirmaDocumentos();

		$inspectorAsignado = $crs->guardarNuevoInspector($conexion, $inspector, $inspector, $tipoSolicitud, $tipoInspector, $idOperadorTipoOperacion, $idHistoricoOperacion);

		foreach ($idGrupoSolicitudes as $solicitud){
			$crs->guardarGrupo($conexion, $solicitud, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $tipoInspector);
		}

		$ordenInspeccion = $crs->buscarSerialOrden($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $tipoInspector);

		// Guardar resultado solicitud (cambio de estado)
		switch ($tipoSolicitud) {
			case 'Operadores':
				{
					$cr = new ControladorRegistroOperador();

					if (count($idAreas) > 0){

						if (! in_array($resultado, $arrayResultados)){

							if ($resultado == 'registrado'){
								$idVigenciaDeclarada = null;
							}else{
								$idVigenciaDeclarada = $resultado;
								$resultado = 'registrado';
							}
						}

						$idInspeccion = $crs->guardarDatosInspeccionElementos($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, $archivo, $resultado, pg_fetch_result($ordenInspeccion, 0, 'orden'), $observaciones);

						for ($i = 0; $i < count($idAreas); $i ++){
							$crs->guardarDatosInspeccionObservaciones($conexion, pg_fetch_result($idInspeccion, 0, 'id_inspeccion'), $idAreas[$i], $observacionesAreas[$i], $tipoElemento, $idOperaciones[$i]);
							$cr->evaluarAreasOperacion($conexion, $idOperaciones[$i], $idAreas[$i], $resultado, $observacionesAreas[$i], $archivo);
						}

						if ($resultado == 'registrado'){
							foreach ($idGrupoSolicitudes as $solicitud){

								$operacion = pg_fetch_assoc($cr->abrirOperacionXid($conexion, $solicitud));
								$idOperadorTipoOperacion = $operacion['id_operador_tipo_operacion'];

								$qHistorialOperacion = $cr->obtenerMaximoIdentificadorHistoricoOperacion($conexion, $idOperadorTipoOperacion);
								$historialOperacion = pg_fetch_assoc($qHistorialOperacion);

								$idflujoOPeracion = pg_fetch_assoc($cr->obtenerIdFlujoXOperacion($conexion, $solicitud));
								$idFlujoActual = pg_fetch_assoc($cr->obtenerEstadoActualFlujoOperacion($conexion, $idflujoOPeracion['id_flujo_operacion'], 'cargarRespaldo'));
								$estado = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $idflujoOPeracion['id_flujo_operacion'], $idFlujoActual['predecesor']));

								if ($operacion['modulo_provee'] == 'moduloExterno' && $estado['estado'] == 'cargarProducto'){
									$estado = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $idflujoOPeracion['id_flujo_operacion'], $idFlujoActual['predecesor'] + 1));
								}

								$idVigenciaDocumento = null;

								if ($operacion['proceso_modificacion'] != 't'){

									if ($idVigenciaDeclarada != null){
										$qVigenciaDeclarada = $cvd->obtenerVigenciaDeclaradaPorIdVigenciaDeclarada($conexion, $idVigenciaDeclarada);
										$vigenciaDeclarada = pg_fetch_assoc($qVigenciaDeclarada);
										$valorVigencia = $vigenciaDeclarada['valor_tiempo_vigencia_declarada'];
										$idVigenciaDocumento = $vigenciaDeclarada['id_vigencia_documento'];
										$tipoTiempoVigencia = $cvd->transformarvalorTipoVigencia($vigenciaDeclarada['tipo_tiempo_vigencia_declarada']);
									}

									$existenciaOperacion = $cr->verificarExistenciaOperaciones($conexion, $operacion['identificador_operador'], $operacion['id_tipo_operacion'], $idAreas[0], 'porCaducar', $idVigenciaDocumento); // TODO:CAMBIADO

									if (pg_num_rows($existenciaOperacion) == 0){
										$cr->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']);
										if ($idVigenciaDocumento != null){
											$cr->actualizarFechaFinalizacionOperacionesNuevos($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $valorVigencia, $tipoTiempoVigencia, $idVigenciaDocumento); // TODO:CAMBIADO
										}
									}else{
										$datosOperacion = pg_fetch_assoc($existenciaOperacion);
										$cr->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $datosOperacion['id_operador_tipo_operacion'], $datosOperacion['id_historial_operacion'], $idVigenciaDocumento); // TODO:CAMBIADO
										if ($idVigenciaDocumento != null){
											$cr->actualizarFechaFinalizacionOperacionesAntiguos($conexion, $idOperadorTipoOperacion, $operacion['id_historial_operacion'], $datosOperacion['fecha_finalizacion'], $valorVigencia, $tipoTiempoVigencia, $idVigenciaDocumento); // TODO:CAMBIADO
										}
										$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $datosOperacion['id_operador_tipo_operacion'], $datosOperacion['id_historial_operacion'], 'noHabilitado', 'Cambio de estado no habilitado por registro de nueva operación ' . $fechaActual, $idVigenciaDocumento); // TODO:CAMBIADO
										$cr->cambiarEstadoAreaOperacionPorPorOperadorTipoOperacionHistorial($conexion, $datosOperacion['id_operador_tipo_operacion'], $datosOperacion['id_historial_operacion'], $idVigenciaDocumento);
										$cr->actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $datosOperacion['id_operador_tipo_operacion'], 'noHabilitado');
									}
								}else{
									$actualizacionFechas = false;
								}

								$qcodigoTipoOperacion = $cc->obtenerCodigoTipoOperacion($conexion, $solicitud);
								$opcionArea = pg_fetch_result($qcodigoTipoOperacion, 0, 'codigo');
								$idArea = pg_fetch_result($qcodigoTipoOperacion, 0, 'id_area');

								switch ($idArea) {
									case 'LT':
										switch ($opcionArea) {
											case 'LDI':
											case 'LDA':
											case 'LDE':
												$identificadorOperador = $operacion['identificador_operador'];
												$existenciaDocumento = $cr->obtenerDocumentoGeneradoInspeccionPorOperadorTipoOperacion($conexion, $idOperadorTipoOperacion);
												if (pg_num_rows($existenciaDocumento) == 0){
													$secuencial = pg_fetch_assoc($cr->obtenerMaximoDocumentoOperador($conexion, $identificadorOperador, 'laboratorioDiagnostico'));
													$secuencial = str_pad($secuencial['secuencial'], 5, '0', STR_PAD_LEFT);
													$actualizacionFechas = true;
												}else{
													$generarDocumento = false;
													$secuencial = str_pad(pg_fetch_result($existenciaDocumento, 0, 'secuencial'), 5, '0', STR_PAD_LEFT);
													$actualizacionFechas = false;
												}
												$codigo = 'REDLAA'.'-'.($opcionArea=='LDI'?'DI':($opcionArea=='LDA'?'DA':'DV')).'-'.$identificadorOperador.'-'.$secuencial;
											break;
										}
									break;
								}

								switch ($estado['estado']) {

									case 'cargarProducto':
										$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado']);
									break;

									case 'registrado':
										$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado'], 'Solicitud aprobada ' . $fechaActual);
										if ($actualizacionFechas){
											$cr->actualizarFechaAprobacionOperaciones($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $idVigenciaDocumento);
										}else{
											$cr->actualizarFechaAprobacionOperacionesProcesoModificacion($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $idVigenciaDocumento);
										}
										$cr->cambiarEstadoAreaOperacionPorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $idVigenciaDocumento);
										$cr->actualizarProcesoActualizacionOperacion($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']);

										switch ($idArea) {

											case 'LT':
												switch ($opcionArea) {
													case 'LDI':
													case 'LDA':
													case 'LDE':
														
														$fechaConvenio =  $_POST['fechaConvenio'];
														$codigoLaboratorio = $_POST['codigoLaboratorio'];
														
														$empleador = pg_fetch_assoc($cu->buscarUsuarioSistema($conexion, $inspector));
														$nombreEmpleado = $empleador['apellido'].' '.$empleador['nombre'];
														$resultadoResponsable = $cef->devolverResponsable($conexion, $inspector);
														
														$cr->actualizarOperacionesLaboratorio($conexion, $idOperadorTipoOperacion, $idHistoricoOperacion, $archivo, $fechaConvenio, $codigoLaboratorio);
														$cr->actualizarFechaFinalizacionOperaciones($conexion, $idOperadorTipoOperacion, $idHistoricoOperacion, $valorVigencia, $tipoTiempoVigencia, $fechaConvenio, $idVigenciaDocumento);

														$ReporteJasper = '/aplicaciones/registroOperador/reportes/laboratorioDiagnostico/laboratorioDiagnostico.jrxml';
														$salidaReporte = '/aplicaciones/registroOperador/certificados/laboratorioDiagnostico/' . $idOperador . '_' . $idOperadorTipoOperacion . '.pdf';
														$rutaArchivo = 'aplicaciones/registroOperador/certificados/laboratorioDiagnostico/' . $idOperador . '_' . $idOperadorTipoOperacion . '.pdf';

														$parameters['parametrosReporte'] = array(
															'numeroCertificado' => $codigo,
															'idOperadorTipoOperacion' => (int) $idOperadorTipoOperacion,
															'nombreTecnico' => $nombreEmpleado);

														$jru->generarReporteJasper($ReporteJasper, $parameters, $conexion, $salidaReporte, 'general');

														if ($generarDocumento){
															$cr->guardarDocumentoOperador($conexion, $solicitud, $idOperadorTipoOperacion, $rutaArchivo, 'laboratorioDiagnostico', $secuencial, $idOperador, 'Certificado de registro de claboratorio de diagnostico.');
														}
														
														//Tabla de firmas físicas
														$firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, 'Coordinador','LT'));
														
														$rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$rutaArchivo;
														
														//Firma Electrónica
														$parametrosFirma = array(
															'archivo_entrada'=>$rutaArchivo,
															'archivo_salida'=>$rutaArchivo,
															'identificador'=>$firmaResponsable['identificador'],
															'razon_documento'=>'Certificado de registro de claboratorio de diagnostico.',
															'tabla_origen'=>'g_operadores.documentos_operador',
															'campo_origen'=>'ruta_archivo',
															'id_origen'=>$solicitud,
															'estado'=>'Por atender',
															'proceso_firmado'=>'NO'
														);
														
														//Guardar registro para firma
														$cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);

													break;
												}
											break;
										}
									break;
								}
							}

							$cr->actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $idOperadorTipoOperacion, $estado['estado']);

						}else{

							foreach ($idGrupoSolicitudes as $solicitud){
								$operacion = pg_fetch_assoc($cr->abrirOperacionXid($conexion, $solicitud));
								$idOperadorTipoOperacion = $operacion['id_operador_tipo_operacion'];

								$qHistorialOperacion = $cr->obtenerMaximoIdentificadorHistoricoOperacion($conexion, $idOperadorTipoOperacion);
								$historialOperacion = pg_fetch_assoc($qHistorialOperacion);

								$cr->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']);

								$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $resultado, $observaciones);

								$cr->actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $idOperadorTipoOperacion, $resultado);
							}
						}

						$mensaje['estado'] = 'exito';
						$mensaje['mensaje'] = 'La operación se ha guardado satisfactoriamente';
					}else{
						$mensaje['estado'] = 'error';
						$mensaje['mensaje'] = 'Debe seleccionar por lo menos un elemento.';
					}

					break;
				}

			default:
			break;
		}
		$conexion->desconectar();
		echo json_encode($mensaje);
	}catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
}catch (Exception $ex){
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>
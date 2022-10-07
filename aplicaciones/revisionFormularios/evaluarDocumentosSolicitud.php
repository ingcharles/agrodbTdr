<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVUE.php';
require_once '../../clases/ControladorMail.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorReportes.php';
require_once '../../clases/ControladorFinanciero.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorVigenciaDocumentos.php';
require_once '../../clases/ControladorEstructuraFuncionarios.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';
require_once '../../clases/ControladorGestionAplicacionesPerfiles.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorFirmaDocumentos.php';


require_once '../general/crearReporteRequisitos.php';
require_once '../general/administrarArchivoFTP.php';

//Controladores por solicitud
require_once '../../clases/ControladorClv.php';
require_once '../../clases/ControladorImportaciones.php';
require_once '../../clases/ControladorFitosanitario.php';
require_once '../../clases/ControladorCertificacionBPA.php';
require_once '../../clases/ControladorTramitesInocuidad.php';
require_once '../../clases/ControladorCertificadoCalidad.php';
require_once '../../clases/ControladorDestinacionAduanera.php';
require_once '../../clases/ControladorFinancieroAutomatico.php';
require_once '../../clases/ControladorZoosanitarioExportacion.php';
require_once '../../clases/ControladorFitosanitarioExportacion.php';
require_once '../../clases/ControladorImportacionesFertilizantes.php';
require_once '../../clases/ControladorMercanciasSinValorComercial.php';
require_once '../../clases/ControladorTransitoInternacional.php';																					  

function reemplazarCaracteres($cadena){
    $cadena = str_replace('á', 'a', $cadena);
    $cadena = str_replace('é', 'e', $cadena);
    $cadena = str_replace('í', 'i', $cadena);
    $cadena = str_replace('ó', 'o', $cadena);
    $cadena = str_replace('ú', 'u', $cadena);
	
	$cadena = str_replace('Á', 'A', $cadena);
	$cadena = str_replace('É', 'E', $cadena);
	$cadena = str_replace('Í', 'I', $cadena);
	$cadena = str_replace('Ó', 'O', $cadena);
	$cadena = str_replace('Ú', 'U', $cadena);
    
    return $cadena;
}

try{
	$inspector = htmlspecialchars ($_POST['inspector'],ENT_NOQUOTES,'UTF-8');
	$idSolicitud = ($_POST['idSolicitud']);
	$tipoSolicitud = htmlspecialchars ($_POST['tipoSolicitud'],ENT_NOQUOTES,'UTF-8');
	$tipoInspector = htmlspecialchars ($_POST['tipoInspector'],ENT_NOQUOTES,'UTF-8');
	$resultadoDocumento = htmlspecialchars ($_POST['resultadoDocumento'],ENT_NOQUOTES,'UTF-8');
	$observacionesDocumento = htmlspecialchars ($_POST['observacionDocumento'],ENT_NOQUOTES,'UTF-8');
	$idOperadorTipoOperacion = htmlspecialchars($_POST['idOperadorTipoOperacion'], ENT_NOQUOTES, 'UTF-8');
	$idHistoricoOperacion = htmlspecialchars($_POST['idHistoricoOperacion'], ENT_NOQUOTES, 'UTF-8');
	$idVue = htmlspecialchars($_POST['idVue'],ENT_NOQUOTES,'UTF-8');
	$nombreOpcion = $_POST['nombreOpcion'];
	$idOperador = $_POST['identificadorOperador'];
	$idAreas = $_POST['idAreas'];
	$codigoProvinciaSitio = htmlspecialchars($_POST['codigoProvinciaSitio'], ENT_NOQUOTES, 'UTF-8');
	$fechaInicio = $_POST['fechaInicio'];
	$provinciaSitio = htmlspecialchars($_POST['provinciaSitio'], ENT_NOQUOTES, 'UTF-8');
	
	$idOperadorTipoOperacion = ($idOperadorTipoOperacion == '' ? 0:$idOperadorTipoOperacion);
	$idHistoricoOperacion = ($idHistoricoOperacion == '' ? 0: $idHistoricoOperacion);

	$idGrupoSolicitudes = explode(",",$idSolicitud);
	$fechaActual = date("Y-m-d h:m:s");
	
	$arrayResultados = array('noHabilitado','subsanacion','subsanacionRepresentanteTecnico','subsanacionProducto');//Verificar si existe subsanacion
	$actualizacionFechas = true;
	$generarDocumento = true;
	
	$generarReporteAlmacenes = false;
	$generarReporteEmpresas = false;
	
	$tipoProceso = false;

	try {
		$conexion = new Conexion();
		$cvd = new ControladorVigenciaDocumentos();
		$crs = new ControladorRevisionSolicitudesVUE();
		$cVUE = new ControladorVUE();
		$cc = new ControladorCatalogos();
		$cfd = new ControladorFirmaDocumentos();

			$inspectorAsignado= $crs->guardarNuevoInspector($conexion, $inspector, $inspector, $tipoSolicitud, $tipoInspector, $idOperadorTipoOperacion, $idHistoricoOperacion);

			foreach ($idGrupoSolicitudes as $solicitud){
				$crs->guardarGrupo($conexion, $solicitud, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $tipoInspector);
			}

			$ordenInspeccion = $crs->buscarSerialOrden($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $tipoInspector);

		//Guardar resultado solicitud (cambio de estado)
		switch ($tipoSolicitud){
			case 'Operadores' :
				
				$cr = new ControladorRegistroOperador();
				
				$estadoProceso = 'documental';
				
				if (!in_array($resultadoDocumento, $arrayResultados)) {
				    
				    if($resultadoDocumento == 'registrado'){
				        $idVigenciaDeclarada = null;
				    }else{
				        $idVigenciaDeclarada = $resultadoDocumento;
				        $resultadoDocumento = 'registrado';
				    }
				}
		
				$crs->guardarDatosInspeccionDocumental($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, $observacionesDocumento, $resultadoDocumento,pg_fetch_result($ordenInspeccion, 0, 'orden'));
				
				$modulosAgregados="";
				$perfilesAgregados="";
				
				if($resultadoDocumento == 'registrado'){
					
					foreach ($idGrupoSolicitudes as $solicitud){

						$qcodigoTipoOperacion= $cc->obtenerCodigoTipoOperacion($conexion, $solicitud);
						$opcionArea = pg_fetch_result($qcodigoTipoOperacion, 0, 'codigo');
						$idArea = pg_fetch_result($qcodigoTipoOperacion, 0, 'id_area');
						$idTipoOperacion = pg_fetch_result($qcodigoTipoOperacion, 0, 'id_tipo_operacion');
						$tipoOperacionNombre = pg_fetch_result($qcodigoTipoOperacion, 0, 'nombre'); 
						
						$operacion = pg_fetch_assoc($cr->abrirOperacionXid($conexion, $solicitud));
						$idOperadorTipoOperacion = $operacion['id_operador_tipo_operacion'];
							
						$qHistorialOperacion = $cr->obtenerMaximoIdentificadorHistoricoOperacion($conexion, $idOperadorTipoOperacion);
						$historialOperacion = pg_fetch_assoc($qHistorialOperacion);
						
						switch ($idArea){
						    case 'LT':
						        switch ($opcionArea){
						            case 'LAL':
						                $existenciaDocumento = $cr->obtenerDocumentoGeneradoInspeccionPorOperadorTipoOperacion($conexion, $idOperadorTipoOperacion);
						                if(pg_num_rows($existenciaDocumento) == 0){
						                    $verificarExistenciaOperacion = $cr->obtenerIdOperadorTipoOperacionPorTipoOperacionAreaEstado($conexion, $idOperador, "('porCaducar', 'noHabilitado')", $idTipoOperacion, $idAreas[0]);
						                    if(pg_num_rows($verificarExistenciaOperacion) == 0){
						                        //TODO: Generar registro y fechas
						                        $secuencial = pg_fetch_assoc($cr->obtenerMaximoDocumentoOperador($conexion, '', 'laboratorioLecheCruda'));
						                        $secuencial = str_pad($secuencial['secuencial'], 4, '0', STR_PAD_LEFT);
						                    }else{
						                        $existenciaDocumento = $cr->obtenerDocumentoGeneradoInspeccionPorOperadorTipoOperacion($conexion, pg_fetch_result($verificarExistenciaOperacion, 0, 'id_operador_tipo_operacion'));
						                        if(pg_num_rows($existenciaDocumento) != 0){
						                            //Mantener registro y generar fechas.
						                            $secuencial = str_pad(pg_fetch_result($existenciaDocumento, 0, 'secuencial'), 4, '0', STR_PAD_LEFT);
						                        }
						                    }
						                }else{
						                    //TODO: Obtengo el numero de registro y mantengo fechas
						                    $secuencial = str_pad(pg_fetch_result($existenciaDocumento, 0, 'secuencial'), 4, '0', STR_PAD_LEFT);
						                    $actualizacionFechas = false;
						                    $generarDocumento = false;
						                }
                                    break;
						            case 'LDV':
						                $existenciaDocumento = $cr->obtenerDocumentoGeneradoInspeccionPorOperadorTipoOperacion($conexion, $idOperadorTipoOperacion);
						                if(pg_num_rows($existenciaDocumento) == 0){
						                    $verificarExistenciaOperacion = $cr->obtenerIdOperadorTipoOperacionPorTipoOperacionAreaEstado($conexion, $idOperador, "('porCaducar', 'noHabilitado')", $idTipoOperacion, $idAreas[0]);
						                    if(pg_num_rows($verificarExistenciaOperacion) == 0){
						                        //TODO: Generar registro y fechas
						                        $secuencial = pg_fetch_assoc($cr->obtenerMaximoDocumentoOperador($conexion, '', 'laboratorioDiagnostico'));
						                        $secuencial = str_pad($secuencial['secuencial'], 4, '0', STR_PAD_LEFT);
						                    }else{
						                        $existenciaDocumento = $cr->obtenerDocumentoGeneradoInspeccionPorOperadorTipoOperacion($conexion, pg_fetch_result($verificarExistenciaOperacion, 0, 'id_operador_tipo_operacion'));
						                        if(pg_num_rows($existenciaDocumento) != 0){
						                            //Mantener registro y generar fechas.
						                            $secuencial = str_pad(pg_fetch_result($existenciaDocumento, 0, 'secuencial'), 4, '0', STR_PAD_LEFT);
						                        }
						                    }
						                }else{
						                    //TODO: Obtengo el numero de registro y mantengo fechas
						                    $secuencial = str_pad(pg_fetch_result($existenciaDocumento, 0, 'secuencial'), 4, '0', STR_PAD_LEFT);
						                    $actualizacionFechas = false;
						                    $generarDocumento = false;
						                }
                                    break;
						            case 'LDI':
						            case 'LDA':
						            case 'LDE':
										$procesoPago = $_POST['exoneracionPago'];
										
										if($procesoPago == 'SI'){
											$estadoProceso = 'verificacion';
										}
										
						            break;
						        }
						        break;
						        
						    case 'AI':
						    	switch ($opcionArea){
						    		case 'INL':
						    			$verificarExistenciaOperacion = $cr->obtenerIdOperadorTipoOperacionPorTipoOperacionAreaEstado($conexion, $idOperador, "('porCaducar', 'noHabilitado')", $idTipoOperacion, $idAreas[0]);
						    			if(pg_num_rows($verificarExistenciaOperacion) == 0){
						    				$secuencial = pg_fetch_assoc($cr->obtenerMaximoDocumentoOperador($conexion, '', 'industriaLactea'));
						    				$secuencial = str_pad($secuencial['secuencial'], 4, '0', STR_PAD_LEFT);
						    				$anio = date('Y');
						    			}else{
						    				$existenciaDocumento = $cr->obtenerDocumentoGeneradoInspeccionPorOperadorTipoOperacion($conexion, pg_fetch_result($verificarExistenciaOperacion, 0, 'id_operador_tipo_operacion'));
						    				if(pg_num_rows($existenciaDocumento) != 0){
						    					$secuencial = str_pad(pg_fetch_result($existenciaDocumento, 0, 'secuencial'), 4, '0', STR_PAD_LEFT);
						    					$anio = date('Y',pg_fetch_result($existenciaDocumento, 0, 'fecha_documento_generado'));
						    					$actualizacionFechas = false;
						    				}
						    			}
						    		break;
						    		case 'VAA':
						    		case 'VAE':
						    		    $existenciaDocumento = $cr->obtenerDocumentoGeneradoInspeccionPorOperadorTipoOperacion($conexion, $idOperadorTipoOperacion);
						    		    if(pg_num_rows($existenciaDocumento) == 0){
						    		        $datosCurso = pg_fetch_assoc($cc->obtenerDatosCursoPorAreaGestionNomenclaturaEstado($conexion, $idArea, 'DIA', 'MVA', 'activo'));
						    		        $nomenclatura = $datosCurso['numero_curso'].$datosCurso['tipo_curso'].$datosCurso['nomenclatura'].($opcionArea == 'VAA' ? 'A':'EM').substr($datosCurso['anio'], -2);
						    		        $secuencial = pg_fetch_assoc($cr->obtenerMaximoDocumentoOperador($conexion, '', $nomenclatura));
						    		        $secuencial = str_pad($secuencial['secuencial'], 3, '0', STR_PAD_LEFT);
						    		        $codigo = $nomenclatura.'-'.$secuencial;
						    		    }else{
						    		        $nomenclatura = pg_fetch_result($existenciaDocumento, 0, 'tipo');
						    		        $secuencial = str_pad(pg_fetch_result($existenciaDocumento, 0, 'secuencial'), 3, '0', STR_PAD_LEFT);
						    		        $codigo = $nomenclatura.'-'.$secuencial;
						    		        $actualizacionFechas = false;
						    		    }
						    		break;
						    		case 'VOA':
						    		case 'VOE':
						    		    $existenciaDocumento = $cr->obtenerDocumentoGeneradoInspeccionPorOperadorTipoOperacion($conexion, $idOperadorTipoOperacion);
						    		    if(pg_num_rows($existenciaDocumento) == 0){
						    		        $datosCurso = pg_fetch_assoc($cc->obtenerDatosCursoPorAreaGestionNomenclaturaEstado($conexion, $idArea, 'DIA', 'MVOA', 'activo'));
						    		        $nomenclatura = $datosCurso['numero_curso'].'-'.$datosCurso['tipo_curso'].$datosCurso['nomenclatura'].'-'.($opcionArea == 'VOA' ? 'A':'EM').'-'.substr($datosCurso['anio'], -2);
						    		        $secuencial = pg_fetch_assoc($cr->obtenerMaximoDocumentoOperador($conexion, '', $nomenclatura));
						    		        $secuencial = str_pad($secuencial['secuencial'], 3, '0', STR_PAD_LEFT);
						    		        $codigo = $nomenclatura.'-'.$secuencial;
						    		    }else{
						    		        $nomenclatura = pg_fetch_result($existenciaDocumento, 0, 'tipo');
						    		        $secuencial = str_pad(pg_fetch_result($existenciaDocumento, 0, 'secuencial'), 3, '0', STR_PAD_LEFT);
						    		        $codigo = $nomenclatura.'-'.$secuencial;
						    		        $actualizacionFechas = false;
						    		    }
						    		break;
						    	}
						    break;
						}
						
						$idVigenciaDocumento = null;
						
						if($operacion['proceso_modificacion'] != 't'){

    						if($idVigenciaDeclarada != null){
    						    $qVigenciaDeclarada = $cvd->obtenerVigenciaDeclaradaPorIdVigenciaDeclarada($conexion, $idVigenciaDeclarada);
    						    $vigenciaDeclarada = pg_fetch_assoc($qVigenciaDeclarada);
    						    $valorVigencia = $vigenciaDeclarada['valor_tiempo_vigencia_declarada'];
    						    $idVigenciaDocumento = $vigenciaDeclarada['id_vigencia_documento'];
    						    $tipoTiempoVigencia = $cvd->transformarvalorTipoVigencia($vigenciaDeclarada['tipo_tiempo_vigencia_declarada']);
    						}
    						
    						$existenciaOperacion = $cr->verificarExistenciaOperaciones($conexion, $operacion['identificador_operador'], $operacion['id_tipo_operacion'], $idAreas[0], 'porCaducar', $idVigenciaDocumento);//TODO:CAMBIADO
    						
    						if(pg_num_rows($existenciaOperacion) == 0){
    						    $cr->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']);
    						    if($idVigenciaDocumento != null){
    						        if($actualizacionFechas){
    						            $cr->actualizarFechaFinalizacionOperacionesNuevos($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $valorVigencia, $tipoTiempoVigencia, $idVigenciaDocumento);//TODO:CAMBIADO
    						        }
    						    }
    						}else{
    						    $datosOperacion = pg_fetch_assoc($existenciaOperacion);
    						    $cr->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $datosOperacion['id_operador_tipo_operacion'], $datosOperacion['id_historial_operacion'], $idVigenciaDocumento);//TODO:CAMBIADO
    						    if($idVigenciaDocumento != null){
    						        if($actualizacionFechas){
    						            $cr->actualizarFechaFinalizacionOperacionesAntiguos($conexion, $idOperadorTipoOperacion, $operacion['id_historial_operacion'], $datosOperacion['fecha_finalizacion'], $valorVigencia, $tipoTiempoVigencia, $idVigenciaDocumento);//TODO:CAMBIADO
    						        }
    						    }
    						    $cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $datosOperacion['id_operador_tipo_operacion'], $datosOperacion['id_historial_operacion'],'noHabilitado', 'Cambio de estado no habilitado por registro de nueva operación '.$fechaActual, $idVigenciaDocumento);//TODO:CAMBIADO
    						    $cr->cambiarEstadoAreaOperacionPorPorOperadorTipoOperacionHistorial($conexion, $datosOperacion['id_operador_tipo_operacion'], $datosOperacion['id_historial_operacion'], $idVigenciaDocumento);
    						    $cr->actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $datosOperacion['id_operador_tipo_operacion'], 'noHabilitado');
    						}
						}else{
							$actualizacionFechas = false;
						}

						$idflujoOperacion = pg_fetch_assoc($cr->obtenerIdFlujoXOperacion($conexion, $solicitud));
						$idFlujoActual = pg_fetch_assoc($cr->obtenerEstadoActualFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], $estadoProceso));
						$estado = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], $idFlujoActual['predecesor']));
						
						if($operacion['modulo_provee'] == 'moduloExterno' && $estado['estado'] == 'cargarProducto'){
							$estado = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], $idFlujoActual['predecesor']+1));
						}
						
						if($estado['estado'] == 'pago'){
						    if($operacion['proceso_modificacion'] == 't'){
						        $tipoProceso = true;
						    }
						    
						    if($tipoProceso){
						        $idFlujoActual = pg_fetch_assoc($cr->obtenerEstadoActualFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], 'verificacion'));
						        $estado = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], $idFlujoActual['predecesor']));
						    }
						}

						switch ($estado['estado']){
								
							case 'cargarProducto':
								$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado']);
								switch ($idArea){
									case 'IAF':
										$jru = new ControladorReportes();
										switch ($opcionArea){
											case 'ALM':
												$generarReporteAlmacenes = true;
											break;
											default:
												$generarReporteEmpresas = true;
										}
										break;
								}
								
								if($generarReporteEmpresas){
									
									$ReporteJasper= '/aplicaciones/registroOperador/reportes/registroEmpresa/riaEmpresas.jrxml';
									$salidaReporte= '/aplicaciones/registroOperador/certificados/registroEmpresa/'.$idOperador.'.pdf';
									$rutaArchivo= 'aplicaciones/registroOperador/certificados/registroEmpresa/'.$idOperador.'.pdf';
									$rutaArchivoCodigoQr = 'http://181.112.155.173/'.$constg::RUTA_APLICACION.$salidaReporte;
									
									$parameters['parametrosReporte'] = array(
										'identificadorOperador'=>$idOperador,
										'rutaCertificado'=> $rutaArchivoCodigoQr,
										'fechaInicio'=> $fechaInicio
									);
									
									$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'ria');
									
									$existenciaDocumento = $cr->obtenerDocumentoGeneradoPorSecuencialIdentificadorOperadorTipoDocumento($conexion, '1', $idOperador, 'riaEmpresas');
									if(pg_num_rows($existenciaDocumento) == 0){
										$cr->guardarDocumentoOperador($conexion, $solicitud, $idOperadorTipoOperacion, $rutaArchivo, 'riaEmpresas', '1', $idOperador, 'Certificación de registro de empresa.');
									}
									
									//Tabla de firmas físicas
									$firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, $provinciaSitio, 'RIA'));
									
									$rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$rutaArchivo;
									
									//Firma Electrónica
									$parametrosFirma = array(
										'archivo_entrada'=>$rutaArchivo,
										'archivo_salida'=>$rutaArchivo,
										'identificador'=>$firmaResponsable['identificador'],
										'razon_documento'=>'Certificación de registro de empresa.',
										'tabla_origen'=>'g_operadores.documentos_operador',
										'campo_origen'=>'ruta_archivo',
										'id_origen'=>$solicitud,
										'estado'=>'Por atender',
										'proceso_firmado'=>'NO'
									);
									
									//Guardar registro para firma
									$cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
									
								}
								
								if($generarReporteAlmacenes){
									
									$datosOperador = pg_fetch_assoc($cr->obtenerOperadorSitioInspeccion($conexion, $solicitud));
									
									$ReporteJasper= '/aplicaciones/registroOperador/reportes/registroAlmacen/riaAlmacenes.jrxml';
									$salidaReporte= '/aplicaciones/registroOperador/certificados/registroAlmacen/'.$idOperador.'_'.$datosOperador['id_sitio'].'.pdf';
									$rutaArchivo= 'aplicaciones/registroOperador/certificados/registroAlmacen/'.$idOperador.'_'.$datosOperador['id_sitio'].'.pdf';
									$rutaArchivoCodigoQr = 'http://181.112.155.173/'.$constg::RUTA_APLICACION.$salidaReporte;
									
									
									$parameters['parametrosReporte'] = array(
										'idSitio' => (int)$datosOperador['id_sitio'],
										'rutaCertificado' => $rutaArchivoCodigoQr,
										'fechaInicio' => $fechaInicio
									);
									
									$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'ria');
									
									$existenciaDocumento = $cr->obtenerDocumentoGeneradoPorSecuencialIdentificadorOperadorTipoDocumento($conexion, $datosOperador['id_sitio'], $idOperador, 'riaAlmacenistas');
									if(pg_num_rows($existenciaDocumento) == 0){
										$cr->guardarDocumentoOperador($conexion, $solicitud, $idOperadorTipoOperacion, $rutaArchivo, 'riaAlmacenistas', $datosOperador['id_sitio'], $idOperador, 'Certificación de registro de almacén de expendio.');
									}
									
									//Tabla de firmas físicas
									$firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, $provinciaSitio, 'RIA'));
									
									$rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$rutaArchivo;
									
									//Firma Electrónica
									$parametrosFirma = array(
										'archivo_entrada'=>$rutaArchivo,
										'archivo_salida'=>$rutaArchivo,
										'identificador'=>$firmaResponsable['identificador'],
										'razon_documento'=>'Certificación de registro de almacén de expendio.',
										'tabla_origen'=>'g_operadores.documentos_operador',
										'campo_origen'=>'ruta_archivo',
										'id_origen'=>$solicitud,
										'estado'=>'Por atender',
										'proceso_firmado'=>'NO'
									);
									
									//Guardar registro para firma
									$cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
									
								}
							break;
							
							case 'pago':
								$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado']);
							break;
							
							case 'cargarRespaldo':
								$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado']);
							break;
							
							case 'inspeccion':
							    $cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado']);
							break;
						
							case 'registrado':
								$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado'], 'Solicitud aprobada '.$fechaActual);
								if($actualizacionFechas){
								    $cr->actualizarFechaAprobacionOperaciones($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $idVigenciaDocumento);
								}else{
									$cr->actualizarFechaAprobacionOperacionesProcesoModificacion($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $idVigenciaDocumento);
								}
								$cr->cambiarEstadoAreaOperacionPorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']);
								$cr->actualizarProcesoActualizacionOperacion($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']);
							break;
						}
						switch ($idArea){
							case 'SV':
								switch ($opcionArea){
									case 'IEA':
										$modulosAgregados.="('PRG_INSP_MUS'),";
										$perfilesAgregados.="('PFL_IEA_MUS'),";
									break;
								}
							break;    
						    case 'AI':
							
						        $cu = new ControladorUsuarios();
						        
						        switch ($opcionArea){
						            case 'PRO':
									case 'REC':
									
						                $idTipoProduccion = $_POST['idTipoProduccion'];
						                $idTipoTransicion = $_POST['idTipoTransicion'];
						                $idAgenciaCertificadora = $_POST['idAgencia'];
						                $idProducto = $_POST['idProducto'];
						                $idOperacion = $_POST['idOperacion'];
						                
                                        $jru = new ControladorReportes();
										
										if(is_array($idAgenciaCertificadora) && count($idAgenciaCertificadora)){

											for ($i = 0; $i < count ($idAgenciaCertificadora); $i++) {
												$cr->guardarOperacionesOrganico($conexion, $idOperacion[$i], $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $idTipoProduccion[$i], $idTipoTransicion[$i], $idAgenciaCertificadora[$i], $idProducto[$i]);
											}
					                    }

										if($opcionArea == "PRO"){
											$modulosAgregados.="('PRG_CERT_BPA'),";
											$perfilesAgregados.="('PFL_USR_CERT_BPA'),";
											
										}
										
                                    break;
                                    
						            case 'PRC':
						            case 'COM':
						                //Procesador
						                $idTipoProduccion = $_POST['idTipoProduccion'];
						                $idTipoTransicion = $_POST['idTipoTransicion'];
						                $idAgenciaCertificadora = $_POST['idAgencia'];
						                $idProducto = $_POST['idProducto'];
						                $idOperacion = $_POST['idOperacion'];
						                //Comercializador
						                $agenciaCertificadora = $_POST['agenciaCertificadora'];
						                $idMercadoDestino = $_POST['idMercadoDestino'];
						                $nacional = ($_POST['nacional']=="")?"":$_POST['nacional'].', ';
						                $importador = ($_POST['importador']=="")?"":$_POST['importador'].', ';
						                $exportador= ($_POST['exportador']=="")?"":$_POST['exportador'].', ';
						                
						                $alcance = $nacional . $importador . $exportador;
						                $alcance = rtrim($alcance,', ');
						                
						                if($opcionArea == "PRC"){
											
						                   if(is_array($idAgenciaCertificadora) && count($idAgenciaCertificadora)){
											
												for ($i = 0; $i < count ($idAgenciaCertificadora); $i++) {
													$cr->guardarOperacionesOrganico($conexion, $idOperacion[$i], $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $idTipoProduccion[$i], $idTipoTransicion[$i], $idAgenciaCertificadora[$i], $idProducto[$i]);
												}
											
											}
											
						                }else  if($opcionArea == "COM"){
											$tipoTransicion = pg_fetch_result($cc->obtenerTipoTransicionXCodigo($conexion, 'COD_TRANS_ORG'), 0, 'id_tipo_transicion');
						                    $qOperacionesOrganicos = $cr->obtenerOperacionesXIdOperadorTipoOperacionXHistorialOperacion($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']);
						  
						                   if(!empty($agenciaCertificadora)){
												while($operacionesOrganicos = pg_fetch_assoc($qOperacionesOrganicos)){
													$qbuscarIdOperacionOrganico = $cr->verificarOperacionOrganico($conexion, $operacionesOrganicos['id_operacion']);
													if(pg_num_rows($qbuscarIdOperacionOrganico) == 0){
														$cr->guardarOperacionesOrganico($conexion, $operacionesOrganicos['id_operacion'], $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], 'null', $tipoTransicion, $agenciaCertificadora, $operacionesOrganicos['id_producto'], $alcance);
														for ($i = 0; $i < count ($idMercadoDestino); $i++) {
															$cr->guardarMercadoDestinoXIdOperacion($conexion, $operacionesOrganicos['id_operacion'], $idMercadoDestino[$i]);
														}
													}	
												}
											}
										}

										
						            break;
						            case 'VAA':
						            case 'VAE':
						                $jru = new ControladorReportes();
						                
						                //TODO:Obtener datos para secuencial.
						                $datosOperador = pg_fetch_assoc($cr->listarOperadoresEmpresa($conexion, $idOperador));

						                $ReporteJasper= '/aplicaciones/registroOperador/reportes/faenador/certificadoFaenador.jrxml';
						                $salidaReporte= '/aplicaciones/registroOperador/certificados/faenador/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
						                $rutaArchivo= 'aplicaciones/registroOperador/certificados/faenador/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
						                
						                $parameters['parametrosReporte'] = array(
						                	'nombreOperador'=>$datosOperador['nombre_operador'],
						                	'nombreOperacion'=>strtoupper($tipoOperacionNombre),
						                	'codigo'=>$codigo,
						                	'fecha'=>$fechaActual 
										);
						                
						                $jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'faenador');
						                $existenciaDocumento = $cr->obtenerDocumentoGeneradoPorSecuencialIdentificadorOperadorTipoDocumento($conexion, $secuencial, $idOperador, $nomenclatura);
						                if(pg_num_rows($existenciaDocumento) == 0){
						                    $cr->guardarDocumentoOperador($conexion, $solicitud, $idOperadorTipoOperacion, $rutaArchivo, $nomenclatura, $secuencial, $idOperador, $tipoOperacionNombre);
						                }
						                $cr->guardarTipoInspectorFaenador($conexion, $idOperador, 'Registrado', $tipoOperacionNombre, 'Registrado por aprobación de registro de operador.', $idOperadorTipoOperacion, $inspector);
										
										$modulosAgregados.="('PRG_A_P_MORTE_CF'),";
										$perfilesAgregados.="('PFL_APM_CF_OP'),";
										
										//Tabla de firmas físicas
										$firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, $provinciaSitio, 'AI'));
										
										$rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$rutaArchivo;
										
										//Firma Electrónica
										$parametrosFirma = array(
											'archivo_entrada'=>$rutaArchivo,
											'archivo_salida'=>$rutaArchivo,
											'identificador'=>$firmaResponsable['identificador'],
											'razon_documento'=> $tipoOperacionNombre,
											'tabla_origen'=>'g_operadores.documentos_operador',
											'campo_origen'=>'ruta_archivo',
											'id_origen'=>$solicitud,
											'estado'=>'Por atender',
											'proceso_firmado'=>'NO'
										);
										
										//Guardar registro para firma
										$cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
										
                                    break;
									case 'VOA':
						            case 'VOE':
						            	$jru = new ControladorReportes();

						            	$datosOperador = pg_fetch_assoc($cr->listarOperadoresEmpresa($conexion, $idOperador));

						            	$ReporteJasper= '/aplicaciones/registroOperador/reportes/faenador/certificadoFaenador.jrxml';
						            	$salidaReporte= '/aplicaciones/registroOperador/certificados/faenador/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
						            	$rutaArchivo= 'aplicaciones/registroOperador/certificados/faenador/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
						            	
						            	$parameters['parametrosReporte'] = array(
						            		'nombreOperador'=> $datosOperador['nombre_operador'],
						            		'nombreOperacion'=> strtoupper($tipoOperacionNombre),
						            		'codigo'=> $codigo,
						            		'fecha'=>$fechaActual
						            	);
						            	
						            	$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'faenador');
						            	$existenciaDocumento = $cr->obtenerDocumentoGeneradoPorSecuencialIdentificadorOperadorTipoDocumento($conexion, $secuencial, $idOperador, $nomenclatura);
						            	if(pg_num_rows($existenciaDocumento) == 0){
						            	    $cr->guardarDocumentoOperador($conexion, $solicitud, $idOperadorTipoOperacion, $rutaArchivo, $nomenclatura, $secuencial, $idOperador, $tipoOperacionNombre);
						            	}
						            	$cr->guardarTipoInspectorFaenador($conexion, $idOperador, 'Registrado', $tipoOperacionNombre, 'Registrado por aprobación de registro de operador.', $idOperadorTipoOperacion, $inspector);
						            	
						            	$modulosAgregados.="('PRG_A_P_MORTE_CF'),";
										$perfilesAgregados.="('PFL_APM_CF_OP'),";
										
										//Tabla de firmas físicas
										$firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, $provinciaSitio, 'AI'));
										
										$rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$rutaArchivo;
										
										//Firma Electrónica
										$parametrosFirma = array(
											'archivo_entrada'=>$rutaArchivo,
											'archivo_salida'=>$rutaArchivo,
											'identificador'=>$firmaResponsable['identificador'],
											'razon_documento'=> $tipoOperacionNombre,
											'tabla_origen'=>'g_operadores.documentos_operador',
											'campo_origen'=>'ruta_archivo',
											'id_origen'=>$solicitud,
											'estado'=>'Por atender',
											'proceso_firmado'=>'NO'
										);
										
										//Guardar registro para firma
										$cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
						            	
						            break;
						            case 'INL':
						            	$jru = new ControladorReportes();
						            	$cef = new ControladorEstructuraFuncionarios();
						            	
						            	$fechaCaducidad = $_POST['fechaCaducidad'];
						            	$resultadoResponsable = $cef->devolverResponsable($conexion, $inspector);
						            	$nombreInspector = pg_fetch_assoc($cu->buscarUsuarioSistema($conexion, $inspector));
						            	
						            	if($resultadoResponsable['usuario']!='' && $resultadoResponsable['nombreArea']!=''){
						            	    $responsable = $resultadoResponsable['usuario'];
						            	    $areaResponsable = $resultadoResponsable['nombreArea'];
						            	}
						            	
						            	//$nombreResponsable = pg_fetch_result($cef->obtenerResponsablePorArea($conexion, 'CGIA'), 0, 'usuario');
						            	
						            	$codigo = "AGRO-IL-".$codigoProvinciaSitio.'-'.$secuencial.'-'.$anio;
						            	
						            	$ReporteJasper= '/aplicaciones/registroOperador/reportes/industriaLactea/industriaLactea.jrxml';
						            	$salidaReporte= '/aplicaciones/registroOperador/certificados/industriaLactea/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
						            	$rutaArchivo= 'aplicaciones/registroOperador/certificados/industriaLactea/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
						            	
						            	$parameters['parametrosReporte'] = array(
						            		'idOperadorTipoOperacion'=> (int)$idOperadorTipoOperacion,
						            		'codigoCertificado'=> $codigo,
						            		'nombreTecnico'=> $nombreInspector['apellido'].' '.$nombreInspector['nombre']
						            	);
						            	
						            	$cr->actualizarFechaFinalizacionOperacionesAntiguos ($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $fechaCaducidad, 0, 'day', 0);
						            	$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'industriaLactea');
						            	$existenciaDocumento = $cr->obtenerDocumentoGeneradoPorSecuencialIdentificadorOperadorTipoDocumento($conexion, $secuencial, $idOperador, 'industriaLactea');
						            	if(pg_num_rows($existenciaDocumento) == 0){
						            	    $cr->guardarDocumentoOperador($conexion, $solicitud, $idOperadorTipoOperacion, $rutaArchivo, 'industriaLactea', $secuencial, $idOperador, 'Certificación de registro de operador de industrias lacteas');
						            	}
						            	$qOperaciones=$cr->buscarOperacionesPorCodigoyAreaOperacion($conexion, $idOperador,"('$opcionArea')","('$idArea')");
						            	
						            	if(pg_num_rows($qOperaciones)>0){
						            		$modulosAgregados.="('PRG_MOV_SUERO'),";
						            		$perfilesAgregados.="('PFL_MOV_SUEROS'),";
						            	}
						            	
						            	//Tabla de firmas físicas
						            	$firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, $provinciaSitio, 'AI'));
						            	
						            	$rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$rutaArchivo;
						            	
						            	//Firma Electrónica
						            	$parametrosFirma = array(
						            		'archivo_entrada'=>$rutaArchivo,
						            		'archivo_salida'=>$rutaArchivo,
						            		'identificador'=>$firmaResponsable['identificador'],
						            		'razon_documento'=>'Certificación de registro de operador de industrias lacteas',
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
								
								if($opcionArea == "PRO" || $opcionArea == "REC" || $opcionArea == "PRC" || $opcionArea == "COM"){
						            
						            $cMail = new ControladorMail();
						            $cr = new ControladorRegistroOperador();
						            
						            $cuerpoMensaje= '<html xmlns="http://www.w3.org/1999/xhtml"><body style="margin:0; padding:0;">
                					<style type="text/css">
                						                
                						.titulo  {
                							margin-top: 30px;
                							width: 800px;
                							text-align: center;
                							font-size: 14px;
                							font-weight: bold;
                							font-family:Times New Roman;
                						}
                						                
                						.lineaDos{
                							font-style: oblique;
                							font-weight: normal;
                						}
                						                
                						.lineaLeft{
                							text-align: left;
                						}
                						                
                						.lineaEspacio{
                							height: 35px;
                						}
                						.lineaEspacioMedio{
                							height: 50px;
                						}
                						.espacioLeft{
                							padding-left: 15px;
                						}
                					</style>';
									
									$qDatosOperador = $cr->buscarOperador($conexion, $idOperador);
						            $datosOperador = pg_fetch_assoc($qDatosOperador);
						            
						            $nombreOperador =  ($datosOperador['razon_social'] == "") ? $datosOperador['nombre_representante'] . ' ' . $datosOperador['apellido_representante'] : $datosOperador['razon_social'];
						            
                					$cuerpoMensaje.='<table class="titulo">
                					<thead>
                					<tr><th></th></tr>
                					</thead>
                					<tbody> 
									<tr><td class="lineaLeft lineaEspacio">Estimados Srs. <b>' . $nombreOperador . '</b></td></tr>
                					<tr><td class="lineaLeft lineaEspacio">Por medio del presente se comunica que su solicitud de registro de operador Orgánico Nro.' . $idSolicitud . ' ha sido atendida.</td></tr>
                					<tr><td class="lineaLeft lineaEspacio"><b>RESULTADO REVISIÓN: </b>' . $resultadoDocumento . '</td></tr>
                                    <tr><td class="lineaLeft lineaEspacio"><b>OBSERVACIÓN: </b>' . $observacionesDocumento . '</td></tr>
                					</tbody>
                					<tfooter>
                					<tr><td class="lineaEspacioMedio"></td></tr>
                					<tr><td class="lineaDos lineaLeft espacioLeft">Por favor ingresar a su perfil del Sistema GUIA y revisar con mejor detalle su registro, en el módulo Inscripción de operadores. </td></tr>
                					<tr><td class="lineaDos lineaLeft espacioLeft"><span style="font-weight:bold;" >NOTA: </span>Este correo fue generado automaticamente por el sistema GUIA, por favor no responder a este mensaje. </td></tr>
                					<tr><td class="lineaDos lineaLeft espacioLeft">Saludos cordiales</td></tr>
                					</tfooter>
                					</table>';
						            
						            $asunto = 'Resultado de revisión de Registro de Operador ORGÁNICO.';
						            $codigoModulo = '';
						            $tablaModulo = '';
						            $destinatarios = array();
						            
						            array_push($destinatarios, $datosOperador['correo']);
						            
						            $qGuardarCorreo = $cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo, '');
						            $idCorreo = pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
						            $cMail->guardarDestinatario($conexion, $idCorreo,$destinatarios);
						            
						        }
								
                            break;
						    case 'LT':
						        $jru = new ControladorReportes();
						        $cef = new ControladorEstructuraFuncionarios();
						        
						        $nombreResponsable = pg_fetch_result($cef->obtenerResponsablePorArea($conexion, 'CGL'), 0, 'usuario');
						        
						        switch ($opcionArea){
						            case 'LAL':
						                $ReporteJasper= '/aplicaciones/registroOperador/reportes/laboratorios/laboratorioLeche.jrxml';
						                $salidaReporte= '/aplicaciones/registroOperador/certificados/laboratorioLeche/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
						                $rutaArchivo= 'aplicaciones/registroOperador/certificados/laboratorioLeche/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';

						                
						                $parameters['parametrosReporte'] = array(
						                	'idOperadorTipoOperacion'=> (int)$idOperadorTipoOperacion,
						                	'codigo'=> 'RLA-LCLC-'.$secuencial
						                );

						                $jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'laboratorio');
						                if($generarDocumento){
						                    $cr->guardarDocumentoOperador($conexion, $solicitud, $idOperadorTipoOperacion, $rutaArchivo, 'laboratorioLecheCruda', $secuencial, $idOperador, 'Certificación de registro de análisis de leche cruda.');
						                }
						                
						                //Tabla de firmas físicas
						                $firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, 'Coordinador','LT'));
						                
						                $rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$rutaArchivo;
						                
						                //Firma Electrónica
						                $parametrosFirma = array(
						                	'archivo_entrada'=>$rutaArchivo,
						                	'archivo_salida'=>$rutaArchivo,
						                	'identificador'=>$firmaResponsable['identificador'],
						                	'razon_documento'=>'Certificación de registro de análisis de leche cruda',
						                	'tabla_origen'=>'g_operadores.documentos_operador',
						                	'campo_origen'=>'ruta_archivo',
						                	'id_origen'=>$solicitud,
						                	'estado'=>'Por atender',
						                	'proceso_firmado'=>'NO'
						                );
						                
						                //Guardar registro para firma
						                $cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
						                
                                    break;
						            case 'LDV':
						                $ReporteJasper= '/aplicaciones/registroOperador/reportes/laboratorios/laboratorioDiagnostico.jrxml';
						                $salidaReporte= '/aplicaciones/registroOperador/certificados/laboratorioDiagnostico/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
						                $rutaArchivo= 'aplicaciones/registroOperador/certificados/laboratorioDiagnostico/'.$idOperador.'_'.$idOperadorTipoOperacion.'.pdf';
						                
						                $parameters['parametrosReporte'] = array(
						                	'idOperadorTipoOperacion'=> (int)$idOperadorTipoOperacion,
						                	'codigo'=> 'RLA-LDV-'.$secuencial
						                );
						                
						                $jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'laboratorio');
						                
						                if($generarDocumento){
						                  $cr->guardarDocumentoOperador($conexion, $solicitud, $idOperadorTipoOperacion, $rutaArchivo, 'laboratorioDiagnostico', $secuencial, $idOperador, 'Certificación de registro de diagnostico veterinario.');
						                }
						                
						                //Tabla de firmas físicas
						                $firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, 'Coordinador','LT'));
						                
						                $rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$rutaArchivo;
						                
						                //Firma Electrónica
						                $parametrosFirma = array(
						                	'archivo_entrada'=>$rutaArchivo,
						                	'archivo_salida'=>$rutaArchivo,
						                	'identificador'=>$firmaResponsable['identificador'],
						                	'razon_documento'=>'Certificación de registro de análisis de leche cruda',
						                	'tabla_origen'=>'g_operadores.documentos_operador',
						                	'campo_origen'=>'ruta_archivo',
						                	'id_origen'=>$solicitud,
						                	'estado'=>'Por atender',
						                	'proceso_firmado'=>'NO'
						                );
						                
						                //Guardar registro para firma
						                $cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
						                
                                    break;
						            case 'LDI':
						            case 'LDA':
						            case 'LDE':
										
										$rutaPago = $_POST['rutaPago'];
										$procesoSancion = $_POST['sancion'];
										$rutaSancion = $_POST['rutaSancion'];
										$certificadoSae = $_POST['certificadoSae'];
										
										$cr->guardarOperacionesLaboratorio($conexion, $solicitud, $idOperadorTipoOperacion, $idHistoricoOperacion, $procesoPago, $rutaPago, $procesoSancion, $rutaSancion, $certificadoSae);
										
									break;
									 }
								break;
									
									//>> Material reproductivo

									case 'SA':
										$tecnicoInspeccionPlanificacion = htmlspecialchars($_POST['tecnicoInspeccion'], ENT_NOQUOTES, 'UTF-8');
										$fechaInspeccionPlanificacion = htmlspecialchars($_POST['fechaInspeccion'], ENT_NOQUOTES, 'UTF-8');
										$horaInspeccionPlanificacion = htmlspecialchars($_POST['horaInspeccion'], ENT_NOQUOTES, 'UTF-8');

										switch($opcionArea){
			
										case 'PMR':
										case 'CPM':
										case 'DMR':
										case 'AMR':
										case 'OEC':
												$cr->guardarPlanificcionInspeccion($conexion,$tecnicoInspeccionPlanificacion, $fechaInspeccionPlanificacion, $horaInspeccionPlanificacion,$solicitud, $idOperadorTipoOperacion);										
										break;
											
										}		
											
									break ;

									//<< Fin material reproductivo

						        }
					}
					
					if(strlen($modulosAgregados)==0){
						$modulosAgregados="''";
					}
					
					if(strlen($perfilesAgregados)==0){
						$perfilesAgregados="''";
					}
					$cgap= new ControladorGestionAplicacionesPerfiles();
					$qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion,'('.rtrim($modulosAgregados,',').')' );
					if(pg_num_rows($qGrupoAplicacion)>0){
						$ca = new ControladorAplicaciones();
						while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
							if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $idOperador))==0){
								$cgap->guardarGestionAplicacion($conexion, $idOperador,$filaAplicacion['codificacion_aplicacion']);
								$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], '('.rtrim($perfilesAgregados,',').')' );
								while($filaPerfil=pg_fetch_assoc($qGrupoPerfiles)){
									$cgap->guardarGestionPerfil($conexion, $idOperador,$filaPerfil['codificacion_perfil']);
								}
							}else{
								$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], '('.rtrim($perfilesAgregados,',').')' );
								while($filaPerfil=pg_fetch_assoc($qGrupoPerfiles)){
									$cu = new ControladorUsuarios();
									$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $filaPerfil['id_perfil'], $idOperador);
									if (pg_num_rows($qPerfil) == 0)
										$cgap->guardarGestionPerfil($conexion, $idOperador,$filaPerfil['codificacion_perfil']);
								}
							}
						}
					}
					
					$cr->cambiarEstadoActualizarCertificado($conexion, $idOperadorTipoOperacion, $idHistoricoOperacion, 'SI');
					
				}else{
					foreach ($idGrupoSolicitudes as $solicitud){
						$operacion = pg_fetch_assoc($cr->abrirOperacionXid($conexion, $solicitud));
						$idOperadorTipoOperacion = $operacion['id_operador_tipo_operacion'];
							
						$qHistorialOperacion = $cr->obtenerMaximoIdentificadorHistoricoOperacion($conexion, $idOperadorTipoOperacion);
						$historialOperacion = pg_fetch_assoc($qHistorialOperacion);
					
						$cr->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']);
						
						$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $resultadoDocumento, $observacionesDocumento);
							
						$cr->actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $idOperadorTipoOperacion, $resultadoDocumento);
						
						$qcodigoTipoOperacion= $cc->obtenerCodigoTipoOperacion($conexion, $solicitud);
						$opcionArea = pg_fetch_result($qcodigoTipoOperacion, 0, 'codigo');
						$idArea = pg_fetch_result($qcodigoTipoOperacion, 0, 'id_area');
						$idTipoOperacion = pg_fetch_result($qcodigoTipoOperacion, 0, 'id_tipo_operacion');
						$tipoOperacionNombre = pg_fetch_result($qcodigoTipoOperacion, 0, 'nombre');
							
						switch ($idArea){
							case 'AI':
								if(($opcionArea ='MDT' || $opcionArea ='MDC') && ($resultadoDocumento=='noHabilitado')){
									$cro = new ControladorRegistroOperador();
									$cro->inactivarVehiculo($conexion, $idOperadorTipoOperacion);
								}   
								$cu = new ControladorUsuarios();
								switch ($opcionArea){
									case 'PRO':
									case 'REC':
									case 'PRC':
									case 'COM':
										
										$cMail = new ControladorMail();
										$cr = new ControladorRegistroOperador();
										
										$cuerpoMensaje= '<html xmlns="http://www.w3.org/1999/xhtml"><body style="margin:0; padding:0;">
                					<style type="text/css">
											
                						.titulo  {
                							margin-top: 30px;
                							width: 800px;
                							text-align: center;
                							font-size: 14px;
                							font-weight: bold;
                							font-family:Times New Roman;
                						}
											
                						.lineaDos{
                							font-style: oblique;
                							font-weight: normal;
                						}
											
                						.lineaLeft{
                							text-align: left;
                						}
											
                						.lineaEspacio{
                							height: 35px;
                						}
                						.lineaEspacioMedio{
                							height: 50px;
                						}
                						.espacioLeft{
                							padding-left: 15px;
                						}
                					</style>';
										
										$qDatosOperador = $cr->buscarOperador($conexion, $idOperador);
										$datosOperador = pg_fetch_assoc($qDatosOperador);
										
										$nombreOperador =  ($datosOperador['razon_social'] == "") ? $datosOperador['nombre_representante'] . ' ' . $datosOperador['apellido_representante'] : $datosOperador['razon_social'];
										
										$cuerpoMensaje.='<table class="titulo">
                					<thead>
                					<tr><th></th></tr>
                					</thead>
                					<tbody>
                                    <tr><td class="lineaLeft lineaEspacio">Estimados Srs. <b>' . $nombreOperador . '</b></td></tr>
                					<tr><td class="lineaLeft lineaEspacio">Por medio del presente se comunica que su solicitud de registro de operador Orgánico Nro.' . $idSolicitud . ' ha sido atendida.</td></tr>
                					<tr><td class="lineaLeft lineaEspacio"><b>RESULTADO REVISIÓN: </b>' . $resultadoDocumento . '</td></tr>
                                    <tr><td class="lineaLeft lineaEspacio"><b>OBSERVACIÓN: </b>' . $observacionesDocumento . '</td></tr>
                					</tbody>
                					<tfooter>
                					<tr><td class="lineaEspacioMedio"></td></tr>
                					<tr><td class="lineaDos lineaLeft espacioLeft">Por favor ingresar a su perfil del Sistema GUIA y revisar con mejor detalle su registro, en el módulo Inscripción de operadores. </td></tr>
                					<tr><td class="lineaDos lineaLeft espacioLeft"><span style="font-weight:bold;" >NOTA: </span>Este correo fue generado automaticamente por el sistema GUIA, por favor no responder a este mensaje. </td></tr>
                					<tr><td class="lineaDos lineaLeft espacioLeft">Saludos cordiales</td></tr>
									</tfooter>
                					</table>';
										
										$asunto = 'Resultado de revisión de Registro de Operador ORGÁNICO.';
										$codigoModulo = '';
										$tablaModulo = '';
										$destinatarios = array();
										
										array_push($destinatarios, $datosOperador['correo']);
										
										$qGuardarCorreo = $cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo, '');
										$idCorreo = pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
										$cMail->guardarDestinatario($conexion, $idCorreo,$destinatarios);
										
										$cr->cambiarEstadoActualizarCertificado($conexion, $idOperadorTipoOperacion, $idHistoricoOperacion, 'SI');
											
						               break;
							    }
							break;
						}
					}
				}
			break;
			
			case 'Importación' : 
				$ci = new ControladorImportaciones();
				$ci->enviarImportacion($conexion, $idSolicitud, $resultadoDocumento);
				
				if(($_POST['idArea']=='SA' || $_POST['idArea']=='SV') && $_POST['requiereSeguimiento']=='SI' ){
					require_once '../../clases/ControladorSeguimientoCuarentenario.php';
					$csc = new ControladorSeguimientoCuarentenario();
					$idAreaSeguimiento = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
					$csc->actualizarDatosSeguimientoCuarentenario($conexion, $idSolicitud, 'TRUE', $idAreaSeguimiento);
				}
				
				if($resultadoDocumento =='subsanacion' && $idVue!=''){
					$cVUE->ingresarSolicitudesXatenderGUIA('101-002-REQ','410','21',$idVue, 'Por atender', $observacionesDocumento);
				}
				
				if($resultadoDocumento =='rechazado' && $idVue!=''){
					$cVUE->ingresarSolicitudesXatenderGUIA('101-002-REQ','310','21',$idVue, 'Por atender', $observacionesDocumento);
				}
				$crs->guardarDatosInspeccionDocumental($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, $observacionesDocumento, $resultadoDocumento,pg_fetch_result($ordenInspeccion, 0, 'orden'));
			break;
			
			case 'ImportaciónFertilizantes':
				$cif = new ControladorImportacionesFertilizantes();
				$cif->cambiarEstadoImportacionFertilizantes($conexion, $idSolicitud, $resultadoDocumento, $inspector, $observacionesDocumento);
				
				if($resultadoDocumento == 'aprobado'){
					$qDocumentoAdjunto = $cif->obtenerDocumentoAdjuntoPorNombre($conexion, $idSolicitud, 'Autorización de importación de fertilizantes');
					$documentoAdjunto = pg_fetch_assoc($qDocumentoAdjunto);
					$cif->actualizarEstadoDocumentoAdjunto($conexion, $idSolicitud, $documentoAdjunto['id_documento_adjunto']);
					
					$cMail = new ControladorMail();
					$cr = new ControladorRegistroOperador();
					
					$nombreComercialProducto = $_POST['nombreComercialProducto'];
					
					$cuerpoMensaje= '<html xmlns="http://www.w3.org/1999/xhtml"><body style="margin:0; padding:0;">
					<style type="text/css">
						
						.titulo  {
							margin-top: 30px;
							width: 800px;
							text-align: center;
							font-size: 14px;
							font-weight: bold;
							font-family:Times New Roman;
						}
						
						.lineaDos{
							font-style: oblique;
							font-weight: normal;
						}
						
						.lineaLeft{
							text-align: left;
						}
						
						.lineaEspacio{
							height: 35px;
						}
						.lineaEspacioMedio{
							height: 50px;
						}
						.espacioLeft{
							padding-left: 15px;
						}
					</style>';
					
					$cuerpoMensaje.='<table class="titulo">
					<thead>
					<tr><th>Estimado usuario,</th></tr>
					</thead>
					<tbody>
					<tr><td class="lineaDos lineaEspacio">El permiso de importación del producto '.$nombreComercialProducto.'</td></tr>
					<tr><td class="lineaDos lineaEspacio"><b>Observación: </b>'.$observacionesDocumento.'</td></tr>
					</tbody>
					<tfooter>
					<tr><td class="lineaEspacioMedio"></td></tr>
					<tr><td class="lineaDos lineaLeft espacioLeft"><span style="font-weight:bold;" >NOTA: </span>Este correo fue generado automaticamente por el sistema GUIA, por favor no responder a este mensaje. </td></tr>
					<tr><td class="lineaDos lineaLeft espacioLeft">Saludos cordiales</td></tr>
					</tfooter>
					</table>';
					
					$asunto = 'Permiso de importación del producto '.$nombreComercialProducto.' aprobado';
					$codigoModulo='';
					$tablaModulo='';
					$destinatarios = array();
					$adjuntos = array();
					$rutaArchivo = $constg::RUTA_SERVIDOR_OPT."/".$constg::RUTA_APLICACION."/".$documentoAdjunto['ruta_archivo'];
					
					$qDatosOperador = $cr->buscarOperador($conexion, $idOperador);
					$datosOperador = pg_fetch_assoc($qDatosOperador);
					
					array_push($destinatarios, $datosOperador['correo']);
					array_push($adjuntos, $rutaArchivo);
					
					$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo, '');
					$idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
					$cMail->guardarDestinatario($conexion, $idCorreo,$destinatarios);
					$cMail->guardarDocumentoAdjunto($conexion, $idCorreo, $adjuntos);
					
				}
				
				$crs->guardarDatosInspeccionDocumental($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, $observacionesDocumento, $resultadoDocumento,pg_fetch_result($ordenInspeccion, 0, 'orden'));
				
			break;
			
			case 'TransitoInternacional' :
			    $cti = new ControladorTransitoInternacional();
			    $cti->enviarTransitoInternacional($conexion, $idSolicitud, $resultadoDocumento);
			    
			    if($resultadoDocumento =='subsanacion' && $idVue!=''){
			        $cVUE->ingresarSolicitudesXatenderGUIA('101-061-REQ','410','21',$idVue, 'Por atender', $observacionesDocumento);
			    }
			    
			    if($resultadoDocumento =='rechazado' && $idVue!=''){
			        $cVUE->ingresarSolicitudesXatenderGUIA('101-061-REQ','310','21',$idVue, 'Por atender', $observacionesDocumento);
			    }
			    
			    if($resultadoDocumento == 'aprobado'){
			        
			        $cti->cambiarEstadoTransitoInternacional($conexion, $idSolicitud, $resultadoDocumento, $inspector, $observacionesDocumento);
			        
					$cVUE->ingresarSolicitudesXatenderGUIA('101-061-REQ','320','21',$idVue, 'Por atender');
			    }
			    
			    $crs->guardarDatosInspeccionDocumental($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, $observacionesDocumento, $resultadoDocumento,pg_fetch_result($ordenInspeccion, 0, 'orden'));
			    break;
			
			case 'DDA' :
				$cd = new ControladorDestinacionAduanera();
				$cd->enviarDDA($conexion, $idSolicitud, $resultadoDocumento);
				
				$fechaEmbarque = htmlspecialchars ($_POST['fechaEmbarque'],ENT_NOQUOTES,'UTF-8');
				$fechaArribo= htmlspecialchars ($_POST['fechaArribo'],ENT_NOQUOTES,'UTF-8');
				$numeroContenedores = htmlspecialchars ($_POST['numeroContenedores'],ENT_NOQUOTES,'UTF-8');
				$archivoDocumental = htmlspecialchars ($_POST['archivoDocumental'],ENT_NOQUOTES,'UTF-8');
				
				$numeroContenedores = $numeroContenedores == '' ? 'null' : $numeroContenedores;
				
				$cd->actualizarDatosInspeccionDDA($conexion, $idSolicitud, $fechaEmbarque, $fechaArribo, $numeroContenedores);
				
				if($resultadoDocumento =='subsanacion' && $idVue!=''){
					$cVUE->ingresarSolicitudesXatenderGUIA('101-024-REQ','410','21',$idVue, 'Por atender', $observacionesDocumento);
				}else{
					$cd->actualizarContadorInspeccionDDA($conexion, $idSolicitud, 1);

					if($_POST['tipoCertificado']=='ANIMAL' && $_POST['requiereSeguimientoCuarentenario']=='t'){
					    $cd->actualizarSeguimientoCuarentenario($conexion, $idSolicitud,$_POST['provincia']);
					}

				}
				$crs->guardarDatosInspeccionDocumental($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, $observacionesDocumento, $resultadoDocumento,pg_fetch_result($ordenInspeccion, 0, 'orden'), $archivoDocumental);
			break;
		
			case 'Fitosanitario' :
				$cf = new ControladorFitosanitario();
				$cf->enviarFito($conexion, $idSolicitud, $resultadoDocumento);
				if($resultadoDocumento =='subsanacion' && $idVue!=''){
					$cVUE->ingresarSolicitudesXatenderGUIA('101-031-REQ','410','21',$idVue, 'Por atender', $observacionesDocumento);
				}
				$crs->guardarDatosInspeccionDocumental($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, $observacionesDocumento, $resultadoDocumento,pg_fetch_result($ordenInspeccion, 0, 'orden'));
			break;
		
			case 'Zoosanitario' :
				$cz = new ControladorZoosanitarioExportacion();
				$cz->enviarZoo($conexion, $idSolicitud, $resultadoDocumento);
				if($resultadoDocumento =='subsanacion' && $idVue!=''){
					$cVUE->ingresarSolicitudesXatenderGUIA('101-008-REQ','410','21',$idVue, 'Por atender', $observacionesDocumento);
				}
				$crs->guardarDatosInspeccionDocumental($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, $observacionesDocumento, $resultadoDocumento,pg_fetch_result($ordenInspeccion, 0, 'orden'));
			break;
		
			case 'Muestras' :
				/*$ci = new ControladorImportaciones();
				 $ci->enviarImportacion($conexion, $idSolicitud, $resultadoDocumento);*/
			break;
		
			case 'CLV' :
				$cl = new ControladorClv();
				$cl->enviarClv($conexion, $idSolicitud, $resultadoDocumento);
				$cl->evaluarProductosCLV($conexion, $idSolicitud, $resultadoDocumento);
				$cl->enviarFechaVigenciaCLV($conexion, $idSolicitud);
				
				if($resultadoDocumento =='subsanacion' && $idVue!=''){
					$cVUE->ingresarSolicitudesXatenderGUIA('101-047-REQ','410','21',$idVue, 'Por atender', $observacionesDocumento);
				}else if ($resultadoDocumento =='aprobado' && $idVue!=''){
					$cVUE->ingresarSolicitudesXatenderGUIA('101-047-REQ','320','21',$idVue, 'Por atender');
				}
				$crs->guardarDatosInspeccionDocumental($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, $observacionesDocumento, $resultadoDocumento,pg_fetch_result($ordenInspeccion, 0, 'orden'));
			break;
			
			case 'certificadoCalidad':
				
				$cca = new ControladorCertificadoCalidad();
				
				$empresaVerificadora = htmlspecialchars ($_POST['empresaVerificadora'],ENT_NOQUOTES,'UTF-8');
				
				foreach ($idGrupoSolicitudes as $solicitud){
					$cca->actualizarEstadoLote($conexion, $solicitud, $resultadoDocumento, $empresaVerificadora);
				}
				$crs->guardarDatosInspeccionDocumental($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, $observacionesDocumento, $resultadoDocumento,pg_fetch_result($ordenInspeccion, 0, 'orden'));
			break;
			
			case 'tramitesInocuidad':
				
				$cti = new ControladorTramitesInocuidad();
				
				$documentoFalso = htmlspecialchars ($_POST['documentoFalso'],ENT_NOQUOTES,'UTF-8');
				
				$cti->actualizarDocumentosFalsosTramite($conexion, $idSolicitud, $documentoFalso);
				$cti->actualizarEstadoTramite($conexion, $idSolicitud, $resultadoDocumento);
				
				$fechaDespacho = date('Y-m-d h:m:s');
				
				$cti-> guardarSeguimientoTramite($conexion, $idSolicitud, $inspector, $fechaDespacho, $observacionesDocumento);
				
				$crs->guardarDatosInspeccionDocumental($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, $observacionesDocumento, $resultadoDocumento,pg_fetch_result($ordenInspeccion, 0, 'orden'));
			break;
			
			case 'FitosanitarioExportacion' :
				$cfe = new ControladorFitosanitarioExportacion();
								
				if($resultadoDocumento =='inspeccion' && $idVue!=''){
					
					$horaInspeccion = substr($_POST['horaInspeccion'], 0, 2);
					$minutosInspeccion = substr($_POST['horaInspeccion'], 3, 2);
					
					$fechaInspeccion = new DateTime($_POST['fechaInspeccion']);
					date_time_set($fechaInspeccion,$horaInspeccion,$minutosInspeccion);
					
					$fechaInspeccion=date_format($fechaInspeccion, 'Y-m-d H:i:s');
									
					$producto = $cfe->obtenerProgramaProductosPorIdentificadorFitosanitarioExportacion($conexion, $idSolicitud);
					
					$arrayProductoPrograma = array();
					$arrayProductoExoneracion = array();
					$arrayProductoFinanciero = array();
					$productoMusaceas = true;
					$productoOrnamentales =true;
					$poseeAnexo = true;
					$productoMango = false;
					$banderaFito = false;
					
					while ($fila = pg_fetch_assoc($producto)){
						
						$partidaArancelaria =  $fila['partida_arancelaria'];
						
						if($productoMusaceas && ($partidaArancelaria == '0803101000' || $partidaArancelaria == '0803901110' || $partidaArancelaria == '0803901200' || $partidaArancelaria == '0803901900' || $partidaArancelaria == '0803901190')){
							$productoMusaceas = false;
							$banderaFito = true;
							$arrayProductoFinanciero[] = array(idProducto => $fila['id_producto'] , nombreProducto => $fila['nombre_comun'], cantidadCobro=> 1, unidadCobro => $fila['unidad_cobro'], exoneracion=> $fila['exoneracion']);
						}else if($productoOrnamentales && ($partidaArancelaria == '0603110000' || $partidaArancelaria == '0603121000' || $partidaArancelaria == '0603129000' || $partidaArancelaria == '0603130000' 
										|| $partidaArancelaria == '0603141000' || $partidaArancelaria == '0603149000' || $partidaArancelaria == '0603150000' || $partidaArancelaria == '0603191000' 
										|| $partidaArancelaria == '0603192000' || $partidaArancelaria == '0603193000' || $partidaArancelaria == '0603194000' || $partidaArancelaria == '0603199010' 
										|| $partidaArancelaria == '0603199090' || $partidaArancelaria == '0603900000' || $partidaArancelaria == '0604200000' || $partidaArancelaria == '0604900000')){
							$productoOrnamentales = false;
							$banderaFito = true;
							$arrayProductoFinanciero[] = array(idProducto => $fila['id_producto'] , nombreProducto => $fila['nombre_comun'], cantidadCobro=> 1, unidadCobro => $fila['unidad_cobro'], exoneracion=> $fila['exoneracion']);
						}else if($partidaArancelaria == '0804502000'){
							$productoMango = true;
							$arrayProductoFinanciero[] = array(idProducto => $fila['id_producto'] , nombreProducto => $fila['nombre_comun'], cantidadCobro=> $fila['cantidad_cobro'], unidadCobro => $fila['unidad_cobro'], exoneracion=> $fila['exoneracion']);
						}else{
							if(!$banderaFito)
								$arrayProductoFinanciero[] = array(idProducto => $fila['id_producto'] , nombreProducto => $fila['nombre_comun'], cantidadCobro=> $fila['cantidad_cobro'], unidadCobro => $fila['unidad_cobro'], exoneracion=> $fila['exoneracion']);
						}
												
						$arrayProductoPrograma[] = $fila['programa']; 
						$arrayProductoExoneracion[] = $fila['exoneracion'];
					}
					
					$arrayProductoProgramaSinRepetidos = array_unique($arrayProductoPrograma);
					$arrayProductoExoneracionSinRepetidos = array_unique($arrayProductoExoneracion);
					
					$anexoFitosanitario = count($cfe->obtenerArchivosAdjuntosFitosanitarioExportacion($conexion, $idSolicitud));
					
					if($anexoFitosanitario == 0){
						$poseeAnexo = false;
					}

					$qFitosanitarioExportacion = $cfe->obtenerCabeceraFitosanitarioExportacion($conexion, $idSolicitud);
					$fitosanitarioExportacion = pg_fetch_assoc($qFitosanitarioExportacion);
					
					if(count($arrayProductoProgramaSinRepetidos) == 1){
												
						if($arrayProductoProgramaSinRepetidos[0] == 'SI' && $poseeAnexo){

							if(count($arrayProductoExoneracionSinRepetidos) == 1){
								if($arrayProductoExoneracionSinRepetidos[0] == 'SI'){
									$procesoPago = false;
								}else{
									$procesoPago = true;
								}
							}else{
								$procesoPago = true;
							}
							
							if($fitosanitarioExportacion['descuento'] == 'SI' || $productoMango){
								if($procesoPago){
									$cfe->actualizarEstadoFitosanitarioExportacion($conexion, $idSolicitud, 'pago', 'enviado', $observacionesDocumento);
								}else{
									$cfe->actualizarEstadoFitosanitarioExportacion($conexion, $idSolicitud, $resultadoDocumento, 'verificacion', 'Aprobado');
									$cfe->actualizarFechasAprobacionFitosanitarioExportacion($conexion, $idSolicitud);
									$cVUE->ingresarSolicitudesXatenderGUIA('101-034-REQ','320','21',$idVue, 'Por atender', 'Aprobación automatica por exoneración.');
								}
								
							}else{
																
								if($procesoPago){
									
									$cff = new ControladorFinanciero();
									$cfa = new ControladorFinancieroAutomatico();
									$itemTarifario = array();
									$totalOrden = 0;
									
									foreach ($arrayProductoFinanciero as $productoFinanciero){
											
										$productoUnidadCobro = pg_fetch_assoc($cff->obtenerUnidadMedidaCobro($conexion, $productoFinanciero['idProducto'], 'Fitosanitario'));
											
										$cantidadProducto = $productoFinanciero['cantidadCobro']; //Cantidad enviada en el CFE
										$cantidadProductoTarifario = $productoUnidadCobro['unidad']; //Unidad del tarifario
										$valorCantidadProductoTarifario = $productoUnidadCobro['valor']; //Costo del producto en el tarifario
											
										if($productoUnidadCobro['cobro_exceso'] == 'No'){
											$valorProductoSinExceso = $cantidadProducto * $valorCantidadProductoTarifario;
											$valorProductoSinExceso = $productoFinanciero['exoneracion'] == 'NO'? $valorProductoSinExceso: 0;
										}else{
											$valorProductoSinExceso  = $valorCantidadProductoTarifario;
											$valorProductoSinExceso = $productoFinanciero['exoneracion'] == 'NO'? $valorProductoSinExceso: 0;
									
											$cantidadProductoExceso = $cantidadProducto - $cantidadProductoTarifario;
									
											if($cantidadProductoExceso != 0){
												$itemExceso = pg_fetch_assoc($cff->obtenerServicio($conexion, $productoUnidadCobro['id_servicio_exceso']));
												$valorProdutoConExceso = round(($cantidadProductoExceso*$itemExceso['valor'])/$itemExceso['unidad'],2);
												$valorProdutoConExceso = $productoFinanciero['exoneracion'] == 'NO'? $valorProdutoConExceso: 0;
												$itemTarifario[] = array(idServicio => $itemExceso['id_servicio'], conceptoServicio => $itemExceso['concepto'], cantidad => $cantidadProductoExceso, valorUnitario => $itemExceso['valor'], descuento => '0', iva => '0', total => $valorProdutoConExceso);
											}
									
										}
											
										$itemTarifario[] = array(idServicio => $productoUnidadCobro['id_servicio'], conceptoServicio => $productoUnidadCobro['concepto'], cantidad => $cantidadProductoTarifario, valorUnitario => $valorCantidadProductoTarifario, descuento => '0', iva => '0', total => $valorProductoSinExceso);
									
										$totalOrden += $valorProductoSinExceso + $valorProdutoConExceso;
									}
									
									$idFinancieroCabecera = pg_fetch_result($cfa->guardarFinancieroAutomaticoCabecera($conexion, $totalOrden, $fitosanitarioExportacion['id_vue'], 'FitosanitarioExportacion'), 0, 'id_financiero_cabecera');
									
									foreach ($itemTarifario as $item){
										$cfa->guardarFinancieroAutomaticoDetalle($conexion, $idFinancieroCabecera, $item['idServicio'], $item['conceptoServicio'], $item['cantidad'], $item['valorUnitario'], $item['descuento'], $item['iva'], $item['total']);
									}
									
									$cfa->actualizarEstadoFinancieroAutomaticoCabecera($conexion, $idFinancieroCabecera, 'Por atender');
									$cfe->actualizarEstadoFitosanitarioExportacion($conexion, $idSolicitud, 'verificacionAutomatica', 'enviado', $observacionesDocumento);
									
								}else{
									$cfe->actualizarEstadoFitosanitarioExportacion($conexion, $idSolicitud, $resultadoDocumento, 'verificacion', 'Aprobado');
									$cfe->actualizarFechasAprobacionFitosanitarioExportacion($conexion, $idSolicitud);
									$cVUE->ingresarSolicitudesXatenderGUIA('101-034-REQ','320','21',$idVue, 'Por atender', 'Aprobación automatica por exoneración.');
								}
								
								
							}							
						}else{
							$cfe->actualizarEstadoFitosanitarioExportacion($conexion, $idSolicitud, $resultadoDocumento, 'enviado', $observacionesDocumento);
							$cfe->actualizarFechaInspeccionFitosanitarioExportacion($conexion, $idSolicitud, $fechaInspeccion);
							$cVUE->ingresarSolicitudesXatenderGUIA('101-034-REQ','230','21',$idVue, 'Por atender', 'Se realizará la inspección en el área '.$fitosanitarioExportacion['lugar_inspeccion'].' el día '.$fechaInspeccion);
						}
					}else{
						$cfe->actualizarEstadoFitosanitarioExportacion($conexion, $idSolicitud, $resultadoDocumento, 'enviado', $observacionesDocumento);
						$cfe->actualizarFechaInspeccionFitosanitarioExportacion($conexion, $idSolicitud, $fechaInspeccion);
						$cVUE->ingresarSolicitudesXatenderGUIA('101-034-REQ','230','21',$idVue, 'Por atender', 'Se realizará la inspección en el área '.$fitosanitarioExportacion['lugar_inspeccion'].' el día '.$fechaInspeccion);
					}
				}else if($resultadoDocumento =='subsanacion' && $idVue!=''){
					$cVUE->ingresarSolicitudesXatenderGUIA('101-034-REQ','410','21',$idVue, 'Por atender', $observacionesDocumento);
				}else if($resultadoDocumento =='rechazado' && $idVue!=''){
					$cVUE->ingresarSolicitudesXatenderGUIA('101-034-REQ','310','21',$idVue, 'Por atender', $observacionesDocumento);
				}
				$crs->guardarDatosInspeccionDocumental($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, $observacionesDocumento, $resultadoDocumento,pg_fetch_result($ordenInspeccion, 0, 'orden'));
			break;
			
			case 'mercanciasSinValorComercialExportacion':
			case 'mercanciasSinValorComercialImportacion':

				$detallePago = $_POST['detallePago'];

				$cme = new ControladorMercanciasSinValorComercial();
				
				$cme->actualizarEstadoMercanciaSV($conexion, $resultadoDocumento, $idSolicitud);
				$cme->actualizarObservacionMercanciaSV($conexion, $observacionesDocumento, $idSolicitud);
				$crs->guardarDatosInspeccionDocumental($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, $observacionesDocumento, $resultadoDocumento,pg_fetch_result($ordenInspeccion, 0, 'orden'));

				if($resultadoDocumento == 'pago'){

					$cme->generarFechaEmision($conexion,$idSolicitud);

					$jru = new ControladorReportes();

					if($tipoSolicitud == 'mercanciasSinValorComercialExportacion'){
						$ReporteJasper= '/aplicaciones/mercanciasSinValorComercial/reportes/certificado_zoosanitario_exportacion.jrxml';
						$salidaReporte= '/aplicaciones/mercanciasSinValorComercial/anexos/exportacion_'.$idSolicitud.'.pdf';
						$rutaArchivo= 'aplicaciones/mercanciasSinValorComercial/anexos/exportacion_'.$idSolicitud.'.pdf';
					}else{
						$ReporteJasper= '/aplicaciones/mercanciasSinValorComercial/reportes/certificado_zoosanitario_importacion.jrxml';
						$salidaReporte= '/aplicaciones/mercanciasSinValorComercial/anexos/importacion_'.$idSolicitud.'.pdf';
						$rutaArchivo= 'aplicaciones/mercanciasSinValorComercial/anexos/importacion_'.$idSolicitud.'.pdf';
					}

					$rutaSubreporte = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/mercanciasSinValorComercial/reportes/';
					
					$parameters['parametrosReporte'] = array(
						'idSolicitud'=>(int)$idSolicitud,
						'rutaSubreporte'=>$rutaSubreporte
					);

					$cme->actualizarObservacionMercanciaSV($conexion, $observacionesDocumento, $idSolicitud);
					$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte, $tipoSolicitud);
					$cme->guardarCertificadoZoosanitario($conexion, $rutaArchivo, $idSolicitud);
					$cme->actualizarDetallePago($conexion, $detallePago, $idSolicitud);
				}
			break;
			
			case 'certificacionBPA':
				
				$ccb = new ControladorCertificacionBPA();
				
				//Guardar resultados de revisiones en esquema por tipo de perfil
				//if($tipoInspector == 'Documental'){
					//si es nacional:
					//si viene con estado pago:
					//Verificar si tiene una orden de pago (estado inspeccion/pago)
					//si tiene, cambiar estado a inspeccion
					//si no tiene mantener el estado y enviar a guardar el estado en la solicitud y la observacion
					//$filaSolicitud['tipo_solicitud']=="Equivalente"?"Aprobado":($tipoAuditoriaBandera==true?"pago":"inspeccion"
					
					
					if($_POST['tipo_solicitud'] == 'Nacional'){
						$solicitudBPA = pg_fetch_assoc($ccb->abrirSolicitud($conexion, $idSolicitud));
						
						if($resultadoDocumento == 'pago'){
							//Verificar si tiene una auditoria de renovacion o ampliacion y si paso o no por pago
							//si tiene ya un pago hecho, cambiar estado a inspeccion
							$auditoriaPago = $ccb->buscarAuditoriasSolicitadasXFase($conexion, $idSolicitud, 'pago');
							
							if(pg_num_rows($auditoriaPago) > 0 && $solicitudBPA['paso_pago'] == 'Si'){
								$resultadoDocumento = 'inspeccion';
							}
						}
												
						if($resultadoDocumento == 'pago' || $resultadoDocumento == 'inspeccion'){
						    $ccb->actualizarResolucionSolicitud($conexion, $idSolicitud, $_POST['resolucion']);
						    
						    //Guardar la fecha de auditoría planificada
						    $ccb->actualizarFechaAuditoriaSolicitud($conexion, $idSolicitud, $_POST['fechaAuditoria']);
						   			
							//revisar si hay datos en la auditoria planificada, sino guardar en el
							//campo de auditoria complementaria,
							//*verificando si entre las auditorias solicitadas hay pedido para una complementaria
							
							/*if($solicitudBPA['fecha_auditoria_programada'] == null){
									$ccb->actualizarFechaAuditoriaSolicitud($conexion, $idSolicitud, $_POST['fechaAuditoria']);
							}else{
								$auditoriaComplementaria = $ccb->buscarAuditoriasSolicitadas($conexion, $idSolicitud, 'Complementaria');
								
								if(pg_num_rows($auditoriaComplementaria) > 0){
									$ccb->actualizarFechaAuditoriaComplementariaSolicitud($conexion, $idSolicitud, $_POST['fechaAuditoria']);
								}else{
									$ccb->actualizarFechaAuditoriaSolicitud($conexion, $idSolicitud, $_POST['fechaAuditoria']);
								}
							}*/		
						}
    						
					}else{
					    if($resultadoDocumento == 'Aprobado'){
						$ccb->actualizarResolucionSolicitud($conexion, $idSolicitud, $_POST['resolucion']);
			 
							//Cambiar de estado a los sitios de la solicitud
					        $ccb->actualizarEstadoSitiosSolicitud($conexion, $idSolicitud, $resultadoDocumento);					        
					    }
					}
				//}
				
				$ccb->actualizarEstadoSolicitud($conexion, $idSolicitud, $resultadoDocumento);
				$ccb->actualizarObservacionRevision($conexion, $idSolicitud, $observacionesDocumento);
				
				$ccb->actualizarDatosRevision($conexion, $idSolicitud, $tipoInspector);
				
				$crs->guardarDatosInspeccionDocumental($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, $observacionesDocumento, $resultadoDocumento,pg_fetch_result($ordenInspeccion, 0, 'orden'));
				
				
				//Generación del certificado
				if($resultadoDocumento == 'Aprobado'){
					 $solicitudBPA = $ccb->abrirSolicitud($conexion, $idSolicitud);
				    
				    $fechaAuditoriaReal = pg_fetch_result($solicitudBPA, 0, 'fecha_auditoria');
				    $fechaAuditoriaComplementaria = pg_fetch_result($solicitudBPA, 0, 'fecha_auditoria_complementaria');
				    
				    if($fechaAuditoriaComplementaria != null){
				        $fechaAuditoria = $fechaAuditoriaComplementaria;
				    }else{
				        $fechaAuditoria = $fechaAuditoriaReal;
				    }
				    
					//poner las fechas de aprobacion de inicio y fin (3 años)
				    $ccb->generarFechasVigencia($conexion, $idSolicitud, $_POST['tipo_solicitud'], $fechaAuditoria);
					
					//crear el numero de certificado y guardar en el registro (crear funcion de numero certificado y de actualizar en registro
					$certificado = '';
					
					
					$tipoExplotacion = pg_fetch_result($solicitudBPA, 0, 'tipo_explotacion');
					$identificador = pg_fetch_result($solicitudBPA, 0, 'identificador_operador');
					
					switch($tipoExplotacion){
						case 'SA':
							$area= 'PP';
							break;
						case 'SV':
							$area= 'PA';
							break;
						case 'AI':
							$area= 'PO';
							break;
					}
					
					//verificar la provincia del tecnico y buscar en localizacion el nombre de la provincia y ubicar el numero de zona
					//aumentar a dos digitos con ceros
					$localizacion = pg_fetch_result($cc->obtenerZonaLocalizacion($conexion, $_SESSION['idProvincia'], 1), 0, 'zona');
					$codigoLocalizacion = str_pad($localizacion, 2, "0", STR_PAD_LEFT);
					
					//buscar la combinacion del codigo hasta antes de la provincia y ver el numero para crear un secuencial
					$anio = date("Y");
					$formato = 'AGRO-CBPA-'.$area.'-'.$identificador;//.$anio.'-'
					$secuencial = $ccb->generarNumeroCertificado($conexion, $formato);
					
					//generarNumeroCertificado
					//AGRO-CBPA-PO-2020-05-00001
					//$certificado = $formato . $codigoLocalizacion . '-' . $secuencial;
					$certificado = $formato;
					
					//guardar el código del certificado y el secuencial
					$ccb->actualizarSecuencialCertificado($conexion, $idSolicitud, $secuencial, $certificado);
					
					//Creación de Certificado PDF
					$jru = new ControladorReportes();
					
					//mandar las rutas al mvc para jrxml
					$ReporteJasper= '/aplicaciones/mvc/modulos/CertificacionBPA/vistas/reportes/CertificadoNacional.jrxml';
					$salidaReporte= '/aplicaciones/mvc/modulos/CertificacionBPA/archivos/certificados/bpa_'.$idSolicitud.'.pdf';
					$rutaArchivo= 'aplicaciones/mvc/modulos/CertificacionBPA/archivos/certificados/bpa_'.$idSolicitud.'.pdf';
				
					$firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, $_SESSION['nombreProvincia'], 'AI'));
					
					$parameters['parametrosReporte'] = array(
						'idSolicitud'=>(int)$idSolicitud,
					    'identificador'=>$firmaResponsable['identificador']
					);
					
					$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'CertificacionBPA');
					$ccb->guardarRutaCertificado($conexion, $idSolicitud, $rutaArchivo);
					
					$rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$rutaArchivo;
					
					//Firma Electrónica
					$parametrosFirma = array(
					    'archivo_entrada'=>$rutaArchivo,
					    'archivo_salida'=>$rutaArchivo,
					    'identificador'=>$firmaResponsable['identificador'],
					    'razon_documento'=>'Certificado BPA',
					    'tabla_origen'=>'g_certificacion_bpa.solicitudes',
					    'campo_origen'=>'ruta_certificado',
					    'id_origen'=>$idSolicitud,
					    'estado'=>'Por atender',
					    'proceso_firmado'=>'NO'
					);
					
					//Guardar registro para firma
					$cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
					
				}else if($resultadoDocumento == 'pago'){
				    $ccb->actualizarPagoSolicitud($conexion, $idSolicitud, 'Si');
				}else if($resultadoDocumento == 'Rechazado'){
				    //Cambiar de estado a los sitios de la solicitud
				    $ccb->actualizarEstadoSitiosSolicitud($conexion, $idSolicitud, $resultadoDocumento);
				}
				/*else if($resultadoDocumento == 'subsanacion'){
				    $fechaMaxRespuesta = $ccb->sumaDiaSemana(date("Y-m-d"),14);
				    $ccb->actualizarFechaMaxRespuesta($conexion, $idSolicitud, $fechaMaxRespuesta);
				}*/
			break;
		}
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'La operación se ha guardado satisfactoriamente';
		
		$conexion->desconectar();
		echo json_encode($mensaje);
		
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>
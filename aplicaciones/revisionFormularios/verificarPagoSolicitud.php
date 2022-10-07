<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVUE.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';
require_once '../../clases/ControladorMercanciasSinValorComercial.php';

require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorGestionAplicacionesPerfiles.php';

//Creación de documento de requerimientos por productos para importacion
require_once '../general/crearReporteRequisitos.php';
require_once '../general/administrarArchivoFTP.php';

//Controladores por solicitud
require_once '../../clases/ControladorClv.php';
require_once '../../clases/ControladorImportaciones.php';
require_once '../../clases/ControladorFitosanitario.php';
require_once '../../clases/ControladorCertificacionBPA.php';
require_once '../../clases/ControladorCertificadoCalidad.php';
require_once '../../clases/ControladorDestinacionAduanera.php';
require_once '../../clases/ControladorZoosanitarioExportacion.php';
require_once '../../clases/ControladorFitosanitarioExportacion.php';
require_once '../../clases/ControladorCertificadoFito.php';
require_once '../../clases/ControladorDossierPecuario.php';
require_once '../../clases/ControladorModificacionProductoRia.php';
require_once '../../clases/ControladorReportes.php';
require_once '../../clases/ControladorFirmaDocumentos.php';
require_once '../../clases/ControladorModificacionProductoRia.php';


try{
	$inspector = htmlspecialchars ($_POST['inspector'],ENT_NOQUOTES,'UTF-8');
	$idSolicitud= $_POST['idSolicitud'];
	$idSolicitudGrupo = explode(",",$_POST['idSolicitud']);
	$tipoSolicitud = htmlspecialchars ($_POST['tipoSolicitud'],ENT_NOQUOTES,'UTF-8');
	$tipoInspector = htmlspecialchars ($_POST['tipoInspector'],ENT_NOQUOTES,'UTF-8');
	//$transaccion = htmlspecialchars ($_POST['transaccion'],ENT_NOQUOTES,'UTF-8');
	
	$codigoBanco = htmlspecialchars ($_POST['banco'],ENT_NOQUOTES,'UTF-8');
	$nombreBanco = htmlspecialchars ($_POST['nombreBancoVerificacion'],ENT_NOQUOTES,'UTF-8');
	//$montoRecaudado = htmlspecialchars ($_POST['montoRecaudado'],ENT_NOQUOTES,'UTF-8');
	$montoRecaudado = htmlspecialchars ($_POST['totalPagar'],ENT_NOQUOTES,'UTF-8');
	$numeroFactura = htmlspecialchars ($_POST['numeroFactura'],ENT_NOQUOTES,'UTF-8');
	//$fechaFacturacion = htmlspecialchars ($_POST['fechaFacturacion'],ENT_NOQUOTES,'UTF-8');//REVISAR!!!
	
	$resultado = htmlspecialchars ($_POST['resultado'],ENT_NOQUOTES,'UTF-8');
	$observacion = htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8');
	$idOperador = htmlspecialchars ($_POST['idOperador'],ENT_NOQUOTES,'UTF-8');
	$idVue = htmlspecialchars ($_POST['idVue'],ENT_NOQUOTES,'UTF-8');
	$idGrupo = htmlspecialchars ($_POST['idGrupo'],ENT_NOQUOTES,'UTF-8');
	
	$generarReporteAlmacenes = false;
	$generarReporteEmpresas = false;
	
	try {
		$conexion = new Conexion();
		$crs = new ControladorRevisionSolicitudesVUE();
		$cVUE = new ControladorVUE();
		
		//Obtener monto a pagar
		$qFinanciero = $crs->buscarIdImposicionTasa($conexion, $idGrupo, $tipoSolicitud, $tipoInspector);
		
		//if(pg_fetch_result($qFinanciero, 0, 'monto') == $montoRecaudado){
			
			//Verifica si la operación está asignada a un inspector, caso contrario se asigna a la persona que realiza la inspección
			//$idGrupoAsignado = $crs->buscarInspectorAsignado($conexion, $idSolicitud, $inspector, $tipoSolicitud, $tipoInspector);
			//$inspectorAsignado = $crs->buscarInspectorAsignado($conexion, $idSolicitud, $inspector, $tipoSolicitud, $tipoInspector);
			
			//if(pg_num_rows($idGrupoAsignado)==0){
				//$idGrupoAsignado= $crs->guardarNuevoInspector($conexion, $inspector, $inspector, $tipoSolicitud, $tipoInspector);
				
				//foreach ($idSolicitudGrupo as $solicitud){
					//$crs->guardarGrupo($conexion, $solicitud,pg_fetch_result($idGrupoAsignado, 0, 'id_grupo'), 'Verificación');
				//}
			//}
			
			//Buscar id de asiganción financiero
			//$financiero = $crs->buscarIdImposicionTasa($conexion, $idGrupo, $tipoSolicitud, $tipoInspector);
			
			//Guarda inspector financiero, fecha, resultado, observación y transaccion
			$crs->guardarInspeccionFinanciero($conexion, pg_fetch_result($qFinanciero, 0, 'id_financiero'), $inspector, $resultado, $observacion, $codigoBanco, $montoRecaudado, $nombreBanco,$numeroFactura);
			
			//Guardar resultado solicitud (cambio de estado)
			switch ($tipoSolicitud){
				
				case 'Operadores' :
						$cr = new ControladorRegistroOperador();
						
						$cu = new ControladorUsuarios();
						$cca = new ControladorCatalogos();
						$ca = new ControladorAplicaciones();
						$cgap= new ControladorGestionAplicacionesPerfiles();
						$cfd = new ControladorFirmaDocumentos();
						
						$modulosAgregados="";
						$perfilesAgregados="";
						
						foreach ($idSolicitudGrupo as $solicitud){

							$operacion = pg_fetch_assoc($cr->abrirOperacionXid($conexion, $solicitud));
							$idOperadorTipoOperacion = $operacion['id_operador_tipo_operacion'];
							
							$qOperadorSitio = $cr->obtenerOperadorSitioInspeccion($conexion, $solicitud);
							$operadorSitio = pg_fetch_assoc($qOperadorSitio);
							 
							$qHistorialOperacion = $cr->obtenerMaximoIdentificadorHistoricoOperacion($conexion, $idOperadorTipoOperacion);
							$historialOperacion = pg_fetch_assoc($qHistorialOperacion);
							
							$idflujoOPeracion = pg_fetch_assoc($cr->obtenerIdFlujoXOperacion($conexion, $solicitud));
							$idFlujoActual = pg_fetch_assoc($cr->obtenerEstadoActualFlujoOperacion($conexion, $idflujoOPeracion['id_flujo_operacion'], 'verificacion'));
							$estado = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $idflujoOPeracion['id_flujo_operacion'], $idFlujoActual['predecesor']));
							
							if($operacion['modulo_provee'] == 'moduloExterno' && $estado['estado'] == 'cargarProducto'){
							    $estado = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $idflujoOPeracion['id_flujo_operacion'], $idFlujoActual['predecesor']+1));
							}
							
							$cr->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']);
							
							$qcodigoTipoOperacion= $cca->obtenerCodigoTipoOperacion($conexion, $solicitud);
							$opcionArea=  pg_fetch_result($qcodigoTipoOperacion, 0, 'codigo');
							$idArea=  pg_fetch_result($qcodigoTipoOperacion, 0, 'id_area');

							switch ($estado['estado']){

							    case 'documental':
							        //$cr->enviarOperacion($conexion, $solicitud, $estado['estado']);
							        $cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado']);
                                break;
							        
								case 'inspeccion':
									//$cr->enviarOperacion($conexion, $solicitud, $estado['estado']);									
									$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado']);
								break;
								case 'cargarProducto':
									//$cr->enviarOperacion($conexion, $solicitud, $estado['estado']);
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
										
										$fechaInicio = pg_fetch_result($cr->obtenerMinimoFechaPorIdentificador($conexion, 'Empresas', '0', $idOperador),0,'fecha_aprobacion');
										setlocale(LC_ALL,"es_ES","esp");
										$fechaInicio = ($fechaInicio == '' ? date("Y-m-d"): $fechaInicio);
										$fechaInicio = strftime("%d de %B de %Y", strtotime($fechaInicio));
										
										$ReporteJasper= '/aplicaciones/registroOperador/reportes/registroEmpresa/riaEmpresas.jrxml';
										$salidaReporte= '/aplicaciones/registroOperador/certificados/registroEmpresa/'.$idOperador.'.pdf';
										$rutaArchivo= 'aplicaciones/registroOperador/certificados/registroEmpresa/'.$idOperador.'.pdf';
										$rutaArchivoCodigoQr = 'https://guia.agrocalidad.gob.ec/'.$constg::RUTA_APLICACION.$salidaReporte;
										
										$parameters['parametrosReporte'] = array(
											'identificadorOperador'=> $idOperador,
											'rutaCertificado'=> $rutaArchivoCodigoQr,
											'fechaInicio'=> $fechaInicio
										);
										
										$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'ria');
										
										$existenciaDocumento = $cr->obtenerDocumentoGeneradoPorSecuencialIdentificadorOperadorTipoDocumento($conexion, '1', $idOperador, 'riaEmpresas');
										if(pg_num_rows($existenciaDocumento) == 0){
											$cr->guardarDocumentoOperador($conexion, $solicitud, $idOperadorTipoOperacion, $rutaArchivo, 'riaEmpresas', '1', $idOperador, 'Certificación de registro de empresa.');
										}
										
										//Tabla de firmas físicas
										$firmaResponsable = pg_fetch_assoc($cca->obtenerFirmasResponsablePorProvincia($conexion, $operadorSitio['provincia'], 'RIA'));
										
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
										
										$fechaInicio = pg_fetch_result($cr->obtenerMinimoFechaPorIdentificador($conexion, 'Almacenista', $operadorSitio['id_sitio'], $idOperador),0,'fecha_aprobacion');
										setlocale(LC_ALL,"es_ES","esp");
										$fechaInicio = ($fechaInicio == '' ? date("Y-m-d"): $fechaInicio);
										$fechaInicio = strftime("%d de %B de %Y", strtotime($fechaInicio));
										
										$ReporteJasper= '/aplicaciones/registroOperador/reportes/registroAlmacen/riaAlmacenes.jrxml';
										$salidaReporte= '/aplicaciones/registroOperador/certificados/registroAlmacen/'.$idOperador.'_'.$operadorSitio['id_sitio'].'.pdf';
										$rutaArchivo= 'aplicaciones/registroOperador/certificados/registroAlmacen/'.$idOperador.'_'.$operadorSitio['id_sitio'].'.pdf';
										$rutaArchivoCodigoQr = 'https://guia.agrocalidad.gob.ec/'.$constg::RUTA_APLICACION.$salidaReporte;
										
										$parameters['parametrosReporte'] = array(
											'idSitio'=> (int)$operadorSitio['id_sitio'],
											'rutaCertificado'=> $rutaArchivoCodigoQr,
											'fechaInicio'=> $fechaInicio
										);
										
										$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'ria');
										
										$existenciaDocumento = $cr->obtenerDocumentoGeneradoPorSecuencialIdentificadorOperadorTipoDocumento($conexion, $operadorSitio['id_sitio'], $idOperador, 'riaAlmacenistas');
										if(pg_num_rows($existenciaDocumento) == 0){
											$cr->guardarDocumentoOperador($conexion, $solicitud, $idOperadorTipoOperacion, $rutaArchivo, 'riaAlmacenistas', $operadorSitio['id_sitio'], $idOperador, 'Certificación de registro de almacén de expendio.');
										}
										
										//Tabla de firmas físicas
										$firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, $operadorSitio['provincia'], 'RIA'));
										
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
								case 'registrado':
									$fechaActual = date('Y-m-d H-i-s');
									//$cr -> enviarOperacion($conexion,$solicitud,$estado['estado'], 'No se tiene proceso de inspección '.$fechaActual);
									$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado'], 'No se tiene proceso de inspección '.$fechaActual);
									$cr->actualizarFechaAprobacionOperaciones($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']);
									$cr->cambiarEstadoAreaOperacionPorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']);
									 
									//$cr -> cambiarEstadoAreaXidSolicitud($conexion, $solicitud, $estado['estado'], 'No se tiene proceso de inspección');

									switch ($idArea){
									
										case 'SA':
											switch ($opcionArea){
												case 'MVB':
												case 'MVC':
												case 'MVE':
													$qOperaciones=$cr->buscarOperacionesPorCodigoyAreaOperacion($conexion, $idOperador,"('$opcionArea')","('$idArea')");
														
													if(pg_num_rows($qOperaciones)>0){
														$modulosAgregados.="('PRG_NOTIF_ENF'),";
														$perfilesAgregados.="('PFL_NOTIF_ENF'),";
													}
														
													break;
												case 'FER':
													$qOperaciones=$cr->buscarOperacionesPorCodigoyAreaOperacion($conexion, $idOperador,"('$opcionArea')","('$idArea')");
														
													if(pg_num_rows($qOperaciones)>0){
														$modulosAgregados.="('PRG_MOVIL_PRODU'),";
														$perfilesAgregados.="('PFL_FISCA_MOVIL'),";
													}
														
													break;
											}
											break;
												
										case 'SV':
											$contador=0;
											switch ($opcionArea){
												case 'ACO':
												    $qOperaciones=$cr->buscarOperacionesPorCodigoyAreaOperacionFloresFollajes($conexion, $idOperador,"('$opcionArea')","('$idArea')");
														
													if(pg_num_rows($qOperaciones)>0){
														$modulosAgregados.="('PRG_EMISI_ETIQU'),";
														$perfilesAgregados.="('PFL_SOLIC_ETIQU'),";
													}
														
													$qOperacionesCacao=$cr->buscarOperacionesPorCodigoyAreaOperacionCacao($conexion, $idOperador,"('$opcionArea')","('$idArea')");
														
													if(pg_num_rows($qOperacionesCacao)>0){
														$modulosAgregados.="('PRG_CONFO_LOTE'),";
														$perfilesAgregados.="('PFL_CONFO_LOTE'),";
													}
													
													$qOperacionesPitahaya = $cr->buscarOperacionesPorCodigoyAreaOperacion($conexion, $idOperador,"('$opcionArea')","('$idArea')");
													
													if(pg_num_rows($qOperacionesPitahaya)>0){
													    $modulosAgregados.="('PRG_CONFO_LOTE'),";
													    $perfilesAgregados.="('PFL_CONFO_LOTE'),";
													}
													
												break;
												
												case 'TRA':
												    
												    $qOperacionesCacao=$cr->buscarOperacionesPorCodigoyAreaOperacion($conexion, $idOperador,"('$opcionArea')","('$idArea')");
												    
												    if(pg_num_rows($qOperacionesCacao)>0){
												        $modulosAgregados.="('PRG_CONFO_LOTE'),";
												        $perfilesAgregados.="('PFL_CONFO_LOTE'),";
												    }
												    
												break;
														
												case 'COM':
													$qOperaciones=$cr->buscarOperacionesPorCodigoyAreaOperacion($conexion, $idOperador,"('$opcionArea')","('$idArea')");
														
													if(pg_num_rows($qOperaciones)>0){
														$modulosAgregados.="('PRG_EMISI_ETIQU'),";
														$perfilesAgregados.="('PFL_SOLIC_ETIQU'),";
													}
													break;
									
												/*case 'EXP':
														
													$qOperacionesCacao=$cr->buscarOperacionesPorCodigoyAreaOperacionCacao($conexion, $idOperador,"('$opcionArea')","('$idArea')");
														
													if(pg_num_rows($qOperacionesCacao)>0){
														$contador++;
														$qOperacionesCacao=$cr->buscarOperacionesPorCodigoyAreaOperacionCacao($conexion, $idOperador,"('ACO')","('$idArea')");
									
														if(pg_num_rows($qOperacionesCacao)>0)
															$contador++;
									
														if($contador==2){
															$modulosAgregados.="('PRG_CONFO_LOTE'),";
															$perfilesAgregados.="('PFL_CONFO_LOTE'),";
														}
													}
												break;*/
														
											}
											break;
									}
									
									/////////////////////////////////////////////////////////////
									
								break;								
								case 'cargarRespaldo':
								    $cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado['estado']);
								break;
							}
							$cr->actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $idOperadorTipoOperacion, $estado['estado']);
						}
						
						if(strlen($modulosAgregados)==0){
							$modulosAgregados="''";
							$perfilesAgregados="''";
						}
							
						$qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion,'('.rtrim($modulosAgregados,',').')' );
						if(pg_num_rows($qGrupoAplicacion)>0){
						
							while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
								if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $idOperador))==0){
									$qAplicacionVacunacion=$cgap->guardarGestionAplicacion($conexion, $idOperador,$filaAplicacion['codificacion_aplicacion']);
									$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], '('.rtrim($perfilesAgregados,',').')' );
									while($filaPerfil=pg_fetch_assoc($qGrupoPerfiles)){
										$cgap->guardarGestionPerfil($conexion, $idOperador,$filaPerfil['codificacion_perfil']);
									}
								}else{
									$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], '('.rtrim($perfilesAgregados,',').')' );
									while($filaPerfil=pg_fetch_assoc($qGrupoPerfiles)){
										$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $filaPerfil['id_perfil'], $idOperador);
										if (pg_num_rows($qPerfil) == 0)
											$cgap->guardarGestionPerfil($conexion, $idOperador,$filaPerfil['codificacion_perfil']);
									}
								}
							}
						}
						
				break;
				
			
				case 'Importación' : 
					$ci = new ControladorImportaciones();
			
					$documento = $ci->abrirImportacionesArchivoIndividual($conexion, $idSolicitud, 'PEDIDO DE AMPLIACION');
					$importacion = pg_fetch_assoc($ci->obtenerImportacion($conexion, $idSolicitud));
					
					if(pg_num_rows($documento)!=0  && $importacion['fecha_inicio']!= ''){
						
						//Obtener datos de importación
						$qImportacion = $ci->obtenerImportacion($conexion, $idSolicitud);
						$importacion = pg_fetch_assoc($qImportacion);
						
						//Asigna el resultado de revisión de pago de solicitud de importacion
						$ci->enviarImportacion($conexion, $idSolicitud, 'ampliado');
						
						//Asignar estado a productos de solicitud
						$ci->enviarProductosImportacion($conexion, $idSolicitud, 'ampliado');
						
						//Asignar fecha de vigencia de solicitud
							
						$fechaAmpliacion = date ('Y-m-j', strtotime("+30 days", strtotime( $importacion['fecha_vigencia'] )));
							
						$ci->enviarFechaVigenciaAmpliacion($conexion, $idSolicitud, $fechaAmpliacion);
						
						if($idVue != ''){
							$cVUE->ingresarSolicitudesXatenderGUIA('101-002-REQ','340','21',$idVue, 'Por atender');
						}
						
					}else{
						
						//Asigna el resultado de revisión de pago de solicitud de importacion
						$ci->enviarImportacion($conexion, $idSolicitud, $resultado);
							
						//Asignar estado a productos de solicitud
						$ci->enviarProductosImportacion($conexion, $idSolicitud, $resultado);
							
						//Asignar fecha de vigencia de solicitud
						$ci->enviarFechaVigenciaImportacion($conexion, $idSolicitud ,$importacion['id_area']);
						
						///////////////////////////////////////////////////////////////////////////////////////////
						/*$pdf = new PDF();
						$pdf->AliasNbPages();
						$pdf->AddPage();
						$pdf->Body($idSolicitud, $tipoSolicitud);
						$pdf->SetFont('Times','',12);
						$pdf->Output("../importaciones/archivosRequisitos/".$idOperador."-".$idSolicitud.".pdf");
							
						$informeRequisitos = "aplicaciones/importaciones/archivosRequisitos/".$idOperador."-".$idSolicitud.".pdf";
							
						//Actualizar registro
						$ci->asignarDocumentoRequisitosImportacion($conexion, $idSolicitud, $informeRequisitos);
						
						///////////////////////////////////////////////////////////////////////////////////////////*/
							
						if($idVue != ''){
							///////////////////////////////////////////////////////////////////////////////////////////
							//$cFTP = new administrarArchivoFTP();
							//$cFTP->enviarArchivo($informeRequisitos, $idOperador.'-'.$idSolicitud.'.pdf', 'importacion');
							///////////////////////////////////////////////////////////////////////////////////////////
							$cVUE->ingresarSolicitudesXatenderGUIA('101-002-REQ','320','21',$idVue, 'Por atender');
						}
						
					}
						
				break;
				
			
				case 'DDA' :
					$cd = new ControladorDestinacionAduanera();
					$cd->enviarDDA($conexion, $idSolicitud, $resultado);
				break;
				
			
				case 'Fitosanitario' :
					$cf = new ControladorFitosanitario();				
					$cf->enviarFito($conexion, $idSolicitud, $resultado);
					$cf->evaluarProductosFito($conexion, $idSolicitud, $resultado);
					$cf->enviarFechaVigenciaFito($conexion, $idSolicitud);
					
					if($idVue != ''){
						$cVUE->ingresarSolicitudesXatenderGUIA('101-031-REQ','320','21',$idVue, 'Por atender');
					}
					
				break;
				
			
				case 'Zoosanitario' :
					$cz = new ControladorZoosanitarioExportacion();
					$cz->enviarZoo($conexion, $idSolicitud, $resultado);
				break;
				
			
				case 'Muestras' :
					/*$ci = new ControladorImportaciones();
					 $ci->enviarImportacion($conexion, $idSolicitud, $resultadoDocumento);*/
				break;
				
			
				case 'CLV' :
					$cl = new ControladorClv();
					$cl->enviarClv($conexion, $idSolicitud, $resultado);
					$cl->evaluarProductosCLV($conexion, $idSolicitud, $resultado);
					$cl->enviarFechaVigenciaCLV($conexion, $idSolicitud);
					if($idVue != ''){
						$cVUE->ingresarSolicitudesXatenderGUIA('101-047-REQ','320','21',$idVue, 'Por atender');
					}
				break;
				
				case 'certificadoCalidad' :
					$cc = new ControladorCertificadoCalidad();
					foreach ($idSolicitudGrupo as $solicitud){
						$cc->actualizarEstadoLote($conexion, $solicitud, $resultado);
					}
						
				break;
				
				case 'FitosanitarioExportacion':
				
					$cfe = new ControladorFitosanitarioExportacion();
					
					$cfe->actualizarEstadoFitosanitarioExportacion($conexion, $idSolicitud, $resultado, 'verificacion', 'Aprobado');
					$cfe->actualizarFechasAprobacionFitosanitarioExportacion($conexion, $idSolicitud);
					
					if($idVue != ''){
						$cVUE->ingresarSolicitudesXatenderGUIA('101-034-REQ','320','21',$idVue, 'Por atender');
					}
					
				break;
				
				case 'mercanciasSinValorComercialImportacion':
				case 'mercanciasSinValorComercialExportacion':
				
				$cme = new ControladorMercanciasSinValorComercial();
								
				$cme->actualizarEstadoMercanciaSV($conexion, 'aprobado', $idSolicitud);

				break;
				
				case 'certificacionBPA':
					
					$ccb = new ControladorCertificacionBPA();
					
					$ccb->actualizarEstadoSolicitud($conexion, $idSolicitud, 'inspeccion');

				break;
				
				case 'certificadoFito':
					
					$ccf = new ControladorCertificadoFito();
					
					$solicitud = pg_fetch_assoc($ccf->abrirSolicitud($conexion, $idSolicitud));
					
					$ccf->actualizarEstadoCertificado($conexion, 'Aprobado', $idSolicitud, $_SESSION['usuario']);
					$ccf->actualizarEstadoExportadoresProductos($conexion, 'Aprobado', $idSolicitud);
					$ccf->actualizarFechaAprobacionCertificado($conexion, 'now()', $idSolicitud);
					
				break;
				
				case 'dossierPecuario':
				    
				    $cdpmvc = new ControladorDossierPecuario();
				    
				    $cdpmvc->actualizarEstadoSolicitud($conexion, 'Recibido', $idSolicitud, $_SESSION['usuario'], $_SESSION['idProvincia'], 'Financiero remitió la solicitud a Registros');
				    																													  
				    $cdpmvc->ingresarHistoricoEstados($conexion, 'Recibido', $idSolicitud, $_SESSION['usuario'], 'Financiero remitió la solicitud a Registros');
																				      
				break;
				
				case 'modificacionProductoRia':
				    
				    $cmp = new ControladorModificacionProductoRia();
				    
				    $cmp->actualizarEstadoSolicitudPorIdSolicitudProducto($conexion, $idSolicitud, 'inspeccion');
				    
				    break;
					
				default :
					break;
			}
	
			//Revisar fechas de vigencia, actual 90 dias revisar y crear proceso parasolicitudde ampliacion que
			//despues de aprobado se extiende 90 dias desde la fecha de solicitud de ampliacion
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'La operación se ha guardado satisfactoriamente.';
			
	/*}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'El monto recaudado no corresponde a la tasa asignada.';
		}*/
		
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
<?php

if($_SERVER['REMOTE_ADDR'] == ''){
//if(1){
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorMail.php';
	require_once '../../clases/ControladorReportes.php';
	require_once '../../clases/ControladorMonitoreo.php';
	require_once '../../clases/ControladorFinanciero.php';
	require_once '../../clases/ControladorCertificados.php';
	require_once '../../clases/ControladorImportaciones.php';
	require_once '../../clases/ControladorFitosanitario.php';
	require_once '../../clases/ControladorFitosanitarioExportacion.php';
	
	define('IN_MSG','<br/> >>> ');

	?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

	<?php

	$conexion = new Conexion();
	$cc = new ControladorCertificados();
	$cf = new ControladorFinanciero();
	$cm = new ControladorMonitoreo();
	$jru = new ControladorReportes();
	$cMail = new ControladorMail();

	$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_FACT_ENV_RECEPTOR');

	if($resultadoMonitoreo){
	//if(1){

			$solicitudesPendientes = $cc->cargarDocumentosPoratenderEnvioSRI($conexion, "'RECIBIDA','POR ATENDER'");

			echo '<p> <strong>INICIO FACTURCIÓN ELECTRONICA ENVIO RECEPTOR' . $solicitudPendiente['clave_acceso'] . '</strong></br>';

			while ($solicitudPendiente = pg_fetch_assoc($solicitudesPendientes)){
					
				echo '<p> <strong>CLAVE DE ACCESO ' . $solicitudPendiente['clave_acceso'] . '</strong>'. IN_MSG . 'Tipo comprobante '. $solicitudPendiente['tipo'];
					
				$nombreArchivoXML = $solicitudPendiente['clave_acceso'].'.xml';
				$nombreArchivoPDF = $solicitudPendiente['clave_acceso'].'.pdf';
					
				switch ($solicitudPendiente['estado_sri']){

					case 'RECEPTOR':
							
						echo IN_MSG . 'Solicitando para envío receptor al SRI';
							
						$cc->cambiarEstadoComprobantesElectronicos($conexion, $solicitudPendiente['tipo'], 'R', $solicitudPendiente['id_comprobante']);
						
						$rutaFecha = date('Y/m/d', strtotime($solicitudPendiente['fecha_facturacion']));		
						
						if (!file_exists('archivoXml/autorizados/'.$rutaFecha.'/')){
						    mkdir('archivoXml/autorizados/'.$rutaFecha.'/', 0777,true);
						}
						
						$estadoAutorizacionXML = 'AUTORIZADO';
						$numeroAutorizacion = $solicitudPendiente['clave_acceso'];
						$fechaAutorizacion = date('c',strtotime($solicitudPendiente['fecha_facturacion']));
						$comprobante = file_get_contents('../../aplicaciones/financiero/archivoXml/firmados/'.$rutaFecha.'/'.$nombreArchivoXML);						
						$rutaArchivoFirmado = $constg::RUTA_SERVIDOR_OPT."/".$constg::RUTA_APLICACION."/aplicaciones/financiero/archivoXml/autorizados/".$rutaFecha."/".$nombreArchivoXML;

						//----------------------------------------------------------------GENERAR XML--------------------------------------------------------------------

						//Generar archivo xml
						$xml = new DomDocument('1.0', 'UTF-8');

						//Nodo principal
						$root = $xml->createElement('autorizacion');
						$root = $xml->appendChild($root);

						$estado=$xml->createElement('estado',$estadoAutorizacionXML);
						$estado =$root->appendChild($estado);

						$numeroAutorizacionMailSRI=$xml->createElement('numeroAutorizacion',$numeroAutorizacion);
						$numeroAutorizacionMailSRI =$root->appendChild($numeroAutorizacionMailSRI);

						$fechaAutorizacionMailSRI=$xml->createElement('fechaAutorizacion',$fechaAutorizacion);
						$fechaAutorizacionMailSRI =$root->appendChild($fechaAutorizacionMailSRI);

						$cdata = $xml->createCDATASection($comprobante);

						$comprobanteMailSRI=$xml->createElement('comprobante');
						$comprobanteMailSRI =$root->appendChild($comprobanteMailSRI);

						$comprobanteMailSRI->appendChild($cdata);

						$xml->formatOutput = true;  //poner los string en la variable $strings_xml:
						$strings_xml = $xml->saveXML();

						$xml->save($rutaArchivoFirmado);

						//----------------------------------------------------------------FIN XML--------------------------------------------------------------------
							
						switch ($solicitudPendiente['tipo']){
							case 'factura':									

									echo IN_MSG . 'Comprobante autorizado.';

									$rutaArchivoAutorizado = $constg::RUTA_SERVIDOR_OPT."/".$constg::RUTA_APLICACION."/aplicaciones/financiero/archivoXml/autorizados/".$rutaFecha."/".$nombreArchivoXML;
									//copy($rutaArchivoFirmado, $rutaArchivoAutorizado);
									
									if (!file_exists('documentos/facturas/'.$rutaFecha.'/')){
									    mkdir('documentos/facturas/'.$rutaFecha.'/', 0777,true);
									}

									//--------------------------------------RUTAS DE REPORTE FACTURA ---------------------------------------------------
									$ReporteJasper='aplicaciones/financiero/reportes/facturaOffline.jrxml';
									$salidaReporte='aplicaciones/financiero/documentos/facturas/'.$rutaFecha.'/'.$nombreArchivoPDF;

									echo IN_MSG . 'Actualización de datos de autorización.';

									$cc->actualizarDatosAutorizacionSRIFactura($conexion, $solicitudPendiente['id_comprobante'], 'POR ATENDER', $numeroAutorizacion, $fechaAutorizacion, $rutaArchivoAutorizado, $salidaReporte);

									//--------------------------------------DATOS NUEVOS REPORTE FACTURA ---------------------------------------------------

									$ordenPago = pg_fetch_assoc($cc->abrirOrdenPago($conexion, $solicitudPendiente['id_comprobante']));

									switch ($ordenPago['tipo_solicitud']){
										case 'Importación':
											$ci = new ControladorImportaciones();
											$importacion = pg_fetch_assoc($ci->obtenerImportacion($conexion, $ordenPago['id_solicitud']));
											$solicitudAtendida = $importacion['id_vue'];
											break;
										case 'Fitosanitario':
											$cfi = new ControladorFitosanitario();
											$fitosanitario = pg_fetch_assoc($cfi->listarFitoExportacion($conexion, $ordenPago['id_solicitud']));
											$solicitudAtendida = $fitosanitario['id_vue'];
											break;
										case 'FitosanitarioExportacion':
											$cfe = new ControladorFitosanitarioExportacion();
											$fitosanotarioExportacion = pg_fetch_assoc($cfe->obtenerCabeceraFitosanitarioExportacion($conexion, $ordenPago['id_solicitud']));
											$solicitudAtendida = $fitosanotarioExportacion['id_vue'];
											break;
										case 'Operadores':
											$solicitudAtendida = (strlen($ordenPago['id_solicitud'])>36?(substr($ordenPago['id_solicitud'],0,36).'...'):$ordenPago['id_solicitud']);
											break;
										case 'Otros':
											$solicitudAtendida = $ordenPago['numero_solicitud'];
											break;
									}

									$observacion = (strlen($ordenPago['observacion'])>100?(substr($ordenPago['observacion'],0,96).'...'):($ordenPago['observacion']!=''?$ordenPago['observacion']:'Sin observación.'));

									$formaPago = $cc->abrirLiquidarOrdenPago($conexion, $solicitudPendiente['id_comprobante']);

									$datosDeposito = '';

									while ($fila = pg_fetch_assoc($formaPago) ){
										switch ($fila['transaccion']){
											case 'Efectivo':
												$datosDeposito .= 'Efectivo, ';
												break;
											case 'Valor nota credito':
												$numeroNotaCredito = pg_fetch_assoc($cc->abrirNotaCredito($conexion, $fila['id_nota_credito']));
												$datosDeposito .= 'Nota de credito: '.$numeroNotaCredito['numero_establecimiento'].'-'.$numeroNotaCredito['punto_emision'].'-'.$numeroNotaCredito['numero_nota_credito'].', ';
												break;
											case 'Saldo disponible':
												$datosDeposito .= 'Saldo, ';
												break;
													
											default:
												$datosDeposito .= 'Deposito: '.$fila['transaccion'].', ';
												break;
										}
									}

									$datosDeposito = rtrim($datosDeposito,', ');
									$datosDeposito = (strlen($datosDeposito)>80?(substr($datosDeposito,0,76).'...'):$datosDeposito);


									//--------------------------------------DATOS NUEVOS REPORTE FACTURA ---------------------------------------------------

									$ivaSRI = $ordenPago['porcentaje_iva'].'%';

									$parameters['parametrosReporte'] = array(
										'idpago' => (int)$solicitudPendiente['id_comprobante'],
										'datosDeposito' => $datosDeposito,
										'solicitudAtendida' => $solicitudAtendida,
										'observacion' => $observacion,
										'compensacion' => $compensacion,
										'ivaSri' => $ivaSRI
									);

									//FIN EJAR

									echo IN_MSG . 'Generación de RIDE.';

									$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'facturacion');


									$asunto = 'Facturación electronica AGROCALIDAD esquema OFFLINE';
									$cuerpoMensaje = 'Estimado Cliente: <br/><br/>AGROCALIDAD informa que en base al cumplimiento con la Resolución No.NAC-DGERCGC12-00105 emitida por el SRI, adjunto a este correo se encuentra su FACTURA electrónico(a) en formato XML, así como su interpretación en formato RIDE.' ;

									$destinatario = array();
									//$correos = explode(';', $solicitudPendiente['correo_electronico']);

									//foreach ($correos as $correo){
									//	array_push($destinatario, $correo);
									//}
									array_push($destinatario, $solicitudPendiente['correo_electronico']);


									$adjuntos = array();
									array_push($adjuntos, $rutaArchivoFirmado, $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/'.$salidaReporte);

									echo IN_MSG . 'Insertar registro de envío de correo electronico.';

									$codigoModulo = 'PRG_FINANCIERO';
									$tablaModulo = 'g_financiero.orden_pago';

									$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo, $solicitudPendiente['id_comprobante']);
									$idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');

									$cMail->guardarDestinatario($conexion, $idCorreo, $destinatario);

									$cMail->guardarDocumentoAdjunto($conexion, $idCorreo, $adjuntos);

									
								break;
									
							case 'notaCredito':
									
									echo IN_MSG . 'Comprobante autorizado.';

									$rutaArchivoAutorizado = $constg::RUTA_SERVIDOR_OPT."/".$constg::RUTA_APLICACION."/aplicaciones/financiero/archivoXml/autorizados/".$rutaFecha."/".$nombreArchivoXML;
									//copy($rutaArchivoFirmado, $rutaArchivoAutorizado);
									
									if (!file_exists('documentos/notaCredito/'.$rutaFecha.'/')){
									    mkdir('documentos/notaCredito/'.$rutaFecha.'/', 0777,true);
									}

									//--------------------------------------RUTAS DE REPORTE FACTURA ---------------------------------------------------
									$ReporteJasper='aplicaciones/financiero/reportes/notaCreditoOffline.jrxml';
									$salidaReporte='aplicaciones/financiero/documentos/notaCredito/'.$rutaFecha.'/'.$nombreArchivoPDF;

									$valoresNotaCredito = $cc -> obtenerDatosNotaCredito($conexion,$solicitudPendiente['id_comprobante']);
									$notaCreditoValores =  pg_fetch_assoc($valoresNotaCredito);

									$ivaSRI = $notaCreditoValores['porcentaje_nota_iva'].'%';

									echo IN_MSG . 'Actualziación de datos de autorización.';

									$cc->actualizarDatosAutorizacionSRINotaCredito($conexion,  $solicitudPendiente['id_comprobante'], 'POR ATENDER', $numeroAutorizacion, $fechaAutorizacion, $rutaArchivoAutorizado, $salidaReporte);

									$parameters['parametrosReporte'] = array(
										'idnotaCredito' => (int)$solicitudPendiente['id_comprobante'],
										'ivaSri' => $ivaSRI
									);

									echo IN_MSG . 'Generación de RIDE.';

									$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'facturacion');


									$asunto = 'Facturación electronica AGROCALIDAD esquema OFFLINE';
									$cuerpoMensaje = 'Estimado Cliente: <br/><br/>AGROCALIDAD informa que en base al cumplimiento con la Resolución No.NAC-DGERCGC12-00105 emitida por el SRI, adjunto a este correo se encuentra su FACTURA electrónico(a) en formato XML, así como su interpretación en formato RIDE.' ;
									$destinatario = array();
									array_push($destinatario, $solicitudPendiente['correo_electronico']);
									$adjuntos = array();
									array_push($adjuntos, $rutaArchivoFirmado, $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/'.$salidaReporte);

									echo IN_MSG . 'Envio correo electronico.';

									//$estadoMail = $cMail->enviarMail($destinatario, $asunto, $cuerpoMensaje, $adjuntos);
									//echo IN_MSG . 'Actualización estado correo electronico.';
									//$cc->cambiarEstadoMailComprobantesElectronicos($conexion, $solicitudPendiente['tipo'], $estadoMail, $solicitudPendiente['id_comprobante']);

									$codigoModulo = 'PRG_FINANCIERO';
									$tablaModulo = 'g_financiero.nota_credito';

									$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo, $solicitudPendiente['id_comprobante']);
									$idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');

									$cMail->guardarDestinatario($conexion, $idCorreo, $destinatario);

									$cMail->guardarDocumentoAdjunto($conexion, $idCorreo, $adjuntos);
									
								break;
									
							default:
								echo 'Tipo formulario desconocido.';
						}
							
						echo IN_MSG . 'Fin de solicitud de envío del comprobante al receptor.';
							
						break;
							
					default:
						echo 'Estado desconocido.';
							
				}
				echo '<br/><strong>FIN</strong></p>';
					
			}
	}
}else{

		$minutoS1=microtime(true);
		$minutoS2=microtime(true);
		$tiempo=$minutoS2-minutoS1;
		$xcadenota = "FECHA ".date("d/m/Y")." ".date("H:i:s");
		$xcadenota.= "; IP REMOTA ".$_SERVER['REMOTE_ADDR'];
		$xcadenota.= "; SERVIDOR HTTP ".$_SERVER['HTTP_REFERER'];
		$xcadenota.= "; SEGUNDOS ".$tiempo."\n";
		$arch = fopen("../../aplicaciones/logs/cron/receptor_facturacion_electronica_".date("d-m-Y").".txt", "a+");
		fwrite($arch, $xcadenota);
		fclose($arch);

	}

	?>

</body>

</html>

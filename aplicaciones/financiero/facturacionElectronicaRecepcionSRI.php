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

	set_time_limit(150);
	ini_set('default_socket_timeout',150);

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

	$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_FACT_AUTORI_SRI');

	if($resultadoMonitoreo){
	//if(1){

		$fechaVigente = pg_fetch_assoc($cf->obtenerFechasContigenciaVigentes($conexion));
		$fechaActualSistema = date('Y-m-d H:i:s');
			
		$fechaContingenciaDesde = date('Y-m-d H:i:s', strtotime($fechaVigente['fecha_desde']));
		$fechaContingenciaHasta = date('Y-m-d H:i:s', strtotime($fechaVigente['fecha_hasta']));
			
			
		if($fechaActualSistema >= $fechaContingenciaDesde && $fechaActualSistema <= $fechaContingenciaHasta ){

			echo '<p> <strong>SISTEMA DEL SRI EN MANTENIMIENTO, CLAVES DE CONTINGENCIA ACTIVADAS</strong></p>';

		}else{
			$solicitudesPendientes = $cc->cargarDocumentosPoratenderEnvioSRI($conexion, "'POR ATENDER','RECEPTOR'");

			echo '<p> <strong>INICIO FACTURCIÓN ELECTRONICA ' . $solicitudPendiente['clave_acceso'] . '</strong></br>';

			while ($solicitudPendiente = pg_fetch_assoc($solicitudesPendientes)){
					
				echo '<p> <strong>CLAVE DE ACCESO ' . $solicitudPendiente['clave_acceso'] . '</strong>'. IN_MSG . 'Tipo comprobante '. $solicitudPendiente['tipo'];
				
				$rutaFecha = date('Y/m/d', strtotime($solicitudPendiente['fecha_facturacion']));				
				
				$nombreArchivoXML = $solicitudPendiente['clave_acceso'].'.xml';
				$nombreArchivoPDF = $solicitudPendiente['clave_acceso'].'.pdf';
					
				switch ($solicitudPendiente['estado_sri']){

					case 'RECIBIDA':
							
						echo IN_MSG . 'Solicitando autorización al SRI';
						
						if (!file_exists('archivoXml/autorizadosSRI/'.$rutaFecha.'/')){
						    mkdir('archivoXml/autorizadosSRI/'.$rutaFecha.'/', 0777,true);
						}
						
						$cc->cambiarEstadoComprobantesElectronicos($conexion, $solicitudPendiente['tipo'], 'WA', $solicitudPendiente['id_comprobante']);
							
						//$respuestaAutorizacion = $cc->obtenerAutorizacionSRI($solicitudPendiente['clave_acceso']);
						$respuestaAutorizacion = $cc->obtenerAutorizacionSRIOffline($solicitudPendiente['clave_acceso']);
							
						$estadoAutorizacionXML = $respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado;
						$numeroAutorizacion = $respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
						$fechaAutorizacion = $respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
						$comprobante = $respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante;
							
						$rutaArchivoFirmado = $constg::RUTA_SERVIDOR_OPT."/".$constg::RUTA_APLICACION."/aplicaciones/financiero/archivoXml/autorizadosSRI/".$rutaFecha."/".$nombreArchivoXML;

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
									
								if($estadoAutorizacionXML == 'AUTORIZADO'){

									echo IN_MSG . 'Comprobante autorizado.';

									$rutaArchivoAutorizado = $constg::RUTA_SERVIDOR_OPT."/".$constg::RUTA_APLICACION."/aplicaciones/financiero/archivoXml/autorizadosSRI/".$rutaFecha."/".$nombreArchivoXML;

									//--------------------------------------RUTAS DE REPORTE FACTURA ---------------------------------------------------
									$salidaReporte='aplicaciones/financiero/documentos/facturas/'.$rutaFecha.'/'.$nombreArchivoPDF;

									echo IN_MSG . 'Actualización de datos de autorización.';

									$cc->actualizarDatosAutorizacionSRIFactura($conexion, $solicitudPendiente['id_comprobante'], $estadoAutorizacionXML, $numeroAutorizacion, $fechaAutorizacion, $rutaArchivoAutorizado, $salidaReporte);

								}else if ($estadoAutorizacionXML == 'NO AUTORIZADO'){

									echo IN_MSG . 'Comprobante no autorizado.';

									if (!file_exists('archivoXml/rechazadosSRI/'.$rutaFecha.'/')){
									    mkdir('archivoXml/rechazadosSRI/'.$rutaFecha.'/', 0777,true);
									}

									$rutaArchivoNoAutorizado = $constg::RUTA_SERVIDOR_OPT."/".$constg::RUTA_APLICACION."/aplicaciones/financiero/archivoXml/rechazadosSRI/".$rutaFecha."/".$nombreArchivoXML;
									copy($rutaArchivoFirmado, $rutaArchivoNoAutorizado);

									echo IN_MSG . 'Actualización de datos del comprobante.';

									$observacionSRI = json_encode($respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->mensajes);
									$observacionSRI = str_replace("'", "''", $observacionSRI);
									$cc->actualizarObservacionSRIFactura($conexion,$solicitudPendiente['id_comprobante'],$observacionSRI, $rutaArchivoNoAutorizado);
									$cc->cambiarEstadoComprobantesElectronicos($conexion, $solicitudPendiente['tipo'], $estadoAutorizacionXML, $solicitudPendiente['id_comprobante']);
									
									$asunto = 'Factura no autorizada.';
									$cuerpoMensaje = 'Revisar factura que ha sido no autorizada en el SRI. Orden de pago #'.$solicitudPendiente['id_comprobante'].' Clave de acceso '.$numeroAutorizacion ;
									$destinatario = array();
									array_push($destinatario, 'jakeddy1907@hotmail.com');
									array_push($destinatario, 'edison.ayala@agrocalidad.gob.ec');
									array_push($destinatario, 'milton.nivelo@agrocalidad.gob.ec');
									
									$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', null, null, null);
									$idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
									
									$cMail->guardarDestinatario($conexion, $idCorreo, $destinatario);
									
								}else{

									echo IN_MSG . 'Error al solicitar autorización.';

									$observacionSRI = json_encode($respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->mensajes);
									$observacionSRI = str_replace("'", "''", $observacionSRI);
									$cc->actualizarObservacionSRIFactura($conexion,$solicitudPendiente['id_comprobante'],$observacionSRI, $rutaArchivoNoAutorizado);
								}
									
								break;
									
							case 'notaCredito':
									
								if($estadoAutorizacionXML == 'AUTORIZADO'){

									echo IN_MSG . 'Comprobante autorizado.';

									$rutaArchivoAutorizado = $constg::RUTA_SERVIDOR_OPT."/".$constg::RUTA_APLICACION."/aplicaciones/financiero/archivoXml/autorizadosSRI/".$rutaFecha."/".$nombreArchivoXML;
									//copy($rutaArchivoFirmado, $rutaArchivoAutorizado);

									//--------------------------------------RUTAS DE REPORTE FACTURA ---------------------------------------------------
									$salidaReporte='aplicaciones/financiero/documentos/notaCredito/'.$rutaFecha.'/'.$nombreArchivoPDF;

									echo IN_MSG . 'Actualziación de datos de autorización.';

									$cc->actualizarDatosAutorizacionSRINotaCredito($conexion,  $solicitudPendiente['id_comprobante'], $estadoAutorizacionXML, $numeroAutorizacion, $fechaAutorizacion, $rutaArchivoAutorizado, $salidaReporte);

								}else if ($estadoAutorizacionXML == 'NO AUTORIZADO'){

									echo IN_MSG . 'Comprobante no autorizado.';

									if (!file_exists('archivoXml/rechazadosSRI/'.$rutaFecha.'/')){
									    mkdir('archivoXml/rechazadosSRI/'.$rutaFecha.'/', 0777,true);
									}

									$rutaArchivoNoAutorizado = $constg::RUTA_SERVIDOR_OPT."/".$constg::RUTA_APLICACION."/aplicaciones/financiero/archivoXml/rechazadosSRI/".$rutaFecha."/".$nombreArchivoXML;
									copy($rutaArchivoFirmado, $rutaArchivoNoAutorizado);

									echo IN_MSG . 'Actualización de datos del comprobante.';

									$observacionSRI = json_encode($respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->mensajes);
									$observacionSRI = str_replace("'", "''", $observacionSRI);
									$cc->actualizarObservacionSRIFactura($conexion,$solicitudPendiente['id_comprobante'],$observacionSRI, $rutaArchivoNoAutorizado);
									$cc->cambiarEstadoComprobantesElectronicos($conexion, $solicitudPendiente['tipo'], $estadoAutorizacionXML, $solicitudPendiente['id_comprobante']);
									
									$asunto = 'Nota de crédito no autorizada.';
									$cuerpoMensaje = 'Revisar nota de crédito que ha sido no autorizada en el SRI. Nota derédito #'.$solicitudPendiente['id_comprobante'].' Clave de acceso: '.$numeroAutorizacion ;
									$destinatario = array();
									array_push($destinatario, 'jakeddy1907@hotmail.com');
									array_push($destinatario, 'edison.ayala@agrocalidad.gob.ec');
									array_push($destinatario, 'milton.nivelo@agrocalidad.gob.ec');
									
									$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', null, null, null);
									$idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
									
									$cMail->guardarDestinatario($conexion, $idCorreo, $destinatario);

								}else{

									echo IN_MSG . 'Error al solicitar autorización.';

									$observacionSRI = json_encode($respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->mensajes);
									$observacionSRI = str_replace("'", "''", $observacionSRI);
									$cc->actualizarObservacionSRIFactura($conexion,$solicitudPendiente['id_comprobante'],$observacionSRI, $rutaArchivoNoAutorizado);
								}
									
								break;
									
							default:
								echo 'Tipo formulario desconocido.';
						}
							
						echo IN_MSG . 'Fin de solicitud de autorización al SRI';
							
						break;
							
					default:
						echo 'Estado desconocido.';
							
				}
				echo '<br/><strong>FIN</strong></p>';
					
			}
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
		$arch = fopen("../../aplicaciones/logs/cron/autoriza_facturacion_electronica_".date("d-m-Y").".txt", "a+");
		fwrite($arch, $xcadenota);
		fclose($arch);

	}

	?>



</body>

</html>

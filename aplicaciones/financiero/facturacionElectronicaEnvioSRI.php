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
	$jru = new ControladorReportes();
	$cMail = new ControladorMail();
	$cm = new ControladorMonitoreo();

	$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_FACT_ENVIO_SRI');

	if($resultadoMonitoreo){
		//if(1){
		$fechaVigente = pg_fetch_assoc($cf->obtenerFechasContigenciaVigentes($conexion));
		$fechaActualSistema = date('Y-m-d H:i:s');
			
		$fechaContingenciaDesde = date('Y-m-d H:i:s', strtotime($fechaVigente['fecha_desde']));
		$fechaContingenciaHasta = date('Y-m-d H:i:s', strtotime($fechaVigente['fecha_hasta']));
			
			
		if($fechaActualSistema >= $fechaContingenciaDesde && $fechaActualSistema <= $fechaContingenciaHasta ){

			echo '<p> <strong>SISTEMA DEL SRI EN MANTENIMIENTO, CLAVES DE CONTINGENCIA ACTIVADAS</strong></p>';

		}else{
			$solicitudesPendientes = $cc->cargarDocumentosPoratenderEnvioSRI($conexion,"'RECIBIDA','RECEPTOR'");

			echo '<p> <strong>INICIO FACTURCIÓN ELECTRONICA ' . $solicitudPendiente['clave_acceso'] . '</strong></br>';

			while ($solicitudPendiente = pg_fetch_assoc($solicitudesPendientes)){
					
				echo '<p> <strong>CLAVE DE ACCESO ' . $solicitudPendiente['clave_acceso'] . '</strong>'. IN_MSG . 'Tipo comprobante '. $solicitudPendiente['tipo'];
					
				$nombreArchivoXML = $solicitudPendiente['clave_acceso'].'.xml';
				$nombreArchivoPDF = $solicitudPendiente['clave_acceso'].'.pdf';
				
				$rutaFecha = date('Y/m/d', strtotime($solicitudPendiente['fecha_facturacion']));
				
				switch ($solicitudPendiente['estado_sri']){

					case 'POR ATENDER':
							
						$cc->cambiarEstadoComprobantesElectronicos($conexion, $solicitudPendiente['tipo'], 'W', $solicitudPendiente['id_comprobante']);
							
						echo IN_MSG . 'Envio de comprobante al SRI.';
						//$respuestaRecepcion = $cc->enviarXMLSRI($constg::RUTA_SERVIDOR_OPT, $constg::RUTA_APLICACION, $nombreArchivoXML);
						$respuestaRecepcion = $cc->enviarXMLSRIOffline($constg::RUTA_SERVIDOR_OPT, $constg::RUTA_APLICACION, $rutaFecha.'/'.$nombreArchivoXML);
							
						$estadoComprobante = $respuestaRecepcion->RespuestaRecepcionComprobante->estado;
						$observacionSRI = json_encode($respuestaRecepcion->RespuestaRecepcionComprobante->comprobantes->comprobante);
						$observacionSRI = str_replace("'", "''", $observacionSRI);
							
						switch ($solicitudPendiente['tipo']){
							case 'factura':
									
								if($estadoComprobante != ''){
									echo IN_MSG . 'Actualización de estado del comprobante';
									$cc->actualizarObservacionSRIFactura($conexion, $solicitudPendiente['id_comprobante'], $observacionSRI);
									$cc->cambiarEstadoComprobantesElectronicos($conexion, $solicitudPendiente['tipo'], $estadoComprobante, $solicitudPendiente['id_comprobante']);
								}else{
									echo IN_MSG . 'Problemas al enviar el comprobante';
									$cc->actualizarObservacionSRIFactura($conexion, $solicitudPendiente['id_comprobante'],$observacionSRI);
								}
									
								break;
									
							case 'notaCredito':
									
								if($estadoComprobante != ''){
									echo IN_MSG . 'Actualización de estado del comprobante';
									$cc->actualizarObservacionSRINotaCredito($conexion, $solicitudPendiente['id_comprobante'], $observacionSRI);
									$cc->cambiarEstadoComprobantesElectronicos($conexion, $solicitudPendiente['tipo'], $estadoComprobante, $solicitudPendiente['id_comprobante']);
								}else{
									echo IN_MSG . 'Problemas al enviar el comprobante';
									$cc->actualizarObservacionSRIFactura($conexion, $solicitudPendiente['id_comprobante'],$observacionSRI);
								}
									
								break;
									
							default:
								echo 'Tipo formulario desconocido.';
						}
							
						echo IN_MSG . 'Fin de envio comprobante al SRI';
							
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
		$arch = fopen("../../aplicaciones/logs/cron/envio_facturacion_electronica_".date("d-m-Y").".txt", "a+");
		fwrite($arch, $xcadenota);
		fclose($arch);

	}

	?>



</body>

</html>

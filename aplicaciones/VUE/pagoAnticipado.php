<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Sistema GUIA</title>
</head>
<body>
	<h1>Solicitudes pendientes por enviar a VUE</h1>
	
<?php
	
	//if($_SERVER['REMOTE_ADDR'] == ''){
		
		require_once '../../clases/Conexion.php';
		require_once '../../clases/ControladorVUE.php';
		require_once '../../clases/ControladorFinanciero.php';
		require_once '../../clases/ControladorReportes.php';
		require_once '../../clases/ControladorFinancieroAutomatico.php';
		require_once '../../clases/ControladorRevisionSolicitudesVUE.php';
		
		$conexion = new Conexion();
		$cf = new ControladorFinanciero();
		$cc = new ControladorCertificados();
		$controladorVUE = new ControladorVUE();
		$cfa = new ControladorFinancieroAutomatico();
		$crs = new ControladorRevisionSolicitudesVUE();	
		
		$solicitudesPendientes = $controladorVUE->cargarSolicitudesPorAtenderPagoAnticipado();
		
		$rutaFecha = date('Y').'/'.date('m').'/'.date('d');
						
		while ($solicitudPendiente = pg_fetch_assoc($solicitudesPendientes)){
			
			$controladorVUE->finalizarPagoAnticipado($solicitudPendiente['id_pago_anticipado'],'W');
			
			$qDatosCliente = $cc->listaComprador($conexion,$solicitudPendiente['identificador']);
			
			if(pg_num_rows($qDatosCliente)!= 0){
				
				$datosCliente = pg_fetch_assoc($qDatosCliente);
				
				echo '<p> <strong>INICIO PASE SOLICITUDES ' . $solicitudPendiente['solicitud'] . '</strong>' . IN_MSG . 'Inicio';
				$idFinancieroCabecera = pg_fetch_result($cfa->guardarFinancieroAutomaticoCabecera($conexion, $solicitudPendiente['valor'], $solicitudPendiente['solicitud'], 'saldoVue'), 0, 'id_financiero_cabecera');
					
				$servicio = pg_fetch_assoc($cf->obtenerIdServicioPorCodigoArea($conexion, '06.01.001', 'COMEX'));
				$cfa->guardarFinancieroAutomaticoDetalle($conexion, $idFinancieroCabecera, $servicio['id_servicio'], $servicio['concepto'], $solicitudPendiente['valor'], $servicio['valor'], 0, 0, $solicitudPendiente['valor']);
				
				$cfa->actualizarTipoProcesoFacturaFinancieroAutomaticoCabecera($conexion, $solicitudPendiente['solicitud'], 'factura');
					
				$datosRecaudador = pg_fetch_assoc($cf->obtenerDatosRecaudadorPorProvinciaEstadoFirma($conexion, /*$fitosanitarioExportacion['id_provincia_revision']*/ 259, 'SI'));
					
				$tipoInspector = 'Financiero';
					
				$idGrupoAsignado= pg_fetch_assoc($crs->guardarNuevoInspector($conexion, $datosRecaudador['identificador_firmante'], $datosRecaudador['identificador_firmante'], 'saldoVue', $tipoInspector));
				$crs->guardarGrupo($conexion, $solicitudPendiente['id_pago_anticipado'], $idGrupoAsignado['id_grupo'], 'Financiero');
					
				$ordenPago = $crs->buscarSerialOrden($conexion, $idGrupoAsignado['id_grupo'], $tipoInspector);
					
				//Guarda inspector, monto y fecha para inspeccion financiera
				$idFinanciero = $crs->asignarMontoSolicitud($conexion, $idGrupoAsignado['id_grupo'], $datosRecaudador['identificador_firmante'], $solicitudPendiente['valor'], pg_fetch_result($ordenPago, 0, 'orden'));
				echo IN_MSG .' Creación de grupo y seguimiento financiero.';
					
				$anioActual = date('Y');
					
				$documento = pg_fetch_assoc($cc -> generarNumeroDocumento($conexion, '%AGR-'.$anioActual.'%'));
				$tmp= explode("-", $documento['numero']);
				$incremento = end($tmp)+1;
				$numeroSolicitud = 'AGR-'.$anioActual.'-'.str_pad($incremento, 9, "0", STR_PAD_LEFT);
					
				echo IN_MSG .' Creación de número de orden de pago.';
					
				$ordenPago = pg_fetch_assoc($cc -> guardarOrdenPago($conexion, $datosCliente['identificador'], $numeroSolicitud, $solicitudPendiente['valor'], 'Imposición automática por sistema GUIA.', $datosRecaudador['oficina'], $datosRecaudador['provincia'], $datosRecaudador['id_provincia'], $datosRecaudador['identificador_firmante'], $solicitudPendiente['solicitud'], 'saldoVue', $idGrupoAsignado['id_grupo'], 5));
				
				$cc->actualizarPorcentajeIvaOrdenPago($conexion, $ordenPago['id_pago'], $datosRecaudador['iva']);
				
				$cc -> guardarTotal($conexion, $ordenPago['id_pago'], $servicio['id_servicio'], $servicio['concepto'],$solicitudPendiente['valor'],0,$servicio['valor'],0,$solicitudPendiente['valor']);
				
				$cf->actualizarNumeroOrdenSolicitudVue($conexion, $solicitudPendiente['solicitud'], $idGrupoAsignado['id_grupo'], 'saldoVue', $solicitudPendiente['solicitud']);
	
				echo IN_MSG .' Creación de cabecera de orden de pago número '.$ordenPago['id_pago'];
				
				echo IN_MSG .' Creación de detalle de orden de pago.';
				
				//Generando orden de pago
				$fecha = time ();
				$fecha_partir1=date ( "h" , $fecha ) ;
				$fecha_partir2=date ( "i" , $fecha ) ;
				$fecha_partir4=date ( "s" , $fecha ) ;
				$fecha_partir3=$fecha_partir1-1;
				$reporte="ReporteOrden";
				$filename = $reporte."_".$datosCliente['identificador']."_".date("Y-m-d")."_".$fecha_partir3.'_'.$fecha_partir2.'_'.$fecha_partir4.'.pdf';
				$nombreArchivo = $reporte."_".$datosCliente['identificador']."_".date("Y-m-d")."_".$fecha_partir3.'_'.$fecha_partir2.'_'.$fecha_partir4;
				
				$jru = new ControladorReportes();
				
				if (!file_exists('../financiero/documentos/ordenPago/'.$rutaFecha.'/')){
				    mkdir('../financiero/documentos/ordenPago/'.$rutaFecha.'/', 0777,true);
				}
				
				$ReporteJasper= '/aplicaciones/financiero/reportes/reporteOrden.jrxml';
				$salidaReporte= '/aplicaciones/financiero/documentos/ordenPago/'.$rutaFecha.'/'.$filename;
				$rutaArchivo= 'aplicaciones/financiero/documentos/ordenPago/'.$rutaFecha.'/'.$filename;
				$compensacion = '';
				
				$parameters['parametrosReporte'] = array(
					'idpago'=> (int)$ordenPago['id_pago'],
					'compensacion'=> $compensacion,
					'totalSubsidio'=> (double)0
				);
				
				$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'defecto');
				
				$cc -> guardarRutaOrdenPago($conexion,$ordenPago['id_pago'],$rutaArchivo);
				
				$cfa->actualizarIdOrdenPagoFinancieroAutomaticoCabecera($conexion, $idFinancieroCabecera, $ordenPago['id_pago']);
				$cfa->actualizarEstadoFinancieroAutomaticoCabecera($conexion, $idFinancieroCabecera, 'Atendida');
				
				//TODO:Eliminado por petición de verificación de saldo al siguiente día por parte financiera.
				//$cfa->actualizarEstadoFacturaFinancieroAutomaticoCabecera($conexion, $solicitudPendiente['solicitud'], 'Por atender');
				
				echo IN_MSG .' Generación de archivo de orden de pago.';
					
				$controladorVUE->finalizarPagoAnticipado($solicitudPendiente['id_pago_anticipado'],'Atendida');
					
				echo OUT_MSG . 'Se ha finalizado la tarea de envio.';
				echo '<br/><strong>FIN</strong></p>';
				
			}else{
				$controladorVUE->actualizarObservacionPagoAnticipado('No posee datos del operador para la facturación electronica.', $solicitudPendiente['id_pago_anticipado']);
			}
			
			
		}
	
	/*}else{
	
		$minutoS1=microtime(true);
		$minutoS2=microtime(true);
		$tiempo=$minutoS2-minutoS1;
		$xcadenota = "FECHA ".date("d/m/Y")." ".date("H:i:s");
		$xcadenota.= "; IP REMOTA ".$_SERVER['REMOTE_ADDR'];
		$xcadenota.= "; SERVIDOR HTTP ".$_SERVER['HTTP_REFERER'];
		$xcadenota.= "; SEGUNDOS ".$tiempo."\n";
		$arch = fopen("../../aplicaciones/logs/cron/proceso_pago_anticipado_".date("d-m-Y").".txt", "a+");
		fwrite($arch, $xcadenota);
		fclose($arch);
	
*/


?>

</body>
</html>
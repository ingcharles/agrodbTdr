<?php

require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorMonitoreo.php';
require_once '../../../clases/ControladorCertificados.php';
require_once '../../../clases/ControladorFinancieroAutomatico.php';

if($_SERVER['REMOTE_ADDR'] == ''){
//if(1){
	$conexion = new Conexion();
	$cm = new ControladorMonitoreo();
	$cc = new ControladorCertificados();
	$cfa = new ControladorFinancieroAutomatico();

	$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_FACT_LAB_PAGO');

	if($resultadoMonitoreo){
    //if(1){
		define('IN_MSG','<br/> >>> ');

		$fecha = date("Y-m-d h:m:s");
		echo IN_MSG . '<p><strong> INICIO SOLICITUD '.$fecha.'</strong></p>';

		//OBTENER LOS REGISTROS A SER ENVIADOS AL ESQUEMA FINANCIERO PARA FACTURACION
		$datosPorGenerar = $cc->obtenerRegistrosPagoLaboratorios($conexion, 'Por atender');

		while ($fila = pg_fetch_assoc($datosPorGenerar)){
		    
			echo IN_MSG.'Número de solicitud: '. $fila['id_solicitud'];
			
			$idSolicitudLaboratorios = $fila['id_solicitud'];
			
			$registroPago = $cc->obtenerRegistrosPagoLaboratoriosPorSolicitud($conexion, $idSolicitudLaboratorios);
			$numeroOrdenPago = pg_fetch_assoc($cfa->obtenerDatosOrdenPagoFacturacionAutmatica($conexion, $idSolicitudLaboratorios, 'g_laboratorios.solicitudes'));
			
			while($pago = pg_fetch_assoc($registroPago)){
			    			    
			    $idPago = $pago['id_pagos'];
			    
			    echo IN_MSG.'Número de pago: '. $idPago;
			    
			    $cc->actualizarEstadoRegistrosPagoLaboratorios($conexion, $idPago, 'W');
			    
			    $cc->guardarPagoOrden($conexion,$numeroOrdenPago['id_orden_pago'],$pago['fecha_deposito'],$pago['id_banco'],$pago['nombre'],$pago['numero_deposito'],$pago['valor_depositado'], 0, $pago['id_cuenta_bancaria'], $pago['numero_cuenta']);
			    
			    $cc->actualizarEstadoRegistrosPagoLaboratorios($conexion, $idPago, 'Atendida');
			    
			}
			
			$cfa->actualizarTipoProcesoFacturaFinancieroAutomaticoCabeceraPorIdentificador($conexion, $numeroOrdenPago['id_financiero_cabecera'], 'factura');
			$cfa->actualizarEstadoFacturaPorIdentificadorFinancieroAutomatico($conexion, $numeroOrdenPago['id_financiero_cabecera'], 'Por atender');
			
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
	$arch = fopen("../../../aplicaciones/logs/cron/automatico_laboratorio_orden".date("d-m-Y").".txt", "a+");
	fwrite($arch, $xcadenota);
	fclose($arch);

}


?>
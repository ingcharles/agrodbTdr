<?php

if($_SERVER['REMOTE_ADDR'] == ''){

	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorMonitoreo.php';
	require_once '../../clases/ControladorFitosanitarioExportacion.php';

	define ( 'IN_MSG', '<br/> >>> ' );
	define ( 'OUT_MSG', '<br/> <<< ' );
	define ( 'PRO_MSG', '<br/> ... ' );

	$conexion = new Conexion ();
	$cm = new ControladorMonitoreo();
	$cfe = new ControladorFitosanitarioExportacion();

	$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_SAC_NOT_CFE');

	if($resultadoMonitoreo){

		echo '<h1>ACTUALIZACION AUTOMÁTICA DE ESTADO EN SANCIÓN CFE</h1>';
		echo '<p> <strong>INICIO PROCESO DE ACTUALIZACION</strong>';
		$contador=1;
		$qVencimientoSancion = $cfe->consultarSancionACaducar ( $conexion,'activo');
		while ($fila = pg_fetch_assoc($qVencimientoSancion ) ) {
			echo '<b>' . PRO_MSG . 'Sanción #' . $contador++ . ' - '.' para el exportador ' . $fila['identificador_exportador'] . '</b>';
			echo IN_MSG . 'Inicio del envio de la solicitud a actualizar el estado de la sanción por vencimiento';
			$cfe->actualizarEstadoSancion($conexion, $fila['id_sancion'],'inactivo');
			echo OUT_MSG . 'Fin del envio de solicitud';
		}

		echo '<br/><strong>FIN</strong></p>';
		$conexion->desconectar ();
	}

}else{

	$minutoS1=microtime(true);
	$minutoS2=microtime(true);
	$tiempo=$minutoS2-minutoS1;
	$xcadenota = "FECHA ".date("d/m/Y")." ".date("H:i:s");
	$xcadenota.= "; IP REMOTA ".$_SERVER['REMOTE_ADDR'];
	$xcadenota.= "; SERVIDOR HTTP ".$_SERVER['HTTP_REFERER'];
	$xcadenota.= "; SEGUNDOS ".$tiempo."\n";
	$arch = fopen("../../aplicaciones/logs/cron/gestionar_notif_sanc_".date("d-m-Y").".txt", "a+");
	fwrite($arch, $xcadenota);
	fclose($arch);

}

?>


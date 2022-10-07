<?php
if ($_SERVER['REMOTE_ADDR'] == ''){
	// if(1){
	require_once '../../../clases/Conexion.php';
	require_once '../../../clases/ControladorMonitoreo.php';
	require_once '../../../clases/ControladorCatastroProducto.php';

	$conexion = new Conexion();
	$cm = new ControladorMonitoreo();
	$ccp = new ControladorCatastroProducto();

	define('PRO_MSG', '<br/> ');
	define('IN_MSG', '<br/> >>> ');
	$fecha = date("Y-m-d h:m:s");

	set_time_limit(6000);

	$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_ACTUAL_EST_CATA'); // TODO: Crear nuevo código de cron en base de datos "CRON_ACTUAL_EST_CATA"
	if ($resultadoMonitoreo){
		// if(1){

		echo IN_MSG . '<b>INICIO PROCESO DE ACTUALIZACIÓN DE ESTADO DE CATASTROS (Actualizar a "eliminado") ' . $fecha . '</b>';

		// Una vez pasado 425 días de catastrado se pasa a eliminado a los cerdos

		$contadorEstadoInactivo = 1;
		$qCambioEstadoInactivo = $ccp->abrirDetalleCatastroXEliminarXIdentificadorProducto($conexion);
		echo pg_num_rows($qCambioEstadoInactivo);
		while ($cambioEstadoInactivo = pg_fetch_assoc($qCambioEstadoInactivo)){
			echo '<b>' . PRO_MSG . 'Proceso  a estado eliminado #' . $contadorEstadoInactivo ++ . ' - ' . ' N° Identificador ' . $cambioEstadoInactivo['identificador'] . '</b>';
			echo IN_MSG . 'Inicio de la actualización a estado eliminado por caducidad de esperanza de vida';
			$ccp->actualizarEstadoDetalleCatastroXIdentificadorProducto($conexion, $cambioEstadoInactivo['identificador'], 'eliminado', 'Dado de baja por caducidad de esperanza de vida');
			echo OUT_MSG . 'Fin del envio de solicitud';
		}

		echo IN_MSG . '<b>FIN DEL PROCESO DE ACTUALIZACIÓN DE ESTADO DE CATASTROS (Actualizar a "eliminado") ' . $fecha . '</b>';
	}
}else{
	$minutoS1 = microtime(true);
	$minutoS2 = microtime(true);
	$tiempo = $minutoS2 - $minutoS1;
	$xcadenota = "FECHA " . date("d/m/Y") . " " . date("H:i:s");
	$xcadenota .= "; IP REMOTA " . $_SERVER['REMOTE_ADDR'];
	$xcadenota .= "; SERVIDOR HTTP " . $_SERVER['HTTP_REFERER'];
	$xcadenota .= "; SEGUNDOS " . $tiempo . "\n";
	$arch = fopen("../../../aplicaciones/logs/cron/catastro_estado_eliminado_" . date("d-m-Y") . ".txt", "a+");
	fwrite($arch, $xcadenota);
	fclose($arch);
}
?>
<?php
if ($_SERVER['REMOTE_ADDR'] == '') {
	//if(1){
	require_once '../../../clases/Conexion.php';
	require_once '../../../clases/ControladorMonitoreo.php';
	require_once '../../../clases/ControladorVacunacion.php';
	
	define('IN_MSG', '<br/> >>> ');
	define('OUT_MSG', '<br/> <<< ');
	define('PRO_MSG', '<br/> ... ');
	
	$conexion = new Conexion();
	$cm = new ControladorMonitoreo();
	$cv = new ControladorVacunacion();

	$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_EST_LOT_PROD'); //Crear cron en base de datos

	if ($resultadoMonitoreo){
		// if (1) {
		try{
			try{
	
				echo '<h1>ACTUALIZACION AUTOMATICA DE ESTADOS EN LOTES DE VACUNACIÓN</h1>';
				echo '<p> <strong>INICIO PROCESO DE ACTUALIZACION</strong>';
	
				$qCambioEstadoLoteVacuna = $cv->consultarLotesVacunacionCaducados($conexion);
	
				$contadorEstado = 1;
	
				while ($cambioEstadoLoteVacuna = pg_fetch_assoc($qCambioEstadoLoteVacuna)){
					$conexion->ejecutarConsulta("begin;");
					echo '<b>' . PRO_MSG . 'Proceso A Estado Inactivo #' . $contadorEstado ++ . ' - ' . ' Id Lote ' . $cambioEstadoLoteVacuna['id_lote'] . '</b>';
					echo IN_MSG . 'Inicio actualizar estado del lote de vacuna a estado inactivo';
					$cv->actualizarEstadoLoteVcaunacionCaducado($conexion, $cambioEstadoLoteVacuna['id_lote'], 'inactivo');
					echo OUT_MSG . 'Fin del envio de solicitud';
					$conexion->ejecutarConsulta("commit;");
				}
	
				echo '<br/><strong>FIN</strong></p>';
				// $conexion->desconectar ();
			}catch (Exception $ex){
				$conexion->ejecutarConsulta("rollback;");
				$err = preg_replace("/\r|\n/", " ", $conexion->mensajeError);
				$conexion->ejecutarLogsTryCatch($ex . '---ERROR:' . $err);
			}finally {
				$conexion->desconectar();
			}
		}catch (Exception $ex){
			$err = preg_replace("/\r|\n/", " ", $conexion->mensajeError);
			$conexion->ejecutarLogsTryCatch($ex . '---ERROR:' . $err);
		}
	}
}else{
	$minutoS1 = microtime(true);
	$minutoS2 = microtime(true);
	$tiempo = $minutoS2 - minutoS1;
	$xcadenota = "FECHA " . date("d/m/Y") . " " . date("H:i:s");
	$xcadenota .= "; IP REMOTA " . $_SERVER['REMOTE_ADDR'];
	$xcadenota .= "; SERVIDOR HTTP " . $_SERVER['HTTP_REFERER'];
	$xcadenota .= "; SEGUNDOS " . $tiempo . "\n";
	$arch = fopen("../../aplicaciones/logs/cron/cambio_estado_lote_vacunacion_" . date("d-m-Y") . ".txt", "a+");
	fwrite($arch, $xcadenota);
	fclose($arch);
}

?>
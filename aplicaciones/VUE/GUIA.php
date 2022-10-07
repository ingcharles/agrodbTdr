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
	if(1){
		require_once '../../clases/ControladorVUE.php';
		require_once '../../clases/ControladorMonitoreo.php';

		$controladorVUE = new ControladorVUE();
		$cm = new ControladorMonitoreo();
		$conexionGUIA = new Conexion();

		//$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexionGUIA, 'CRON_GUIA_VUE');

		//if($resultadoMonitoreo){
		if(1){
			$solicitudesPendientes = $controladorVUE->cargarSolicitudesPorAtenderGUIA();

			while ($solicitudPendiente = pg_fetch_assoc($solicitudesPendientes)){

				echo '<p> <strong>INICIO PASE SOLICITUDES ' . $solicitudPendiente['solicitud'] . '</strong>' . IN_MSG . 'Inicio';
				$controladorVUE->finalizarGUIA($solicitudPendiente['id'],'W');
				$controladorVUE->ingresarSolicitudesXatenderVUE($solicitudPendiente['formulario'], $solicitudPendiente['codigo_procesamiento'], $solicitudPendiente['codigo_verificacion'], $solicitudPendiente['solicitud'],$solicitudPendiente['observacion']);
				$controladorVUE->finalizarGUIA($solicitudPendiente['id'],'Atendida');
				echo OUT_MSG . 'Se ha finalizado la tarea de envio.';
				echo '<br/><strong>FIN</strong></p>';
			}

			$controladorVUE->cambiarSolicitudesPendientesAPorAtenderGUIA();
		}

	}else{

		$minutoS1=microtime(true);
		$minutoS2=microtime(true);
		$tiempo=$minutoS2-minutoS1;
		$xcadenota = "FECHA ".date("d/m/Y")." ".date("H:i:s");
		$xcadenota.= "; IP REMOTA ".$_SERVER['REMOTE_ADDR'];
		$xcadenota.= "; SERVIDOR HTTP ".$_SERVER['HTTP_REFERER'];
		$xcadenota.= "; SEGUNDOS ".$tiempo."\n";
		$arch = fopen("../../aplicaciones/logs/cron/proceso_guia_".date("d-m-Y").".txt", "a+");
		fwrite($arch, $xcadenota);
		fclose($arch);

	}

	?>

</body>
</html>

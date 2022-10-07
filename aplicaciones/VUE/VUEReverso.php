<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Sistema GUIA</title>
</head>
<body>
	<h1>Solicitudes pendientes por atender</h1>

	<?php

	if($_SERVER['REMOTE_ADDR'] == ''){

		require_once '../../clases/ControladorVUE.php';
		require_once '../../clases/ControladorMonitoreo.php';

		$controladorVUE = new ControladorVUE();
		$cm = new ControladorMonitoreo();
		$conexionVUE = new Conexion();

		$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexionVUE, 'CRON_VUE_REVER');

		if($resultadoMonitoreo){

			//$usuario = array('id' => '101','nombre' => 'G.U.I.A.');

			$solicitudesPendientes = $controladorVUE->cargarSolicitudesPorAtenderVUEReverso($conexionVUE);

			while ($solicitudPendiente = pg_fetch_assoc($solicitudesPendientes)){
				$formulario = $controladorVUE->instanciarFormulario($solicitudPendiente);

				if ($formulario == null){
					echo PRO_MSG . 'formulario desconocido.';
					continue;
				}

				echo '<p> <strong>INICIO SOLICITUD ' . $formulario . '</strong>' . IN_MSG . 'Instanciada';
				//revisión de CODIGO DE DISTRIBUICION DE DOCUMENTO (Pág. 13)
				/* echo '<pre>';
				 print_r($formulario);
				//print_r($solicitudesPendientes);
				echo '</pre>'; */
				//echo $formulario->obtenerCodigoDeProcesamiento();
				switch ($formulario->obtenerCodigoDeProcesamiento()){

					case PAGO_AUTORIZADO:
							
						$controladorVUE->actualizarEstadoPorIdentificadorVUE($solicitudPendiente['solicitud'], 'Solicitud no procesada por petición de reverso.', 'Por atender', 'Reverso');
						$controladorVUE->finalizar($formulario,'W');
							
						switch (substr($solicitudPendiente['formulario'], 0,7)){

							case '101-002':
								$formulario->reversoSolicitud();
								$controladorVUE->finalizar($formulario, 'Atendida');
								break;

							case '101-031':
								$formulario->reversoSolicitud();
								$controladorVUE->finalizar($formulario, 'Atendida');
								break;

							case '101-034':
								$formulario->reversoSolicitud();
								$controladorVUE->finalizar($formulario,'Atendida');
								break;

							default:
								echo "pago autorizado solicitado - desconocido";
								break;
						}
							
						break;

					default:
						echo "desconocido";
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
		$arch = fopen("../../aplicaciones/logs/cron/proceso_vue_reverso_".date("d-m-Y").".txt", "a+");
		fwrite($arch, $xcadenota);
		fclose($arch);

	}

	?>

</body>
</html>

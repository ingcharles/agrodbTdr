<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
</head>

<body>
	<?php

	if ($_SERVER['REMOTE_ADDR'] == '') {
		//if(1){
		require_once '../../clases/Conexion.php';
		require_once '../../clases/ControladorVacaciones.php';
		require_once '../../clases/ControladorAreas.php';
		require_once '../../clases/ControladorMonitoreo.php';

		define('IN_MSG', '<br/> >>> ');

		$conexion = new Conexion();
		$cv = new ControladorVacaciones();
		$cm = new ControladorMonitoreo();

		$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_VACAC_AUME');

		if ($resultadoMonitoreo) {
			//if(1){
			$contratos = $cv->obtenerContratosActivos($conexion);
			echo IN_MSG . 'Gestion Minutos.';
			while ($datos = pg_fetch_assoc($contratos)) {
				echo IN_MSG . 'Usuario que poseen mas de 1 contrato activo.';
				echo IN_MSG . 'Actualización del usuario con cédula número ' . $datos['identificador'];
				$cv->actualizarEstadoContrato($conexion, $datos['identificador']);
			}
			$res = $cv->obtenerDiaIngresoEmpleado($conexion, date("d"));
			//$res=$cv->obtenerDiaIngresoEmpleado($conexion, date("18"));
			while ($fila = pg_fetch_assoc($res)) {

				switch ($fila['regimen_laboral']) {
					case 'CÓDIGO DE TRABAJO':
					case 'SUJETOS CÓDIGO DE TRABAJO':
						$cantidad = 600;
						break;
					default:
						$cantidad = 1200;
				}
				echo IN_MSG . 'Acreditación de ' . $cantidad . ' minutos al usuario con cédula número ' . $fila['identificador'] . ' en el mes de ' . date("F");
				$anioEmpleado = pg_fetch_assoc($cv->obtenerAnioMayor($conexion, $fila['identificador']));
				if ($anioEmpleado['anio'] != '') {
					if ($anioEmpleado['anio'] == date('Y')) {
						$anio = $anioEmpleado['anio'];
						$cv->incrementarSaldosFuncionario($conexion, $fila['identificador'], $cantidad, $anio);
						//Incrementar dias a funcionarios que cumplieron 5 o mas años bajo el codigo de trabajo
						$cantidad = 480; //un dia 8 horas en minutos
						$cv->incrementoDiaPasadoCincoAnios($conexion, $anio, $cantidad);
					} else {
						$anio = $anioEmpleado['anio'] + 1;
						$cv->incrementarSaldosFuncionarioNuevoAnio($conexion, $fila['identificador'], $cantidad, $anio);
					}
				} else {
					$anio = date('Y');
					$secuencial = pg_fetch_assoc($cv->obtenerSecuencialanio($conexion, $fila['identificador'], $anio));
					if ($secuencial['secuencial'] == '') $secu = 1;
					else $secu = $secuencial['secuencial'] + 1;
					$cv->incrementarSaldosFuncionarioNuevoAnio($conexion, $fila['identificador'], $cantidad, $anio, $secu);
				}
			}
			$cv->verificarSaldosMayores60($conexion);
		}
	} else {

		$minutoS1 = microtime(true);
		$minutoS2 = microtime(true);
		$tiempo = $minutoS2 - minutoS1;
		$xcadenota = "FECHA " . date("d/m/Y") . " " . date("H:i:s");
		$xcadenota .= "; IP REMOTA " . $_SERVER['REMOTE_ADDR'];
		$xcadenota .= "; SERVIDOR HTTP " . $_SERVER['HTTP_REFERER'];
		$xcadenota .= "; SEGUNDOS " . $tiempo . "\n";
		$arch = fopen("../../aplicaciones/logs/cron/gestionar_minutos_" . date("d-m-Y") . ".txt", "a+");
		fwrite($arch, $xcadenota);
		fclose($arch);
	}
	?>
</body>

</html>
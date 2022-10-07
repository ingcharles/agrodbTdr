<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
<?php

 //if($_SERVER['REMOTE_ADDR'] == ''){
 	if(1){
	// require_once '../../clases/Conexion.php';
	require_once '/var/www/html/SVNguia/clases/Conexion.php';
	require_once '/var/www/html/SVNguia/clases/ControladorVacaciones.php';
	require_once '/var/www/html/SVNguia/clases/ControladorAreas.php';
	require_once '/var/www/html/SVNguia/clases/ControladorMonitoreo.php';

	define('IN_MSG', '<br/> >>> ');
	try{
		$conexion = new Conexion();
		$cv = new ControladorVacaciones();
		$cm = new ControladorMonitoreo();

		 $resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_VACAC_AUME');
		 if(1){
		//if($resultadoMonitoreo){
			$contratos = $cv->obtenerContratosActivos($conexion);
			echo IN_MSG . 'Gestion Minutos.';

			while ($datos = pg_fetch_assoc($contratos)){
				echo IN_MSG . 'Usuario que poseen mas de 1 contrato activo.';
				echo IN_MSG . 'Actualización del usuario con cédula número ' . $datos['identificador'];
				$cv->actualizarEstadoContrato($conexion, $datos['identificador']);
			}
			$fecha_actual = date("d-m-Y");
			$mesAnterior = $cv->devolverMes(date("m", strtotime($fecha_actual . " - 1 months")));
			$anioAnterior = date("Y", strtotime($fecha_actual . " - 1 months"));
			$contador = 0;
			$datos = $cv->obtenerDiaIngresoFuncionarios($conexion);
			foreach ($datos as $fila){
				if ($fila['activo']){
					$proporcional = $fila['tiempo'];
				}else{
					if ($fila['dia'] != 1){
						$proporcional = $cv->devolverTiempoProporcionalInicial($fila['fecha_inicial'], $fila['tiempo']);
					}else{
						$proporcional = $fila['tiempo'];
					}
				}
				$observacion = 'Acreditación de ' . $proporcional . ' minutos en el més de ' . $mesAnterior . ' del año ' . $anioAnterior . '->proceso automatico';
				echo IN_MSG . ++ $contador . ' Acreditación de ' . $proporcional . ' minutos al usuario con cédula número ' . $fila['identificador'] . ' en el més de ' . $mesAnterior . ' del año ' . $anioAnterior . ' Fecha inical ----' . $fila['fecha_inicial'];
				$cv->agregarMinutosServidoresMes($conexion, $fila['identificador'], $proporcional, $anioAnterior, $mesAnterior, $observacion, $fila['fecha_inicial']);
			}
			// **********verificar saldo mayor a 60 dias y descontar***********************
			$cv->verificarSaldosMayores60Xfuncionarios($conexion);
		}
	}catch (Exception $e){
		echo $e;
	}
}else{

	$minutoS1 = microtime(true);
	$minutoS2 = microtime(true);
	$tiempo = $minutoS2 - $minutoS1;
	$xcadenota = "FECHA " . date("d/m/Y") . " " . date("H:i:s");
	$xcadenota .= "; IP REMOTA " . $_SERVER['REMOTE_ADDR'];
	$xcadenota .= "; SERVIDOR HTTP " . $_SERVER['HTTP_REFERER'];
	$xcadenota .= "; SEGUNDOS " . $tiempo . "\n";
	$arch = fopen("/var/www/html/SVNguia/aplicaciones/logs/cron/gestionar_minutos_" . date("d-m-Y") . ".txt", "a+");
	fwrite($arch, $xcadenota);
	fclose($arch);
}

?>
</body>
</html>
<?php

if($_SERVER['REMOTE_ADDR'] == ''){
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorMonitoreo.php';
	require_once '../../clases/ControladorCertificados.php';

	define('IN_MSG','<br/> >>> ');
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
	$cm = new ControladorMonitoreo();

	$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_FACT_REPROCESA');

	if($resultadoMonitoreo){

		echo '<p> <strong>INICIO FACTURCIÓN ELECTRONICA REPROCESAMIENTO </strong></br>';

		$cc->cargarDocumentosPorReprocesar($conexion);

		echo '<br/><strong>FIN</strong></p>';

	}
}else{

	$minutoS1=microtime(true);
	$minutoS2=microtime(true);
	$tiempo=$minutoS2-minutoS1;
	$xcadenota = "FECHA ".date("d/m/Y")." ".date("H:i:s");
	$xcadenota.= "; IP REMOTA ".$_SERVER['REMOTE_ADDR'];
	$xcadenota.= "; SERVIDOR HTTP ".$_SERVER['HTTP_REFERER'];
	$xcadenota.= "; SEGUNDOS ".$tiempo."\n";
	$arch = fopen("../../aplicaciones/logs/cron/reprocesamineto_facturacion_".date("d-m-Y").".txt", "a+");
	fwrite($arch, $xcadenota);
	fclose($arch);

}

?>

</body>
</html>

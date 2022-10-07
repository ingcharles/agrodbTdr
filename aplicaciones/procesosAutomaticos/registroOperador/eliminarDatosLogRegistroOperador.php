<?php
//echo "df";
if($_SERVER['REMOTE_ADDR'] == ''){
//if(1){
	require_once '../../../clases/Conexion.php';
	require_once '../../../clases/ControladorMonitoreo.php';
	require_once '../../../clases/ControladorRegistroOperador.php';

	$conexion = new Conexion();
	$cr = new ControladorRegistroOperador();
	$cm = new ControladorMonitoreo();
	
	define('PRO_MSG', '<br/> ');
	define('IN_MSG','<br/> >>> ');
	$fecha = date("Y-m-d h:m:s");

	$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_ELIMINA_REG_OPER_MAS');
	if($resultadoMonitoreo){
	//if(1){
		
		echo IN_MSG .'<b>INICIO PROCESO DE ELIMINACION DE LOG REGISTRO OPERADOR '.$fecha.'</b>';
		
		$cr->eliminarDatosLogRegistroOperador($conexion);
		
		echo IN_MSG .'<b>FIN DEL PROCESO DE ELIMINACION DE LOG REGISTRO OPERADOR '.$fecha.'</b>';
		
		
	}
}else{
    $minutoS1=microtime(true);
    $minutoS2=microtime(true);
    $tiempo=$minutoS2-$minutoS1;
    $xcadenota = "FECHA ".date("d/m/Y")." ".date("H:i:s");
    $xcadenota.= "; IP REMOTA ".$_SERVER['REMOTE_ADDR'];
    $xcadenota.= "; SERVIDOR HTTP ".$_SERVER['HTTP_REFERER'];
    $xcadenota.= "; SEGUNDOS ".$tiempo."\n";
    $arch = fopen("../../../aplicaciones/logs/cron/registro_operador_eliminacion_log_".date("d-m-Y").".txt", "a+");
    fwrite($arch, $xcadenota);
    fclose($arch);
    
}
?>
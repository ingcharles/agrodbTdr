<?php
if($_SERVER['REMOTE_ADDR'] == ''){
//if(1){
	require_once '../../../clases/Conexion.php';
	require_once '../../../clases/ControladorTablets.php';
	require_once '../../../clases/ControladorMonitoreo.php';
	require_once '../../../clases/ControladorAdministracionDeTrampas.php';

	$conexion = new Conexion();
	$ct = new ControladorTablets();
	$cm = new ControladorMonitoreo();
	$cat = new ControladorAdministracionDeTrampas();
	
	$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_MOSCA_TRAM');
	if($resultadoMonitoreo){
	//if(1){
		$datosSincronizar = $ct->obtenerRegistrosTrampasMosca($conexion, 'ORIGINAL', 'POR ENVIAR');
		
		while ($fila = pg_fetch_assoc($datosSincronizar)){
			
			$codigoTrampa = $fila['codigo_trampa'];
			$estadoTrampa = $fila['estado_trampa'];
			$idRegistroTrampa = $fila['id'];
			$fechaInspeccion = $fila['fecha_inspeccion'];
			$usuarioIdentificador = $fila['usuario_id'];
			
			$ct->actualizarEstadoTrampasMosca($conexion, $idRegistroTrampa, 'W');
			
			$trampa = pg_fetch_assoc($cat->obtenerTrampaPorCodigoTrampa($conexion, $codigoTrampa));
			
			if($estadoTrampa != $trampa['estado_trampa']){
				
				$cat->modificarNuevoAdminintracionTrampas($conexion, $trampa['id_administracion_trampa'], $estadoTrampa, 'Estado actualizado por tablets el '.$fechaInspeccion);
				
				$cat->guardarNuevoHistoriaAdminintracionTrampas($conexion, $trampa['id_administracion_trampa'], $trampa['codigo_trampa'], $trampa['id_area_trampa'], $trampa['etapa_trampa'], $trampa['fecha_instalacion_trampa'], $trampa['id_provincia'], 
																$trampa['id_canton'], $trampa['id_parroquia'], $trampa['coordenadax'], $trampa['coordenaday'], $trampa['coordenadaz'], $trampa['id_lugar_instalacion'], $trampa['numero_lugar_instalacion'], 
																$trampa['id_plaga'], $trampa['id_tipo_trampa'], $trampa['id_tipo_atrayente'], $estadoTrampa, 'Estado actualizado por tablets el '.$fechaInspeccion, $usuarioIdentificador, $trampa['codigo_programa_especifico']);	
			}

			$ct->actualizarEstadoTrampasMosca($conexion, $idRegistroTrampa, 'FINALIZADO');
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
	$arch = fopen("../../../aplicaciones/logs/cron/tablets_trampas_".date("d-m-Y").".txt", "a+");
	fwrite($arch, $xcadenota);
	fclose($arch);

}
?>
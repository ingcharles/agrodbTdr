<?php
if($_SERVER['REMOTE_ADDR'] == ''){
//if(1){
	require_once '../../../clases/Conexion.php';
	require_once '../../../clases/ControladorMail.php';
	require_once '../../../clases/ControladorTablets.php';
	require_once '../../../clases/ControladorMonitoreo.php';
	require_once '../../../clases/ControladorSeguimientoCuarentenario.php';

	$conexion = new Conexion();
	$ct = new ControladorTablets();
	$cMail = new ControladorMail();
	$cm = new ControladorMonitoreo();
	$csc = new ControladorSeguimientoCuarentenario();

	$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_CONTROL_SEG');
	if($resultadoMonitoreo){
	//if(1){
		$datosSincronizar = $ct->obtenerRegistrosSeguiminetoCuarentenario($conexion, 'POR ENVIAR');

		while ($fila = pg_fetch_assoc($datosSincronizar)){

			$idRegistro = $fila['id'];
			$idSeguimiento = $fila['id_seguimiento_cuarentenario'];
			$fechaInspeccion = $fila['fecha_inspeccion'];
			$resultadoInspeccion = $fila['resultado_inspeccion'];
			$observaciones = $fila['observaciones'];
				
			$ct->actualizarEstadoSeguiminetoCuarentenario($conexion, $idRegistro, 'W');
				
			$qSecuencial = $csc->obtenerMaxSecuencialSeguimientoCuarentenario($conexion, $idSeguimiento);
			$secuencial = pg_fetch_result($qSecuencial, 0, 'secuencial');
				
			$seguimiento = pg_fetch_assoc($csc->obtenerSeguimientoCuarentenarioPorIdentificador($conexion, $idSeguimiento));
				
			$csc->guardarNuevoDetalleSeguiminetoCuarentenario($conexion, $idSeguimiento, $secuencial, $fechaInspeccion, $resultadoInspeccion, $observaciones);
				
				
			if($seguimiento['numero_seguimientos']<$secuencial){

				$qListadoTecnico = $csc->listarTecnicoInspectorProvinciaDDA($conexion, $seguimiento['provincia_seguimiento'], "('PFL_SEGUI_CUARE')");
				
				$cuerpoMensaje= '<html xmlns="http://www.w3.org/1999/xhtml"><body style="margin:0; padding:0;">
									<style type="text/css">
										.titulo  {
											margin-top: 30px;
											width: 800px;
											text-align: center;
											font-size: 14px;
											font-weight: bold;
											font-family:Times New Roman;
										}
										.lineaDos{
											font-style: oblique;
											font-weight: normal;
										}
										.lineaLeft{
											text-align: left;
										}
										.lineaEspacio{
											height: 35px;
										}
										.lineaEspacioMedio{
											height: 50px;
										}
										.espacioLeft{
											padding-left: 15px;
										}
									</style>';
				
				$cuerpoMensaje.='<table class="titulo">
									<thead>
										<tr><th>Se ha registrado un exceso en el seguimiento cuarentenario.</th></tr>
									</thead>
									<tbody>
									<tr><td class="lineaDos lineaEspacio">Se ha registrado un exceso en el seguimiento cuarentenario de la solicitud con número # '.$seguimiento['codigo_certificado'].' </td>	</tr>
									<tr><td class=""><a href="https://guia.agrocalidad.gob.ec">guia.agrocalidad.gob.ec</a></td></tr>
									</tbody>
									<tfooter>
									<tr><td class="lineaEspacioMedio"></td></tr>
									<tr><td class="lineaDos lineaLeft espacioLeft"><span style="font-weight:bold;" >NOTA: </span>Este correo fue generado automaticamente por el sistema GUIA, por favor no responder a este mensaje. </td></tr>
									<tr><td class="lineaDos lineaLeft espacioLeft">Dirección de Tecnologías de Información y Comunicación</td></tr>
									</tfooter>
									</table>';
				
				$asunto = 'Exceso en el seguimiento cuarentenario';
				$codigoModulo='';
				$tablaModulo='';
				
				foreach ((array)$qListadoTecnico['listado_usuarios'] as $fila) {
					$destinatarios = array();
					if($fila['mail_institucional']!= ''){
						$destinatarios  = explode('; ',$fila['mail_institucional']);
				
					}else if($fila['mail_personal'] !=''){
						$destinatarios  = explode('; ',$fila['mail_personal']);
					}
				
					$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo, '');
					$idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
					$cMail->guardarDestinatario($conexion, $idCorreo,$destinatarios);
				
				}
			}
			
			$ct->actualizarEstadoSeguiminetoCuarentenario($conexion, $idRegistro, 'FINALIZADO');
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
	$arch = fopen("../../../aplicaciones/logs/cron/tablets_seguimiento_cuarentenario_".date("d-m-Y").".txt", "a+");
	fwrite($arch, $xcadenota);
	fclose($arch);

}
?>
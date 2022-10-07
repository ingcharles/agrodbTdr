<?php
//if($_SERVER['REMOTE_ADDR'] == ''){
if(1){
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorMail.php';
	require_once '../../clases/ControladorMonitoreo.php';
	require_once '../../clases/ControladorRegistroOperador.php';

	$conexion = new Conexion();
	$cr = new ControladorRegistroOperador();
	$cMail = new ControladorMail();
	$cm = new ControladorMonitoreo();
	
	define('PRO_MSG', '<br/> ');
	define('IN_MSG','<br/> >>> ');
	$fecha = date("Y-m-d h:m:s");

	//$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_INSC_REG_OPER');
	//if($resultadoMonitoreo){
	if(1){
		$datosSincronizar = $cr->obtenerRegistrosCaducidadVigenciaRegistroOperador($conexion, '1');
		
		echo IN_MSG .'<b>INICIO PROCESO ENVÍO MAIL OPERADOR '.$fecha.'</b>';

		while ($fila = pg_fetch_assoc($datosSincronizar)){
			
			echo PRO_MSG;
			echo IN_MSG .'Operador #'.$fila['identificador'].'';

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
					<tr><th>Certificación de predio de cuarentena próximo a caducar.</th></tr>
					</thead>
					<tbody>
					<tr><td class="lineaDos lineaEspacio">Tener en cuenta para el respectivo proceso de renovación.</td></tr>
					<tr><td class=""><a href="https://guia.agrocalidad.gob.ec">guia.agrocalidad.gob.ec</a></td></tr>
					</tbody>
					<tfooter>
					<tr><td class="lineaEspacioMedio"></td></tr>
					<tr><td class="lineaDos lineaLeft espacioLeft"><span style="font-weight:bold;" >NOTA: </span>Este correo fue generado automaticamente por el sistema GUIA, por favor no responder a este mensaje. </td></tr>
					<tr><td class="lineaDos lineaLeft espacioLeft">Dirección de Tecnologías de Información y Comunicación</td></tr>
					</tfooter>
					</table>';

			$asunto = 'Certificación de predio de cuarentena próximo a caducar.';
			$codigoModulo='';
			$tablaModulo='';

			$destinatarios = array();
			
			echo IN_MSG .'Razón social '.$fila['razon_social'].'';

			array_push($destinatarios, $fila['correo']);
			echo IN_MSG .'Correo '.$fila['correo'].'';

			$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo, '');
			$idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
			$cMail->guardarDestinatario($conexion, $idCorreo,$destinatarios);

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
	$arch = fopen("../../../aplicaciones/logs/cron/verificacion_reg_operador_".date("d-m-Y").".txt", "a+");
	fwrite($arch, $xcadenota);
	fclose($arch);

}
?>
<?php
require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorMail.php';
require_once '../../../clases/ControladorMonitoreo.php';

//if($_SERVER['REMOTE_ADDR'] == ''){
if(1){
	$conexion = new Conexion();
	$cMail = new ControladorMail();
	$cm = new ControladorMonitoreo();

	//set_time_limit(600);
	$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_GUIA_CORREO');

	if($resultadoMonitoreo){
	//if(1){
		$contador=0;
		define('PRO_MSG', '<br/> ... ');
		define('IN_MSG','<br/> >>> ');

		echo '<center><h1>ENVIO AUTOMATICO DE CORREOS</h1></center>';

		$qBuscarCorreo=$cMail->buscarCorreoPorEnviar($conexion);

		while($correo=pg_fetch_assoc($qBuscarCorreo)){



			$fecha = date("Y-m-d h:m:s");
			$idCorreo=$correo['id_correo'];
			$idSolicitudTabla=$correo['id_solicitud_tabla'];
			$asunto=$correo['asunto'];
			$cuerpoMensaje=$correo['cuerpo'];
			$codigoModulo=$correo['codigo_modulo'];
			$tablaModulo=$correo['tabla_modulo'];
			$idGrupoSolicitudes = explode(", ",($idSolicitudTabla));
			$destinatarios = array();
			$documentosAdjuntos = array();
			echo PRO_MSG .'<b>PROCESO #'.($contador+1).'</b>';
			echo IN_MSG .'<b>'.$fecha.'</b>';
			echo IN_MSG .'<b>Solicitudes: </b>'.$idSolicitudTabla;
			echo IN_MSG .'<b>Módulo: </b>'.$codigoModulo;
			echo IN_MSG .'<b>Tabla: </b>'.$tablaModulo ;

			$cMail->actualizarCorreoEnviado($conexion, $idCorreo, 'W');

			$qDestinatarios=$cMail->buscarDestinatarios($conexion, $idCorreo);
			$cDestinatario=1;
			while($desti=pg_fetch_assoc($qDestinatarios)){
				array_push($destinatarios, $desti['destinatario_correo']);
				echo IN_MSG . '<b>Destinatario '.$cDestinatario.'</b>. '.$desti['destinatario_correo'];
				$cDestinatario++;
			}

			$qDocumentosAdjuntos=$cMail->buscarDocumentosAdjuntos($conexion, $idCorreo);
			while($docAdj=pg_fetch_assoc($qDocumentosAdjuntos)){
				array_push($documentosAdjuntos, $docAdj['ruta_documento_adjunto']);
			}

			$estadoMail = $cMail->enviarMail($conexion, $destinatarios, $asunto, $cuerpoMensaje, $documentosAdjuntos);
			$cMail->actualizarCorreoEnviado($conexion, $idCorreo, $estadoMail);

			switch ($codigoModulo) {
				case 'PRG_SERVI_LINEA'://MODULO SERVICIOS EN LINEA
					switch ($tablaModulo) {
						case 'g_servicios_linea.detalle_confirmacion_pagos'://TABLA SOLICITUD - ACTUALIZAR CAMPO ESTADO CORREO
							$estadoCorreoSolicitud=($estadoMail=='Mail enviado.'?'TRUE':'FALSE');
							foreach ($idGrupoSolicitudes as $idSolicitud ){
								$cMail->actualizarEstadoMailTablaSolicitud($conexion, $tablaModulo, 'correo_enviado', $estadoCorreoSolicitud, 'id_detalle_confirmacion_pago', $idSolicitud);
							}
						break;
					}
				break;				
				case 'PRG_FINANCIERO'://MODULO FINANCIERO
					switch ($tablaModulo) {
						case 'g_financiero.orden_pago':
							$cMail->actualizarEstadoMailTablaSolicitud($conexion, $tablaModulo, 'estado_mail', $estadoMail, 'id_pago', $idSolicitudTabla);
						break;
						case 'g_financiero.nota_credito':
							$cMail->actualizarEstadoMailTablaSolicitud($conexion, $tablaModulo, 'estado_mail', $estadoMail, 'id_nota_credito', $idSolicitudTabla);
						break;						
					}						
				break;
				
				case 'PRG_INVEST_AI'://MODULO S.S.O
					$cMail->actualizarEstadoMailTablaSolicitud($conexion, $tablaModulo, 'estado_mail', $estadoMail, 'codigo_datos_accidente', $idSolicitudTabla);
				break;
				
				case 'PRG_DDA'://MODULO DDA DESTINACION ADUANERA
					$cMail->actualizarEstadoMailTablaSolicitud($conexion, $tablaModulo, 'estado_mail', $estadoMail, 'id_destinacion_aduanera', $idSolicitudTabla);
				break;
				
				case 'PRG_CATASTRO':
					switch ($tablaModulo) {
						case 'g_uath.funcionario_rol_pagos':
							$cMail->actualizarEstadoMailTablaSolicitud($conexion, $tablaModulo, 'estado_mail', $estadoMail, 'id_funcionario_rol_pago', $idSolicitudTabla);
						break;
					}
				break;
			}

			echo IN_MSG . '<b>Asunto: </b>'.$asunto.'<br>';
			echo IN_MSG .'<b>Estado envio mail: </b>'.$estadoMail.'<br>';

			//if($contador%50==0)sleep(5);

			$contador++;
		}
	}

}else{

	$minutoS1=microtime(true);
	$minutoS2=microtime(true);
	$tiempo=$minutoS2-$minutoS1;
	$xcadenota = "FECHA ".date("d/m/Y")." ".date("H:i:s");
	$xcadenota.= "; IP REMOTA ".$_SERVER['REMOTE_ADDR'];
	$xcadenota.= "; SERVIDOR HTTP ".$_SERVER['HTTP_REFERER'];
	$xcadenota.= "; SEGUNDOS ".$tiempo."\n";
	$arch = fopen("../../../aplicaciones/logs/cron/mails_".date("d-m-Y").".txt", "a+");
	fwrite($arch, $xcadenota);
	fclose($arch);

}
?>
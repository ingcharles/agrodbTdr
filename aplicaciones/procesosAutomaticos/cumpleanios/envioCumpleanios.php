<?php

if($_SERVER['REMOTE_ADDR'] == ''){

	require_once '../../../clases/Conexion.php';
	require_once '../../../clases/ControladorMail.php';
	require_once '../../../clases/ControladorUsuarios.php';
	require_once '../../../clases/ControladorMonitoreo.php';

	$conexion = new Conexion();
	$cMail = new ControladorMail();
	$cu = new ControladorUsuarios();
	$cm = new ControladorMonitoreo();

	$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_CUMPLE');

	if($resultadoMonitoreo){

		$datosCumpleanios = $cu->obtenerCumpleniosAgrocalidad($conexion);

		define('IN_MSG','<br/> >>> ');
		$asunto = 'FeLiZ CuMpLeAñOs, te desea AGROCALIDAD';

		function cuerpoMensajeNombre($nombreUsuario){

			$cuerpoMensaje = ' <html xmlns="http://www.w3.org/1999/xhtml">
					<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
					<title>¡Feliz Cumpleaños!</title>
					<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
					</head>

					<body style="margin:0; padding:0;">
					<table align="center" border="0" cellpadding="0" cellspacing="0" width="650" style="border-collapse:collapse;">
					<tr>
					<td align="center" bgcolor="#8FD1E2" style="padding: 15px 0 15px 0;">
					<img src="https://guia.agrocalidad.gob.ec/agrodb/aplicaciones/general/img/felizcumpleanios.png" alt="Feliz Cumpleaños" width="588" height="230" style="display:block;" />
					</td>
					</tr>
					<tr>
					<td bgcolor="#8FD1E2" style="padding: 30px 30px 30px 30px;">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
					<td style="padding: 10px 5px 10px 5px; color: #153643; font-family: Arial, sans-serif; font-size: 24px; text-align:center;">
					<b> ¡ '.$nombreUsuario.' ! </b>
							</td>
							</tr>
							<tr>
							<td>
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
							<td width="260" valign="top">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
							<td>
							<img src="https://guia.agrocalidad.gob.ec/agrodb/aplicaciones/general/img/izquierdaCumpleanios.png" alt="Torta" width="100%" height="226" style="display:block;" />
							</td>
							</tr>
							<tr>
							<td style="padding: 25px 0 0 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px; text-align:justify;">
							En este día tan importante y especial para ti, te deseamos muchas felicidades, éxitos, bendiciones y que todas las metas propuestas tanto en tu vida personal como profesional sean realizadas.
							</td>
							</tr>
							</table>
							</td>
							<td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
							<td width="260" valign="top">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
							<td>
							<img src="https://guia.agrocalidad.gob.ec/agrodb/aplicaciones/general/img/derechaCumpleanios.png" alt="regalo" width="100%" height="226" style="display: block;" />
							</td>
							</tr>
							<tr>
							<td style="padding: 25px 0 0 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px; text-align:justify;">
							Gracias por formar parte de este equipo, con tu aporte diario conseguiremos alcanzar nuestras metas institucionales y ayudaremos a construir un mejor país!
							</td>
							</tr>
							</table>
							</td>

							</tr>
							</table>
							</td>
							</tr>
							<tr>
							<td style="padding:15px 0 15px 0; color: #1B213E; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px; font-weight:bold;" align="right"> ¡Tus compañeros de Agrocalidad!</td>
							</tr>
							</table>
							</td>
							</tr>
							<tr>
							<td bgcolor="#D83D5B" style="padding: 20px 30px 20px 30px;">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
							<td width="55%" >
							<a href="http://guia.agrocalidad.gob.ec/">
							<img src="https://guia.agrocalidad.gob.ec/agrodb/aplicaciones/general/img/ecuadorEsCalidad.png" width="282" height="84" alt="agrocalidad"/></a>
							</td>
							<td style="color: #ffffff; font-family: Arial, sans-serif; font-size: 18px;" align="right"> AGROCALIDAD TE DESEA FELIZ CUMPLEAÑOS!</td>
							</tr>
							</table>
							</td>
							</tr>
							<tr><td style="color: #153643; font-family: Arial, sans-serif; font-size: 10px;" align="right">Copyright © DTICS</td> </tr>
							</table>
							</body>
							</html>' ;

			return $cuerpoMensaje;

		}

		while ($fila = pg_fetch_assoc($datosCumpleanios)){
			$cuerpoMensaje = cuerpoMensajeNombre($fila['nombre_completo']);

			$destinatario = array();
			if($fila['mail_institucional']!= ''){
				array_push($destinatario, $fila['mail_institucional']);
			}

			if($fila['mail_personal'] !=''){
				array_push($destinatario, $fila['mail_personal']);
			}

			$fecha = date("Y-m-d h:m:s");

			echo IN_MSG . $fecha;
			echo IN_MSG . 'Envio correo electronico. '.$fila['nombre_completo'].' '.$fila['identificador'];
			$estadoMail = $cMail->enviarMail($conexion, $destinatario, $asunto, $cuerpoMensaje);
			echo IN_MSG . 'Actualización estado correo electronico.';

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
	$arch = fopen("../../../aplicaciones/logs/cron/cumpleanios_".date("d-m-Y").".txt", "a+");
	fwrite($arch, $xcadenota);
	fclose($arch);

}
?>
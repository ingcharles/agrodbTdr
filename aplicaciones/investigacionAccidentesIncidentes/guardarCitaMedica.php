<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAccidentesIncidentes.php';
require_once '../../clases/ControladorMail.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/Constantes.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$identificadorRegistro = $_SESSION['usuario'];

try {
	$conexion = new Conexion();
	$cMail = new ControladorMail();
	$ca = new ControladorCatastro();
	$cai = new ControladorAccidentesIndicentes();
	$constg = new Constantes();

	$conexion->ejecutarConsulta("begin;");
	$datos = array(
			'solicitud' =>  htmlspecialchars ($_POST['solicitud'],ENT_NOQUOTES,'UTF-8'),
			'fechaCita' => htmlspecialchars ($_POST['fechaCita'],ENT_NOQUOTES,'UTF-8'),
			'horaCita' => htmlspecialchars ($_POST['horaCita'],ENT_NOQUOTES,'UTF-8'),
			'nombreMedico' => htmlspecialchars ( $_POST['nombreMedico'],ENT_NOQUOTES,'UTF-8'),
			'direccionMedico' => htmlspecialchars ( $_POST['direccionMedico'],ENT_NOQUOTES,'UTF-8'),
			'identificadorAccidentado' =>htmlspecialchars ( $_POST['identificadorAccidentado'],ENT_NOQUOTES,'UTF-8') );

	$archivo= $_POST['reporte'];
	
	$fila=pg_fetch_assoc($ca->obtenerDatosUsuarioAgrocalidad($conexion,$datos['identificadorAccidentado']));
	$asunto = 'S.S.O INVESTIGACIÓN ACCIDENTES E INCIDENTES CITA MÉDICA';
	$familiaLetra = "font-family:Text Me One,Segoe UI, Tahoma, Helvetica, freesans, sans-serif";
	$letraCodigo = "font-family:'Segoe UI', 'Helvetica'";
	$cuerpoMensaje = '<table><tbody>
			<tr><td style="'.$familiaLetra.'; text-align:center; font-size:30px; color:rgb(255,206,0); font-weight:bold; text-transform:uppercase;">Sistema GUIA <span style="color:rgb(19,126,255);">te </span> <span style="color:rgb(204,41,44);">saluda</span></td></tr>
			<tr><td style="'.$familiaLetra.'; padding-top:2px; font-size:17px; color:rgb(19,126,255); font-weight:bold;"></td></tr>
			<tr><td style="'.$familiaLetra.'; padding-top:20px; font-size:20px; color:rgb(46,78,158);font-weight:bold;">CITA MÉDICA PROGRAMADA</td></tr>
			<tr><td style="'.$familiaLetra.'; padding-top:20px; font-size:14px;color:#2a2a2a;">Estimad@,</tr>
			<tr><td style="'.$familiaLetra.'; padding-top:30px; font-size:14px;color:#2a2a2a;">Información de cita médica:<br><br>Fecha de atención:  '.$datos['fechaCita'].'<br><br>Hora: '.$datos['horaCita'].'<br><br>Nombre Médico/a: '.$datos['nombreMedico'].'<br><br>Dirección de Atención: '.$datos['direccionMedico'].'</td></tr>
			<tr><td style="'.$familiaLetra.'; padding-top:30px; font-size:14px;color:#2a2a2a;">Recuerde qué también esta información lo tiene disponible en:<br> <span style="color:rgb(46,78,158); font-weight:bold;">Sistema GUIA</span> >>> <span style="color:rgb(46,78,158); font-weight:bold;">OPCION MIS DATOS</span> >>> <span style="color:rgb(46,78,158); font-weight:bold;">CITA MÉDICA IESS(Accidentes laborables).</span> </td></tr>
			<tr><td style="'.$familiaLetra.'; padding-top:30px; font-size:14px;color:#2a2a2a;"><span style="'.$familiaLetra.'; padding-top:20px; font-size:14px; color:rgb(204,41,44);font-weight:bold;" >NOTA: </span>Este correo fue generado automaticamente por el sistema GUIA, por favor no responder a este mensaje. </td></tr>
			</tbody></table>';
	$destinatario = array();
	$mailDestino='';
	if($fila['mail_institucional']!= ''){
		array_push($destinatario, $fila['mail_institucional']);
		$mailDestino=$fila['mail_institucional'];
	}else if($fila['mail_personal'] !=''){
		array_push($destinatario, $fila['mail_personal']);
		$mailDestino=$fila['mail_personal'];
	}
	
	
	//--------------------------crear cita medica---------------------------------
	$cai->guardarCitaMedica($conexion,$datos['solicitud'],$datos['fechaCita'],$datos['horaCita'],
			$datos['nombreMedico'],$datos['direccionMedico'],$archivo,$mailDestino);
	//---------------------------actualizar estado del registro--------------------
	$cai->actualizarRegistroSso($conexion,$datos['solicitud'],'creado','',3);
	
	if($mailDestino != ''){
	//----------------guardar correo para proceso automatico-----------------------
	$codigoModulo = 'PRG_INVEST_AI';
	$tablaModulo = 'g_investigacion_accidente_incidente.cita_medica';
		
	$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo, $datos['solicitud']);
	$idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
		
	$cMail->guardarDestinatario($conexion, $idCorreo, $destinatario);
	}	
	//$cMail->guardarDocumentoAdjunto($conexion, $idCorreo, $adjuntos);
		
	//$adjuntos = array();
	//array_push($adjuntos, $rutaArchivoFirmado, $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/'.$salidaReporte);
		
	$conexion->ejecutarConsulta("commit;");
	$mensaje['estado'] = 'exito';
	$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
	echo json_encode($mensaje);

} catch (Exception $e) {
	$conexion->ejecutarConsulta("rollback;");
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = "Error al ejecutar sentencia".$e;
	echo json_encode($mensaje);
} finally {
	$conexion->desconectar();
}

?>
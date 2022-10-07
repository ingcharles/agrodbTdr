<?php
require 'class.phpmailer.php';
require 'class.smtp.php';
require 'ControladorComplementos.php';

class ControladorMail{

	public function enviarMail($conexion, $destinatarios,$asunto, $cuerpoMensaje, $adjuntos=null){
		
		$correo = $this->obtenerCorreoEnvio($conexion);
		
		$cc = new ControladorComplementos();
		$clave = $cc->desencriptarClave($correo['clave'],$correo['direccion_correo']);

		$mail = new PHPMailer(true); 
		$mail->IsSMTP();   
		try {                                   
			$mail->Host = 'mail.agrocalidad.gob.ec';             
			$mail->Port = 587;                                   
			$mail->SMTPAuth = true;                              
			$mail->Username = $correo['direccion_correo'];         
			$mail->Password = $clave;                       
			$mail->SMTPSecure = 'tls';                          
			$mail->SetFrom($correo['direccion_correo'], 'Sistema integrado G.U.I.A. AGROCALIDAD');
			
			for ($i=0; $i<count($destinatarios); $i++){
				$mail->AddAddress($destinatarios[$i]); 
			}
				
			$mail->IsHTML(true);                                
			$mail->CharSet='UTF-8';
			$mail->Priority=1;									
		
			$mail->Subject = 'Ambiente de pruebas. '.$asunto;
			$mail->Body    = 'Este mensaje ha sido generado en el ambiente de pruebas de AGROCALIDAD. '.$cuerpoMensaje;
			
			for ($i=0; $i<count($adjuntos); $i++){
				$mail->AddAttachment($adjuntos[$i]);
			}
		
			$mail->Send();

			$this->agregarSecuenciaDetalleCorreo($conexion, $correo);			
		
			return 'Mail enviado.';
		}catch (phpmailerException $e){
			return $e->errorMessage();
		}catch (Exception $e){
			return $e->getMessage();
		} 
	}
	
	public function guardarCorreo($conexion,$asunto,$cuerpo,$estado,$codigoModulo,$tablaModulo,$idSolicitudTabla){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_correos.correos(asunto, cuerpo, estado,  codigo_modulo,tabla_modulo, id_solicitud_tabla)
				VALUES ('$asunto', $$$cuerpo$$, '$estado', '$codigoModulo', '$tablaModulo','$idSolicitudTabla') RETURNING id_correo ;");
		return $res;
	}
	
	public function guardarDestinatario($conexion,$idCorreo,$destinatarioCorreo){	
		for ($i=0; $i<count($destinatarioCorreo); $i++){			
			$res = $conexion->ejecutarConsulta("INSERT INTO g_correos.destinatarios(id_correo, destinatario_correo)
					VALUES ($idCorreo, '$destinatarioCorreo[$i]');");			
		}
	}
	
	public function guardarDocumentoAdjunto($conexion,$idCorreo,$rutaDocumentoAdjunto){	
		for ($i=0; $i<count($rutaDocumentoAdjunto); $i++){			
			$res = $conexion->ejecutarConsulta("INSERT INTO g_correos.documentos_adjuntos(id_correo, ruta_documento_adjunto)
					VALUES ($idCorreo, '$rutaDocumentoAdjunto[$i]');");			
		}		
	}
	
	public function buscarCorreoPorEnviar($conexion){
		$res = $conexion->ejecutarConsulta("SELECT *
											FROM g_correos.correos
											WHERE estado='Por enviar'
											ORDER BY id_correo desc
											LIMIT 15;");
		return $res;
	}
	
	public function buscarDestinatarios($conexion,$idCorreo){
		$res = $conexion->ejecutarConsulta("SELECT *
											FROM g_correos.destinatarios
											WHERE id_correo='$idCorreo';");
		return $res;
	}
	
	public function buscarDocumentosAdjuntos($conexion,$idCorreo){
		$res = $conexion->ejecutarConsulta("SELECT *
										FROM g_correos.documentos_adjuntos
										WHERE id_correo='$idCorreo';");
		return $res;
	}
	
	public function actualizarCorreoEnviado($conexion,$idCorreo,$estadoMail){
		$res = $conexion->ejecutarConsulta("UPDATE g_correos.correos
											SET  estado='$estadoMail', fecha_envio='now()'
											WHERE id_correo='$idCorreo';");
		return $res;
	}

	public function actualizarEstadoMailTablaSolicitud($conexion,$tabla,$campoActualizar,$valorActualizar,$campoIdSolicitud,$idSolicitud){			
		$res = $conexion->ejecutarConsulta("UPDATE ". $tabla ."
											SET ".$campoActualizar."='".$valorActualizar."'
											WHERE ".$campoIdSolicitud."=".$idSolicitud.";");

		return $res;
	}
	
	public function buscarIngresoPrevioMail($conexion, $codigoModulo, $tablaModulo, $idOperadorTipoOperacion) {	
		$consulta = "SELECT * FROM g_correos.correos WHERE codigo_modulo = '$codigoModulo' and tabla_modulo = '$tablaModulo' and id_solicitud_tabla = '$idOperadorTipoOperacion';";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function obtenerCorreoEnvio($conexion){

		$consulta = "SELECT * FROM g_correos.detalle_registro_correos WHERE id_detalle_registro_correo = (SELECT max(id_detalle_registro_correo) FROM g_correos.detalle_registro_correos)";

		$detalleCorreo = pg_fetch_assoc($conexion->ejecutarConsulta($consulta));

		$consulta = "SELECT * FROM g_correos.registro_correos WHERE id_registro_correo = '" . $detalleCorreo['id_registro_correo'] . "'";

		$correo = pg_fetch_assoc($conexion->ejecutarConsulta($consulta));

		if ($detalleCorreo['secuencial'] == $correo['cantidad_envio']){

			$consulta = "SELECT secuencial + 1 as secuencial FROM g_correos.registro_correos WHERE id_registro_correo = '" . $correo['id_registro_correo'] . "'";

			$secuencialCorreo = pg_fetch_assoc($conexion->ejecutarConsulta($consulta));
			
			$consulta = "SELECT * FROM g_correos.registro_correos WHERE secuencial = '" . $secuencialCorreo['secuencial'] . "' and estado = 'Activo'";
			
			$nuevoCorreo = $conexion->ejecutarConsulta($consulta);

			if (pg_num_rows($nuevoCorreo) != 0){
				$correo = pg_fetch_assoc($nuevoCorreo);
			}else{
				$consulta = "SELECT * FROM g_correos.registro_correos WHERE id_registro_correo = (SELECT min(id_registro_correo) FROM g_correos.registro_correos WHERE estado = 'Activo')";
				$correo = pg_fetch_assoc($conexion->ejecutarConsulta($consulta));
			}
		}

		return $correo;
	}
	
	public function agregarSecuenciaDetalleCorreo($conexion, $correo){
		
		$consulta = "SELECT * FROM g_correos.detalle_registro_correos WHERE id_detalle_registro_correo = (SELECT max(id_detalle_registro_correo) FROM g_correos.detalle_registro_correos WHERE id_registro_correo = '" . $correo['id_registro_correo'] . "')";
		
		$detalleCorreo = pg_fetch_assoc($conexion->ejecutarConsulta($consulta));
		
		if($detalleCorreo['secuencial'] == $correo['cantidad_envio']){
			$secuencia = 1;
		}else{
			$secuencia = $detalleCorreo['secuencial']+1;
		}
		
		$consulta = "INSERT INTO g_correos.detalle_registro_correos(id_registro_correo, secuencial) VALUES ('" . $correo['id_registro_correo'] . "', '" . $secuencia . "')";
		
		$conexion->ejecutarConsulta($consulta);
		
	}
}

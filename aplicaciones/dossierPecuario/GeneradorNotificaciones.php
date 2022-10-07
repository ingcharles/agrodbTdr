<?php
session_start();

require_once '../../clases/ControladorMail.php';

require_once '../../clases/ControladorEnsayoEficacia.php';
require_once '../../clases/ControladorDossierPecuario.php';



class GeneradorNotificaciones{

	public function generarNotificacionSanidadAnimal($conexion,$controladorEficacia,$controladorPecuario,$id_tramite,$id_solicitud,$asunto,$destinatario,$adjuntos){

		$mensaje=array();
		$mensaje['mensaje'] = 'Notificación no enviada';
		$mensaje['estado'] = 'error';

		$cMail=new ControladorMail();
		
		$fechaActual=new DateTime();

		$cuerpoMensaje=$controladorPecuario->redactarNotificacionEmailPC($conexion,$id_tramite,$fechaActual->format('Y-m-d H:i'),$asunto);

		$codigoModulo = 'PRG_DOSSIER_PEC';
		$tablaModulo = '';
		$idSolicitudTabla='';	

		try{
			$conexion->Begin();
			$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo,$idSolicitudTabla);
			$idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
			$cMail->guardarDestinatario($conexion, $idCorreo, $destinatario);
			$cMail->guardarDocumentoAdjunto($conexion, $idCorreo, $adjuntos);

			$mensaje['mensaje'] = 'Notificación enviada';
			$mensaje['estado'] = 'exito';
			$conexion->Commit();
		}
		catch(Exception $e){
			$conexion->Rollback();
		}

		return $mensaje;

	}

}

?>



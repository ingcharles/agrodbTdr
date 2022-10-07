<?php
session_start();

require_once '../../clases/ControladorMail.php';

class GeneradorProcesoPecuario{

	public function generarTerminacionFlujo($conexion,$ensayo,$dossier,$idTramite,$idTramiteFlujo,$datosDocumento,$adjuntos,$destinatarios,$observacion,$retraso,$codigoTerminacion,$fecha){
		$mensaje=array();
		$asuntoCorreo=$observacion;
		
		try{
			$conexion->Begin();
			
			$ensayo->terminarTramiteFlujo($conexion,$idTramite,$idTramiteFlujo,$observacion,$retraso,$codigoTerminacion,$fecha);
			$dossier -> guardarSolicitud($conexion,$datosDocumento);
			$this->enviarCorreo($conexion,$dossier,$idTramite,$asuntoCorreo,$adjuntos,$destinatarios);

			$conexion->Commit();
			$datoMensaje['resultado']=$codigoTerminacion;
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $datoMensaje;
		}catch(Exception $e){
			$conexion->Rollback();
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'Error al procesar la finalizacion del trámite';
		}
		return $mensaje;
	}

	public function enviarCorreo($conexion,$dossier,$idTramite,$asuntoCorreo,$adjuntos,$destinatarios){
		$fechaActual=new DateTime();
		$cuerpoMensaje=$dossier->redactarNotificacionEmailPC($conexion,$idTramite,$fechaActual->format('Y-m-d H:i'),$asuntoCorreo);
		array_push($destinatarios,$cuerpoMensaje['datos']['email_representante_legal']);				
		$codigoModulo = 'PRG_DOSSIER_PEC';
		$tablaModulo = '';
		$idSolicitudTabla='';
		$cMail=new ControladorMail();
		$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asuntoCorreo, $cuerpoMensaje['mensaje'], 'Por enviar', $codigoModulo, $tablaModulo,$idSolicitudTabla);
		$idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
		$cMail->guardarDestinatario($conexion, $idCorreo, $destinatarios);
		$cMail->guardarDocumentoAdjunto($conexion, $idCorreo, $adjuntos);
	}
	
	
}

?>



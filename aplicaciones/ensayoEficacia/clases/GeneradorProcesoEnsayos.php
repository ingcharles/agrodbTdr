<?php
session_start();

require_once '../../clases/ControladorMail.php';

class GeneradorProcesoEnsayos{

	//public function generarTerminacionFlujo($conexion,$ensayo,$dossier,$idTramite,$idTramiteFlujo,$datosDocumento,$adjuntos,$destinatarios,$observacion,$retraso,$codigoTerminacion,$fecha){
	//   $mensaje=array();
	//   $asuntoCorreo=$observacion;
	//   //$datosDocumento['estado']='rechazado';
	//   try{
	//      $conexion->Begin();
			
	//      $ensayo->terminarTramiteFlujo($conexion,$idTramite,$idTramiteFlujo,$observacion,$retraso,$codigoTerminacion,$fecha);
	//      $dossier -> guardarSolicitud($conexion,$datosDocumento);
	//      $this->enviarCorreo($conexion,$dossier,$idTramite,$asuntoCorreo,$adjuntos,$destinatarios);

	//      $conexion->Commit();
	//      $datoMensaje['resultado']=$codigoTerminacion;
	//      $mensaje['estado'] = 'exito';
	//      $mensaje['mensaje'] = $datoMensaje;
	//   }catch(Exception $e){
	//      $conexion->Rollback();
	//      $mensaje['estado'] = 'error';
	//      $mensaje['mensaje'] = 'Error al procesar la finalizacion del trámite';
	//   }
	//   return $mensaje;
	//}

	//public function enviarCorreo($conexion,$dossier,$idTramite,$asuntoCorreo,$adjuntos,$destinatarios){
	//   $fechaActual=new DateTime();
	//   $cuerpoMensaje=$dossier->redactarNotificacion($conexion,$idTramite,$fechaActual->format('Y-m-d H:i'),$asuntoCorreo);
	//   array_push($destinatarios,$cuerpoMensaje['datos']['email_representante_legal']);				
	//   $codigoModulo = 'PRG_ENSAYO_EFI';
	//   $tablaModulo = '';
	//   $idSolicitudTabla='';
	//   $cMail=new ControladorMail();
	//   $qGuardarCorreo=$cMail->guardarCorreo($conexion, $asuntoCorreo, $cuerpoMensaje['mensaje'], 'Por enviar', $codigoModulo, $tablaModulo,$idSolicitudTabla);
	//   $idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
	//   $cMail->guardarDestinatario($conexion, $idCorreo, $destinatarios);
	//   $cMail->guardarDocumentoAdjunto($conexion, $idCorreo, $adjuntos);
	//}

	
	public function enviarCorreoPersonal($conexion,$dossier,$nombreDestino,$expediente,$producto,$asuntoCorreo,$adjuntos,$destinatarios,$asuntoAdisional,$empresaDestino=null){
		$fechaActual=new DateTime();
		$cuerpoMensaje=$dossier->obtenerFormatoNotificacion($nombreDestino,$fechaActual->format('Y-m-d H:i'),$expediente,$producto,$asuntoAdisional,$empresaDestino=null);
		
		$codigoModulo = 'PRG_ENSAYO_EFI';
		$tablaModulo = '';
		$idSolicitudTabla='';
		$cMail=new ControladorMail();
		$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asuntoCorreo, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo,$idSolicitudTabla);
		$idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
		$cMail->guardarDestinatario($conexion, $idCorreo, $destinatarios);
		$cMail->guardarDocumentoAdjunto($conexion, $idCorreo, $adjuntos);
	}
	
}

?>



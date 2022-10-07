<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorImportaciones.php';
require_once 'crearReporteRequisitosImportacion.php'; //Generacion de documento pdf con requisitos por producto

try{
	$inspector = htmlspecialchars ($_POST['inspector'],ENT_NOQUOTES,'UTF-8');
	$idImportacion = ($_POST['idSolicitud']);
	$resultado = htmlspecialchars ($_POST['resultado'],ENT_NOQUOTES,'UTF-8');
	$tipoSolicitud = htmlspecialchars ($_POST['tipoSolicitud'],ENT_NOQUOTES,'UTF-8');
	$tipoInspector = htmlspecialchars ($_POST['tipoInspector'],ENT_NOQUOTES,'UTF-8');
	$observacion = htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8');
	$transaccion = htmlspecialchars ($_POST['transaccion'],ENT_NOQUOTES,'UTF-8');
	$idOperador = htmlspecialchars ($_POST['idOperador'],ENT_NOQUOTES,'UTF-8');
		
	try {
		$conexion = new Conexion();
		$ci = new ControladorImportaciones();
		
		//Buscar asignación para actualizacion
		$inspectorFinancieroAsignado = $ci->listarInspectoresAsignados($conexion, $idImportacion, $tipoSolicitud, $tipoInspector);
		
		//Guarda inspector financiero, fecha, resultado, observación y transaccion
		$ci->guardarDatosResultadoFinanciero($conexion, pg_fetch_result($inspectorFinancieroAsignado, 0, 'id_asignacion'), $inspector, $resultado, $observacion, $transaccion);
		
		//Asigna el resultado de revisión de pago de solicitud de importacion
		$res= $ci->enviarImportacion($conexion, $idImportacion, $resultado);
		
		//Asignar estado a productos de solicitud
		$ci->enviarProductosImportacion($conexion, $idImportacion, $resultado);
		
		//Asignar fecha de vigencia de solicitud
		$ci->enviarFechaVigenciaImportacion($conexion, $idImportacion);
		
		//Revisar fechas de vigencia, actual 90 dias revisar y crear proceso parasolicitudde ampliacion que
		//despues de aprobado se extiende 90 dias desde la fecha de solicitud de ampliacion
		
		if($resultado == 'aprobado'){
			//CREACION DEL PDF CON REQUISITOS POR PRODUCTO
			$pdf = new PDF();
			$pdf->AliasNbPages();
			$pdf->AddPage();
			$pdf->Body($idImportacion);
			$pdf->SetFont('Times','',12);
			$pdf->Output("archivosRequisitos/".$idOperador."-".$idImportacion.".pdf");
		
			$informeRequisitos = "aplicaciones/importaciones/archivosRequisitos/".$idOperador."-".$idImportacion.".pdf";
		
			//Actualizar registro
			$ci->asignarDocumentoRequisitosImportacion($conexion, $idImportacion, $informeRequisitos);
		}
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'La operación se ha guardado satisfactoriamente';
		
		$conexion->desconectar();
		echo json_encode($mensaje);
		
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>
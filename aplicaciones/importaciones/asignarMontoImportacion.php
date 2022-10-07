<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorImportaciones.php';
require_once 'crearReporteRequisitosImportacion.php';

try{
	$inspector = htmlspecialchars ($_POST['inspector'],ENT_NOQUOTES,'UTF-8');
	$idImportacion = ($_POST['idSolicitud']);
	$tipoSolicitud = ($_POST['tipoSolicitud']);
	$tipoInspector = ($_POST['tipoInspector']);
	$idImportacion = ($_POST['idSolicitud']);
	$monto = htmlspecialchars ($_POST['monto'],ENT_NOQUOTES,'UTF-8');
		
	try {
		$conexion = new Conexion();
		$ci = new ControladorImportaciones();
		
		//Verifica si la operación está asignada a un inspector financiero, caso contrario se asigna a la persona que realiza la inspección
		$inspectorFinancieroAsignado = $ci->listarInspectoresAsignados($conexion, $idImportacion, $tipoSolicitud, $tipoInspector);
		
		if(pg_num_rows($inspectorFinancieroAsignado)==0){
			$inspectorFinancieroAsignado= $ci->guardarNuevoInspector($conexion, $idImportacion, $inspector, $inspector, $tipoSolicitud, $tipoInspector);
			$res= $ci->enviarImportacion($conexion, $idImportacion, 'verificacion');
		}
		
		//Asigna el monto a pagar por la solicitud de importacion
		$ci->asignarMontoImportacion($conexion, pg_fetch_result($inspectorFinancieroAsignado, 0, 'id_asignacion'), $inspector, $monto);
			
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
<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorImportaciones.php';
require_once 'crearReporteRequisitosImportacion.php';

try{
	$inspector = htmlspecialchars ($_POST['inspector'],ENT_NOQUOTES,'UTF-8');
	$idImportacion = ($_POST['idSolicitud']);
	$resultadoDocumento = htmlspecialchars ($_POST['resultadoDocumento'],ENT_NOQUOTES,'UTF-8');
	$observacionesDocumento = htmlspecialchars ($_POST['observacionDocumento'],ENT_NOQUOTES,'UTF-8');
	$tipoSolicitud = htmlspecialchars ($_POST['tipoSolicitud'],ENT_NOQUOTES,'UTF-8');
	$tipoInspector = htmlspecialchars ($_POST['tipoInspector'],ENT_NOQUOTES,'UTF-8');
	
	try {
		$conexion = new Conexion();
		$ci = new ControladorImportaciones();
		
		//Verifica si la operación está asignada a un inspector, caso contrario se asigna a la persona que realiza la inspección
		//$inspectorAsignado = $ci->listarInspectoresAsignados($conexion, $idImportacion, $tipoSolicitud, $tipoInspector);
		$inspectorAsignado = $ci->buscarInspectorAsignado($conexion, $idImportacion, $inspector, $tipoSolicitud, $tipoInspector);
		
		
		if(pg_num_rows($inspectorAsignado)==0){
			$inspectorAsignado= $ci->guardarNuevoInspector($conexion, $idImportacion, $inspector, $inspector, $tipoSolicitud, $tipoInspector);
			$res= $ci->enviarImportacion($conexion, $idImportacion, 'asignado');
		}
		
		//Guarda inspector, calificación y fecha para inspeccion documental
		$ci->guardarDatosInspeccionDocumental($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_asignacion'), $inspector, $observacionesDocumento, $resultadoDocumento);
		
		//Guardar resultado solicitud (cambio de estado)
		$ci->enviarImportacion($conexion, $idImportacion, $resultadoDocumento);
			
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
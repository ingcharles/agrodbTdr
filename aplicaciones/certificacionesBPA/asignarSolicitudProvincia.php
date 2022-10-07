<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificacionBPA.php';

$idSolicitud= $_POST['idSolicitud'];
$provincia= $_POST['provincia'];

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try {
	
	$conexion = new Conexion();
	$cbpa = new ControladorCertificacionBPA();
	
	try{		
		$conexion->ejecutarConsulta("begin;");
		$cbpa->actualizarProvinciaSolicitud($conexion, $idSolicitud, $provincia);
		$conexion->ejecutarConsulta("commit;");
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido actualizados.';

	} catch (Exception $ex) {
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] = $ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError;
	} finally {
		$conexion->desconectar();
	}
} catch (Exception $ex) {
	$mensaje['mensaje'] = $ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
} finally {
	echo json_encode($mensaje);
}
?>
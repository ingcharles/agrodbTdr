<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicacionesPerfiles.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$conexion = new Conexion();
	$cap = new ControladorAplicacionesPerfiles();
	$idAccion = htmlspecialchars ($_POST['idAccion'],ENT_NOQUOTES,'UTF-8');
	
	try {
		
		$conexion->ejecutarConsulta("begin;");
		$cap->eliminarAccion($conexion, $idAccion);
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idAccion;
		$conexion->ejecutarConsulta("commit;");
		
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
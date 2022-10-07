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

	$nombre = htmlspecialchars ($_POST['nombrePerfil'],ENT_NOQUOTES,'UTF-8');
	$idPerfil = htmlspecialchars ($_POST['idPerfil'],ENT_NOQUOTES,'UTF-8');
	$estado = htmlspecialchars ($_POST['estadoPerfil'],ENT_NOQUOTES,'UTF-8');
	$codificacion = htmlspecialchars ($_POST['codificionPerfil'],ENT_NOQUOTES,'UTF-8');
	
	try {
		
		$cap->actualizarPerfil($conexion, $idPerfil, $nombre, $estado, $codificacion);
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
	
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
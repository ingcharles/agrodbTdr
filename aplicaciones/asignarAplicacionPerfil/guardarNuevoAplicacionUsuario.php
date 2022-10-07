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
	
	$idAplicacion = htmlspecialchars ($_POST['aplicacion'],ENT_NOQUOTES,'UTF-8');
	$cantidadNotificacion = htmlspecialchars ($_POST['cantidadNotificaciones'],ENT_NOQUOTES,'UTF-8');
	$mensajeNotificacion = htmlspecialchars ($_POST['mensajeNotificaciones'],ENT_NOQUOTES,'UTF-8');
	$identificador = htmlspecialchars ($_POST['identificadorUsuario'],ENT_NOQUOTES,'UTF-8');
	$nombreAplicacion = htmlspecialchars ($_POST['nombreAplicacion'],ENT_NOQUOTES,'UTF-8');
		
	try {
	
		if(pg_num_rows($cap->buscarAplicacionUsuario($conexion, $idAplicacion, $identificador))==0){
			$conexion->ejecutarConsulta("begin;");
			$cap->guardarNuevoAplicacionRegistrada($conexion, $idAplicacion, $identificador, $cantidadNotificacion, $mensajeNotificacion);
			$conexion->ejecutarConsulta("commit;");
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cap->imprimirLineaAplicacionesUsuario($identificador, $idAplicacion, $nombreAplicacion, $cantidadNotificacion, $mensajeNotificacion);
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "El usuario ya cuenta con el modulo a ser asignado.";
		}
		
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
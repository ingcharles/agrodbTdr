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
	$idAplicacion = htmlspecialchars ($_POST['idAplicacion'],ENT_NOQUOTES,'UTF-8');
	$identificador = htmlspecialchars ($_POST['identificacionUsuario'],ENT_NOQUOTES,'UTF-8');
	
	try {
		if(pg_num_rows(	$cap->buscarPerfilAplicacionesUsuario($conexion, $idAplicacion, $identificador))==0){
			$conexion->ejecutarConsulta("begin;");
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $idAplicacion;
			$cap->eliminarAplicacionRegistrada($conexion,$idAplicacion,$identificador);
			$conexion->ejecutarConsulta("commit;");
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "La aplicacion cuenta con perfiles asignados a este usuario.";
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
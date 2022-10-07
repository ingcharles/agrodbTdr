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
	
	$idPerfil = htmlspecialchars ($_POST['perfil'],ENT_NOQUOTES,'UTF-8');
	$identificador = htmlspecialchars ($_POST['identificadorUsuario'],ENT_NOQUOTES,'UTF-8');
	$nombrePerfil = htmlspecialchars ($_POST['nombrePerfil'],ENT_NOQUOTES,'UTF-8');
	try {
		if(pg_num_rows($cap->buscarPerfilUsuario($conexion, $idPerfil, $identificador))==0){
			$conexion->ejecutarConsulta("begin;");
			$cap->guardarNuevoPefilUsuario($conexion, $idPerfil, $identificador);
			$conexion->ejecutarConsulta("commit;");
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cap->imprimirLineaPerfilesUsuario($identificador, $idPerfil, $nombrePerfil);
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "El usuario ya cuenta con el perfil a ser asignado.";
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
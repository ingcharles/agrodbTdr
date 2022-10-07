<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicacionesPerfiles.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$cap = new ControladorAplicacionesPerfiles();
	$idPerfil = htmlspecialchars ($_POST['idPerfil'],ENT_NOQUOTES,'UTF-8');
	$idAplicacion = htmlspecialchars ($_POST['idAplicacion'],ENT_NOQUOTES,'UTF-8');
	$conexion = new Conexion();

	try {
		if(pg_num_rows($cap->buscarAccionesPerfilesXidAplicacion($conexion, $idAplicacion, $idPerfil))==0){
			$conexion->ejecutarConsulta("begin;");
			$cap->eliminarPerfil($conexion, $idPerfil);
			$conexion->ejecutarConsulta("commit;");
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $idPerfil;
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "El perfil cuenta con acciones asignadas.";
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
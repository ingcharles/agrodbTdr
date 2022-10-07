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
	$estado = htmlspecialchars ($_POST['estadoPerfil'],ENT_NOQUOTES,'UTF-8');
	$codificacion= htmlspecialchars ($_POST['codificacionPerfil'],ENT_NOQUOTES,'UTF-8');
	$idAplicacion = htmlspecialchars ($_POST['idAplicacion'],ENT_NOQUOTES,'UTF-8');

	try {
		$conexion->ejecutarConsulta("begin;");
		$idPerfil=pg_fetch_row($cap->guardarNuevoPerfil($conexion, $idAplicacion, $nombre, $estado, $codificacionPerfil));
		$conexion->ejecutarConsulta("commit;");
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $cap->imprimirLineaPerfilesAplicacion($idPerfil[0], $nombre, $estado, $codificacion,$idAplicacion);
		
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
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
	$nombre = htmlspecialchars ($_POST['nombreAplicacion'],ENT_NOQUOTES,'UTF-8');
	$version = htmlspecialchars ($_POST['versionAplicacion'],ENT_NOQUOTES,'UTF-8');
	$ruta = htmlspecialchars ($_POST['rutaAplicacion'],ENT_NOQUOTES,'UTF-8');
	$color = htmlspecialchars ($_POST['colorAplicacion'],ENT_NOQUOTES,'UTF-8');
	$codificacion = htmlspecialchars ($_POST['codificacionAplicacion'],ENT_NOQUOTES,'UTF-8');
	$estado = htmlspecialchars ($_POST['estadoAplicacion'],ENT_NOQUOTES,'UTF-8');
	$descripcion = htmlspecialchars ($_POST['descripcionAplicacion'],ENT_NOQUOTES,'UTF-8');
	
	try {
		
		$cap->actualizarAplicacion($conexion, $idAplicacion, $nombre, $version, $ruta, $descripcion, $color, $codificacion, $estado);
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';
	
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
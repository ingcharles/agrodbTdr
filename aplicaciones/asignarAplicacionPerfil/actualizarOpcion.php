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
	
	$idOpcion = htmlspecialchars ($_POST['idOpcion'],ENT_NOQUOTES,'UTF-8');
	$nombre = htmlspecialchars ($_POST['nombreOpcion'],ENT_NOQUOTES,'UTF-8');
	$pagina = htmlspecialchars ($_POST['paginaOpcion'],ENT_NOQUOTES,'UTF-8');
	$orden = htmlspecialchars ($_POST['ordenOpcion'],ENT_NOQUOTES,'UTF-8');
	$estilo = htmlspecialchars ($_POST['estiloOpcion'],ENT_NOQUOTES,'UTF-8');
	$idAplicacion = htmlspecialchars ($_POST['idAplicacion'],ENT_NOQUOTES,'UTF-8');

	try {
		
		$cap->actualizarOpcion($conexion, $idOpcion, $nombre, $estilo, $pagina, $orden);
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
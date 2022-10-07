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
	
	$estilo = htmlspecialchars ($_POST['estiloAccion'],ENT_NOQUOTES,'UTF-8');
	$idAccion = htmlspecialchars ($_POST['idAccion'],ENT_NOQUOTES,'UTF-8');
	$descripcion = htmlspecialchars ($_POST['descripcionAccion'],ENT_NOQUOTES,'UTF-8');
	$pagina = htmlspecialchars ($_POST['paginaAccion'],ENT_NOQUOTES,'UTF-8');
	$orden = htmlspecialchars ($_POST['ordenAccion'],ENT_NOQUOTES,'UTF-8');
	
	try {
		
		$cap->actualizarAccion($conexion, $idAccion, $descripcion, $estilo, $pagina, $orden);
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
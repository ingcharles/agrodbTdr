<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorLotes.php';

$idRegistro = htmlspecialchars($_POST['idRegistro'], ENT_NOQUOTES, 'UTF-8');
$cantidad = htmlspecialchars($_POST['cantidad'], ENT_NOQUOTES, 'UTF-8');
$idUnidad = htmlspecialchars($_POST['unidad'], ENT_NOQUOTES, 'UTF-8');
$nUnidad = htmlspecialchars($_POST['codigoUnidad'], ENT_NOQUOTES, 'UTF-8');
$operador= $_SESSION['usuario'];



try {
	$conexion = new Conexion();
	$cl = new ControladorLotes();
	
	try {
		
		$mensaje = array();
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = 'Ha ocurrido un error!';
		
		$conexion->ejecutarConsulta("begin;");
		
		$cl->actualizarRegistro($conexion,$idRegistro, $cantidad,$idUnidad,$nUnidad);
		
		$conexion->ejecutarConsulta("commit;");
			
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = "Los datos han sido guardados satisfactoriamente";
		
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

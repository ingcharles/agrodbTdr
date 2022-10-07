<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';

$idCatalogo= htmlspecialchars($_POST['txtIdCatalogo'], ENT_NOQUOTES, 'UTF-8');
$nombre = htmlspecialchars ($_POST['txtNombreCatalogo'],ENT_NOQUOTES,'UTF-8');

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try {
	
	$conexion = new Conexion();
	$cac = new ControladorAdministrarCatalogos();
	
	try{		
		
		$cac->actualizarNombreCatalogo($conexion,$idCatalogo,$nombre);
		
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



?>


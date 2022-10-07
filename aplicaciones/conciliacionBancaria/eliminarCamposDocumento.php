<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorConciliacionBancaria.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {
	$conexion = new Conexion ();
	$cc = new ControladorCatalogos();
	$cb = new ControladorConciliacionBancaria();

	$idCampoDocumento = $_POST['idCampoDocumento'];	

	try {
	
		$conexion->ejecutarConsulta("begin;");
	
		$cb -> eliminarCampoDocumento($conexion, $idCampoDocumento);

		$conexion->ejecutarConsulta("commit;");


		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idCampoDocumento;
	
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
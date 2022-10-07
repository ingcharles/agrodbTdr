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

	$opcion = $_POST['opcion'];	
	
	switch ($opcion){
		case "catalogoCampoCabeceraTrama":						
			$idCatalogoCampoCabeceraTrama = $_POST['idCatalogoCampoCabeceraTrama'];
		break;
		case "catalogoCampoDetalleTrama":
			$idCatalogoCampoDetalleTrama = $_POST['idCatalogoCampoDetalleTrama'];
		break;
	}
	

	try {
	
		$conexion->ejecutarConsulta("begin;");
	
		switch ($opcion){
			case "catalogoCampoCabeceraTrama":
				$cb -> eliminarCatalogoCampoCabeceraTrama($conexion, $idCatalogoCampoCabeceraTrama);
			break;			
			case "catalogoCampoDetalleTrama":
				$cb -> eliminarCatalogoCampoDetalleTrama($conexion, $idCatalogoCampoDetalleTrama);
			break;
		}

		$conexion->ejecutarConsulta("commit;");
	
		switch ($opcion){
			case "catalogoCampoCabeceraTrama":
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = $idCatalogoCampoCabeceraTrama;
			break;
			case "catalogoCampoDetalleTrama":
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = $idCatalogoCampoDetalleTrama;
			break;
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
	
	
?>
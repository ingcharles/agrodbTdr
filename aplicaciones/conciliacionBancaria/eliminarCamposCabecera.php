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
		case "campoCabeceraTrama":						
			$idCampoCabeceraTrama = $_POST['idCampoCabeceraTrama'];
		break;
		case "campoDetalleTrama":
			$idCampoDetalleTrama = $_POST['idCampoDetalleTrama'];
		break;
	}
	

	try {
	
		$conexion->ejecutarConsulta("begin;");
	
		switch ($opcion){
			case "campoCabeceraTrama":
				$cb -> eliminarCampoCabeceraTrama($conexion, $idCampoCabeceraTrama);
			break;			
			case "campoDetalleTrama":
				$cb -> eliminarCampoDetalleTrama($conexion, $idCampoDetalleTrama);
			break;
		}

		$conexion->ejecutarConsulta("commit;");
	
		switch ($opcion){
			case "campoCabeceraTrama":
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = $idCampoCabeceraTrama;
			break;
			case "campoDetalleTrama":
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = $idCampoDetalleTrama;
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
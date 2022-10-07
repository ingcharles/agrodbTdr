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
			$nombreCampoCabeceraTrama = $_POST['nombreCampoCabeceraTrama'];
			$posicionInicialCampoCabeceraTrama = $_POST['posicionInicialCampoCabeceraTrama'];
			$posicionFinalCampoCabeceraTrama = $_POST['posicionFinalCampoCabeceraTrama'];	
			$longitudSegmentoCampoCabeceraTrama = $_POST['longitudSegmentoCampoCabeceraTrama'];
			$tipoCampoCabeceraTrama = $_POST['tipoCampoCabeceraTrama'];
		break;
		case "campoDetalleTrama": 
			$idCampoDetalleTrama = $_POST['idCampoDetalleTrama'];
			$nombreCampoDetalleTrama = $_POST['nombreCampoDetalleTrama'];
			$posicionInicialCampoDetalleTrama = $_POST['posicionInicialCampoDetalleTrama'];
			$posicionFinalCampoDetalleTrama = $_POST['posicionFinalCampoDetalleTrama'];	
			$longitudSegmentoCampoDetalleTrama = $_POST['longitudSegmentoCampoDetalleTrama'];
			$tipoCampoDetalleTrama = $_POST['tipoCampoDetalleTrama'];
		break;
	}
	

	try {
	
		$conexion->ejecutarConsulta("begin;");
	
		switch ($opcion){

			case "campoCabeceraTrama":
				$cb -> actualizarCampoCabeceraTrama($conexion, $idCampoCabeceraTrama, $nombreCampoCabeceraTrama, $posicionInicialCampoCabeceraTrama, $posicionFinalCampoCabeceraTrama, $longitudSegmentoCampoCabeceraTrama, $tipoCampoCabeceraTrama);
			break;
			case "campoDetalleTrama":
				$cb -> actualizarCampoDetalleTrama($conexion, $idCampoDetalleTrama, $nombreCampoDetalleTrama, $posicionInicialCampoDetalleTrama, $posicionFinalCampoDetalleTrama, $longitudSegmentoCampoDetalleTrama, $tipoCampoDetalleTrama);
			break;
		}

		$conexion->ejecutarConsulta("commit;");
	
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = "Los datos se han guardado correctamente";
	
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
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
		case "registroTrama":
			$idTrama = $_POST['idTrama'];
			$nombreTrama = $_POST['nombreTrama'];
			$separadorTrama = $_POST['separadorTrama'];
			$formatoEntradaTrama = $_POST['formatoEntradaTrama'];
			$formatoSalidaTrama = $_POST['formatoSalidaTrama'];
		break;
		case "cabeceraRegistroTrama":
			$idCabeceraTrama = $_POST['idCabeceraTrama'];
			$codigoSegmentoCabeceraTrama = $_POST['codigoSegmentoCabeceraTrama'];
			$tamanioSegmentoCabeceraTrama = $_POST['tamanioSegmentoCabeceraTrama'];			
		break;
		case "detalleRegistroTrama": 
			$idDetalleTrama = $_POST['idDetalleTrama'];
			$codigoSegmentoDetalleTrama = $_POST['codigoSegmentoDetalleTrama'];
			$tamanioSegmentoDetalleTrama = $_POST['tamanioSegmentoDetalleTrama'];
		break;
	}
	

	try {
	
		$conexion->ejecutarConsulta("begin;");
	
		switch ($opcion){
			case "registroTrama":
				$cb -> actualizarRegistroTrama($conexion, $idTrama, $nombreTrama, $separadorTrama, $formatoEntradaTrama, $formatoSalidaTrama);
			break;
			case "cabeceraRegistroTrama":
				$cb -> actualizarRegistroCabeceraTrama($conexion, $idCabeceraTrama, $codigoSegmentoCabeceraTrama, $tamanioSegmentoCabeceraTrama);
			break;
			case "detalleRegistroTrama":
				$cb -> actualizarRegistroDetalleTrama($conexion, $idDetalleTrama, $codigoSegmentoDetalleTrama, $tamanioSegmentoDetalleTrama);
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
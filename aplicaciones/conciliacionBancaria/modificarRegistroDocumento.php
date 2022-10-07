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

	$idDocumento = $_POST['idDocumento'];
	$nombreDocumento = $_POST['nombreDocumento'];
	$tipoDocumento = $_POST['tipoDocumento'];
	$formatoEntradaDocumento = $_POST['formatoEntradaDocumento'];
	$numeroColumnasDocumento = $_POST['numeroColumnasDocumento'];
	$filaInicioLecturaDocumento = $_POST['filaInicioLecturaDocumento'];
	$columnaInicioLecturaDocumento = $_POST['columnaInicioLecturaDocumento'];

	try {
	
		$conexion->ejecutarConsulta("begin;");
		
		$cb -> actualizarRegistroDocumento($conexion, $idDocumento, $nombreDocumento, $tipoDocumento, $formatoEntradaDocumento, $numeroColumnasDocumento, $filaInicioLecturaDocumento, $columnaInicioLecturaDocumento);

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
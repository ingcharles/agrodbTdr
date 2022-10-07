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

	
	$idRegistroProcesoConciliacion = $_POST['idRegistroProcesoConciliacion'];
	$nombreRegistroProcesoConciliacion = $_POST['nombreRegistroProcesoConciliacion'];
	$facturaRegistroProcesoConciliacion = $_POST['facturaRegistroProcesoConciliacion'];
	$tipoRevisionRegistroProcesoConciliacion = $_POST['tipoRevisionRegistroProcesoConciliacion'];	

	try {
	
		$conexion->ejecutarConsulta("begin;");
	
		$cb -> actualizarRegistroProcesoConciliacion($conexion, $idRegistroProcesoConciliacion, $nombreRegistroProcesoConciliacion, $facturaRegistroProcesoConciliacion, $tipoRevisionRegistroProcesoConciliacion);

		$conexion->ejecutarConsulta("commit;");
	
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = "Los datos se han actualizado correctamente";
	
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
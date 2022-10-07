<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$nombreUnidadEjeDes = $_POST['nombreUnidadEjeDes'];
	$codigoUnidadEjeDes = $_POST['codigoUnidadEjeDes'];
	$idLocalizacion = $_POST['idLocalizacion'];
	$codigoGeografico = $_POST['codigoGeografico'];
	$tipo = $_POST['tipo'];
	$identificador = $_SESSION['usuario'];
		
	try {
		$conexion = new Conexion();
		$cpp = new ControladorProgramacionPresupuestaria();
		
		$conexion->ejecutarConsulta("begin;");
		$cpp->nuevaUnidadEjeDes($conexion, $nombreUnidadEjeDes, $codigoUnidadEjeDes, $tipo, $idLocalizacion, 
											$codigoGeografico, $identificador);
		$conexion->ejecutarConsulta("commit;");
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'La unidad se han guardado correctamente';
		
		$conexion->desconectar();
		
		echo json_encode($mensaje);
		
	} catch (Exception $ex){
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] = $ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError;
		$conexion->desconectar();
	}/* finally {
		$conexion->desconectar();
	}*/
	
} catch (Exception $ex) {
	$mensaje['mensaje'] = $ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
	$conexion->desconectar();
} /*finally {
	echo json_encode($mensaje);
}*/
?>
<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorConciliacionBancaria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$idRegistroProcesoConciliacion = $_POST['idRegistroProcesoConciliacion'];
		
	try {
		$conexion = new Conexion();
		$cb = new ControladorConciliacionBancaria();
		
		$cb->eliminarRegistroProcesoConciliacion($conexion, $idRegistroProcesoConciliacion);
				
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'El registro de proceso de conciliación se ha eliminado satisfactoriamente';
		
		$conexion->desconectar();
		echo json_encode($mensaje);
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>
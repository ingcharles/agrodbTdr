<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorConciliacionBancaria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$idTrama = $_POST['idTrama'];
		
	try {
		$conexion = new Conexion();
		$cb = new ControladorConciliacionBancaria();
		
		$conexion->ejecutarConsulta("begin;");
		
		$cb->eliminarRegistroTrama($conexion, $idTrama);

		$conexion->ejecutarConsulta("commit;");
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'La trama se ha eliminado satisfactoriamente';
		
		$conexion->desconectar();
		echo json_encode($mensaje);
		
	} catch (Exception $ex){
		
		$conexion->ejecutarConsulta("rollback;");		
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
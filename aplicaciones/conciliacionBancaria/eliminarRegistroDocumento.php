<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorConciliacionBancaria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$idDocumento = $_POST['idDocumento'];
		
	try {
		$conexion = new Conexion();
		$cb = new ControladorConciliacionBancaria();
		
		$conexion->ejecutarConsulta("begin;");
		
		$cb->eliminarRegistroDocumento($conexion, $idDocumento);

		$conexion->ejecutarConsulta("commit;");
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'El documento se ha eliminado satisfactoriamente';
		
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
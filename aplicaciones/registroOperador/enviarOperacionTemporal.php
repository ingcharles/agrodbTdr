<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	          
	$operaciones = explode(",",$_POST['idOperaciones']);
	 
	try {
		$conexion = new Conexion();
		$cr = new ControladorRegistroOperador();
		
		foreach ($operaciones as $operacion){
			$cr->enviarOperacion($conexion, $operacion, 'pago');
		}
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = "Las operaciones han sido enviadas";	
			
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
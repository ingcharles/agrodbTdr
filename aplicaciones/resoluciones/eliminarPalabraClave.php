<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorResoluciones.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$palabra = htmlspecialchars ($_POST['palabraClave'],ENT_NOQUOTES,'UTF-8');
	
	try {
		$conexion = new Conexion();
		$cr = new ControladorResoluciones();
		
		$cr->eliminarPalabraClave($conexion, $palabra);
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $palabra;
		
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
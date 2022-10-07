<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFormularios.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
	
	try {
		$conexion = new Conexion();
		$cf = new ControladorFormularios();
		
		$cf->eliminarOpcion($conexion, $opcion);
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $opcion;
		
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
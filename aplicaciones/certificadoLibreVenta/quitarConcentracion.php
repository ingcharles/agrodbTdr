<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorClv.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$idClvComponente = $_POST['idClvConcentracion'];
	
	try {
		$conexion = new Conexion();
		$cr = new ControladorClv();
		
		$cr->eliminarDetalleProductos($conexion, $idClvComponente);
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idClvComponente;
		
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

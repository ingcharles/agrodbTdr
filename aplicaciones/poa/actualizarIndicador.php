<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$id = $_POST['idIndicador'];
	$descripcion = $_POST['descripcion'];
	$lineaBase = $_POST['lineaBase'];
	$metodoCalculo = $_POST['metodoCalculo'];
	$tipo = $_POST['tipo'];
		
	try {
		$conexion = new Conexion();
		$cpoa = new ControladorPAPP();
		
		$cpoa->actualizarIndicador($conexion, $id, $descripcion, $lineaBase, $metodoCalculo, $tipo);
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'El indicador ha sido actualizado satisfactoriamente';
		
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

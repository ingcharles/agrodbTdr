<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$id_planta = $_POST['id_item_planta'];
	$codigo_item = $_POST['codigo_item'];
	$id_presupuesto = $_POST['id_presupuesto'];
	
	try {
		$conexion = new Conexion();
		$cpoa = new ControladorPAPP();
		
		$cpoa->eliminarItemPresupuesto($conexion, $id_planta,$codigo_item, $id_presupuesto);
		//$cpoa->eliminarItemPresupuesto($conexion, $id_planta,$codigo_item);
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'El gasto ha sido eliminado satisfactoriamente';
		
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

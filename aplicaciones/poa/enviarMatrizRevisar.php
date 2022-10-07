<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$id_presupuesto = $_POST['id_presupuesto'];
	$id_item_planta = $_POST['id_item_planta'];
	$codigo_presupuesto = $_POST['id_item_presupuesto'];
	$observacion=$_POST['observacion'];
		
	try {
		$conexion = new Conexion();
		$cpoa = new ControladorPAPP();
		
		$cpoa->actualizarEstadoMatrizPresupuesto($conexion,$codigo_presupuesto, $id_item_planta,1,$observacion,$_SESSION['usuario'], $id_presupuesto);
		//$cpoa->actualizarEstadoMatrizPresupuesto($conexion,$codigo_presupuesto, $id_item_planta,1,$observacion,$_SESSION['usuario']);
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'El registro se ha enviado para la revisión';
		
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


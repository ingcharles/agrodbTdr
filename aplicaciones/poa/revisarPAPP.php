<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$id = $_POST['idPlanta'];
		
	try {
		$conexion = new Conexion();
		$cpoa = new ControladorPAPP();
		
		$cpoa->revisarPAPP($conexion,$id, 'true');
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'El elemento ha sido revisado satisfactoriamente';
		
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
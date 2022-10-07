<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$idItem = $_POST['id_item_planta'];
	$trimestre = $_POST['trimestre'];
	$observacion=$_POST['observacion'];
		
	try {
		$conexion = new Conexion();
		$cpoa = new ControladorPAPP();
		
		$cpoa->revisionSeguimientoTrimestral($conexion, $idItem, $trimestre, $observacion, $_SESSION['usuario'], 1);
		//Revisar para activar notificaciones en el sistema 
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'El registro se ha enviado para revisión';
		
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


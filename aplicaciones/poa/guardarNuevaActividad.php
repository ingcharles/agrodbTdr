<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$id = $_POST['idSubproceso'];
	$descripcion = $_POST['descripcionActividad'];
	$anio = $_POST['anio'];
		
	try {
		$conexion = new Conexion();
		$cpoa = new ControladorPAPP();
		
		$idActividad = $cpoa->nuevaActividad($conexion, $id, $descripcion, $anio);
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $cpoa->imprimirLineaActividad(pg_fetch_result($idActividad, 0, 'id_actividad'), $descripcion);
		
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

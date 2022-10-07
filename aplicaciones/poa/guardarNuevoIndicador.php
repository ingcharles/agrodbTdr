<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$idActividad = $_POST['idActividad'];
	$descripcion = $_POST['descripcionIndicador'];
	$lineaBase = $_POST['lineaBase'];
	$metodoCalculo = $_POST['metodoCalculo'];
	$tipo = $_POST['tipo'];
	$anio = $_POST['anio'];
		
	try {
		$conexion = new Conexion();
		$cpoa = new ControladorPAPP();
		
		$idIndicador = $cpoa->nuevoIndicador($conexion,$descripcion, $idActividad, $lineaBase, $metodoCalculo, $tipo, $anio);
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $cpoa->imprimirLineaIndicador(pg_fetch_result($idIndicador, 0, 'id_indicador'), $descripcion, $lineaBase, $metodoCalculo, $tipo);
		
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

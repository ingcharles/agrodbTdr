<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$nombreArea = $_POST['nombreArea'];
	$idSitio = $_POST['idSitio'];
	try {
		$conexion = new Conexion();
		$cr = new ControladorRegistroOperador();
		
		$areasOperacion = $cr->verificarAreaOperacion($conexion, $nombreArea, $idSitio);
		
		if( pg_num_rows($areasOperacion) > 0){
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'El área está siendo utilizada para una operación y no se puede eliminar.';
		}else{
			$cr->eliminarArea($conexion, $nombreArea, $idSitio);
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'El área ha sido eliminado satisfactoriamente';
		}
		
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

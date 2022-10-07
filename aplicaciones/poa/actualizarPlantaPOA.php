<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$id_Item = $_POST['idItem'];
	$meta1 = $_POST['meta1'];
	$meta2 = $_POST['meta2'];
	$meta3 = $_POST['meta3'];
	$meta4 = $_POST['meta4'];
	
		
	try {
		$conexion = new Conexion();
		$cpoa = new ControladorPAPP();
		
	    $cpoa->actualizarMetaPlanta($conexion,$id_Item, $meta1, $meta2,$meta3,$meta4);
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'El Registro PAPP ha sido actualizado satisfactoriamente';
		
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

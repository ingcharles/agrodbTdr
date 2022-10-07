<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFinanciero.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$idItem = ($_POST['idItem']);
		
	try {
		$conexion = new Conexion();
		$cf = new ControladorFinanciero();
		
		$res = $cf->quitarItem($conexion, $idItem);
		
		
		if(!$res){
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = $conexion->mensajeError;
		}else{
			
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idItem;
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
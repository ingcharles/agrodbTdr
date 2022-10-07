<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFinanciero.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$estadoRequisito = htmlspecialchars ($_POST['estadoRequisito'],ENT_NOQUOTES,'UTF-8');
	$idDescuentoCupo = htmlspecialchars($_POST['idDescuentoCupo'],ENT_NOQUOTES,'UTF-8');
	$idServicio = htmlspecialchars($_POST['idServicio'],ENT_NOQUOTES,'UTF-8');
	
	try {
		$conexion = new Conexion();
		$cf = new ControladorFinanciero();
		
		$cf->actualizarEstadoServicio($conexion, $idDescuentoCupo, $idServicio, $_SESSION['usuario'], $estadoRequisito);
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idDescuentoCupo;
		
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
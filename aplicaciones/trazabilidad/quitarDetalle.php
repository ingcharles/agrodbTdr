<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorTrazabilidad.php';




$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


$detalleId= $_POST['id_detalle_ingreso'];

	try {

		$conexion = new Conexion();
		$cr = new ControladorTrazabilidad();
		$cr->eliminarDetalle($conexion, $detalleId);

		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'El registro ha sido eliminado satisfactoriamente';


		$conexion->desconectar();
		echo json_encode($mensaje);
	} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}


?>

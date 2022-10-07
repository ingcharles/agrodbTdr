<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$idProveedor = $_POST['idProveedor'];
	$idSolicitud = $_POST['idSolicitud'];
	$idPais = $_POST['idPais'];
	
	try {
		$conexion = new Conexion();
		$cr = new ControladorRegistroOperador();
		
		$cr->eliminarProveedor($conexion, $idProveedor, $idSolicitud, $idPais);
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'El proveedor ha sido eliminado satisfactoriamente';
		
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

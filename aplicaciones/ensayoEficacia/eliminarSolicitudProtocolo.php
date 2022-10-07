<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEnsayoEficacia.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	$id_protocolo=$_POST['id_protocolo'];

	try {
		$conexion = new Conexion();
		$ce = new ControladorEnsayoEficacia();

		$res=$ce -> eliminarProtocolo($conexion,$id_protocolo);

		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Solicitud eliminada';

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
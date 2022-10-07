<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
		$conexion = new Conexion();
		$ced = new ControladorEvaluacionesDesempenio();
	try {

		$conexion->ejecutarConsulta("begin;");
		$ced->inactivarActivarParametros ($conexion,$_POST['codParametro'],'false');
		$conexion->ejecutarConsulta("commit;");
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Parámetros guardados satisfactoriamente';
		echo json_encode($mensaje);
	} catch (Exception $ex){
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	} finally {
	$conexion->desconectar();
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>
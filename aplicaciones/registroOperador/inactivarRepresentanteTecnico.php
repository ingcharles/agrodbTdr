<?php

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	$idDetalleRepresentanteTecnico = $_POST['idDetalleRepresentanteTecnico'];

	try {
		$conexion = new Conexion();
		$cr = new ControladorRegistroOperador();

		$conexion->ejecutarConsulta("begin;");

		$cr->inactivarRepresentanteTecnico($conexion, $idDetalleRepresentanteTecnico);

		$conexion->ejecutarConsulta("commit;");
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idDetalleRepresentanteTecnico;
		
	} catch (Exception $ex) {
			$conexion->ejecutarConsulta("rollback;");
			$mensaje['mensaje'] = $ex->getMessage();
			$mensaje['error'] = $conexion->mensajeError;
	} finally {
		$conexion->desconectar();
	}
} catch (Exception $ex) {
	$mensaje['mensaje'] = $ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
} finally {
	echo json_encode($mensaje);
}
?>
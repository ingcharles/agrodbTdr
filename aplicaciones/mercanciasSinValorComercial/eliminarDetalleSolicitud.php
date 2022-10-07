<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMercanciasSinValorComercial.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$conexion = new Conexion();
	$ce = new ControladorMercanciasSinValorComercial();

	try {
		$idProductoSolicitud= $_POST['idProductoSolicitud'];

		$conexion->ejecutarConsulta("begin;");
		$ce->eliminarDetalle($conexion, $idProductoSolicitud);
		$conexion->ejecutarConsulta("commit;");

		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idProductoSolicitud;

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
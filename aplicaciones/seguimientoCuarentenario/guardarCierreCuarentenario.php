<?php

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorSeguimientoCuarentenario.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	$idDestinacionAduanera = $_POST['idDestinacionAduanera'];
	$cantidadProductoCierre = $_POST['cantidadProductosCierre'];
	$fechaCierre = $_POST['fechaCierre'];
	$observacionCierre = $_POST['observaciones'];

	try {
		$conexion = new Conexion();
		$csc = new ControladorSeguimientoCuarentenario();

		$conexion->ejecutarConsulta("begin;");
		$csc->actualizarSeguimientoDDACierre($conexion, $idDestinacionAduanera, $cantidadProductoCierre, $fechaCierre, $observacionCierre);
		$conexion->ejecutarConsulta("commit;");

		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = "Los datos han sido guardado satisfactoriamente";

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
<?php

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorSeguimientoCuarentenario.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	$idDestinacionAduanera = $_POST['idDestinacionAduanera'];
	$usuarioCierre = $_POST['usuarioCierre'];
	$fechaCierre = $_POST['fechaCierre'];
	$rutaInformeLaboratorio = $_POST['archivoInformeLaboratorio'];
	$rutaLevantamientoCuarentena = $_POST['archivoLevantamientoCuarentena'];
	try {
		$conexion = new Conexion();
		$csc = new ControladorSeguimientoCuarentenario();

		$conexion->ejecutarConsulta("begin;");
		$csc->actualizarSeguimientoSADDACierre($conexion, $idDestinacionAduanera,$usuarioCierre, $fechaCierre, $rutaInformeLaboratorio, $rutaLevantamientoCuarentena);
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
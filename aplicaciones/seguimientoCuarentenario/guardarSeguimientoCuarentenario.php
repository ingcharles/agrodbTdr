<?php

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorSeguimientoCuarentenario.php';
require_once '../../clases/ControladorMail.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$idDestinacionAduanera = $_POST['idDestinacionAduanera'];
	$numeroSeguimiento = $_POST['numeroSeguimientos'];
	$numeroPlantas = $_POST['numeroPlantas'];
	$provincia = $_POST['nombreProvincia'];
	try {
		$conexion = new Conexion();
		$csc = new ControladorSeguimientoCuarentenario();
		$cMail = new ControladorMail();

		$conexion->ejecutarConsulta("begin;");
		$qConsultarSeguimientoDDA=$csc->consultarSeguimientoDDA($conexion, $idDestinacionAduanera);
		if(pg_num_rows($qConsultarSeguimientoDDA)==0){
			$csc->guardarNuevoSeguimientoDDA($conexion, $idDestinacionAduanera, $numeroSeguimiento, $numeroPlantas, 'abierto');
			$csc->actualizarEstadoSeguimientoDDA($conexion, $idDestinacionAduanera);
		}else{
			$csc->actualizarSeguimientosDDA($conexion, $idDestinacionAduanera, $numeroSeguimiento, $numeroPlantas);
		}

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
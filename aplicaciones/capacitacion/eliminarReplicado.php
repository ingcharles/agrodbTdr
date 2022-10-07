<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCapacitacion.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {
	$conexion = new Conexion();
	$cc = new ControladorCapacitacion();
	try {
		$conexion->ejecutarConsulta("begin;");
		$idFuncionarioReplicado = htmlspecialchars ($_POST['idFuncionarioReplicado'],ENT_NOQUOTES,'UTF-8');

		$cc->eliminarReplicadoXid($conexion, $idFuncionarioReplicado);
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idFuncionarioReplicado;
		$conexion->ejecutarConsulta("commit;");

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
<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorServiciosInformacionTecnica.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {
	$conexion = new Conexion ();
	$csit = new ControladorServiciosInformacionTecnica();
	try {
		$idEnfermedadRequerimiento = $_POST['idEnfermedadRequerimiento'];
		$usuarioResponsable = $_POST['usuarioResponsable'];
		$conexion->ejecutarConsulta("begin;");
		$csit->actualizarEstadoEnfermedadRequerimiento($conexion, $idEnfermedadRequerimiento,$usuarioResponsable);
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idEnfermedadRequerimiento;
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
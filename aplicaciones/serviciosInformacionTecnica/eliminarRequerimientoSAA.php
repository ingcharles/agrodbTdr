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
		$idRequerimiento = $_POST['idRequerimiento'];
		$nombre = $_POST['nombreRequerimiento'];
		$descripcion = $_POST['descripcion'];
		$usuarioResponsable = $_POST['usuarioResponsable'];
		$conexion->ejecutarConsulta("begin;");
		$csit->actualizarRequerimiento($conexion, $idRequerimiento,$usuarioResponsable);
		$mensaje['estado'] = 'exito';
		$mensaje ['mensaje'] = 'Los datos han sido eliminados satisfactoriamente';
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
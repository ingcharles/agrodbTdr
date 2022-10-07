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
		$idEnfermedad = $_POST['idEnfermedad'];
		$nombre = $_POST['nombreEnfermedad'];
		$descripcion = $_POST['descripcion'];
		$observacion = $_POST['observacion'];
		$usuarioResponsable = $_POST['usuarioResponsable'];
		$conexion->ejecutarConsulta("begin;");
		$csit->actualizarEnfermedadAnimal($conexion, $idEnfermedad, $nombre, $descripcion, $observacion,$usuarioResponsable);
		$mensaje['estado'] = 'exito';
		$mensaje ['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
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
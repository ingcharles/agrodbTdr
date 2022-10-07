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
		$idEnfermedadLocalizacion = $_POST['idEnfermedadLocalizacion'];
		$usuarioResponsable = $_POST['usuarioResponsable'];
		$conexion->ejecutarConsulta("begin;");
		$csit->actualizarEstadoEnfermedadLocalizacion($conexion, $idEnfermedadLocalizacion,$usuarioResponsable);
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idEnfermedadLocalizacion;		
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
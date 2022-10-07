<?php
session_start ();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacion.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {
	$conexion = new Conexion ();
	$va = new ControladorVacunacion ();
	
	$datos = array (
			'motivoAnulacion' => htmlspecialchars ( $_POST ['motivoAnulacion'], ENT_NOQUOTES, 'UTF-8' ),
	        'idProvincia' => htmlspecialchars ( $_POST ['idProvincia'], ENT_NOQUOTES, 'UTF-8' ),
			'idSerieDocumento' => htmlspecialchars ( $_POST['idSerieDocumento'], ENT_NOQUOTES, 'UTF-8' ),
			'usuarioModificacion' => htmlspecialchars ( $_SESSION['usuario'], ENT_NOQUOTES, 'UTF-8' ),
			'estado' => 'anulado'
	);
	try {
		$conexion->ejecutarConsulta("begin;");
		$va->actualizarEstadoCertificadoAnulado($conexion, $datos['idSerieDocumento'], $datos['estado'], $datos['motivoAnulacion'], $datos['usuarioModificacion'], $datos['idProvincia']);
		$conexion->ejecutarConsulta("commit;");
		$mensaje ['estado'] = 'exito';
		$mensaje ['mensaje'] = 'Los datos han sido guardado satisfactoriamente.';
		
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
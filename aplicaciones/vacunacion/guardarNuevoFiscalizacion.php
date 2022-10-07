<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacion.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {
	$conexion = new Conexion ();
	$va = new ControladorVacunacion ();

	$datos = array(
				'idVacunacion' => htmlspecialchars ($_POST['idVacunacion'],ENT_NOQUOTES,'UTF-8'),
				'usuarioResponsable' => htmlspecialchars ($_POST['usuarioResponsable'],ENT_NOQUOTES,'UTF-8'),
				'observacion' => htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8'),
				'estado' => htmlspecialchars ($_POST['estadoFiscalizacion'],ENT_NOQUOTES,'UTF-8'),
				'fechaFiscalizacion' => htmlspecialchars ($_POST['fechaFiscalizacion'],ENT_NOQUOTES,'UTF-8'),
				'identificadorComerciante' => htmlspecialchars ($_POST['identificadorComerciante'],ENT_NOQUOTES,'UTF-8')
		);
	
	try {
		$numeroFiscalizacion = $va->generarNumeroCertificadoFiscalizacion($conexion);
		$conexion->ejecutarConsulta("begin;");
		$va->guardarFiscalizacion($conexion, $datos['idVacunacion'], $numeroFiscalizacion, $datos['usuarioResponsable'], $datos['observacion'], $datos['estado'], $datos['fechaFiscalizacion'], $datos['identificadorComerciante']);
		$va->actualizarEstadoVacunacionFiscalizacion($conexion, $datos['idVacunacion']);
		$conexion->ejecutarConsulta("commit;");
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido guardado satisfactoriamente';
		
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
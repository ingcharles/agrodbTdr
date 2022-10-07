<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorServiciosLinea.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {
	$conexion = new Conexion ();
	$csl = new ControladorServiciosLinea();

	try {

		$idRutaTransporte = htmlspecialchars ( $_POST['idRutaTransporte'], ENT_NOQUOTES, 'UTF-8' );
		$identificador= htmlspecialchars ( $_POST['identificadorResponsable'], ENT_NOQUOTES, 'UTF-8' );
		$conexion->ejecutarConsulta("begin;");
		$csl->eliminarRutasTransporte($conexion, $idRutaTransporte, $identificador);
		$mensaje ['estado'] = 'exito';
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
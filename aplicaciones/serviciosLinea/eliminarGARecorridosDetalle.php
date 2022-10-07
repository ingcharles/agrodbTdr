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
		
		$idDetalleRuta = htmlspecialchars ( $_POST['idDetalleRuta'], ENT_NOQUOTES, 'UTF-8' );
		$conexion->ejecutarConsulta("begin;");
		$csl->eliminarDetalleRutasTransporteXid($conexion, $idDetalleRuta);
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idDetalleRuta;
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
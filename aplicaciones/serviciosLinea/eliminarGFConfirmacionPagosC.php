<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorServiciosLinea.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {

	$conexion = new Conexion ();
	$csl = new ControladorServiciosLinea();
	
	try {
		
		$fecha = htmlspecialchars ( $_POST['fecha'], ENT_NOQUOTES, 'UTF-8' );
		$localizacion = htmlspecialchars ( $_POST['localizacion'], ENT_NOQUOTES, 'UTF-8' );
		$identificador = htmlspecialchars ( $_POST['identificadorResponsable'], ENT_NOQUOTES, 'UTF-8' );	
		
		$conexion->ejecutarConsulta("begin;");
		$qIdConfirmacionPago=$csl->obtenerIdConfirmacionPagoConsolidado($conexion, $fecha, $localizacion);
		while($fila=pg_fetch_assoc($qIdConfirmacionPago)){
			$csl->actualizarEstadoEliminarConfirmacionPago($conexion, $fila['id_confirmacion_pago'], $_SESSION['usuario']);
		}
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
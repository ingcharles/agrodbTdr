<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAdministrarCaracteristicas.php';

$idCatalogo= $_POST['idCatalogo'];
$estadoCatalogo= $_POST['estadoCatalogo'];
$estado= $_POST['estadoRequisito'];
$idServicioProducto = htmlspecialchars($_POST['idServicioProducto'],ENT_NOQUOTES,'UTF-8');

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try {
	
	$conexion = new Conexion();
	$cac = new controladorAdministrarCaracteristicas();
	
	try{		
		$conexion->ejecutarConsulta("begin;");
		$cac->actualizarEstadoCaracteristica($conexion,$idServicioProducto,$estado);
		$conexion->ejecutarConsulta("commit;");
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idServicioProducto;

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
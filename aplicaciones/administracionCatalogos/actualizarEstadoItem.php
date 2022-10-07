<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';

$idCatalogo= $_POST['idItemPadre'];
$estadoCatalogo= $_POST['estadoCatalogo'];
$estado= $_POST['estadoRequisito'];
$idServicioProducto = htmlspecialchars($_POST['idServicioProducto'],ENT_NOQUOTES,'UTF-8');

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try {
	
	$conexion = new Conexion();
	$cac = new ControladorAdministrarCatalogos();
	
	try{		
		$conexion->ejecutarConsulta("begin;");
		$cac->actualizarEstadoItem($conexion,$idServicioProducto,$estado,$idCatalogo,$estadoCatalogo);
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
<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorServiciosInformacionTecnica.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {
	$conexion = new Conexion ();
	$csit = new ControladorServiciosInformacionTecnica();
	$cc = new ControladorCatalogos();
	try {
		$idRequerimiento = $_POST['idRequerimiento'];
		$nombre = $_POST['nombreElemento'];
		$descripcion = $_POST['descripcionElemento'];
		$usuarioResponsable = $_POST['usuarioResponsable'];
		$conexion->ejecutarConsulta("begin;");
		$idRequerimientoElemento=pg_fetch_row($cc->guardarRequerimientoElemento($conexion, $idRequerimiento, $nombre, $descripcion,$usuarioResponsable));
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $csit->imprimirLineaRequerimientoElemento($idRequerimientoElemento[0], $nombre, $descripcion,$usuarioResponsable);
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
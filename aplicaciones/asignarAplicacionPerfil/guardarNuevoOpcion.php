<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicacionesPerfiles.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$conexion = new Conexion();
	$cap = new ControladorAplicacionesPerfiles();
	
	$nombre = htmlspecialchars ($_POST['nombreOpcion'],ENT_NOQUOTES,'UTF-8');
	$estilo = htmlspecialchars ($_POST['estiloOpcion'],ENT_NOQUOTES,'UTF-8');
	$pagina = htmlspecialchars ($_POST['paginaOpcion'],ENT_NOQUOTES,'UTF-8');
	$orden = htmlspecialchars ($_POST['ordenOpcion'],ENT_NOQUOTES,'UTF-8');
	$idAplicacion = htmlspecialchars ($_POST['idAplicacion'],ENT_NOQUOTES,'UTF-8');

	try {

		$conexion->ejecutarConsulta("begin;");
		$idOpcion=$cap->obtenerSecuencialOpcion($conexion);
		$cap->guardarNuevoOpcionAplicacion($conexion,$idOpcion, $idAplicacion, $nombre, $estilo, $pagina, $orden);
		$conexion->ejecutarConsulta("commit;");
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $cap->imprimirLineaOpcionesAplicacion($idOpcion, $nombre, $estilo, $pagina, $orden,$idAplicacion);
		
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
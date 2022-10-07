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
	
	$descripcion = htmlspecialchars ($_POST['descripcionAccion'],ENT_NOQUOTES,'UTF-8');
	$estilo = htmlspecialchars ($_POST['estiloAccion'],ENT_NOQUOTES,'UTF-8');
	$pagina = htmlspecialchars ($_POST['paginaAccion'],ENT_NOQUOTES,'UTF-8');
	$orden = htmlspecialchars ($_POST['ordenAccion'],ENT_NOQUOTES,'UTF-8');
	$idAplicacion = htmlspecialchars ($_POST['idAplicacion'],ENT_NOQUOTES,'UTF-8');
	$idOpcion = htmlspecialchars ($_POST['idOpcion'],ENT_NOQUOTES,'UTF-8');
	
	try {

		$conexion->ejecutarConsulta("begin;");
		$idAccion=pg_fetch_row($cap->guardarNuevoAccion($conexion, $idAplicacion, $idOpcion, $descripcion, $estilo, $pagina, $orden));
		$conexion->ejecutarConsulta("commit;");
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $cap->imprimirLineaAccionesOpcion($idAccion[0], $descripcion, $pagina,$estilo, $orden);
		
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
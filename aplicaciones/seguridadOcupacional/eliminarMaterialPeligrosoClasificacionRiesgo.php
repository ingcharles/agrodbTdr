<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorSeguridadOcupacional.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$conexion = new Conexion();
	$so = new ControladorSeguridadOcupacional ();
	
	$idMaterialPeligrosoClasificacionRiesgo = htmlspecialchars ($_POST['idMaterialPeligrosoClasificacionRiesgo'],ENT_NOQUOTES,'UTF-8');

	try {
		
		$conexion->ejecutarConsulta("begin;");

		$so->eliminarMaterialPeligrosoClasificacionRiesgo($conexion, $idMaterialPeligrosoClasificacionRiesgo);
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idMaterialPeligrosoClasificacionRiesgo;
		
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
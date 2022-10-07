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
	$idOpcion = htmlspecialchars ($_POST['idOpcion'],ENT_NOQUOTES,'UTF-8');
	$idAplicacion = htmlspecialchars ($_POST['idAplicacion'],ENT_NOQUOTES,'UTF-8');
	
	try {
		
		if(pg_num_rows($cap->buscarAccionesOpcion($conexion, $idOpcion, $idAplicacion))==0){
			$conexion->ejecutarConsulta("begin;");
			$cap->eliminarOpcionAplicacion($conexion,$idOpcion);
			$conexion->ejecutarConsulta("commit;");
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $idOpcion;
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "La opcion cuenta con acciones asignadas.";
		}
			
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
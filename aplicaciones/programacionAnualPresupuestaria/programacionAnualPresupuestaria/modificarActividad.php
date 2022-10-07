<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$idObjetivoEstrategico = htmlspecialchars ($_POST['idObjetivoEstrategico'],ENT_NOQUOTES,'UTF-8');
	$idObjetivoEspecifico = htmlspecialchars ($_POST['idObjetivoEspecifico'],ENT_NOQUOTES,'UTF-8');
	$idObjetivoOperativo = htmlspecialchars ($_POST['idObjetivoOperativo'],ENT_NOQUOTES,'UTF-8');
	$idProcesoProyecto = htmlspecialchars ($_POST['idProcesoProyecto'],ENT_NOQUOTES,'UTF-8');
	$idComponente = htmlspecialchars ($_POST['idComponente'],ENT_NOQUOTES,'UTF-8');
	$idActividad = htmlspecialchars ($_POST['idActividad'],ENT_NOQUOTES,'UTF-8');
	
	$nombreActividad = htmlspecialchars ($_POST['nombreActividad'],ENT_NOQUOTES,'UTF-8');
	$idCodigoActividad = htmlspecialchars ($_POST['idCodigoActividad'],ENT_NOQUOTES,'UTF-8');
	$codigoCodigoActividad = htmlspecialchars ($_POST['codigoCodigoActividad'],ENT_NOQUOTES,'UTF-8');
	$anio = htmlspecialchars ($_POST['anio'],ENT_NOQUOTES,'UTF-8');
	$identificador = ($_SESSION['usuario']);
	
	try {
		$conexion = new Conexion();
		$cpp = new ControladorProgramacionPresupuestaria();

		$conexion->ejecutarConsulta("begin;");
		$cpp->modificarActividad($conexion, $idActividad, $nombreActividad, $idCodigoActividad, $codigoCodigoActividad, $identificador);
		$conexion->ejecutarConsulta("commit;");
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos fueron actualizados';

		$conexion->desconectar();
		echo json_encode($mensaje);
	
	} catch (Exception $ex){
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] = $ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError;
		$conexion->desconectar();
	}/* finally {
		$conexion->desconectar();
	}*/
	
} catch (Exception $ex) {
	$mensaje['mensaje'] = $ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
	$conexion->desconectar();
} /*finally {
	echo json_encode($mensaje);
}*/
?>
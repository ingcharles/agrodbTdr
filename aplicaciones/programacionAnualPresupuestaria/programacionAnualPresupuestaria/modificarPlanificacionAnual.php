<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$fecha = getdate();
	$anio = $fecha['year'];
	
	$idPlanificacionAnual = htmlspecialchars ($_POST['idPlanificacionAnual'],ENT_NOQUOTES,'UTF-8');
	$idProvincia = $_POST['idProvincia'];
	$nombreProvincia = $_POST['nombreProvincia'];
	
	$cantidadUsuarios = $_POST['cantidadUsuarios'];
	$poblacionObjetivo = $_POST['poblacionObjetivo'];
	$medioVerificacion = $_POST['medioVerificacion'];
	
	$idResponsable = $_POST['idResponsable'];
	$nombreResponsable = $_POST['nombreResponsable'];
	
	try {
		$conexion = new Conexion();
		$cpp = new ControladorProgramacionPresupuestaria();

		$conexion->ejecutarConsulta("begin;");
		$cpp->modificarPlanificacionAnual($conexion, $idPlanificacionAnual, $idProvincia, 
											$nombreProvincia, $cantidadUsuarios, $poblacionObjetivo, 
											$medioVerificacion, $idResponsable, $nombreResponsable);
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
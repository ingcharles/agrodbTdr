<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$ejercicio = $_POST['ejercicio'];
	$entidad = $_POST['entidad'];
	$subprograma = $_POST['subprograma'];
	$renglonAux = $_POST['renglonAux'];
	$fuente = $_POST['fuente'];
	$organismo = $_POST['organismo'];
	$correlativo = $_POST['correlativo'];
	$obra = $_POST['obra'];
	$operacionBid = $_POST['operacionBid'];
	$proyectoBid = $_POST['proyectoBid'];
	$iva = $_POST['iva'];
	$identificador = $_SESSION['usuario'];
		
	try {
		$conexion = new Conexion();
		$cpp = new ControladorProgramacionPresupuestaria();
		
		$conexion->ejecutarConsulta("begin;");
		$cpp->modificarParametros($conexion, $ejercicio, $entidad, $subprograma, $renglonAux, $fuente, $organismo, $correlativo, $obra, $operacionBid, $proyectoBid, $iva, $identificador);
		$conexion->ejecutarConsulta("commit;");
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los parámetros se han actualizado correctamente';
		
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
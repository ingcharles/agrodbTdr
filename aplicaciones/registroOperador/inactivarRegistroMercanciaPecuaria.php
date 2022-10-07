<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$idCentroPecuario = htmlspecialchars($_POST['idCentroPecuario'], ENT_NOQUOTES, 'UTF-8');

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	$conexion = new Conexion();
	$cr = new ControladorRegistroOperador();

	try{
		$conexion->ejecutarConsulta("begin;");

		$cr->inactivarRegistroMercanciasPecuaria($conexion, $idCentroPecuario);

		$conexion->ejecutarConsulta("commit;");

		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idCentroPecuario;
	}catch (Exception $ex){
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] = $ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError;
	}finally {
		$conexion->desconectar();
	}
}catch (Exception $ex){
	$mensaje['mensaje'] = $ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
}finally {
	echo json_encode($mensaje);
}
?>
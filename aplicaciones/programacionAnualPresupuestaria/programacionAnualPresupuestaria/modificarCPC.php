<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$idCPC = $_POST['idCPC'];
	$nombreCPC = $_POST['nombreCPC'];
	$codigoCPC = $_POST['codigoCPC'];
	$nivelCPC = $_POST['nivelCPC'];
	$identificador = $_SESSION['usuario'];
		
	try {
		$conexion = new Conexion();
		$cpp = new ControladorProgramacionPresupuestaria();
		
		$conexion->ejecutarConsulta("begin;");
		$cpp->modificarCPC($conexion, $idCPC, $nombreCPC, $codigoCPC, $nivelCPC, $identificador);
		$conexion->ejecutarConsulta("commit;");
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'El CPC se ha actualizado correctamente';
		
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
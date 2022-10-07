<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
	
	try{
		$idRenglon = $_POST['idRenglon'];
		$identificador = $_SESSION['usuario'];

		try {
			$conexion = new Conexion();
			$cpp = new ControladorProgramacionPresupuestaria();
			
			$conexion->ejecutarConsulta("begin;");
			$cpp->eliminarRenglon($conexion, $idRenglon, $identificador);
			$conexion->ejecutarConsulta("commit;");
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Se han eliminado los elementos seleccionados';
			
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
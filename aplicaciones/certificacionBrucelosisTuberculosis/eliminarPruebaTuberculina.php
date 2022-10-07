<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorBrucelosisTuberculosis.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
	
try{	
	 
	try {
		$conexion = new Conexion();
		$cbt = new ControladorBrucelosisTuberculosis();
		
		$identificador = $_SESSION['usuario'];
	
		$idPruebaTuberculina = htmlspecialchars ($_POST['idPruebaTuberculina'],ENT_NOQUOTES,'UTF-8');
		
		$conexion->ejecutarConsulta("begin;");
					
			$cbt->eliminarPruebaTuberculina($conexion, $idPruebaTuberculina);
		
		$conexion->ejecutarConsulta("commit;");
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idPruebaTuberculina;
		
		$conexion->desconectar();
		
		echo json_encode($mensaje);
	
	} catch (Exception $ex){
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] = $ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError;
		$conexion->desconectar();
	}
	
} catch (Exception $ex) {
	$mensaje['mensaje'] = $ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
	$conexion->desconectar();
}
?>
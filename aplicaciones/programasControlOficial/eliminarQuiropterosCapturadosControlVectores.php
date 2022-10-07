<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramasControlOficial.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
	
try{	
	 
	try {
		$conexion = new Conexion();
		$cpco = new ControladorProgramasControlOficial();
		
		$identificador = $_SESSION['usuario'];
	
		$idControlVectores = htmlspecialchars ($_POST['idControlVectores'],ENT_NOQUOTES,'UTF-8');
		$idControlVectoresQuiropterosCapturados = htmlspecialchars ($_POST['idControlVectoresQuiropterosCapturados'],ENT_NOQUOTES,'UTF-8');
		
		$conexion->ejecutarConsulta("begin;");
					
			$cpco->eliminarQuiropterosCapturadosControlVectores($conexion, $idControlVectoresQuiropterosCapturados);
			
			$cpco->eliminarTotalQuiropterosTratadosControlVectores($conexion, $idControlVectores);
		
		$conexion->ejecutarConsulta("commit;");
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idControlVectoresQuiropterosCapturados;
		
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
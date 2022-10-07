<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramasControlOficial.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$conexion = new Conexion();
	$cpco = new ControladorProgramasControlOficial();

	$identificador = $_SESSION['usuario'];

	$idControlVectores = htmlspecialchars ($_POST['idControlVectores'],ENT_NOQUOTES,'UTF-8');
	
	$tipoRefugio = htmlspecialchars ($_POST['tipoRefugio'],ENT_NOQUOTES,'UTF-8');
	$observaciones = htmlspecialchars ($_POST['observacionesControlVectores'],ENT_NOQUOTES,'UTF-8');
	
	try {
		
		if(($identificador != null) || ($identificador != '')){
		
			$especiesAtacadasControlVectores = $cpco->listarEspeciesAtacadasControlVectores($conexion, $idControlVectores);
			$quiropterosCapturadosControlVectores = $cpco->listarQuiropterosCapturadosControlVectores($conexion, $idControlVectores);
			$quiropterosTratadosControlVectores = $cpco->listarQuiropterosTratadosControlVectores($conexion, $idControlVectores);
			$sitiosCapturaControlVectores = $cpco->listarSitiosCapturaControlVectores($conexion, $idControlVectores);
			
			if((pg_num_rows($especiesAtacadasControlVectores) != 0) && (pg_num_rows($quiropterosCapturadosControlVectores) != 0) && (pg_num_rows($quiropterosTratadosControlVectores) != 0) && (pg_num_rows($sitiosCapturaControlVectores) != 0)){
					
				$conexion->ejecutarConsulta("begin;");		
					
				$cpco->cierreControlVectores($conexion, $idControlVectores, $identificador, $observaciones);
										
				$conexion->ejecutarConsulta("commit;");
			
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Los datos se han guardado exitosamente';
				
			}else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = "Debe ingresar por lo menos un resultado de inspección para poder continuar.";
			}
				
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Su sesión ha expirado, por favor ingrese nuevamente al sistema para continuar.";
		}
		
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
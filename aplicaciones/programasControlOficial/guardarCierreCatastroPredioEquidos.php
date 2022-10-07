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

	$idCatastroPredioEquidos = htmlspecialchars ($_POST['idCatastroPredioEquidos'],ENT_NOQUOTES,'UTF-8');
	
	$observaciones = htmlspecialchars ($_POST['observaciones'],ENT_NOQUOTES,'UTF-8');
	
	try {
		
		if(($identificador != null) || ($identificador != '')){
		
			$motivoCatastroPredioEquidos = $cpco->listarMotivoCatastroPredioEquidos($conexion, $idCatastroPredioEquidos);
			$tipoActividadPredioEquidos = $cpco->listarTipoActividadPredioEquidos($conexion, $idCatastroPredioEquidos);
			$especiePredioEquidos = $cpco->listarEspeciePredioEquidos($conexion, $idCatastroPredioEquidos);
			$bioseguridadPredioEquidos = $cpco->listarBioseguridadPredioEquidos($conexion, $idCatastroPredioEquidos);
			$sanidadPredioEquidos = $cpco->listarSanidadPredioEquidos($conexion, $idCatastroPredioEquidos);
			$historialPatologiaPredioEquidos = $cpco->listarHistorialPatologiasPredioEquidos($conexion, $idCatastroPredioEquidos);
			
			if((pg_num_rows($motivoCatastroPredioEquidos) != 0) && (pg_num_rows($tipoActividadPredioEquidos) != 0) 
					&& (pg_num_rows($especiePredioEquidos) != 0) && (pg_num_rows($bioseguridadPredioEquidos) != 0)
					 && (pg_num_rows($sanidadPredioEquidos) != 0) && (pg_num_rows($historialPatologiaPredioEquidos) != 0)){
					
				$conexion->ejecutarConsulta("begin;");		
					
				$cpco->cierreCatastroPredioEquidos($conexion, $idCatastroPredioEquidos, $identificador, $observaciones);
										
				$conexion->ejecutarConsulta("commit;");
			
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Los datos se han guardado exitosamente';
			
			}else if((pg_num_rows($motivoCatastroPredioEquidos) != 0) && (pg_num_rows($tipoActividadPredioEquidos) != 0) 
					&& (pg_num_rows($especiePredioEquidos) != 0) && (pg_num_rows($sanidadPredioEquidos) != 0) 
					 && (pg_num_rows($historialPatologiaPredioEquidos) != 0)){
					
				$conexion->ejecutarConsulta("begin;");	
				
				$cpco->nuevaBioseguridadPredioEquidos($conexion, $idCatastroPredioEquidos, $identificador, 0, 'No Aplica');
					
				$cpco->cierreCatastroPredioEquidos($conexion, $idCatastroPredioEquidos, $identificador, $observaciones);
										
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
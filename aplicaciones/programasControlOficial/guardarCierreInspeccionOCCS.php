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

	$idInspeccionOCCS = htmlspecialchars ($_POST['idInspeccionOCCS'],ENT_NOQUOTES,'UTF-8');
	
	$observaciones = htmlspecialchars ($_POST['observaciones'],ENT_NOQUOTES,'UTF-8');
	
	try {
		
		if(($identificador != null) || ($identificador != '')){
		
			$tiposExplotacionInspeccionOCCS = $cpco->listarTipoExplotacionInspeccionOCCS($conexion, $idInspeccionOCCS);
			$especieInspeccionOCCS = $cpco->listarEspecieInspeccionOCCS($conexion, $idInspeccionOCCS);
			$bioseguridadInspeccionOCCS = $cpco->listarBioseguridadInspeccionOCCS($conexion, $idInspeccionOCCS);
			$enfermedadInspeccionOCCS = $cpco->listarHistorialPatologiasInspeccionOCCS($conexion, $idInspeccionOCCS);
	
			if((pg_num_rows($tiposExplotacionInspeccionOCCS) != 0) && (pg_num_rows($especieInspeccionOCCS) != 0) && (pg_num_rows($bioseguridadInspeccionOCCS) != 0) && (pg_num_rows($enfermedadInspeccionOCCS) != 0)){
					
				$conexion->ejecutarConsulta("begin;");		
					
				$cpco->cierreInspeccionOCCS($conexion, $idInspeccionOCCS, $identificador, $observaciones);
										
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
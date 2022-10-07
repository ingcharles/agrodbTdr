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

	$idMurcielagosHematofagos = htmlspecialchars ($_POST['idMurcielagosHematofagos'],ENT_NOQUOTES,'UTF-8');
	$nuevaInspeccion = htmlspecialchars ($_POST['nuevaInspeccion'],ENT_NOQUOTES,'UTF-8');
	$nuevaFechaInspeccion = htmlspecialchars ($_POST['fechaNuevaInspeccion'],ENT_NOQUOTES,'UTF-8');
	
	try {
		
		if(($identificador != null) || ($identificador != '')){
		
			$inspeccionMH = $cpco->listarInspeccionMurcielagosHematofagos($conexion, $idMurcielagosHematofagos);
			
			if(pg_num_rows($inspeccionMH) != 0){
				
				$conexion->ejecutarConsulta("begin;");		
					
					if($nuevaInspeccion == 'Si'){
						$cpco->planificacionNuevaInspeccionMurcielagosHematofagos($conexion, $idMurcielagosHematofagos,  
																	$identificador, $nuevaInspeccion, $nuevaFechaInspeccion);
					}else{
						$cpco->cierreControlMurcielagosHematofagos($conexion, $idMurcielagosHematofagos, 
																	$identificador, $nuevaInspeccion);
					}
					
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
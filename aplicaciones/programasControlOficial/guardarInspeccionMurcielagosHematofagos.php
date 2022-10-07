<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramasControlOficial.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$ruta = 'programasControlOficial';

try{
	$conexion = new Conexion();
	$cpco = new ControladorProgramasControlOficial();
	
	$identificador = $_SESSION['usuario'];
	
	$idMurcielagosHematofagos = htmlspecialchars ($_POST['idMurcielagosHematofagos'],ENT_NOQUOTES,'UTF-8');
	
	$numInspeccion = htmlspecialchars ($_POST['inspeccion'],ENT_NOQUOTES,'UTF-8');
	$fechaInspeccion = htmlspecialchars ($_POST['fechaInspeccion'],ENT_NOQUOTES,'UTF-8');	
	$presenciaMH = htmlspecialchars ($_POST['presenciaMH'],ENT_NOQUOTES,'UTF-8');
	$controlRealizado = htmlspecialchars ($_POST['controlRealizado'],ENT_NOQUOTES,'UTF-8');
	$numMachos = htmlspecialchars ($_POST['numMurcielagosMacho'],ENT_NOQUOTES,'UTF-8');
	$numHembras = htmlspecialchars ($_POST['numMurcielagosHembra'],ENT_NOQUOTES,'UTF-8');
	$observaciones = htmlspecialchars ($_POST['observacionesInspeccion'],ENT_NOQUOTES,'UTF-8');
		
	try {
		
		$inspeccionMH = $cpco->buscarInspeccionMurcielagosHematofagos($conexion, $idMurcielagosHematofagos, $numInspeccion);
		
		if(pg_num_rows($inspeccionMH) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				$idMurcielagosHematofagosInspecciones = pg_fetch_result($cpco->nuevaInspeccionMurcielagosHematofagos($conexion, 
														$idMurcielagosHematofagos, $identificador, $numInspeccion, 
														$fechaInspeccion, $presenciaMH, $controlRealizado, 
														$numMachos, $numHembras, $observaciones), 
														0, 'id_murcielagos_hematofagos_inspecciones');
			
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaInspeccionMurcielagosHematofagos($idMurcielagosHematofagosInspecciones,
																$idMurcielagosHematofagos, $numInspeccion, $fechaInspeccion,
																$presenciaMH, $controlRealizado, $numMachos, 
																$numHembras, $observaciones, $ruta);
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "La inspección ingresada ya existe, por favor verificar en el listado.";
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
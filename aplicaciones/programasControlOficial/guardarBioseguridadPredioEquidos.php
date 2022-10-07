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
	
	$idCatastroPredioEquidos = htmlspecialchars ($_POST['idCatastroPredioEquidos'],ENT_NOQUOTES,'UTF-8');
	
	$idBioseguridad = htmlspecialchars ($_POST['bioseguridad'],ENT_NOQUOTES,'UTF-8');
	$nombreBioseguridad = htmlspecialchars ($_POST['nombreBioseguridad'],ENT_NOQUOTES,'UTF-8');
		
	try {
		
		$bioseguridad = $cpco->buscarBioseguridadPredioEquidos($conexion, $idCatastroPredioEquidos, $nombreBioseguridad);
		
		if(pg_num_rows($bioseguridad) == 0){
			$conexion->ejecutarConsulta("begin;");
			
			$idBioseguridadPredioEquidos = pg_fetch_result($cpco->nuevaBioseguridadPredioEquidos($conexion, 
														$idCatastroPredioEquidos, $identificador, 
														$idBioseguridad, $nombreBioseguridad), 
														0, 'id_catastro_predio_equidos_bioseguridad');				
				
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaBioseguridadPredioEquidos($idBioseguridadPredioEquidos, 
														$idCatastroPredioEquidos, $nombreBioseguridad, $ruta);
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "El tipo de bioseguridad ya existe, por favor verificar en el listado.";
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
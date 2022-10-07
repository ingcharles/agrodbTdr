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
	
	$idTipoActividad = htmlspecialchars ($_POST['actividad'],ENT_NOQUOTES,'UTF-8');
	$nombreTipoActividad = htmlspecialchars ($_POST['nombreTipoActividad'],ENT_NOQUOTES,'UTF-8');
	$extensionActividad = htmlspecialchars ($_POST['extensionActividad'],ENT_NOQUOTES,'UTF-8');
		
	try {
		
		$tipoActividad = $cpco->buscarTipoActividadPredioEquidos($conexion, $idCatastroPredioEquidos, $nombreTipoActividad);
		
		if(pg_num_rows($tipoActividad) == 0){
			$conexion->ejecutarConsulta("begin;");
			
			$idTipoActividadPredioEquidos = pg_fetch_result($cpco->nuevoTipoActividadCatastroPredioEquidos($conexion, 
													$idCatastroPredioEquidos, $identificador, $idTipoActividad, 
													$nombreTipoActividad, $extensionActividad), 
													0, 'id_catastro_predio_equidos_tipo_actividad');
			
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaTipoActividadPredioEquidos($idTipoActividadPredioEquidos, 
					$idCatastroPredioEquidos, $nombreTipoActividad, $extensionActividad, $ruta);
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "El tipo de actividad ya existe, por favor verificar en el listado.";
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
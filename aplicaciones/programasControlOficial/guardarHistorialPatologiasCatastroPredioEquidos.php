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
	
	$idEnfermedad = htmlspecialchars ($_POST['enfermedad'],ENT_NOQUOTES,'UTF-8');
	$nombreEnfermedad = htmlspecialchars ($_POST['nombreEnfermedad'],ENT_NOQUOTES,'UTF-8');
	$idVacuna = htmlspecialchars ($_POST['vacuna'],ENT_NOQUOTES,'UTF-8');
	$nombreVacuna = htmlspecialchars ($_POST['nombreVacuna'],ENT_NOQUOTES,'UTF-8');
	$laboratorio = htmlspecialchars ($_POST['laboratorio'],ENT_NOQUOTES,'UTF-8');
		
	try {
		
		$historialPatologia = $cpco->buscarHistorialPatologiasPredioEquidos($conexion, $idCatastroPredioEquidos, $nombreEnfermedad, $nombreVacuna);
		
		if(pg_num_rows($historialPatologia) == 0){
			$conexion->ejecutarConsulta("begin;");
			
			$idHistorialPatologiaPredioEquidos = pg_fetch_result($cpco->nuevoHistorialPatologiaPredioEquidos($conexion, 
													$idCatastroPredioEquidos, $identificador, $idEnfermedad, $nombreEnfermedad, 
													$idVacuna, $nombreVacuna, $laboratorio),
													0, 'id_catastro_predio_equidos_historial_patologias');
			
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaHistorialPatologiaPredioEquidos($idHistorialPatologiaPredioEquidos, $idCatastroPredioEquidos, 
																						$nombreEnfermedad, $nombreVacuna, $laboratorio, $ruta);
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "El Historial de Patologías ingresado ya existe, por favor verificar en el listado.";
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
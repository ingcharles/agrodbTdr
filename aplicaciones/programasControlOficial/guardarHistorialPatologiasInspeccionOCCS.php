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
	
	$idInspeccionOCCS = htmlspecialchars ($_POST['idInspeccionOCCS'],ENT_NOQUOTES,'UTF-8');
	
	$idEnfermedad = htmlspecialchars ($_POST['enfermedad'],ENT_NOQUOTES,'UTF-8');
	$nombreEnfermedad = htmlspecialchars ($_POST['nombreEnfermedad'],ENT_NOQUOTES,'UTF-8');
		
	try {
		
		$enfermedadInspeccion = $cpco->buscarHistorialPatologiasInspeccionOCCS($conexion, $idInspeccionOCCS, $nombreEnfermedad);
		
		if(pg_num_rows($enfermedadInspeccion) == 0){
			$conexion->ejecutarConsulta("begin;");
			
			$idEnfermedadInspeccionOCCS = pg_fetch_result($cpco->nuevoHistorialPatologiasInspeccionOCCS($conexion, $idInspeccionOCCS, 
													$identificador, $idEnfermedad, $nombreEnfermedad), 
													0, 'id_inspeccion_occs_historial_patologias');
			
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaHistorialPatologiasInspeccionOCCS($idEnfermedadInspeccionOCCS, 
																		$idInspeccionOCCS, $nombreEnfermedad, $ruta);
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "La especie, raza y categoría ingresada ya existe, por favor verificar en el listado.";
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
<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramasControlOficial.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$fecha = getdate();

$ruta = 'programasControlOficial';

try{
	$conexion = new Conexion();
	$cpco = new ControladorProgramasControlOficial();
	
	$identificador = $_SESSION['usuario'];
	
	$idInspeccionOCCS = htmlspecialchars ($_POST['idInspeccionOCCS'],ENT_NOQUOTES,'UTF-8');
	
	$idExplotacion = htmlspecialchars ($_POST['explotacion'],ENT_NOQUOTES,'UTF-8');
	$nombreExplotacion = htmlspecialchars ($_POST['nombreExplotacion'],ENT_NOQUOTES,'UTF-8');	
	$superficieExplotacion = htmlspecialchars ($_POST['superficieExplotacion'],ENT_NOQUOTES,'UTF-8');
		
	try {
		
		$especieAtacada = $cpco->buscarExplotacionInspeccionOCCS($conexion, $idInspeccionOCCS, $nombreExplotacion);
		
		if(pg_num_rows($especieAtacada) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				$idEspecieAtacadaInspeccionOCCS = pg_fetch_result($cpco->nuevaExplotacionInspeccionOCCS($conexion, 
														$identificador, $idInspeccionOCCS, $idExplotacion, 
														$nombreExplotacion, $superficieExplotacion), 
														0, 'id_inspeccion_occs_tipo_explotacion');
			
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaTipoExplotacionInspeccionOCCS($idEspecieAtacadaInspeccionOCCS, 
														$nombreExplotacion, $superficieExplotacion, $ruta);
			//date("Y-m-d H:i:s")
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "La especie atacada ingresada ya existe, por favor verificar en el listado.";
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
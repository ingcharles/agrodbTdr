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
	
	$idEspecie = htmlspecialchars ($_POST['especie'],ENT_NOQUOTES,'UTF-8');
	$nombreEspecie = htmlspecialchars ($_POST['nombreEspecie'],ENT_NOQUOTES,'UTF-8');	
	$idRaza = htmlspecialchars ($_POST['raza'],ENT_NOQUOTES,'UTF-8');
	$nombreRaza = htmlspecialchars ($_POST['nombreRaza'],ENT_NOQUOTES,'UTF-8');
	$idCategoria = htmlspecialchars ($_POST['categoria'],ENT_NOQUOTES,'UTF-8');
	$nombreCategoria = htmlspecialchars ($_POST['nombreCategoria'],ENT_NOQUOTES,'UTF-8');
	$numeroAnimales = htmlspecialchars ($_POST['numeroAnimales'],ENT_NOQUOTES,'UTF-8');
		
	try {
		
		$especieInspeccion = $cpco->buscarEspecieInspeccionOCCS($conexion, $idInspeccionOCCS, $nombreEspecie,
																		$nombreRaza, $nombreCategoria);
		
		if(pg_num_rows($especieInspeccion) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				$idEspecieInspeccionOCCS = pg_fetch_result($cpco->nuevaEspecieInspeccionOCCS($conexion, 
														$idInspeccionOCCS, $identificador, $idEspecie, 
														$nombreEspecie, $idRaza, $nombreRaza, $idCategoria,
														$nombreCategoria, $numeroAnimales), 
														0, 'id_inspeccion_occs_especie');
			
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaEspecieInspeccionOCCS($idEspecieInspeccionOCCS,
																$idInspeccionOCCS, $nombreEspecie, 
																$nombreRaza, $nombreCategoria, 
																$numeroAnimales, $ruta);
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
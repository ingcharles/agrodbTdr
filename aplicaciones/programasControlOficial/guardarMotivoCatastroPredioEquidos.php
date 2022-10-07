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
	
	$idMotivoCatastro = htmlspecialchars ($_POST['motivoCatastro'],ENT_NOQUOTES,'UTF-8');
	$nombreMotivoCatastro = htmlspecialchars ($_POST['nombreMotivoCatastro'],ENT_NOQUOTES,'UTF-8');
		
	try {
		
		$motivoCatastro = $cpco->buscarMotivoCatastroPredioEquidos($conexion, $idCatastroPredioEquidos, $nombreMotivoCatastro);
		
		if(pg_num_rows($motivoCatastro) == 0){
			$conexion->ejecutarConsulta("begin;");
			
			$idMotivoCatastroPredioEquidos = pg_fetch_result($cpco->nuevoMotivoCatastroPredioEquidos($conexion, 
													$idCatastroPredioEquidos, $identificador, $idMotivoCatastro,
													$nombreMotivoCatastro), 
													0, 'id_catastro_predio_equidos_catastro');
			
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaMotivoCatastroPredioEquidos($idMotivoCatastroPredioEquidos,
																$idCatastroPredioEquidos, $nombreMotivoCatastro, $ruta);
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "El motivo del catastro ya existe, por favor verificar en el listado.";
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
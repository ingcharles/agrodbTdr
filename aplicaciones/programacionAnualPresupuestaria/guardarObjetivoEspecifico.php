<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$idObjetivoEstrategico = htmlspecialchars ($_POST['idObjetivoEstrategico'],ENT_NOQUOTES,'UTF-8');
	$nombreObjetivoEspecifico = htmlspecialchars ($_POST['nombreObjetivoEspecifico'],ENT_NOQUOTES,'UTF-8');
	$idArea = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
	$nombreArea = htmlspecialchars ($_POST['nombreArea'],ENT_NOQUOTES,'UTF-8');
	$anio = htmlspecialchars ($_POST['anio'],ENT_NOQUOTES,'UTF-8');
	$identificador = ($_SESSION['usuario']);
	
	try {
		$conexion = new Conexion();
		$cpp = new ControladorProgramacionPresupuestaria();
		
		$objetivoEspecifico = $cpp->buscarObjetivoEspecifico($conexion, $nombreObjetivoEspecifico, $idArea, $idObjetivoEstrategico);
		
		if(pg_num_rows($proyecto) == 0){
			$conexion->ejecutarConsulta("begin;");
			$idObjetivoEspecifico = pg_fetch_result($cpp->nuevoObjetivoEspecifico($conexion, $nombreObjetivoEspecifico, $anio, $idObjetivoEstrategico, $idArea, $nombreArea, $identificador), 0, 'id_objetivo_especifico');
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpp->imprimirLineaObjetivoEspecifico($idObjetivoEspecifico, 
					$nombreObjetivoEspecifico, $nombreArea, $idObjetivoEstrategico, $anio, 
					'programacionAnualPresupuestaria');
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "El objetivo específico seleccionado ya existe dentro de esta clasificación, por favor verificar en el listado.";
		}
		
		$conexion->desconectar();
		echo json_encode($mensaje);
	
	} catch (Exception $ex){
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] = $ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError;
		$conexion->desconectar();
	}/* finally {
	$conexion->desconectar();
	}*/
		
} catch (Exception $ex) {
	$mensaje['mensaje'] = $ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
	$conexion->desconectar();
} /*finally {
echo json_encode($mensaje);
}*/
?>
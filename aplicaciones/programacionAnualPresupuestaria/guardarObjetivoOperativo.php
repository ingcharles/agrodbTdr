<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$idObjetivoEstrategico = htmlspecialchars ($_POST['idObjetivoEstrategico'],ENT_NOQUOTES,'UTF-8');
	$idObjetivoEspecifico = htmlspecialchars ($_POST['idObjetivoEspecifico'],ENT_NOQUOTES,'UTF-8');
	$nombreObjetivoOperativo = htmlspecialchars ($_POST['nombreObjetivoOperativo'],ENT_NOQUOTES,'UTF-8');
	$idArea = htmlspecialchars ($_POST['areaOO'],ENT_NOQUOTES,'UTF-8');
	$nombreArea = htmlspecialchars ($_POST['nombreAreaOO'],ENT_NOQUOTES,'UTF-8');
	$anio = htmlspecialchars ($_POST['anio'],ENT_NOQUOTES,'UTF-8');
	$identificador = ($_SESSION['usuario']);
	
	try {
		$conexion = new Conexion();
		$cpp = new ControladorProgramacionPresupuestaria();
		
		$objetivoOperativo = $cpp->buscarObjetivoOperativo($conexion, $nombreObjetivoOperativo, $idArea, $idObjetivoEspecifico);
		
		if(pg_num_rows($objetivoOperativo) == 0){
			$conexion->ejecutarConsulta("begin;");
			$idObjetivoOperativo = pg_fetch_result($cpp->nuevoObjetivoOperativo($conexion, $nombreObjetivoOperativo, $anio, $idObjetivoEspecifico, $idArea, $nombreArea, $identificador), 0, 'id_objetivo_operativo');
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpp->imprimirLineaObjetivoOperativo($idObjetivoOperativo, $nombreObjetivoOperativo, $nombreArea, $idObjetivoEspecifico, $idObjetivoEstrategico, $anio, 'programacionAnualPresupuestaria');
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "El objetivo operativo seleccionado ya existe dentro de esta clasificación, por favor verificar en el listado.";
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
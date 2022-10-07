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
	$idObjetivoOperativo = htmlspecialchars ($_POST['idObjetivoOperativo'],ENT_NOQUOTES,'UTF-8');
	$idProcesoProyecto = htmlspecialchars ($_POST['idProcesoProyecto'],ENT_NOQUOTES,'UTF-8');
	$idComponente = htmlspecialchars ($_POST['idComponente'],ENT_NOQUOTES,'UTF-8');
	$idPrograma = htmlspecialchars ($_POST['idPrograma'],ENT_NOQUOTES,'UTF-8');
	$idCodigoProyecto = htmlspecialchars ($_POST['idCodigoProyecto'],ENT_NOQUOTES,'UTF-8');
	
	$nombreActividad = htmlspecialchars ($_POST['nombreActividad'],ENT_NOQUOTES,'UTF-8');
	$idCodigoActividad = htmlspecialchars ($_POST['codigoActividad'],ENT_NOQUOTES,'UTF-8');
	$codigoCodigoActividad = htmlspecialchars ($_POST['codigoCodigoActividad'],ENT_NOQUOTES,'UTF-8');
	/*$idArea = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
	$nombreArea = htmlspecialchars ($_POST['nombreArea'],ENT_NOQUOTES,'UTF-8');*/
	$anio = htmlspecialchars ($_POST['anio'],ENT_NOQUOTES,'UTF-8');
	$identificador = ($_SESSION['usuario']);
	
	try {
		$conexion = new Conexion();
		$cpp = new ControladorProgramacionPresupuestaria();
		
		$actividad = $cpp->buscarActividad($conexion, $nombreActividad, $idComponente);
		
		if(pg_num_rows($actividad) == 0){
			$conexion->ejecutarConsulta("begin;");
			$idActividad = pg_fetch_result($cpp->nuevaActividad($conexion, $nombreActividad, 
												$idCodigoActividad, $codigoCodigoActividad, $anio, 
												$idComponente, $identificador),
												 0, 'id_actividad');
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpp->imprimirLineaActividad($idActividad, $nombreActividad,
					$codigoCodigoActividad, $idObjetivoEstrategico, $idObjetivoEspecifico, 
					$idObjetivoOperativo, $idProcesoProyecto, $idComponente, $idPrograma, 
					$idCodigoProyecto, $idCodigoActividad, $anio, 'programacionAnualPresupuestaria');
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "La actividad seleccionado ya existe dentro de esta clasificación, por favor verificar en el listado.";
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
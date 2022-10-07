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
	$idPrograma = htmlspecialchars ($_POST['idPrograma'],ENT_NOQUOTES,'UTF-8');
	
	$nombreComponente = htmlspecialchars ($_POST['nombreComponente'],ENT_NOQUOTES,'UTF-8');
	$idCodigoProyecto = htmlspecialchars ($_POST['codigoProyecto'],ENT_NOQUOTES,'UTF-8');
	$codigoCodigoProyecto = htmlspecialchars ($_POST['codigoCodigoProyecto'],ENT_NOQUOTES,'UTF-8');
	/*$idArea = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
	$nombreArea = htmlspecialchars ($_POST['nombreArea'],ENT_NOQUOTES,'UTF-8');*/
	$anio = htmlspecialchars ($_POST['anio'],ENT_NOQUOTES,'UTF-8');
	$identificador = ($_SESSION['usuario']);
	
	try {
		$conexion = new Conexion();
		$cpp = new ControladorProgramacionPresupuestaria();
		
		$componente = $cpp->buscarComponente($conexion, $nombreComponente, $idProcesoProyecto);
		
		if(pg_num_rows($componente) == 0){
			$conexion->ejecutarConsulta("begin;");
			$idComponente = pg_fetch_result($cpp->nuevoComponente($conexion, $nombreComponente, $idCodigoProyecto, $codigoCodigoProyecto, $anio, $idProcesoProyecto, $identificador),
												 0, 'id_componente');
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpp->imprimirLineaComponente($idComponente, $nombreComponente, $codigoCodigoProyecto,
					 $idObjetivoEstrategico, $idObjetivoEspecifico, $idObjetivoOperativo, $idProcesoProyecto, 
					$idPrograma, $idCodigoProyecto, $anio, 'programacionAnualPresupuestaria');
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "El componente seleccionado ya existe dentro de esta clasificación, por favor verificar en el listado.";
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
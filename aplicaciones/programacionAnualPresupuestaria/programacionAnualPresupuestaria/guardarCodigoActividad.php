<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$idPrograma = htmlspecialchars ($_POST['idPrograma'],ENT_NOQUOTES,'UTF-8');
	$idProyecto = htmlspecialchars ($_POST['idCodigoProyecto'],ENT_NOQUOTES,'UTF-8');
	$nombreActividad = htmlspecialchars ($_POST['nombreActividad'],ENT_NOQUOTES,'UTF-8');
	$codigoActividad = htmlspecialchars ($_POST['codigoActividad'],ENT_NOQUOTES,'UTF-8');
	$idProvincia = htmlspecialchars ($_POST['idProvincia'],ENT_NOQUOTES,'UTF-8');
	$nombreProvincia = htmlspecialchars ($_POST['nombreProvincia'],ENT_NOQUOTES,'UTF-8');
	$codigoGeograficoProvincia = htmlspecialchars ($_POST['geograficoProvincia'],ENT_NOQUOTES,'UTF-8');
	$idCanton = htmlspecialchars ($_POST['idCanton'],ENT_NOQUOTES,'UTF-8');
	$nombreCanton = htmlspecialchars ($_POST['nombreCanton'],ENT_NOQUOTES,'UTF-8');
	$codigoGeograficoCanton = htmlspecialchars ($_POST['geograficoCanton'],ENT_NOQUOTES,'UTF-8');
	$identificador = ($_SESSION['usuario']);
	
	try {
		$conexion = new Conexion();
		$cpp = new ControladorProgramacionPresupuestaria();
		
		$actividad = $cpp->buscarCodigoActividad($conexion, $codigoActividad, $idProyecto);
		
		if(pg_num_rows($actividad) == 0){
			$conexion->ejecutarConsulta("begin;");
			$idCodigoActividad = pg_fetch_result($cpp->nuevoCodigoActividad($conexion, $nombreActividad, $codigoActividad, $idProyecto, 
														$idProvincia, $nombreProvincia, $codigoGeograficoProvincia, 
														$idCanton, $nombreCanton, $codigoGeograficoCanton, $identificador),
														 0, 'id_codigo_actividad');
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpp->imprimirLineaCodigoActividad($idCodigoActividad, $nombreActividad, $codigoActividad, 
														$idProyecto, $idPrograma, $nombreProvincia, $codigoGeograficoProvincia, 
														$nombreCanton, $codigoGeograficoCanton, 'programacionAnualPresupuestaria');
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "La actividad ya existe dentro de esta clasificación, por favor verificar en el listado.";
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
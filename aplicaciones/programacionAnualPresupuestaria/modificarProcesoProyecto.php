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
	
	$nombreProcesoProyecto = htmlspecialchars ($_POST['nombreProcesoProyecto'],ENT_NOQUOTES,'UTF-8');
	$productoFinal = htmlspecialchars ($_POST['productoFinal'],ENT_NOQUOTES,'UTF-8');
	$tipo = htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8');
	$financiamiento = htmlspecialchars ($_POST['financiamiento'],ENT_NOQUOTES,'UTF-8');
	$idPrograma = htmlspecialchars ($_POST['programa'],ENT_NOQUOTES,'UTF-8');
	$codigoPrograma = htmlspecialchars ($_POST['codigoPrograma'],ENT_NOQUOTES,'UTF-8');
	$idArea = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
	$nombreArea = htmlspecialchars ($_POST['nombreArea'],ENT_NOQUOTES,'UTF-8');
	$anio = htmlspecialchars ($_POST['anio'],ENT_NOQUOTES,'UTF-8');
	$identificador = ($_SESSION['usuario']);
	
	try {
		$conexion = new Conexion();
		$cpp = new ControladorProgramacionPresupuestaria();

		$conexion->ejecutarConsulta("begin;");
		$cpp->modificarProcesoProyecto($conexion, $idProcesoProyecto, $nombreProcesoProyecto, $productoFinal, 
				$tipo, $financiamiento, $idObjetivoOperativo, $idArea, $nombreArea, $idPrograma, 
				$codigoPrograma, $identificador);
		$conexion->ejecutarConsulta("commit;");
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos fueron actualizados';

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
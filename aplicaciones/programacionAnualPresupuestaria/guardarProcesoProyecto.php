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
	
	$nombreProcesoProyecto = htmlspecialchars ($_POST['nombreProcesoProyecto'],ENT_NOQUOTES,'UTF-8');
	$productoFinal = htmlspecialchars ($_POST['productoFinal'],ENT_NOQUOTES,'UTF-8');
	$tipo = htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8');
	$financiamiento = htmlspecialchars ($_POST['financiamiento'],ENT_NOQUOTES,'UTF-8');
	$idPrograma = htmlspecialchars ($_POST['programa'],ENT_NOQUOTES,'UTF-8');
	$nombrePrograma = htmlspecialchars ($_POST['nombrePrograma'],ENT_NOQUOTES,'UTF-8');
	$codigoPrograma = htmlspecialchars ($_POST['codigoPrograma'],ENT_NOQUOTES,'UTF-8');
	$idArea = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
	$nombreArea = htmlspecialchars ($_POST['nombreArea'],ENT_NOQUOTES,'UTF-8');
	$anio = htmlspecialchars ($_POST['anio'],ENT_NOQUOTES,'UTF-8');
	$areaObjetivoOperativo = htmlspecialchars ($_POST['idAreaOO'],ENT_NOQUOTES,'UTF-8');
	
	$identificador = ($_SESSION['usuario']);
	
	try {
		$conexion = new Conexion();
		$cpp = new ControladorProgramacionPresupuestaria();
		
		$procesoProyecto = $cpp->buscarProcesoProyecto($conexion, $nombreProcesoProyecto, $idArea, $idObjetivoOperativo);
		
		if(pg_num_rows($procesoProyecto) == 0){
			$conexion->ejecutarConsulta("begin;");
			$idProcesoProyecto = pg_fetch_result($cpp->nuevoProcesoProyecto($conexion, $nombreProcesoProyecto, 
					$productoFinal, $anio, $tipo, $financiamiento, $idObjetivoOperativo, $idArea, $nombreArea,
					 $idPrograma, $codigoPrograma, $identificador), 0, 'id_proceso_proyecto');
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpp->imprimirLineaProcesoProyecto($idProcesoProyecto, $nombreProcesoProyecto,
            			                                             $tipo, $financiamiento, $codigoPrograma, $nombrePrograma, $nombreArea, $idObjetivoEstrategico,
            												         $idObjetivoEspecifico, $idObjetivoOperativo, $areaObjetivoOperativo, $anio, 'programacionAnualPresupuestaria');
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "El proceso o proyecto seleccionado ya existe dentro de esta clasificación, por favor verificar en el listado.";
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
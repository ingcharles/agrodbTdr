<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$idPrograma = htmlspecialchars ($_POST['idPrograma'],ENT_NOQUOTES,'UTF-8');
	$nombreProyecto = htmlspecialchars ($_POST['nombreProyecto'],ENT_NOQUOTES,'UTF-8');
	$codigoProyecto = htmlspecialchars ($_POST['codigoProyecto'],ENT_NOQUOTES,'UTF-8');
	$identificador = ($_SESSION['usuario']);
	
	try {
		$conexion = new Conexion();
		$cpp = new ControladorProgramacionPresupuestaria();
		
		$proyecto = $cpp->buscarCodigoProyecto($conexion, $codigoProyecto, $idPrograma);
		
		if(pg_num_rows($proyecto) == 0){
			$conexion->ejecutarConsulta("begin;");
			$idCodigoProyecto = pg_fetch_result($cpp->nuevoCodigoProyecto($conexion, $nombreProyecto, $codigoProyecto, $idPrograma, $identificador), 0, 'id_codigo_proyecto');
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpp->imprimirLineaCodigoProyecto($idCodigoProyecto, $nombreProyecto, $codigoProyecto, $idPrograma, 'programacionAnualPresupuestaria');
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "El subtipo de producto seleccionado ya existe dentro de esta clasificación, por favor verificar en el listado.";
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
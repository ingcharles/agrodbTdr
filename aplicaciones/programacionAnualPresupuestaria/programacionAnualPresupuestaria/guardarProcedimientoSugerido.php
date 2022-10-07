<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$idTipoCompra = htmlspecialchars ($_POST['idTipoCompra'],ENT_NOQUOTES,'UTF-8');
	$nombreProcedimientoSugerido = htmlspecialchars ($_POST['nombreProcedimientoSugerido'],ENT_NOQUOTES,'UTF-8');
	$identificador = ($_SESSION['usuario']);
	
	try {
		$conexion = new Conexion();
		$cpp = new ControladorProgramacionPresupuestaria();
		
		$procedimientoSugerido = $cpp->buscarProcedimientoSugerido($conexion, $nombreProcedimientoSugerido, $idTipoCompra);
		
		if(pg_num_rows($procedimientoSugerido) == 0){
			$conexion->ejecutarConsulta("begin;");
			$idProcedimientoSugerido = pg_fetch_result($cpp->nuevoProcedimientoSugerido($conexion, $nombreProcedimientoSugerido, $idTipoCompra, $identificador), 0, 'id_procedimiento_sugerido');
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpp->imprimirLineaProcedimientoSugerido($idProcedimientoSugerido, $nombreProcedimientoSugerido, 
												$idTipoCompra, 'programacionAnualPresupuestaria');
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
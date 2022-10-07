<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
	
	try{
		$idCodigoProyecto = $_POST['idCodigoProyecto'];
		$identificador = $_SESSION['usuario'];
		 
		try {
			$conexion = new Conexion();
			$cpp = new ControladorProgramacionPresupuestaria();
			
			$conexion->ejecutarConsulta("begin;");
						
			while ($actividades = pg_fetch_assoc($cpp->listarCodigoActividad($conexion, $idCodigoProyecto))){
				$cpp->eliminarCodigoActividad($conexion, $actividades['id_codigo_actividad'], $identificador);
			}
			
			$cpp->eliminarCodigoProyecto($conexion, $idCodigoProyecto, $identificador);
			$conexion->ejecutarConsulta("commit;");
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $idCodigoProyecto;
			
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
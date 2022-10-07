<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';

$fecha = getdate();
$anio = $fecha['year'];

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
	
	try{
		$idProcesoProyecto = $_POST['idProcesoProyecto'];
		$identificador = $_SESSION['usuario'];
		 
		try {
			$conexion = new Conexion();
			$cpp = new ControladorProgramacionPresupuestaria();
			
			$conexion->ejecutarConsulta("begin;");		

			/*while ($componente = pg_fetch_assoc($cpp->listarComponenteXProcesoProyecto($conexion, $idProcesoProyecto))){
				$cpp->eliminarActividadXComponente($conexion, $componente['id_componente'], $identificador);
			}*/
					
			$cpp->eliminarComponenteXProcesoProyecto($conexion, $idProcesoProyecto, $identificador);
			$cpp->eliminarProcesoProyecto($conexion, $idProcesoProyecto, $identificador);
			
			$conexion->ejecutarConsulta("commit;");
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $idProcesoProyecto;
			
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
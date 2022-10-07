<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$fecha = getdate();
	$anio = $fecha['year'];
	
	$planificacionAnual = $_POST['id'];
	$identificador = $_POST['identificador'];
	
	try {
		$conexion = new Conexion();
		$ca = new ControladorAreas();
		$cpp = new ControladorProgramacionPresupuestaria();

		$idAprobadorDGPGE = pg_fetch_result($ca->buscarResponsableSubproceso($conexion,'DGPGE' ), 0, 'identificador');
			
		if ($identificador != ''){
			if($idAprobadorDGPGE == $identificador){
				$conexion->ejecutarConsulta("begin;");
				
				$cpp -> cerrarProcesoPlanificacionAnual($conexion, $identificador, 'aprobado', 'aprobadoDGPGE');
						
				$conexion->ejecutarConsulta("commit;");
				
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
			}else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = 'Solamente el Director General de Planificación puede finalizar el proceso.';
			}
					
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Su sesión expiró, por favor ingrese nuevamente al sistema";
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
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

		if ($identificador != ''){
			for ($i = 0; $i < count($planificacionAnual); $i++) {
				$conexion->ejecutarConsulta("begin;");
			
				$elementosPlanificacionAnual = pg_fetch_assoc($cpp->abrirProgramacionAnualRevision($conexion, $planificacionAnual[$i]));
			
				if($elementosPlanificacionAnual['estado'] == 'revisado'){
					$cpp -> enviarPlanificacionAnual($conexion, $planificacionAnual[$i], 'enviadoAprobador');
					
					//Asignar Aprobador
					$idAprobadorDGPGE = pg_fetch_result($ca->buscarResponsableSubproceso($conexion,'DGPGE' ), 0, 'identificador');
					$cpp->asignarAprobadorPlanificacionAnual($conexion, $elementosPlanificacionAnual['id_planificacion_anual'], $idAprobadorDGPGE, 'DGPGE');
				}
			
				$elementosPresupuesto = $cpp->listarPresupuestos($conexion, $planificacionAnual[$i], $anio);
			
				while ($presupuestos = pg_fetch_assoc($elementosPresupuesto)){
					if(($presupuestos['estado'] == 'revisado')){
						$cpp -> enviarPresupuesto($conexion, $presupuestos['id_presupuesto'], 'enviadoAprobador');
						
						//Asignar Aprobador
						$idAprobadorDGAF = pg_fetch_result($ca->buscarResponsableSubproceso($conexion,'DGAF' ), 0, 'identificador');
						$cpp->asignarAprobadorPresupuesto($conexion, $presupuestos['id_presupuesto'], $idAprobadorDGAF, 'DGAF');
					}
				}
			
				$conexion->ejecutarConsulta("commit;");
			}
	
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
					
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
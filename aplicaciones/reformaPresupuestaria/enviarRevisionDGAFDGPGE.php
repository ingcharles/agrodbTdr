<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorReformaPresupuestaria.php';

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
		$crp = new ControladorReformaPresupuestaria();

		if ($identificador != ''){
			for ($i = 0; $i < count($planificacionAnual); $i++) {
				$conexion->ejecutarConsulta("begin;");
			
				$elementosPlanificacionAnual = pg_fetch_assoc($crp->abrirProgramacionAnualRevisionTemporal($conexion, $planificacionAnual[$i]));
			
				if($elementosPlanificacionAnual['estado'] == 'revisado'){
					//Revisar si es Gasto Corriente o Proyectos de Inversión para asignación
					//en proceso de revisión
					if(($elementosPlanificacionAnual['tipo'] == 'Proceso') || ($elementosPlanificacionAnual['tipo'] == 'Proyecto Gasto Corriente')){
						$crp -> enviarPlanificacionAnualTemporal($conexion, $planificacionAnual[$i], 'enviadoRevisorGA');
						
						//Asignar Aprobador
						$idAprobadorGA = pg_fetch_result($ca->buscarResponsableSubproceso($conexion,'GA' ), 0, 'identificador');
						$crp->asignarAprobadorPlanificacionAnualTemporal($conexion, $elementosPlanificacionAnual['id_planificacion_anual'], $idAprobadorGA, 'GA');
						
					}else if ($elementosPlanificacionAnual['tipo'] == 'Proyecto Inversion'){
						$crp -> enviarPlanificacionAnualTemporal($conexion, $planificacionAnual[$i], 'enviadoRevisorDGPGE');
						
						//Asignar Aprobador
						$idAprobadorDGPGE = pg_fetch_result($ca->buscarResponsableSubproceso($conexion,'DGPGE' ), 0, 'identificador');
						$crp->asignarAprobadorPlanificacionAnualTemporal($conexion, $elementosPlanificacionAnual['id_planificacion_anual'], $idAprobadorDGPGE, 'DGPGE');
					}
				}
			
				$elementosPresupuesto = $crp->listarPresupuestosTemporales($conexion, $planificacionAnual[$i]);
			
				while ($presupuestos = pg_fetch_assoc($elementosPresupuesto)){
					if(($presupuestos['estado'] == 'revisado')){
						//Revisar si es Gasto Corriente o Proyectos de Inversión para asignación
						//en proceso de revisión
						if($presupuestos['tipo_presupuesto'] == 'Gasto Corriente'){						
							$crp -> enviarPresupuestoTemporal($conexion, $presupuestos['id_presupuesto'], 'enviadoRevisorGA');
							
							//Asignar Aprobador
							$idAprobadorGA = pg_fetch_result($ca->buscarResponsableSubproceso($conexion,'GA' ), 0, 'identificador');
							$crp->asignarAprobadorPresupuestoTemporal($conexion, $presupuestos['id_presupuesto'], $idAprobadorGA, 'GA');
						}else{
							$crp -> enviarPresupuestoTemporal($conexion, $presupuestos['id_presupuesto'], 'enviadoRevisorDGPGE');
							
							//Asignar Aprobador
							$idAprobadorDGPGE = pg_fetch_result($ca->buscarResponsableSubproceso($conexion,'DGPGE' ), 0, 'identificador');
							$crp->asignarAprobadorPresupuestoTemporal($conexion, $presupuestos['id_presupuesto'], $idAprobadorDGPGE, 'DGPGE');
						}
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
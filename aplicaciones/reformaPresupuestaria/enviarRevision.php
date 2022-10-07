<?php
session_start();
require_once '../../clases/Conexion.php';
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
		$crp = new ControladorReformaPresupuestaria();

		if ($identificador != ''){
			for ($i = 0; $i < count($planificacionAnual); $i++) {
				$conexion->ejecutarConsulta("begin;");
				
				$elementosPlanificacionAnual = pg_fetch_assoc($crp->abrirProgramacionAnualTemporal($conexion, $planificacionAnual[$i], $identificador));
				
				if(($elementosPlanificacionAnual['estado'] == 'creado') || ($elementosPlanificacionAnual['estado'] == 'rechazado')){
					$crp -> enviarPlanificacionAnualTemporal($conexion, $planificacionAnual[$i], 'enviadoRevisor');
				}
				
				$elementosPresupuesto = $crp->listarPresupuestosTemporales($conexion, $planificacionAnual[$i]);
				
				while ($presupuestos = pg_fetch_assoc($elementosPresupuesto)){
					if(($presupuestos['estado'] == 'creado') || ($presupuestos['estado'] == 'rechazado')){
						$crp -> enviarPresupuestoTemporal($conexion, $presupuestos['id_presupuesto'], 'enviadoRevisor');
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
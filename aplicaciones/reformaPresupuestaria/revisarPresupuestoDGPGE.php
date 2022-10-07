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
	
	$idPlanificacionAnual = $_POST['idPlanificacionAnual'];
	$idPresupuesto = $_POST['idPresupuesto'];
	$idRevisor = $_POST['identificadorRevisor'];
	$estado = $_POST['estadoRevision'];
	$observaciones = $_POST['observaciones'];
	
	$detalleGasto = htmlspecialchars ($_POST['detalleGasto'],ENT_NOQUOTES,'UTF-8');
	$cantidadAnual = htmlspecialchars ($_POST['cantidadAnual'],ENT_NOQUOTES,'UTF-8');
	
	$idUnidadMedida = htmlspecialchars ($_POST['idUnidadMedida'],ENT_NOQUOTES,'UTF-8');
	$unidadMedida = htmlspecialchars ($_POST['nombreUnidadMedida'],ENT_NOQUOTES,'UTF-8');
	
	$costo = htmlspecialchars ($_POST['costo'],ENT_NOQUOTES,'UTF-8');
	$iva = htmlspecialchars ($_POST['iva'],ENT_NOQUOTES,'UTF-8');
	$costoIva = htmlspecialchars ($_POST['costoIva'],ENT_NOQUOTES,'UTF-8');
	
	$idCuatrimestre = htmlspecialchars ($_POST['idCuatrimestre'],ENT_NOQUOTES,'UTF-8');
	$cuatrimestre = htmlspecialchars ($_POST['nombreCuatrimestre'],ENT_NOQUOTES,'UTF-8');
	
	try {
		$conexion = new Conexion();
		$ca = new ControladorAreas();
		$crp = new ControladorReformaPresupuestaria();
		
		$identificador = $_POST['identificador'];
		$areaFuncionario = pg_fetch_assoc($ca->areaUsuario($conexion, $identificador));
		$idAreaFuncionario = $areaFuncionario['id_area'];
		$idAreaRevisor = $areaFuncionario['id_area_padre'];

		if ($idRevisor != ''){
			$conexion->ejecutarConsulta("begin;");
				$crp -> aprobarPresupuestoTemporalDGPGE($conexion, $idPresupuesto, $estado, $observaciones, $idRevisor);
				
				//crear opciones para control de cambios y auditorias!!!!!
				//todos los cambios deben ser registrados en una tabla con los campos, fechas y observaciones
				//del usuario debe registrarse también cada proceso de revisión y aprobación o rechazo con fechas
				//de las tablas temporales y de las finales en una sola.
				
				$numControlCambios = pg_fetch_result($crp->generarNumeroControlCambios($conexion, $idPlanificacionAnual, $idPresupuesto), 0, 'numero');
				$numControlCambios++;
				
				$crp -> registrarControlCambios($conexion, $idPlanificacionAnual, $idPresupuesto, $numControlCambios,
						$identificador, $idAreaFuncionario, 'Revisión de presupuesto por la Dirección General de Planificación y Gestión Estratégica', $detalleGasto,
						 $idUnidadMedida, $unidadMedida, $costo, $iva, $costoIva, $cuatrimestre,
						$idRevisor, $idAreaRevisor, $estado, $observaciones,
						'revisionDGPGE');
				
				//verificar cambio de estado final para Planificacion Anual
				
				$presupuesto = $crp->listarPresupuestosTemporales($conexion, $idPlanificacionAnual);
				
				$total = pg_fetch_assoc($crp->numeroPresupuestosYCostoTotalIVATemporal($conexion, $idPlanificacionAnual));
				$presupuestosRevisados = pg_fetch_result($crp->numeroPresupuestosRevisadosTemporal($conexion, $idPlanificacionAnual, 'revisadoDGPGE'), 0, 'num_presupuestos_revisados');
				$presupuestosRechazados = pg_fetch_result($crp->numeroPresupuestosRevisadosTemporal($conexion, $idPlanificacionAnual, 'rechazado'), 0, 'num_presupuestos_revisados');
				$presupuestosEnviadosRevisor = pg_fetch_result($crp->numeroPresupuestosXEstadoTemporal($conexion, $idPlanificacionAnual, 'enviadoRevisorDGPGE'), 0, 'num_presupuestos_revisados');
				$presupuestosAprobados = pg_fetch_result($crp->numeroPresupuestosXEstadoTemporal($conexion, $idPlanificacionAnual, 'aprobado'), 0, 'num_presupuestos_revisados');
				
				if(($total['num_presupuestos'] - $presupuestosAprobados) == $presupuestosRevisados){
					$crp -> enviarPlanificacionAnualTemporal($conexion, $idPlanificacionAnual, 'revisadoDGPGE');
					
					//Ingresar fecha de revision y Observaciones
					$crp -> actualizarFechaRevisionReformaPlanificacionAnualTemporal($conexion, $idPlanificacionAnual, 'DGPGE', 'revisado');
				}
			
			$conexion->ejecutarConsulta("commit;");
	
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
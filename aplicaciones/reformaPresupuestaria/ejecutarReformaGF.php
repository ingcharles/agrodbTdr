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
	$numeroCur = $_POST['numeroCur'];
	$observaciones = $_POST['observaciones'];
	
	try {
		$conexion = new Conexion();
		$ca = new ControladorAreas();
		$crp = new ControladorReformaPresupuestaria();

		if ($identificador != ''){
			for ($i = 0; $i < count($planificacionAnual); $i++) {
				$conexion->ejecutarConsulta("begin;");
			
				$elementosPlanificacionAnual = pg_fetch_assoc($crp->abrirProgramacionAnualRevisionTemporal($conexion, $planificacionAnual[$i]));
			
				if($elementosPlanificacionAnual['estado'] == 'enviadoRevisorGF'){

					$crp -> enviarPlanificacionAnualTemporal($conexion, $planificacionAnual[$i], 'aprobado');
					
					//Ingresar fecha de revision y Observaciones
					$crp -> actualizarFechaRevisionReformaPlanificacionAnualTemporal($conexion, $planificacionAnual[$i], 'GF', $observaciones);
										
					//Copiar datos a Planificacion Anual Real
					$crp -> actualizarProgramacionAnualReformaPresupuestaria($conexion, $planificacionAnual[$i], 
							$elementosPlanificacionAnual['identificador_revisor'], $elementosPlanificacionAnual['id_area_revisor'], 
							$elementosPlanificacionAnual['fecha_revision'], $elementosPlanificacionAnual['observaciones_revision'], 
							$elementosPlanificacionAnual['identificador_aprobador'], $elementosPlanificacionAnual['id_area_aprobador'], 
							$elementosPlanificacionAnual['fecha_aprobacion'], $elementosPlanificacionAnual['observaciones_aprobacion'], 
							$elementosPlanificacionAnual['identificador_aprobador_ga'], $elementosPlanificacionAnual['id_area_aprobador_ga'], 
							$elementosPlanificacionAnual['fecha_aprobacion_ga'], $elementosPlanificacionAnual['observaciones_aprobacion_ga'],  
							$elementosPlanificacionAnual['identificador_aprobador_gf'], $elementosPlanificacionAnual['id_area_aprobador_gf'], 
							$observaciones,
							$elementosPlanificacionAnual['tipo']);
						
				}
			
				$elementosPresupuesto = $crp->listarPresupuestosTemporales($conexion, $planificacionAnual[$i]);
			
				while ($presupuestos = pg_fetch_assoc($elementosPresupuesto)){
					if(($presupuestos['estado'] == 'enviadoRevisorGF')){
				
						$crp -> enviarPresupuestoTemporal($conexion, $presupuestos['id_presupuesto'], 'aprobado');
						
						//Ingresar fecha de revision y Observaciones
						$crp -> actualizarFechaRevisionReformaPresupuestoTemporal($conexion, $presupuestos['id_presupuesto'], 'GF', $observaciones);
						
						//Ingresar número de CUR
						$crp -> actualizarNumeroCurPresupuestoTemporal($conexion, $presupuestos['id_presupuesto'], $presupuestos['numero_cur'].'-'.$numeroCur);
						
						//Actualizar Presupuesto Original con el valor aprobado
						$crp -> actualizarCostoIvaOriginalTemporal($conexion, $presupuestos['id_presupuesto'], $presupuestos['costo'], $presupuestos['iva'], $presupuestos['costo_iva']);
						
						//crear opciones para control de cambios y auditorias!!!!!
						//todos los cambios deben ser registrados en una tabla con los campos, fechas y observaciones
						//del usuario debe registrarse también cada proceso de revisión y aprobación o rechazo con fechas
						//de las tablas temporales y de las finales en una sola.
							
						$numControlCambios = pg_fetch_result($crp->generarNumeroControlCambios($conexion, $planificacionAnual[$i], $presupuestos['id_presupuesto']), 0, 'numero');
						$numControlCambios++;
							
						$crp -> registrarControlCambios($conexion, $planificacionAnual[$i], $presupuestos['id_presupuesto'], $numControlCambios,
								$presupuestos['identificador'], $presupuestos['id_area'], 'Revisión de presupuesto por Gestión Financiera - DGAF', $presupuestos['detalle_gasto'],
								$presupuestos['id_unidad_medida'], $presupuestos['unidad_medida'], $presupuestos['costo'], $presupuestos['iva'], 
								$presupuestos['costo_iva'], $presupuestos['cuatrimestre'], $identificador, 'GF', 'aprobado', $observaciones,
								'revisionGF');
						
						//Copiar datos a Presupuesto Asignado Real
						//Verificar si el registro existe o sino crearlo
						
						$presupuestoReal = $crp -> abrirPresupuesto($conexion, $presupuestos['id_presupuesto']);
						$presupuestoTemporal = pg_fetch_assoc($crp -> abrirPresupuestoTemporal($conexion, $presupuestos['id_presupuesto']));
						
						if(pg_num_rows($presupuestoReal) != 0){
						
							$crp -> actualizarPresupuestoReformaPresupuestaria($conexion, $presupuestoTemporal['id_presupuesto'],
									$presupuestoTemporal['detalle_gasto'], $presupuestoTemporal['id_unidad_medida'], $presupuestoTemporal['unidad_medida'],
									$presupuestoTemporal['costo'], $presupuestoTemporal['iva'], $presupuestoTemporal['costo_iva'], $presupuestoTemporal['cuatrimestre'],
									$presupuestoTemporal['numero_cur'], $presupuestoTemporal['tipo_cambio'],
									$presupuestoTemporal['identificador_revisor'], $presupuestoTemporal['id_area_revisor'],
									$presupuestoTemporal['fecha_revision'], $presupuestoTemporal['observaciones_revision'],
									$presupuestoTemporal['identificador_revisor_dgpge'], $presupuestoTemporal['id_area_revisor_dgpge'],
									$presupuestoTemporal['fecha_revisor_dgpge'], $presupuestoTemporal['observaciones_revisor_dgpge'],
									$presupuestoTemporal['identificador_revisor_ga'], $presupuestoTemporal['id_area_revisor_ga'],
									$presupuestoTemporal['fecha_revision_ga'], $presupuestoTemporal['observaciones_revision_ga'],
									$presupuestoTemporal['identificador_revisor_gf'], $presupuestoTemporal['id_area_revisor_gf'],
									$presupuestoTemporal['fecha_revision_gf'], $presupuestoTemporal['observaciones_revision_gf'],
									$presupuestoTemporal['costo_original'], $presupuestoTemporal['iva_original'], $presupuestoTemporal['costo_iva_original'],
									$presupuestoTemporal['tipo_presupuesto']);
						}else{
							
							$crp -> nuevoPresupuestoReformaPresupuestaria($conexion, $presupuestoTemporal['id_presupuesto'], 
									$presupuestoTemporal['identificador'], $presupuestoTemporal['id_area'], 
									$presupuestoTemporal['fecha_creacion'], $presupuestoTemporal['anio'], $presupuestoTemporal['id_planificacion_anual'], 
									$presupuestoTemporal['ejercicio'], $presupuestoTemporal['entidad'], $presupuestoTemporal['id_unidad_ejecutora'], 
									$presupuestoTemporal['unidad_ejecutora'], $presupuestoTemporal['id_unidad_desconcentrada'], 
									$presupuestoTemporal['unidad_desconcentrada'], $presupuestoTemporal['programa'], 
									$presupuestoTemporal['subprograma'], $presupuestoTemporal['codigo_proyecto'], $presupuestoTemporal['codigo_actividad'], 
									$presupuestoTemporal['obra'], $presupuestoTemporal['geografico'], $presupuestoTemporal['id_renglon'], 
									$presupuestoTemporal['renglon'], $presupuestoTemporal['renglon_auxiliar'], $presupuestoTemporal['fuente'], 
									$presupuestoTemporal['organismo'], $presupuestoTemporal['correlativo'], $presupuestoTemporal['id_cpc'], 
									$presupuestoTemporal['cpc'], $presupuestoTemporal['id_tipo_compra'], $presupuestoTemporal['tipo_compra'], 
									$presupuestoTemporal['id_actividad'], $presupuestoTemporal['nombre_actividad'], 
									$presupuestoTemporal['actividad'], $presupuestoTemporal['detalle_gasto'], $presupuestoTemporal['cantidad_anual'], 
									$presupuestoTemporal['id_unidad_medida'], $presupuestoTemporal['unidad_medida'], $presupuestoTemporal['costo'], 
									$presupuestoTemporal['cuatrimestre'], $presupuestoTemporal['tipo_producto'], 
									$presupuestoTemporal['catalogo_electronico'], $presupuestoTemporal['id_procedimiento_sugerido'], 
									$presupuestoTemporal['procedimiento_sugerido'], $presupuestoTemporal['fondos_bid'], $presupuestoTemporal['operacion_bid'], 
									$presupuestoTemporal['proyecto_bid'], $presupuestoTemporal['tipo_regimen'], $presupuestoTemporal['tipo_presupuesto'], 
									$presupuestoTemporal['identificador_revisor'], $presupuestoTemporal['id_area_revisor'], 
									$presupuestoTemporal['fecha_revision'], $presupuestoTemporal['observaciones_revision'], 
									$presupuestoTemporal['identificador_revisor_dgpge'], $presupuestoTemporal['id_area_revisor_dgpge'], 
									$presupuestoTemporal['fecha_revision_dgpge'], $presupuestoTemporal['observaciones_revision_dgpge'], 
									'aprobado', $presupuestoTemporal['fecha_modificacion'], 
									$presupuestoTemporal['agregar_pac'], 
									$presupuestoTemporal['iva'], $presupuestoTemporal['costo_iva'], $presupuestoTemporal['numero_cur'], 
									$presupuestoTemporal['identificador_revisor_ga'], $presupuestoTemporal['id_area_revisor_ga'], $presupuestoTemporal['fecha_revision_ga'], 
									$presupuestoTemporal['observaciones_revision_ga'], $presupuestoTemporal['identificador_revisor_gf'], $presupuestoTemporal['id_area_revisor_gf'], 
									$presupuestoTemporal['fecha_revision_gf'], $presupuestoTemporal['observaciones_revision_gf'], 
									$presupuestoTemporal['costo_original'], $presupuestoTemporal['iva_original'], 
									$presupuestoTemporal['costo_iva_original'], $presupuestoTemporal['tipo_cambio']);
						}	
						
						
						//crear opciones para control de cambios y auditorias!!!!!
						//todos los cambios deben ser registrados en una tabla con los campos, fechas y observaciones
						//del usuario debe registrarse también cada proceso de revisión y aprobación o rechazo con fechas
						//de las tablas temporales y de las finales en una sola.
							
						$numControlCambios = pg_fetch_result($crp->generarNumeroControlCambios($conexion, $planificacionAnual[$i], $presupuestos['id_presupuesto']), 0, 'numero');
						$numControlCambios++;
							
						$crp -> registrarControlCambios($conexion, $planificacionAnual[$i], $presupuestos['id_presupuesto'], $numControlCambios,
								$presupuestos['identificador'], $presupuestos['id_area'], 'Ejecución de Reforma Presupuestaria, Actualización de registros por Gestión Financiera - DGAF', $presupuestos['detalle_gasto'],
								$presupuestos['id_unidad_medida'], $presupuestos['unidad_medida'], $presupuestos['costo'], $presupuestos['iva'],
								$presupuestos['costo_iva'], $presupuestos['cuatrimestre'], $identificador, 'GF', 'aprobado', $observaciones,
								'reforma');
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
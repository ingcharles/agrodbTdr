<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';
require_once '../../clases/ControladorReformaPresupuestaria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$fecha = getdate();
	$anio = $fecha['year'];
	
	$identificador = $_POST['identificador'];
	$observaciones = $_POST['observaciones'];
	
	try {
		$conexion = new Conexion();
		$ca = new ControladorAreas();
		$cpp = new ControladorProgramacionPresupuestaria();
		$crp = new ControladorReformaPresupuestaria();

		$idAprobadorDGAF = pg_fetch_result($ca->buscarResponsableSubproceso($conexion,'DGAF' ), 0, 'identificador');
		
		$ejercicio = $crp->buscarImportacionPapPac($conexion, $anio);
					
		if ($identificador != ''){
			if($idAprobadorDGAF == $identificador){
				if(pg_num_rows($ejercicio) == 0){
					$conexion->ejecutarConsulta("begin;");
					
					$planificacionAnual = $cpp -> listarProgramacionAnualAprobada($conexion, $anio);
					
					//copia ok
					//datos verificar
					
					while($fila = pg_fetch_assoc($planificacionAnual)){
						
						if($fila['total_presupuesto_solicitado']==null){
							$fila['total_presupuesto_solicitado']=0;
						}
						
						$pap = $crp->nuevaProgramacionAnualImportacion($conexion, $fila['id_planificacion_anual'], $fila['identificador'], 
															$fila['id_area_funcionario'], $fila['fecha_creacion'], $fila['anio'],
															$fila['id_objetivo_estrategico'], $fila['id_area_n2'], $fila['id_objetivo_especifico'], 
															$fila['id_area_n4'], $fila['id_objetivo_operativo'], $fila['id_area_unidad'], 
															$fila['id_proceso_proyecto'], $fila['id_componente'], $fila['producto_final'], 
															$fila['id_provincia'], $fila['provincia'], $fila['cantidad_usuarios'], 
															$fila['poblacion_objetivo'], $fila['medio_verificacion'], $fila['identificador_responsable'], 
															$fila['nombre_responsable'], $fila['total_presupuesto_solicitado'], $fila['identificador_revisor'], 
															$fila['id_area_revisor'], $fila['tipo'], $fila['id_actividad']);
						
						$papTemporal = $crp->nuevaProgramacionAnualTemporalImportacion($conexion, $fila['id_planificacion_anual'], $fila['identificador'],
															$fila['id_area_funcionario'], $fila['fecha_creacion'], $fila['anio'],
															$fila['id_objetivo_estrategico'], $fila['id_area_n2'], $fila['id_objetivo_especifico'],
															$fila['id_area_n4'], $fila['id_objetivo_operativo'], $fila['id_area_unidad'],
															$fila['id_proceso_proyecto'], $fila['id_componente'], $fila['producto_final'],
															$fila['id_provincia'], $fila['provincia'], $fila['cantidad_usuarios'],
															$fila['poblacion_objetivo'], $fila['medio_verificacion'], $fila['identificador_responsable'],
															$fila['nombre_responsable'], $fila['total_presupuesto_solicitado'], $fila['identificador_revisor'],
															$fila['id_area_revisor'], $fila['tipo'], $fila['id_actividad']);
					}
					
					
					//copia ok
					//datos verificar
					$presupuesto = $cpp -> listarPresupuestosAprobados($conexion, $anio);
					
					while($filaP = pg_fetch_assoc($presupuesto)){					
						$pres = $crp->nuevoPresupuestoImportacion  ($conexion, $filaP['id_presupuesto'], $filaP['identificador'], $filaP['id_area'], 
																$filaP['fecha_creacion'], $filaP['anio'], $filaP['id_planificacion_anual'], 
																$filaP['ejercicio'], $filaP['entidad'], $filaP['id_unidad_ejecutora'], 
																$filaP['unidad_ejecutora'], $filaP['id_unidad_desconcentrada'], $filaP['unidad_desconcentrada'], 
																$filaP['programa'], $filaP['subprograma'], $filaP['codigo_proyecto'], 
																$filaP['codigo_actividad'], $filaP['obra'], $filaP['geografico'], $filaP['id_renglon'], 
																$filaP['renglon'], $filaP['renglon_auxiliar'], $filaP['fuente'], $filaP['organismo'], 
																$filaP['correlativo'], $filaP['id_cpc'], $filaP['cpc'], $filaP['id_tipo_compra'], 
																$filaP['tipo_compra'], $filaP['id_actividad'], $filaP['nombre_actividad'], $filaP['actividad'], 
																$filaP['detalle_gasto'], $filaP['cantidad_anual'], $filaP['id_unidad_medida'], 
																$filaP['unidad_medida'], $filaP['costo'], $filaP['cuatrimestre'], $filaP['tipo_producto'], 
																$filaP['catalogo_electronico'], $filaP['id_procedimiento_sugerido'], $filaP['procedimiento_sugerido'], 
																$filaP['fondos_bid'], $filaP['operacion_bid'], $filaP['proyecto_bid'], $filaP['tipo_regimen'], 
																$filaP['tipo_presupuesto'], $filaP['identificador_revisor'], $filaP['id_area_revisor'], 
																$filaP['agregar_pac'], $filaP['iva'], $filaP['costo_iva']);
						
						//incremento de valores actuales
						$presTemporal = $crp->nuevoPresupuestoTemporalImportacion  ($conexion, $filaP['id_presupuesto'], $filaP['identificador'], $filaP['id_area'], 
																$filaP['fecha_creacion'], $filaP['anio'], $filaP['id_planificacion_anual'], 
																$filaP['ejercicio'], $filaP['entidad'], $filaP['id_unidad_ejecutora'], 
																$filaP['unidad_ejecutora'], $filaP['id_unidad_desconcentrada'], $filaP['unidad_desconcentrada'], 
																$filaP['programa'], $filaP['subprograma'], $filaP['codigo_proyecto'], 
																$filaP['codigo_actividad'], $filaP['obra'], $filaP['geografico'], $filaP['id_renglon'], 
																$filaP['renglon'], $filaP['renglon_auxiliar'], $filaP['fuente'], $filaP['organismo'], 
																$filaP['correlativo'], $filaP['id_cpc'], $filaP['cpc'], $filaP['id_tipo_compra'], 
																$filaP['tipo_compra'], $filaP['id_actividad'], $filaP['nombre_actividad'], $filaP['actividad'], 
																$filaP['detalle_gasto'], $filaP['cantidad_anual'], $filaP['id_unidad_medida'], 
																$filaP['unidad_medida'], $filaP['costo'], $filaP['cuatrimestre'], $filaP['tipo_producto'], 
																$filaP['catalogo_electronico'], $filaP['id_procedimiento_sugerido'], $filaP['procedimiento_sugerido'], 
																$filaP['fondos_bid'], $filaP['operacion_bid'], $filaP['proyecto_bid'], $filaP['tipo_regimen'], 
																$filaP['tipo_presupuesto'], $filaP['identificador_revisor'], $filaP['id_area_revisor'], 
																$filaP['agregar_pac'], $filaP['iva'], $filaP['costo_iva']);
						
					}
					
					$crp -> nuevaImportacionPapPac($conexion, $anio, $identificador, 'DGAF', $observaciones);
						
					$conexion->ejecutarConsulta("commit;");
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'Los datos han sido importados satisfactoriamente';
				}else{
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = 'Sólo puede ejecutar el proceso una vez.';
				}
			}else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = 'Solamente la Directora General Administrativa Financiera puede ejecutar el proceso.';
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
<?php

class ControladorReformaPresupuestaria{

	public function listarImportacionPapPac ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_reforma_presupuestaria.importacion_pap_pac p
											ORDER BY
												p.anio asc;");
	
		return $res;
	}
	
	public function buscarImportacionPapPac ($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_reforma_presupuestaria.importacion_pap_pac p
											WHERE
												p.anio = $anio
											ORDER BY
												p.anio asc;");
	
				return $res;
	}
	
	public function cerrarImportacionPapPac ($conexion, $anio, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_reforma_presupuestaria.importacion_pap_pac
										   	SET 
												estado='cerrado', 
												usuario_cierre='$identificador', 
												fecha_cierre=now()
										 	WHERE 
												anio=$anio;");
	
		return $res;
	}
	
	//REFPRES
	public function cerrarImportacionPlanificacionAnual ($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_reforma_presupuestaria.planificacion_anual
   											SET 
												estado='cerrado', 
												fecha_cierre=now()
 											WHERE 
												anio=$anio;");
	
		return $res;
	}
	
	//REFPRESTMP
	public function cerrarImportacionPlanificacionAnualTemporal ($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_reforma_presupuestaria.planificacion_anual_temporal
											SET
												estado='cerrado',
												fecha_cierre=now()
											WHERE
												anio=$anio;");
	
		return $res;
	}
	
	//REFPRES
	public function cerrarImportacionPresupuesto ($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_reforma_presupuestaria.presupuesto_asignado
											SET
												estado='cerrado',
												fecha_cierre=now()
											WHERE
												anio=$anio;");
	
		return $res;
	}
	
	//REFPRESTMP
	public function cerrarImportacionPresupuestoTemporal ($conexion, $anio){

		$res = $conexion->ejecutarConsulta("UPDATE
												g_reforma_presupuestaria.presupuesto_asignado_temporal
											SET
												estado='cerrado',
												fecha_cierre=now()
											WHERE
												anio=$anio;");
	
		return $res;
	}
	
	public function eliminarImportacionPapPac ($conexion, $anio, $identificador){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_reforma_presupuestaria.importacion_pap_pac
 											WHERE
												anio=$anio;");
	
				return $res;
	}

	public function nuevaImportacionPapPac ($conexion, $anio, $identificador, $idAreaAdministrador, $observaciones){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_reforma_presupuestaria.importacion_pap_pac(
										            anio, fecha_importacion, identificador_administrador, id_area_administrador, 
										            observaciones, estado)
										    VALUES ($anio, now(), '$identificador', '$idAreaAdministrador', 
										            '$observaciones', 'activo');");
	
		return $res;
	}
	
	public function imprimirLineaPresupuestoAprobado($idPresupuesto, $actividad, $detalleGasto, $renglon, $costo, $cantidadAnual, $cuatrimestre,
			$idPlanificacionAnual, $ruta, $estadoRevision){
			
		return '<tr id="R' . $idPresupuesto . '">' .
				'<td width="30%">' .
				$actividad .$estadoRevision.
				'</td>' .
				'<td width="30%">' .
				$detalleGasto .
				'</td>' .
				'<td width="10%">' .
				$renglon .
				'</td>' .
				'<td width="10%">' .
				$costo .
				'</td>' .
				'<td width="10%">' .
				$cuatrimestre .
				'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="'.$ruta.'" data-opcion="abrirPresupuestoAprobado" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idPresupuesto" value="' . $idPresupuesto . '" >' .
				'<input type="hidden" name="idPlanificacionAnual" value="' . $idPlanificacionAnual . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>';
	}
	
	public function  generarNumeroPresupuesto($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(id_presupuesto) as numero
											FROM
												g_reforma_presupuestaria.presupuesto_asignado_temporal;");
		return $res;
	}
	
	public function listarAniosPapPacRefPres ($conexion){
		
		$res = $conexion->ejecutarConsulta("SELECT 
												distinct(anio)
  											FROM 
												g_reforma_presupuestaria.planificacion_anual_temporal;");
		
		return $res;
	}
	
	//REFPRESTMP
	public function numeroPresupuestosReformadosTemporal ($conexion, $idPlanificacionAnual, $estado){
	
		$res = $conexion->ejecutarConsulta("SELECT
												count(id_presupuesto) as num_presupuestos
											FROM
												g_reforma_presupuestaria.presupuesto_asignado_temporal
											WHERE
												id_planificacion_anual = $idPlanificacionAnual and
												estado in ('$estado');");
					
		return $res;
	}
	
	
	//IMPORTACION DE DATOS DE PAP-PAC A REFORMA PRESUPUESTARIA
	//cambiado el estado como prueba apra ver el flujo de información
	//REFPRES
	public function nuevaProgramacionAnualImportacion ($conexion,$idPlanificacionAnual, $identificador, $idAreaFuncionario, $fechaCreacion, $anio,
			$idObjetivoEstrategico, $idAreaN2, $idObjetivoEspecifico,
			$idAreaN4, $idObjetivoOperativo, $idGestion, $idProcesoProyecto,
			$idComponente, $productoFinal, $idProvincia, $nombreProvincia,
			$cantidadUsuarios, $poblacionObjetivo, $medioVerificacion, $idResponsable,
			$nombreResponsable, $totalPresupuesto=0, $idRevisor, $idAreaRevisor, $tipo, $idActividad){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
				g_reforma_presupuestaria.planificacion_anual(
				id_planificacion_anual, identificador, id_area_funcionario, fecha_creacion,
				anio, id_objetivo_estrategico, id_area_n2, id_objetivo_especifico,
				id_area_n4, id_objetivo_operativo, id_area_unidad, id_proceso_proyecto,
				id_componente, producto_final, id_provincia, provincia, cantidad_usuarios,
				poblacion_objetivo, medio_verificacion, identificador_responsable,
				nombre_responsable, total_presupuesto_solicitado, estado, tipo, id_actividad,
				fecha_migracion, identificador_revisor, id_area_revisor)
				VALUES ($idPlanificacionAnual, '$identificador', '$idAreaFuncionario', '$fechaCreacion',
				$anio, $idObjetivoEstrategico, '$idAreaN2', $idObjetivoEspecifico,
				'$idAreaN4', $idObjetivoOperativo, '$idGestion', $idProcesoProyecto,
				$idComponente, '$productoFinal', $idProvincia, '$nombreProvincia', $cantidadUsuarios,
				'$poblacionObjetivo', '$medioVerificacion', '$idResponsable',
				'$nombreResponsable', $totalPresupuesto, 'aprobado', '$tipo', $idActividad,
				now(), '$idRevisor', '$idAreaRevisor')
				RETURNING id_planificacion_anual;");
	
				return $res;
	}
	
	//REFPRESTMP
	public function nuevaProgramacionAnualTemporalImportacion ($conexion,$idPlanificacionAnual, $identificador, $idAreaFuncionario, $fechaCreacion, $anio,
	$idObjetivoEstrategico, $idAreaN2, $idObjetivoEspecifico,
	$idAreaN4, $idObjetivoOperativo, $idGestion, $idProcesoProyecto,
	$idComponente, $productoFinal, $idProvincia, $nombreProvincia,
	$cantidadUsuarios, $poblacionObjetivo, $medioVerificacion, $idResponsable,
	$nombreResponsable, $totalPresupuesto=0, $idRevisor, $idAreaRevisor, $tipo, $idActividad){
	
	$res = $conexion->ejecutarConsulta("INSERT INTO
			g_reforma_presupuestaria.planificacion_anual_temporal(
					id_planificacion_anual, identificador, id_area_funcionario, fecha_creacion,
					anio, id_objetivo_estrategico, id_area_n2, id_objetivo_especifico,
					id_area_n4, id_objetivo_operativo, id_area_unidad, id_proceso_proyecto,
					id_componente, producto_final, id_provincia, provincia, cantidad_usuarios,
					poblacion_objetivo, medio_verificacion, identificador_responsable,
					nombre_responsable, total_presupuesto_solicitado, estado, tipo, id_actividad,
					fecha_migracion, identificador_revisor, id_area_revisor)
					VALUES ($idPlanificacionAnual, '$identificador', '$idAreaFuncionario', '$fechaCreacion',
					$anio, $idObjetivoEstrategico, '$idAreaN2', $idObjetivoEspecifico,
					'$idAreaN4', $idObjetivoOperativo, '$idGestion', $idProcesoProyecto,
					$idComponente, '$productoFinal', $idProvincia, '$nombreProvincia', $cantidadUsuarios,
					'$poblacionObjetivo', '$medioVerificacion', '$idResponsable',
					'$nombreResponsable', $totalPresupuesto, 'aprobado', '$tipo', $idActividad,
					now(), '$idRevisor', '$idAreaRevisor')
					RETURNING id_planificacion_anual;");
	
					return $res;
	}
	
	//REFPRES
	public function nuevoPresupuestoImportacion (  $conexion, $idPresupuesto, $identificador, $idAreaFuncionario, $fechaCreacion, $anio,
			$idPlanificacionAnual, $ejercicio, $entidad, $idUnidadEjecutora, $unidadEjecutora,
			$idUnidadDesconcentrada, $unidadDesconcentrada, $programa, $subprograma,
			$codigoProyecto, $codigoActividad, $obra, $geografico, $idRenglon, $renglon, $renglonAuxiliar,
			$fuente, $organismo, $correlativo, $idCPC, $cpc, $idTipoCompra, $tipoCompra, $idActividad,
			$nombreActividad, $actividad, $detalleGasto, $cantidadAnual, $idUnidadMedida, $unidadMedida,
			$costo, $cuatrimestre, $tipoProducto, $catalogoElectronico, $idProcedimientoSugerido, $procedimientoSugerido,
			$fondosBID, $operacionBID, $proyectoBID, $tipoRegimen, $tipoPresupuesto, $idRevisor, $idAreaRevisor,
			$agregarPac, $iva, $costoIva){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
				g_reforma_presupuestaria.presupuesto_asignado(
				id_presupuesto, identificador, id_area, fecha_creacion, anio,
				id_planificacion_anual, ejercicio, entidad, id_unidad_ejecutora,
				unidad_ejecutora, id_unidad_desconcentrada, unidad_desconcentrada,
				programa, subprograma, codigo_proyecto, codigo_actividad, obra,
				geografico, id_renglon, renglon, renglon_auxiliar, fuente, organismo,
				correlativo, id_cpc, cpc, id_tipo_compra, tipo_compra, id_actividad,
				nombre_actividad, actividad, detalle_gasto, cantidad_anual, id_unidad_medida,
				unidad_medida, costo, cuatrimestre, tipo_producto, catalogo_electronico,
				id_procedimiento_sugerido, procedimiento_sugerido, fondos_bid,
				operacion_bid, proyecto_bid, tipo_regimen, tipo_presupuesto,
				identificador_revisor, id_area_revisor, estado, agregar_pac, iva, costo_iva,
				fecha_migracion)
				VALUES ($idPresupuesto, '$identificador', '$idAreaFuncionario', '$fechaCreacion', $anio,
				$idPlanificacionAnual, $ejercicio, '$entidad', $idUnidadEjecutora,
				'$unidadEjecutora', $idUnidadDesconcentrada, '$unidadDesconcentrada',
				'$programa', '$subprograma', '$codigoProyecto', '$codigoActividad', '$obra',
				'$geografico', $idRenglon, '$renglon', '$renglonAuxiliar', '$fuente', '$organismo',
				'$correlativo', $idCPC, '$cpc', $idTipoCompra, '$tipoCompra', $idActividad,
				'$nombreActividad', '$actividad', '$detalleGasto', $cantidadAnual, $idUnidadMedida,
				'$unidadMedida', $costo, '$cuatrimestre', '$tipoProducto', '$catalogoElectronico',
				$idProcedimientoSugerido, '$procedimientoSugerido', '$fondosBID',
				'$operacionBID', '$proyectoBID', '$tipoRegimen', '$tipoPresupuesto',
				'$idRevisor', '$idAreaRevisor', 'aprobado', '$agregarPac', $iva, $costoIva,
				now())
				RETURNING id_presupuesto;");
	
				return $res;
	}
	
	//REFPRESTMP
	public function nuevoPresupuestoTemporalImportacion (  $conexion, $idPresupuesto, $identificador, $idAreaFuncionario, $fechaCreacion, $anio,
			$idPlanificacionAnual, $ejercicio, $entidad, $idUnidadEjecutora, $unidadEjecutora,
			$idUnidadDesconcentrada, $unidadDesconcentrada, $programa, $subprograma,
			$codigoProyecto, $codigoActividad, $obra, $geografico, $idRenglon, $renglon, $renglonAuxiliar,
			$fuente, $organismo, $correlativo, $idCPC, $cpc, $idTipoCompra, $tipoCompra, $idActividad,
			$nombreActividad, $actividad, $detalleGasto, $cantidadAnual, $idUnidadMedida, $unidadMedida,
			$costo, $cuatrimestre, $tipoProducto, $catalogoElectronico, $idProcedimientoSugerido, $procedimientoSugerido,
			$fondosBID, $operacionBID, $proyectoBID, $tipoRegimen, $tipoPresupuesto, $idRevisor, $idAreaRevisor,
			$agregarPac, $iva, $costoIva){
		
			$res = $conexion->ejecutarConsulta("INSERT INTO
													g_reforma_presupuestaria.presupuesto_asignado_temporal(
															id_presupuesto, identificador, id_area, fecha_creacion, anio,
															id_planificacion_anual, ejercicio, entidad, id_unidad_ejecutora,
															unidad_ejecutora, id_unidad_desconcentrada, unidad_desconcentrada,
															programa, subprograma, codigo_proyecto, codigo_actividad, obra,
															geografico, id_renglon, renglon, renglon_auxiliar, fuente, organismo,
															correlativo, id_cpc, cpc, id_tipo_compra, tipo_compra, id_actividad,
															nombre_actividad, actividad, detalle_gasto, cantidad_anual, id_unidad_medida,
															unidad_medida, costo, cuatrimestre, tipo_producto, catalogo_electronico,
															id_procedimiento_sugerido, procedimiento_sugerido, fondos_bid,
															operacion_bid, proyecto_bid, tipo_regimen, tipo_presupuesto,
															identificador_revisor, id_area_revisor, estado, agregar_pac, iva, costo_iva,
															fecha_migracion, costo_original, iva_original, costo_iva_original)
															VALUES ($idPresupuesto, '$identificador', '$idAreaFuncionario', '$fechaCreacion', $anio,
															$idPlanificacionAnual, $ejercicio, '$entidad', $idUnidadEjecutora,
															'$unidadEjecutora', $idUnidadDesconcentrada, '$unidadDesconcentrada',
															'$programa', '$subprograma', '$codigoProyecto', '$codigoActividad', '$obra',
															'$geografico', $idRenglon, '$renglon', '$renglonAuxiliar', '$fuente', '$organismo',
															'$correlativo', $idCPC, '$cpc', $idTipoCompra, '$tipoCompra', $idActividad,
															'$nombreActividad', '$actividad', '$detalleGasto', $cantidadAnual, $idUnidadMedida,
															'$unidadMedida', $costo, '$cuatrimestre', '$tipoProducto', '$catalogoElectronico',
															$idProcedimientoSugerido, '$procedimientoSugerido', '$fondosBID',
															'$operacionBID', '$proyectoBID', '$tipoRegimen', '$tipoPresupuesto',
															'$idRevisor', '$idAreaRevisor', 'aprobado', '$agregarPac', $iva, $costoIva,
															now(), $costo, $iva, $costoIva)
												RETURNING id_presupuesto;");
		
			return $res;
	}		
	
	///////////////////////////////////////////////////////////////////////////////
	
	//Ejecución de Reforma Presupuestaria, Actualización de Información entre tablas Real y Temporal

	//REFPRESTMP
	public function actualizarFechaRevisionReformaPlanificacionAnualTemporal ($conexion, $idPlanificacionAnual, $idArea, $observaciones){
	
		if($idArea == 'DGPGE'){
			$revisor="fecha_revision_dgpge=now(), observaciones_revision_dgpge = '$observaciones'";
		}else if($idArea == 'GA'){
			$revisor="fecha_revision_ga=now(), observaciones_revision_ga = '$observaciones'";
		}else  if($idArea == 'GF'){
			$revisor="fecha_revision_gf=now(), observaciones_revision_gf = '$observaciones'";
		}else{
			$revisor="fecha_revision=now(), observaciones_revision = '$observaciones'";
		}
		
		$res = $conexion->ejecutarConsulta("UPDATE
												g_reforma_presupuestaria.planificacion_anual_temporal
											SET
												$revisor
											WHERE
												id_planificacion_anual=$idPlanificacionAnual;");
		
		return $res;
	}
	
	//REFPRESTMP
	public function actualizarFechaRevisionReformaPresupuestoTemporal ($conexion, $idPresupuesto, $idArea, $observaciones){
	
		if($idArea == 'DGPGE'){
			$revisor="fecha_revision_dgpge=now(), observaciones_revision_dgpge = '$observaciones'";
		}else if($idArea == 'GA'){
			$revisor="fecha_revision_ga=now(), observaciones_revision_ga = '$observaciones'";
		}else  if($idArea == 'GF'){
			$revisor="fecha_revision_gf=now(), observaciones_revision_gf = '$observaciones'";
		}else{
			$revisor="fecha_revision=now(), observaciones_revision = '$observaciones'";
		}
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_reforma_presupuestaria.presupuesto_asignado_temporal
											SET
												$revisor
											WHERE
												id_presupuesto=$idPresupuesto;");
	
		return $res;
	}
	
	//REFPRESTMP
	public function actualizarNumeroCurPresupuestoTemporal ($conexion, $idPresupuesto, $numeroCur){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_reforma_presupuestaria.presupuesto_asignado_temporal
											SET
												numero_cur = '$numeroCur'
											WHERE
												id_presupuesto=$idPresupuesto;");
	
		return $res;
	}
	
	//REFPRESTMP
	public function actualizarCostoIvaOriginalTemporal ($conexion, $idPresupuesto, $costo, $iva, $costoIva){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_reforma_presupuestaria.presupuesto_asignado_temporal
											SET
												costo_original = $costo,
												iva_original = $iva,
												costo_iva_original = $costoIva
											WHERE
												id_presupuesto=$idPresupuesto;");
	
		return $res;
	}
	
	//ACTUALIZACION DE DATOS DE REFORMA PRESUPUESTARIA A PAP-PAC
	//REFPRES
	public function actualizarProgramacionAnualReformaPresupuestaria ($conexion,$idPlanificacionAnual, 
			$idRevisor, $idAreaRevisor, $fechaRevision=null, $observacionesRevision,
			$idRevisorDGPGE, $idAreaRevisorDGPGE, $fechaRevisionDGPGE=null, $observacionesRevisionDGPGE,
			$idRevisorGA, $idAreaRevisorGA, $fechaRevisionGA=null, $observacionesRevisionGA,
			$idRevisorGF, $idAreaRevisorGF, $observacionesRevisionGF, $tipo){
	
		if($tipo == 'Proyecto Inversion'){
			$proyectoInversion = "	identificador_revisor_dgpge='$idRevisorDGPGE',
									id_area_revisor_dgpge='$idAreaRevisorDGPGE', 
									fecha_revision_dgpge='$fechaRevisionDGPGE', 
									observaciones_revision_dgpge='$observacionesRevisionDGPGE',";
		}else{
			$proyectoInversion = '';
		}
		
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_reforma_presupuestaria.planificacion_anual
										   	SET 
												identificador_revisor='$idRevisor', 
												id_area_revisor='$idAreaRevisor', 
										    	fecha_revision='$fechaRevision', 
												observaciones_revision='$observacionesRevision', 
				
												$proyectoInversion
				
												identificador_revisor_ga='$idRevisorGA', 
												id_area_revisor_ga='$idAreaRevisorGA', 
												fecha_revision_ga='$fechaRevisionGA',
										       	observaciones_revision_ga='$observacionesRevisionGA', 
				
												identificador_revisor_gf='$idRevisorGF', 
												id_area_revisor_gf='$idAreaRevisorGF', 
										       	fecha_revision_gf=now(), 
												observaciones_revision_gf='$observacionesRevisionGF',
				
												fecha_modificacion=now()
										 	WHERE 
												id_planificacion_anual=$idPlanificacionAnual
				
				RETURNING id_planificacion_anual;");
		
		return $res;
	}
		
	//REFPRES
	public function actualizarPresupuestoReformaPresupuestaria ($conexion,$idPresupuesto,
			$detalleGasto, $idUnidadMedida, $unidadMedida, $costo, $iva, $costoIva, $cuatrimestre,
			$numeroCur, $tipoCambio,
			$idRevisor, $idAreaRevisor, $fechaRevision, $observacionesRevision,
			$idRevisorDGPGE, $idAreaRevisorDGPGE, $fechaRevisionDGPGE, $observacionesRevisionDGPGE,
			$idRevisorGA, $idAreaRevisorGA, $fechaRevisionGA, $observacionesRevisionGA,
			$idRevisorGF, $idAreaRevisorGF, $fechaRevisionGF, $observacionesRevisionGF,
			$costoOriginal, $ivaOriginal, $costoIvaOriginal, $tipoPresupuesto){
	
		if($tipoPresupuesto != 'Gasto Corriente'){
			$proyectoInversion = "	identificador_revisor_dgpge='$idRevisorDGPGE',
									id_area_revisor_dgpge='$idAreaRevisorDGPGE',
									fecha_revision_dgpge='$fechaRevisionDGPGE',
									observaciones_revision_dgpge='$observacionesRevisionDGPGE',";
		}else{
			$proyectoInversion = '';
		}
		
		$res = $conexion->ejecutarConsulta("UPDATE
												g_reforma_presupuestaria.presupuesto_asignado
											SET
												detalle_gasto='$detalleGasto',
												id_unidad_medida=$idUnidadMedida,
												unidad_medida='$unidadMedida',
												costo=$costo,
												iva=$iva,
												costo_iva=$costoIva,
												cuatrimestre='$cuatrimestre',
												numero_cur='$numeroCur',
												tipo_cambio='$tipoCambio',
												
												identificador_revisor='$idRevisor',
												id_area_revisor='$idAreaRevisor',
												fecha_revision='$fechaRevision',
												observaciones_revision='$observacionesRevision',
									
												$proyectoInversion
									
												identificador_revisor_ga='$idRevisorGA',
												id_area_revisor_ga='$idAreaRevisorGA',
												fecha_revision_ga='$fechaRevisionGA',
												observaciones_revision_ga='$observacionesRevisionGA',
									
												identificador_revisor_gf='$idRevisorGF',
												id_area_revisor_gf='$idAreaRevisorGF',
												fecha_revision_gf='$fechaRevisionGF',
												observaciones_revision_gf='$observacionesRevisionGF',
				
												costo_original=$costoOriginal,
												iva_original=$ivaOriginal,
												costo_iva_original=$costoIvaOriginal,
									
												fecha_modificacion=now()
											WHERE
												id_presupuesto=$idPresupuesto
									
											RETURNING id_presupuesto;");
	
		return $res;
	}
	
	public function nuevoPresupuestoReformaPresupuestaria ($conexion, $idPresupuesto, $identificador, $idArea, $fechaCreacion, $anio, 
										            $idPlanificacionAnual, $ejercicio, $entidad, $idUnidadEjecutora, 
										            $unidadEjecutora, $idUnidadDesconcentrada, $unidadDesconcentrada, 
										            $programa, $subprograma, $codigoProyecto, $codigoActividad, $obra, 
										            $geografico, $idRenglon, $renglon, $renglonAuxiliar, $fuente, $organismo, 
										            $correlativo, $idCpc, $cpc, $idTipoCompra, $tipoCompra, $idActividad, 
										            $nombreActividad, $actividad, $detalleGasto, $cantidadAnual, $idUnidadMedida, 
										            $unidadMedida, $costo, $cuatrimestre, $tipoProducto, $catalogoElectronico, 
										            $idProcedimientoSugerido, $procedimientoSugerido, $fondosBid, 
										            $operacionBid, $proyectoBid, $tipoRegimen, $tipoPresupuesto, 
										            $identificadorRevisor, $idAreaRevisor, $fechaRevision, $observacionesRevision, 
										            $identificadorRevisorDGPGE, $idAreaRevisorDGPGE, $fechaRevisionDGPGE, 
										            $observacionesRevisionDGPGE, $estado, $fechaModificacion, 
										            $agregarPac,  
										            $iva, $costoIva, $numeroCur, $identificadorRevisorGA, $idAreaRevisorGA, 
										            $fechaRevisionGA, $observacionesRevisionGA, $identificadorRevisorGF, 
										            $idAreaRevisorGF, $fechaRevisionGF, $observacionesRevisionGF, 
										            $costoOriginal, $ivaOriginal, $costoIvaOriginal, 
										            $tipoCambio){
	
		if($tipoPresupuesto == 'Gasto Corriente'){
			$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_reforma_presupuestaria.presupuesto_asignado(
										            id_presupuesto, identificador, id_area, fecha_creacion, anio, 
										            id_planificacion_anual, ejercicio, entidad, id_unidad_ejecutora, 
										            unidad_ejecutora, id_unidad_desconcentrada, unidad_desconcentrada, 
										            programa, subprograma, codigo_proyecto, codigo_actividad, obra, 
										            geografico, id_renglon, renglon, renglon_auxiliar, fuente, organismo, 
										            correlativo, id_cpc, cpc, id_tipo_compra, tipo_compra, id_actividad, 
										            nombre_actividad, actividad, detalle_gasto, cantidad_anual, id_unidad_medida, 
										            unidad_medida, costo, cuatrimestre, tipo_producto, catalogo_electronico, 
										            id_procedimiento_sugerido, procedimiento_sugerido, fondos_bid, 
										            operacion_bid, proyecto_bid, tipo_regimen, tipo_presupuesto, 
										            identificador_revisor, id_area_revisor, fecha_revision, observaciones_revision, 
										            estado, fecha_modificacion, 
										            agregar_pac,  
										            iva, costo_iva, numero_cur, identificador_revisor_ga, id_area_revisor_ga, 
										            fecha_revision_ga, observaciones_revision_ga, identificador_revisor_gf, 
										            id_area_revisor_gf, fecha_revision_gf, observaciones_revision_gf, 
										            costo_original, iva_original, costo_iva_original, 
										            tipo_cambio)
										    VALUES ($idPresupuesto, '$identificador', '$idArea', '$fechaCreacion', $anio, 
										            $idPlanificacionAnual, $ejercicio,'$entidad', $idUnidadEjecutora, 
										            '$unidadEjecutora', $idUnidadDesconcentrada, '$unidadDesconcentrada', 
										            '$programa', '$subprograma', '$codigoProyecto', '$codigoActividad', '$obra', 
										            '$geografico', $idRenglon, '$renglon', '$renglonAuxiliar', '$fuente', '$organismo', 
										            '$correlativo', $idCpc, '$cpc', $idTipoCompra, '$tipoCompra', $idActividad, 
										            '$nombreActividad', '$actividad', '$detalleGasto', $cantidadAnual, $idUnidadMedida, 
										            '$unidadMedida', $costo, '$cuatrimestre', '$tipoProducto', '$catalogoElectronico', 
										            $idProcedimientoSugerido, '$procedimientoSugerido', '$fondosBid', 
										            '$operacionBid', '$proyectoBid', '$tipoRegimen', '$tipoPresupuesto', 
										            '$identificadorRevisor', '$idAreaRevisor', '$fechaRevision', '$observacionesRevision', 
										            '$estado', '$fechaModificacion', 
										            '$agregarPac',  
										            $iva, $costoIva, '$numeroCur', '$identificadorRevisorGA', '$idAreaRevisorGA', 
										            '$fechaRevisionGA', '$observacionesRevisionGA', '$identificadorRevisorGF', 
										            '$idAreaRevisorGF', '$fechaRevisionGF', '$observacionesRevisionGF', 
										            $costoOriginal, $ivaOriginal, $costoIvaOriginal, 
										            '$tipoCambio')
											RETURNING id_presupuesto;");
			
		}else{
			$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_reforma_presupuestaria.presupuesto_asignado(
										            id_presupuesto, identificador, id_area, fecha_creacion, anio, 
										            id_planificacion_anual, ejercicio, entidad, id_unidad_ejecutora, 
										            unidad_ejecutora, id_unidad_desconcentrada, unidad_desconcentrada, 
										            programa, subprograma, codigo_proyecto, codigo_actividad, obra, 
										            geografico, id_renglon, renglon, renglon_auxiliar, fuente, organismo, 
										            correlativo, id_cpc, cpc, id_tipo_compra, tipo_compra, id_actividad, 
										            nombre_actividad, actividad, detalle_gasto, cantidad_anual, id_unidad_medida, 
										            unidad_medida, costo, cuatrimestre, tipo_producto, catalogo_electronico, 
										            id_procedimiento_sugerido, procedimiento_sugerido, fondos_bid, 
										            operacion_bid, proyecto_bid, tipo_regimen, tipo_presupuesto, 
										            identificador_revisor, id_area_revisor, fecha_revision, observaciones_revision, 
										            identificador_revisor_dgpge, id_area_revisor_dgpge, fecha_revision_dgpge, 
										            observaciones_revision_dgpge, estado, fecha_modificacion, 
										            agregar_pac,  
										            iva, costo_iva, numero_cur, identificador_revisor_ga, id_area_revisor_ga, 
										            fecha_revision_ga, observaciones_revision_ga, identificador_revisor_gf, 
										            id_area_revisor_gf, fecha_revision_gf, observaciones_revision_gf, 
										            costo_original, iva_original, costo_iva_original, 
										            tipo_cambio)
										    VALUES ($idPresupuesto, '$identificador', '$idArea', '$fechaCreacion', $anio, 
										            $idPlanificacionAnual, $ejercicio,'$entidad', $idUnidadEjecutora, 
										            '$unidadEjecutora', $idUnidadDesconcentrada, '$unidadDesconcentrada', 
										            '$programa', '$subprograma', '$codigoProyecto', '$codigoActividad', '$obra', 
										            '$geografico', $idRenglon, '$renglon', '$renglonAuxiliar', '$fuente', '$organismo', 
										            '$correlativo', $idCpc, '$cpc', $idTipoCompra, '$tipoCompra', $idActividad, 
										            '$nombreActividad', '$actividad', '$detalleGasto', $cantidadAnual, $idUnidadMedida, 
										            '$unidadMedida', $costo, '$cuatrimestre', '$tipoProducto', '$catalogoElectronico', 
										            $idProcedimientoSugerido, '$procedimientoSugerido', '$fondosBid', 
										            '$operacionBid', '$proyectoBid', '$tipoRegimen', '$tipoPresupuesto', 
										            '$identificadorRevisor', '$idAreaRevisor', '$fechaRevision', '$observacionesRevision', 
										            '$identificadorRevisorDGPGE', '$idAreaRevisorDGPGE', '$fechaRevisionDGPGE', 
										            '$observacionesRevisionDGPGE', '$estado', '$fechaModificacion', 
										            '$agregarPac',  
										            $iva, $costoIva, '$numeroCur', '$identificadorRevisorGA', '$idAreaRevisorGA', 
										            '$fechaRevisionGA', '$observacionesRevisionGA', '$identificadorRevisorGF', 
										            '$idAreaRevisorGF', '$fechaRevisionGF', '$observacionesRevisionGF', 
										            $costoOriginal, $ivaOriginal, $costoIvaOriginal, 
										            '$tipoCambio')
											RETURNING id_presupuesto;");
	
		}
		
		
				return $res;
	}
	
	/////////////////////////////////////////////////////////////////////////////
	
	//Reporte Partidas Presupuestarias Esigef
		
	//REFPRESTMP
	public function obtenerPartidasReformadasTemporal($conexion,$idAreaN2, $idPrograma,
			$idProyecto, $idActividad, $anio, $estado){
	
		if($idPrograma==''){
			$programa = "";
		}else{
			$programa = "pat.programa='$idPrograma' and ";
		}
		
		if($idProyecto==''){
			$proyecto = "";
		}else{
			$proyecto = "pat.codigo_proyecto='$idProyecto' and ";
		}
		
		if($idActividad==''){
			$actividad = "";
		}else{
			$actividad = "pat.codigo_actividad='$idActividad' and ";
		}

		$res = $conexion->ejecutarConsulta("SELECT 
												distinct(pat.renglon), sum(pat.costo), r.nombre
											FROM
												g_reforma_presupuestaria.presupuesto_asignado_temporal pat,
												g_programacion_presupuestaria.renglon r
											WHERE
												pat.estado='$estado' and
												pat.ejercicio=$anio and
												pat.id_area_revisor='$idAreaN2' and
												$programa
												$proyecto
												$actividad
												r.codigo=pat.renglon
											GROUP BY 
												pat.renglon, r.nombre;");
		
		return $res;
	}
	
	//REFPRES
	public function obtenerMontoTotalPartidas($conexion,$idAreaN2, $idPrograma,
			$idProyecto, $idActividad, $idProvincia, $anio, $estado){
	
		if($idPrograma==''){
			$programa = "";
		}else{
			$programa = "pat.programa=$idPrograma and ";
		}
	
		if($idProyecto==''){
			$proyecto = "";
		}else{
			$proyecto = "pat.codigo_proyecto=$idProyecto and ";
		}
	
		if($idActividad==''){
			$actividad = "";
		}else{
			$actividad = "pat.codigo_actividad=$idActividad and ";
		}
	
		$res = $conexion->ejecutarConsulta("SELECT
												distinct(pat.renglon), sum(pat.costo), r.nombre
											FROM
												g_reforma_presupuestaria.presupuesto_asignado pat,
												g_programacion_presupuestaria.renglon r
											WHERE
												pat.estado='$estado' and
												pat.ejercicio=$anio and
												pat.id_area_revisor='$idAreaN2' and
												$programa
												$proyecto
												$actividad
												r.codigo=pat.renglon
											GROUP BY
												pat.renglon, r.nombre;");
	
				return $res;
	}
	
	//REFPRES
	public function obtenerMontoTotalXPartida($conexion,$idAreaN2, $idPrograma='',
			$idProyecto='', $idActividad='', $anio, $estado, $renglon){
	
		if($idPrograma==''){
			$programa = "";
		}else{
			$programa = "pat.programa='$idPrograma' and ";
		}
	
		if($idProyecto==''){
			$proyecto = "";
		}else{
			$proyecto = "pat.codigo_proyecto='$idProyecto' and ";
		}
	
		if($idActividad==''){
			$actividad = "";
		}else{
			$actividad = "pat.codigo_actividad='$idActividad' and ";
		}
	
		$res = $conexion->ejecutarConsulta("SELECT
												distinct(pat.renglon), 
												sum(pat.costo) as codificado, 
												r.nombre
											FROM
												g_reforma_presupuestaria.presupuesto_asignado pat,
												g_programacion_presupuestaria.renglon r
											WHERE
												pat.estado='$estado' and
												pat.ejercicio=$anio and
												pat.id_area_revisor='$idAreaN2' and
												$programa 
												$proyecto 
												$actividad 
												r.codigo=pat.renglon and
												pat.renglon='$renglon'
											GROUP BY
												pat.renglon, r.nombre;");
		
			
		return $res;
	}
	
	//REFPRES
	public function obtenerMontoReformadoXPartidasYEstadoTemporal($conexion,$idAreaN2, $idPrograma,
			$idProyecto, $idActividad, $anio, $estado, $renglon, $tipoCambio){
	
		if($idPrograma==''){
			$programa = "";
		}else{
			$programa = "pat.programa='$idPrograma' and ";
		}
	
		if($idProyecto==''){
			$proyecto = "";
		}else{
			$proyecto = "pat.codigo_proyecto='$idProyecto' and ";
		}
	
		if($idActividad==''){
			$actividad = "";
		}else{
			$actividad = "pat.codigo_actividad='$idActividad' and ";
		}
		
		if($tipoCambio == 'incremento'){
			$monto = "abs(sum(pat.costo_original-pat.costo)) as monto_modificado,";
		}else if($tipoCambio == 'decremento'){
			$monto = "sum(pat.costo_original-pat.costo) as monto_modificado,";
		}else{
			$monto = "sum(pat.costo_original-pat.costo) as monto_modificado,";
		}
	
		$res = $conexion->ejecutarConsulta("SELECT
												distinct(pat.renglon), 
												$monto 
												r.nombre
											FROM
												g_reforma_presupuestaria.presupuesto_asignado_temporal pat,
												g_programacion_presupuestaria.renglon r
											WHERE
												pat.estado='$estado' and
												pat.ejercicio=$anio and
												pat.id_area_revisor='$idAreaN2' and
												$programa
												$proyecto
												$actividad
												r.codigo=pat.renglon and
												pat.tipo_cambio='$tipoCambio' and
												pat.renglon='$renglon'
											GROUP BY
												pat.renglon, r.nombre;");
		
				return $res;
	}
	
	
	////////////////////////////////////////////////////////////////////////////
	
	//Auditorias, Controles de Cambios			
		
	public function registrarControlCambios ($conexion, $idPlanificacionAnual, $idPresupuestoAsignado, $idControlCambios,
											$identificador, $idAreaUsuario, $razonCambio, $detalleGasto, $idUnidadMedida,
											$unidadMedida, $costo, $iva, $costoIva, $cuatrimestre, 
											$identificadorRevisor, $idAreaRevisor, $estadoRevisor, 
											$observacionRevisor, $tipo){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_reforma_presupuestaria.control_cambios(
										            id_planificacion_anual, id_presupuesto_asignado, id_control_cambios,
										            identificador, id_area_usuario, fecha_creacion, razon_cambio, 
													detalle_gasto, id_unidad_medida, 
										            unidad_medida, costo, iva, costo_iva, 
													cuatrimestre, identificador_revisor, id_area_revisor, 
										            estado_revisor, observacion_revisor, fecha_revision, tipo)
									    	VALUES ($idPlanificacionAnual, $idPresupuestoAsignado, $idControlCambios,
													'$identificador', '$idAreaUsuario', now(), '$razonCambio', 
													'$detalleGasto', $idUnidadMedida, 
													'$unidadMedida', $costo, $iva, $costoIva, 
													'$cuatrimestre', '$identificadorRevisor', '$idAreaRevisor',
													'$estadoRevisor', '$observacionRevisor', now(), '$tipo');");
		
		return $res;
	}
		
	public function  generarNumeroControlCambios($conexion, $idPlanificacionAnual, $idPresupuesto){
	
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(id_control_cambios) as numero
											FROM
												g_reforma_presupuestaria.control_cambios
											WHERE
												id_planificacion_anual = $idPlanificacionAnual and
												id_presupuesto_asignado = $idPresupuesto;");
		
		return $res;
	}
		
	////////////////////////////////////////////////////////////////////////////
	
		
		
/**********************************************************************************************************/	
	
	//USUARIO PROGRAMACION ANUAL
	
	public function listarProgramacionAnual ($conexion, $identificador, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.planificacion_anual pa
											WHERE
												pa.estado not in ('eliminado') and
												pa.anio = $anio and
												pa.identificador = '$identificador'
											ORDER BY
												pa.id_objetivo_estrategico, pa.id_objetivo_especifico, 
										        pa.id_objetivo_operativo, pa.id_area_unidad asc;");
									
		return $res;
	}
	
	//REFPRES
	public function listarProgramacionAnualAprobadaVista ($conexion, $identificador, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_reforma_presupuestaria.vista_planificacion_anual_aprobada pa
											WHERE
												pa.estado not in ('eliminado') and
												pa.anio = $anio and
												pa.identificador = '$identificador'
											ORDER BY
												pa.id_objetivo_estrategico, pa.id_objetivo_especifico,
												pa.id_objetivo_operativo, pa.id_area_unidad asc;");
		
		return $res;
	}
	
	//REFPRESTMP
	public function listarProgramacionAnualVistaTemporal ($conexion, $identificador, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_reforma_presupuestaria.vista_planificacion_anual_temporal pa
											WHERE
												pa.estado not in ('eliminado') and
												pa.anio = $anio and
												pa.identificador = '$identificador'
											ORDER BY
												pa.id_objetivo_estrategico, pa.id_objetivo_especifico,
												pa.id_objetivo_operativo, pa.id_area_unidad asc;");
					
		return $res;
	}
	
	
	
	//REFPRES
	public function abrirProgramacionAnual ($conexion, $idProgramacionAnual, $identificador){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_reforma_presupuestaria.vista_planificacion_anual pa
											WHERE
												pa.identificador = '$identificador' and
												pa.id_planificacion_anual = $idProgramacionAnual;");
					
		return $res;
	}
	
	//REFPRESTMP
	public function abrirProgramacionAnualTemporal ($conexion, $idProgramacionAnual, $identificador){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_reforma_presupuestaria.vista_planificacion_anual_temporal pa
											WHERE
												pa.identificador = '$identificador' and
												pa.id_planificacion_anual = $idProgramacionAnual;");
			
		return $res;
	}
	
	public function modificarPlanificacionAnual ($conexion, $idPlanificacionAnual, $idProvincia, $nombreProvincia,
											$cantidadUsuarios, $poblacionObjetivo, $medioVerificacion, $idResponsable,
											$nombreResponsable){
	
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_programacion_presupuestaria.planificacion_anual
											SET 
												id_provincia=$idProvincia, 
												provincia='$nombreProvincia', 
												cantidad_usuarios=$cantidadUsuarios, 
												poblacion_objetivo='$poblacionObjetivo', 											
												medio_verificacion='$medioVerificacion', 
												identificador_responsable='$idResponsable', 
												nombre_responsable='$nombreResponsable',
												fecha_modificacion = now(),
												revisado = null
											 WHERE 
												id_planificacion_anual=$idPlanificacionAnual;");
	
		return $res;
	}
	
	public function eliminarPlanificacionAnual ($conexion, $idPlanificacionAnual, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.planificacion_anual
											SET
												estado ='eliminado',
												fecha_eliminacion = now(),
												usuario_eliminacion = '$identificador'
											WHERE
												id_planificacion_anual in ($idPlanificacionAnual);");
	
		return $res;
	}
	
	//REFPRESTMP
	public function buscarPresupuestoTemporal ($conexion, $detalleGasto, $cuatrimestre, $idPlanificacionAnual){

		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_reforma_presupuestaria.presupuesto_asignado_temporal
											WHERE
												detalle_gasto = '$detalleGasto' and
												cuatrimestre = '$cuatrimestre' and
												id_planificacion_anual = $idPlanificacionAnual
											ORDER BY
												detalle_gasto asc;");
	
		return $res;
	}
	
	
	
	//REFPRES
	public function nuevoPresupuesto ($conexion, $idPlanificacionAnual, $ejercicio, $entidad, $idUnidadEjecutora, $unidadEjecutora,
			$idUnidadDesconcentrada, $unidadDesconcentrada, $programa, $subprograma,
			$codigoProyecto, $codigoActividad, $obra, $geografico, $idRenglon, $renglon, $renglonAuxiliar,
			$fuente, $organismo, $correlativo, $idCPC, $cpc, $idTipoCompra, $tipoCompra, $idActividad,
			$nombreActividad, $actividad, $detalleGasto, $cantidadAnual, $idUnidadMedida, $unidadMedida,
			$costo, $cuatrimestre, $tipoProducto, $catalogoElectronico, $idProcedimientoSugerido, $procedimientoSugerido,
			$fondosBID, $operacionBID, $proyectoBID, $tipoRegimen, $tipoPresupuesto, $agregarPac, $iva, $costoIva,
			$idRevisor, $idAreaRevisor, $anio, $idAreaFuncionario, $identificador){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
				g_reforma_presupuestaria.presupuesto_asignado(
				identificador, id_area, fecha_creacion, anio,
				id_planificacion_anual, ejercicio, entidad, id_unidad_ejecutora,
				unidad_ejecutora, id_unidad_desconcentrada, unidad_desconcentrada,
				programa, subprograma, codigo_proyecto, codigo_actividad,
				obra, geografico, id_renglon, renglon, renglon_auxiliar, fuente,
				organismo, correlativo, id_cpc, cpc, id_tipo_compra, tipo_compra,
				id_actividad, nombre_actividad, actividad, detalle_gasto, cantidad_anual,
				id_unidad_medida, unidad_medida, costo, cuatrimestre, tipo_producto,
				catalogo_electronico, id_procedimiento_sugerido, procedimiento_sugerido,
				fondos_bid, operacion_bid, proyecto_bid, tipo_regimen, tipo_presupuesto,
				identificador_revisor, id_area_revisor, estado, agregar_pac, iva, costo_iva)
				VALUES ('$identificador', '$idAreaFuncionario', now(), $anio,
				$idPlanificacionAnual, $ejercicio, '$entidad', $idUnidadEjecutora,
				'$unidadEjecutora', $idUnidadDesconcentrada, '$unidadDesconcentrada',
				'$programa', '$subprograma', '$codigoProyecto', '$codigoActividad',
				'$obra', '$geografico', $idRenglon, '$renglon', '$renglonAuxiliar', '$fuente',
				'$organismo', '$correlativo', $idCPC, '$cpc', $idTipoCompra, '$tipoCompra',
				$idActividad, '$nombreActividad', '$actividad', '$detalleGasto', $cantidadAnual,
				$idUnidadMedida, '$unidadMedida', $costo, '$cuatrimestre', '$tipoProducto',
				'$catalogoElectronico', $idProcedimientoSugerido, '$procedimientoSugerido',
				'$fondosBID', '$operacionBID', '$proyectoBID', '$tipoRegimen', '$tipoPresupuesto',
				'$idRevisor', '$idAreaRevisor', 'creado', '$agregarPac', $iva, $costoIva)
				RETURNING id_presupuesto;");
	
				return $res;
	}
	
	//REFPRESTMP
	public function nuevoPresupuestoTemporal ($conexion, $idPresupuesto, $idPlanificacionAnual, $ejercicio, $entidad, $idUnidadEjecutora, $unidadEjecutora,
			$idUnidadDesconcentrada, $unidadDesconcentrada, $programa, $subprograma,
			$codigoProyecto, $codigoActividad, $obra, $geografico, $idRenglon, $renglon, $renglonAuxiliar,
			$fuente, $organismo, $correlativo, $idCPC, $cpc, $idTipoCompra, $tipoCompra, $idActividad,
			$nombreActividad, $actividad, $detalleGasto, $cantidadAnual, $idUnidadMedida, $unidadMedida,
			$costo, $cuatrimestre, $tipoProducto, $catalogoElectronico, $idProcedimientoSugerido, $procedimientoSugerido,
			$fondosBID, $operacionBID, $proyectoBID, $tipoRegimen, $tipoPresupuesto, $agregarPac, $iva, $costoIva,
			$idRevisor, $idAreaRevisor, $anio, $idAreaFuncionario, $identificador, $tipoCambio){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_reforma_presupuestaria.presupuesto_asignado_temporal(
												id_presupuesto, identificador, id_area, fecha_creacion, anio,
												id_planificacion_anual, ejercicio, entidad, id_unidad_ejecutora,
												unidad_ejecutora, id_unidad_desconcentrada, unidad_desconcentrada,
												programa, subprograma, codigo_proyecto, codigo_actividad,
												obra, geografico, id_renglon, renglon, renglon_auxiliar, fuente,
												organismo, correlativo, id_cpc, cpc, id_tipo_compra, tipo_compra,
												id_actividad, nombre_actividad, actividad, detalle_gasto, cantidad_anual,
												id_unidad_medida, unidad_medida, costo, cuatrimestre, tipo_producto,
												catalogo_electronico, id_procedimiento_sugerido, procedimiento_sugerido,
												fondos_bid, operacion_bid, proyecto_bid, tipo_regimen, tipo_presupuesto,
												identificador_revisor, id_area_revisor, estado, agregar_pac, iva, costo_iva,
												costo_original, iva_original, costo_iva_original, tipo_cambio)
											VALUES ($idPresupuesto, '$identificador', '$idAreaFuncionario', now(), $anio,
												$idPlanificacionAnual, $ejercicio, '$entidad', $idUnidadEjecutora,
												'$unidadEjecutora', $idUnidadDesconcentrada, '$unidadDesconcentrada',
												'$programa', '$subprograma', '$codigoProyecto', '$codigoActividad',
												'$obra', '$geografico', $idRenglon, '$renglon', '$renglonAuxiliar', '$fuente',
												'$organismo', '$correlativo', $idCPC, '$cpc', $idTipoCompra, '$tipoCompra',
												$idActividad, '$nombreActividad', '$actividad', '$detalleGasto', $cantidadAnual,
												$idUnidadMedida, '$unidadMedida', $costo, '$cuatrimestre', '$tipoProducto',
												'$catalogoElectronico', $idProcedimientoSugerido, '$procedimientoSugerido',
												'$fondosBID', '$operacionBID', '$proyectoBID', '$tipoRegimen', '$tipoPresupuesto',
												'$idRevisor', '$idAreaRevisor', 'creado', '$agregarPac', $iva, $costoIva,
												0, 0, 0, '$tipoCambio')
											RETURNING id_presupuesto;");
		
		/*
		 *		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_reforma_presupuestaria.presupuesto_asignado_temporal(
												id_presupuesto, identificador, id_area, fecha_creacion, anio,
												id_planificacion_anual, ejercicio, entidad, id_unidad_ejecutora,
												unidad_ejecutora, id_unidad_desconcentrada, unidad_desconcentrada,
												programa, subprograma, codigo_proyecto, codigo_actividad,
												obra, geografico, id_renglon, renglon, renglon_auxiliar, fuente,
												organismo, correlativo, id_cpc, cpc, id_tipo_compra, tipo_compra,
												id_actividad, nombre_actividad, actividad, detalle_gasto, cantidad_anual,
												id_unidad_medida, unidad_medida, costo, cuatrimestre, tipo_producto,
												catalogo_electronico, id_procedimiento_sugerido, procedimiento_sugerido,
												fondos_bid, operacion_bid, proyecto_bid, tipo_regimen, tipo_presupuesto,
												identificador_revisor, id_area_revisor, estado, agregar_pac, iva, costo_iva,
												costo_original, iva_original, costo_iva_original, tipo_cambio)
											VALUES ($idPresupuesto, '$identificador', '$idAreaFuncionario', now(), $anio,
												$idPlanificacionAnual, $ejercicio, '$entidad', $idUnidadEjecutora,
												'$unidadEjecutora', $idUnidadDesconcentrada, '$unidadDesconcentrada',
												'$programa', '$subprograma', '$codigoProyecto', '$codigoActividad',
												'$obra', '$geografico', $idRenglon, '$renglon', '$renglonAuxiliar', '$fuente',
												'$organismo', '$correlativo', $idCPC, '$cpc', $idTipoCompra, '$tipoCompra',
												$idActividad, '$nombreActividad', '$actividad', '$detalleGasto', $cantidadAnual,
												$idUnidadMedida, '$unidadMedida', $costo, '$cuatrimestre', '$tipoProducto',
												'$catalogoElectronico', $idProcedimientoSugerido, '$procedimientoSugerido',
												'$fondosBID', '$operacionBID', '$proyectoBID', '$tipoRegimen', '$tipoPresupuesto',
												'$idRevisor', '$idAreaRevisor', 'creado', '$agregarPac', $iva, $costoIva,
												$costo, $iva, $costoIva, '$tipoCambio')
											RETURNING id_presupuesto;");
		 */
	
		return $res;
	}
	
	
	public function imprimirLineaPresupuesto($idPresupuesto, $actividad, $detalleGasto, $renglon, $costo, $cantidadAnual, $cuatrimestre, 
												$idPlanificacionAnual, $ruta, $estadoRevision, $estado){
		
		if($estadoRevision == true && ($estado == 'revisado' || $estado == 'revisadoDGPGE' || $estado == 'revisadoGA')){
			$revisado = 'activo';
		}else{
			$revisado = 'inactivo';
		}
				
		return '<tr id="R' . $idPresupuesto . '">' .
				'<td width="30%">' .
				$actividad .
				'</td>' .
				'<td width="30%">' .
				$detalleGasto .
				'</td>' .
				'<td width="10%">' .
				$renglon .
				'</td>' .
				'<td width="10%">' .
				$costo .
				'</td>
				<td width="10%">' .
				$cuatrimestre .
				'</td>
				<td width="10%">' .
				$estado .
				'</td>' .
				'<td>' .
				'<div class="'.$revisado.'" >' .
				'<button type="button" class="icono"></button>' .
				'</div>' .
				'</td>' .

				'<td>' .
				'<form class="abrir" data-rutaAplicacion="'.$ruta.'" data-opcion="abrirPresupuesto" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idPresupuesto" value="' . $idPresupuesto . '" >' .
				'<input type="hidden" name="idPlanificacionAnual" value="' . $idPlanificacionAnual . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	//REFPRES
	public function listarPresupuestos ($conexion, $idPlanificacionAnual){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_reforma_presupuestaria.presupuesto_asignado
											WHERE
												id_planificacion_anual = $idPlanificacionAnual and
												estado not in ('eliminado')
											ORDER BY
												nombre_actividad, detalle_gasto asc;");
	
		return $res;
	}
	
	//REFPRESTMP
	public function listarPresupuestosTemporales ($conexion, $idPlanificacionAnual){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_reforma_presupuestaria.presupuesto_asignado_temporal
											WHERE
												id_planificacion_anual = $idPlanificacionAnual and
												estado not in ('eliminado')
											ORDER BY
												nombre_actividad, detalle_gasto asc;");
	
		return $res;
	}
	
	//REFPRESTMP
	public function listarPresupuestosTemporalesXEstado ($conexion, $idPlanificacionAnual, $estado){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_reforma_presupuestaria.presupuesto_asignado_temporal
											WHERE
												id_planificacion_anual = $idPlanificacionAnual and
												estado in ('$estado')
											ORDER BY
												nombre_actividad, detalle_gasto asc;");
	
		return $res;
	}
	
	//REFPRES
	public function abrirPresupuesto ($conexion,$idPresupuesto){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_reforma_presupuestaria.presupuesto_asignado
											WHERE
												id_presupuesto = $idPresupuesto;");
	
		return $res;
	}
	
	//REFPRESTMP
	public function abrirPresupuestoTemporal ($conexion,$idPresupuesto){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_reforma_presupuestaria.presupuesto_asignado_temporal
											WHERE
												id_presupuesto = $idPresupuesto;");
	
		return $res;
	}
	
	//REFPRES
	public function modificarPresupuesto ($conexion, $idPresupuesto, $detalleGasto, $idUnidadMedida, 
													$unidadMedida, $costo, $cuatrimestre, $iva, $costoIva){
	
		$res = $conexion->ejecutarConsulta("UPDATE
										   		g_reforma_presupuestaria.presupuesto_asignado
										   SET 
												detalle_gasto='$detalleGasto', 
										        id_unidad_medida=$idUnidadMedida, 
										        unidad_medida='$unidadMedida', 
										        costo=$costo, 
										        cuatrimestre='$cuatrimestre', 
										        iva = $iva,
												costo_iva = $costoIva, 
										        fecha_modificacion = now()
										 WHERE 
												id_presupuesto=$idPresupuesto;");
	
		return $res;
	}
	
	//REFPRESTMP
	public function modificarPresupuestoTemporal ($conexion, $idPresupuesto, $detalleGasto, $idUnidadMedida, 
													$unidadMedida, $costo, $cuatrimestre, $iva, $costoIva, 
													$tipoCambio){
	
		$res = $conexion->ejecutarConsulta("UPDATE
										   		g_reforma_presupuestaria.presupuesto_asignado_temporal
										    SET 
												detalle_gasto='$detalleGasto', 
										        id_unidad_medida=$idUnidadMedida, 
										        unidad_medida='$unidadMedida', 
										        costo=$costo, 
										        cuatrimestre='$cuatrimestre', 
										        iva = $iva,
												costo_iva = $costoIva, 
										        fecha_modificacion = now(),
												tipo_cambio = '$tipoCambio'
										 	WHERE 
												id_presupuesto=$idPresupuesto;");
	
		return $res;
	}
	
	public function eliminarPresupuesto ($conexion, $idPresupuesto, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
									   			g_programacion_presupuestaria.presupuesto_asignado
											SET
												estado ='eliminado',
												fecha_eliminacion = now(),
												usuario_eliminacion = '$identificador'
											WHERE
												id_presupuesto=$idPresupuesto;");
	
		return $res;
	}
	
	public function eliminarPresupuestoXPlanificacionAnual ($conexion, $idPlanificacionAnual, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
										   		g_programacion_presupuestaria.presupuesto_asignado
											SET
												estado ='eliminado',
												fecha_eliminacion = now(),
												usuario_eliminacion = '$identificador'
											WHERE
												id_planificacion_anual in ($idPlanificacionAnual);");
	
		return $res;
	}
	
	//revisar si hay influencia en reportes
	//Función sin IVA
	//REFPRES
	public function numeroPresupuestosYCostoTotal ($conexion, $idPlanificacionAnual){
	
		$res = $conexion->ejecutarConsulta("SELECT 
												count(id_presupuesto) as num_presupuestos, 
												sum(costo) as total
								  			FROM 
												g_reforma_presupuestaria.presupuesto_asignado
								  			WHERE
												id_planificacion_anual = $idPlanificacionAnual and
												estado not in ('eliminado');");
					
		return $res;
	}
	
	//REFPRESTMP
	public function numeroPresupuestosYCostoTotalTemporal ($conexion, $idPlanificacionAnual){
	
		$res = $conexion->ejecutarConsulta("SELECT
												count(id_presupuesto) as num_presupuestos,
												sum(costo) as total
											FROM
												g_reforma_presupuestaria.presupuesto_asignado_temporal
											WHERE
												id_planificacion_anual = $idPlanificacionAnual and
												estado not in ('eliminado');");
					
		return $res;
	}
	
	//REFPRES
	public function numeroPresupuestosYCostoTotalIVA ($conexion, $idPlanificacionAnual){
	
		$res = $conexion->ejecutarConsulta("SELECT
												count(id_presupuesto) as num_presupuestos,
												sum(costo_iva) as total
											FROM
												g_reforma_presupuestaria.presupuesto_asignado
											WHERE
												id_planificacion_anual = $idPlanificacionAnual and
												estado not in ('eliminado');");
					
		return $res;
	}
	
	//REFPRESTMP
	public function numeroPresupuestosYCostoTotalIVATemporal ($conexion, $idPlanificacionAnual){
	
		$res = $conexion->ejecutarConsulta("SELECT
												count(id_presupuesto) as num_presupuestos,
												sum(costo_iva) as total
											FROM
												g_reforma_presupuestaria.presupuesto_asignado_temporal
											WHERE
												id_planificacion_anual = $idPlanificacionAnual and
												estado not in ('eliminado');");
					
		return $res;
	}
	
	//revisar si hay influencia en reportes
	//REFPRES
	public function numeroPresupuestosYCostoTotalAprobado ($conexion, $idPlanificacionAnual){
	
		$res = $conexion->ejecutarConsulta("SELECT
												count(id_presupuesto) as num_presupuestos,
												sum(costo*cantidad_anual) as total
											FROM
												g_reforma_presupuestaria.presupuesto_asignado
											WHERE
												id_planificacion_anual = $idPlanificacionAnual and
												estado in ('aprobadoDGAF','aprobado');");
					
		return $res;
	}
	
	//REFPRESTMP
	public function numeroPresupuestosYCostoTotalAprobadoTemporal ($conexion, $idPlanificacionAnual){
	
		$res = $conexion->ejecutarConsulta("SELECT
												count(id_presupuesto) as num_presupuestos,
												sum(costo*cantidad_anual) as total
											FROM
												g_reforma_presupuestaria.presupuesto_asignado_temporal
											WHERE
												id_planificacion_anual = $idPlanificacionAnual and
												estado in ('aprobadoDGAF','aprobado');");
					
		return $res;
	}
	
	//REFPRESTMP
	public function numeroPresupuestosYCostoTotalXEstado ($conexion, $idPlanificacionAnual, $estado){
	
		$res = $conexion->ejecutarConsulta("SELECT
												count(id_presupuesto) as num_presupuestos,
												sum(costo*cantidad_anual) as total
											FROM
												g_reforma_presupuestaria.presupuesto_asignado_temporal
											WHERE
												id_planificacion_anual = $idPlanificacionAnual and
												estado in ('$estado');");
					
				return $res;
	}
	
	public function numeroPresupuestosYCostoTotalAprobadoIva ($conexion, $idPlanificacionAnual){
	
		$res = $conexion->ejecutarConsulta("SELECT
												count(id_presupuesto) as num_presupuestos,
												sum(costo_iva*cantidad_anual) as total
											FROM
												g_programacion_presupuestaria.presupuesto_asignado
											WHERE
												id_planificacion_anual = $idPlanificacionAnual and
												estado in ('aprobadoDGAF','aprobado');");
					
				return $res;
	}
	
	//REFPRES
	public function numeroPresupuestosRevisados ($conexion, $idPlanificacionAnual, $estado){
	
		$res = $conexion->ejecutarConsulta("SELECT
												count(id_presupuesto) as num_presupuestos_revisados
											FROM
												g_reforma_presupuestaria.presupuesto_asignado
											WHERE
												id_planificacion_anual = $idPlanificacionAnual and
												estado in ('$estado') and
												revisado is true;");
					
		return $res;
	}
	
	//REFPRESTMP
	public function numeroPresupuestosRevisadosTemporal ($conexion, $idPlanificacionAnual, $estado){
	
		$res = $conexion->ejecutarConsulta("SELECT
												count(id_presupuesto) as num_presupuestos_revisados
											FROM
												g_reforma_presupuestaria.presupuesto_asignado_temporal
											WHERE
												id_planificacion_anual = $idPlanificacionAnual and
												estado in ('$estado') and
												revisado is true;");
					
		return $res;
	}
	
	//REFPRES
	public function numeroPresupuestosXEstado ($conexion, $idPlanificacionAnual, $estado){
	
		$res = $conexion->ejecutarConsulta("SELECT
												count(id_presupuesto) as num_presupuestos_revisados
											FROM
												g_reforma_presupuestaria.presupuesto_asignado
											WHERE
												id_planificacion_anual = $idPlanificacionAnual and
												estado in ('$estado');");
					
		return $res;
	}
	
	//REFPRESTMP
	public function numeroPresupuestosXEstadoTemporal ($conexion, $idPlanificacionAnual, $estado){
	
		$res = $conexion->ejecutarConsulta("SELECT
												count(id_presupuesto) as num_presupuestos_revisados
											FROM
												g_reforma_presupuestaria.presupuesto_asignado_temporal
											WHERE
												id_planificacion_anual = $idPlanificacionAnual and
												estado in ('$estado');");
			
		return $res;
	}
	
	//REFPRES
	public function enviarPlanificacionAnual ($conexion, $idPlanificacionAnual, $estado){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_reforma_presupuestaria.planificacion_anual
											SET
												estado='$estado',
												fecha_modificacion = now(),
												revisado = null
											WHERE
												id_planificacion_anual=$idPlanificacionAnual;");
	
		return $res;
	}
	
	//REFPRESTMP
	public function enviarPlanificacionAnualTemporal ($conexion, $idPlanificacionAnual, $estado){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_reforma_presupuestaria.planificacion_anual_temporal
											SET
												estado='$estado',
												fecha_modificacion = now(),
												revisado = null
											WHERE
												id_planificacion_anual=$idPlanificacionAnual;");
	
		return $res;
	}
	
	public function enviarPresupuestosXPlanificacionAnual ($conexion, $idPlanificacionAnual, $estado){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.presupuesto_asignado
											SET
												estado='$estado',
												fecha_modificacion = now(),
												revisado = null
											WHERE
												id_planificacion_anual=$idPlanificacionAnual and
												estado not in ('eliminado');");
	
		return $res;
	}
	
	//REFPRES
	public function enviarPresupuesto ($conexion, $idPresupuesto, $estado){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_reforma_presupuestaria.presupuesto_asignado
											SET
												estado='$estado',
												fecha_modificacion = now(),
												revisado = null
											WHERE
												id_presupuesto=$idPresupuesto and
												estado not in ('eliminado', 'aprobado');");
	
		return $res;
	}
	
	//REFPRESTMP
	public function enviarPresupuestoTemporal ($conexion, $idPresupuesto, $estado){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_reforma_presupuestaria.presupuesto_asignado_temporal
											SET
												estado='$estado',
												fecha_modificacion = now(),
												revisado = null
											WHERE
												id_presupuesto=$idPresupuesto and
												estado not in ('eliminado');");
	
		return $res;
	}
	
	//REVISORES PROGRAMACION ANUAL
	//REFPRES
	public function listarProgramacionAnualVistaRevision ($conexion, $areaN2, $areaN4, $idGestion, $tipo, $anio, $identificadorRevisor, $estado){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_reforma_presupuestaria.vista_planificacion_anual pa
											WHERE
												pa.id_area_n2 = '$areaN2' and
												pa.id_area_n4 = '$areaN4' and
												pa.id_area_unidad = '$idGestion' and
												pa.tipo = '$tipo' and
												pa.estado in ($estado) and
												pa.anio = $anio and
												pa.identificador_revisor = '$identificadorRevisor'
											ORDER BY
												pa.id_objetivo_estrategico, pa.id_objetivo_especifico,
												pa.id_objetivo_operativo, pa.id_area_unidad asc;");
					
		return $res;
	}
	
	//RESPRESTMP
	public function listarProgramacionAnualTemporalVistaRevision ($conexion, $areaN2, $areaN4, $idGestion, $tipo, $anio, $identificadorRevisor, $estado){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_reforma_presupuestaria.vista_planificacion_anual_temporal pa
											WHERE
												pa.id_area_n2 = '$areaN2' and
												pa.id_area_n4 = '$areaN4' and
												pa.id_area_unidad = '$idGestion' and
												pa.tipo = '$tipo' and
												pa.estado in ($estado) and
												pa.anio = $anio and
												pa.identificador_revisor = '$identificadorRevisor'
											ORDER BY
												pa.id_objetivo_estrategico, pa.id_objetivo_especifico,
												pa.id_objetivo_operativo, pa.id_area_unidad asc;");
		
		return $res;
	}
	
	//REFPRES
	public function abrirProgramacionAnualRevision ($conexion, $idProgramacionAnual){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_reforma_presupuestaria.vista_planificacion_anual pa
											WHERE
												pa.id_planificacion_anual = $idProgramacionAnual;");
			
		return $res;
	}
	
	//REFPRESTMP
	public function abrirProgramacionAnualRevisionTemporal ($conexion, $idProgramacionAnual){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_reforma_presupuestaria.vista_planificacion_anual_temporal pa
											WHERE
												pa.id_planificacion_anual = $idProgramacionAnual;");
			
		return $res;
	}
	
	public function revisarPlanificacionAnual ($conexion, $idPlanificacionAnual, $estado, $observaciones, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.planificacion_anual
											SET
												estado='$estado',
												fecha_revision = now(),
												observaciones_revision = '$observaciones',
												revisado = true
											WHERE
												id_planificacion_anual=$idPlanificacionAnual and
												identificador_revisor='$identificador';");
	
		return $res;
	}
	
	//REFPRES
	public function imprimirLineaPresupuestoRevision($idPresupuesto, $actividad, $detalleGasto, $renglon, $costo, $cantidadAnual, $cuatrimestre,
			$idPlanificacionAnual, $ruta, $estadoRevision, $estado){
		echo $estadoRevision;
		if($estadoRevision == true){
			$revisado = 'activo';
		}else{
			$revisado = 'inactivo';
		}
		
		return '<tr id="R' . $idPresupuesto . '">' .
				'<td width="30%">' .
				$actividad .$estadoRevision.
				'</td>' .
				'<td width="30%">' .
				$detalleGasto .
				'</td>' .
				'<td width="10%">' .
				$renglon .
				'</td>' .
				'<td width="10%">' .
				$costo .
				'</td>' .
				'<td width="10%">' .
				$cuatrimestre .
				'</td>' .
				'<td width="10%">' .
				$estado .
				'</td>' .
				'<td>' .
				'<div class="'.$revisado.'" >' .
				'<button type="button" class="icono"></button>' .
				'</div>' .
				'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="'.$ruta.'" data-opcion="abrirPresupuestoRevision" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idPresupuesto" value="' . $idPresupuesto . '" >' .
				'<input type="hidden" name="idPlanificacionAnual" value="' . $idPlanificacionAnual . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>';
	}
	
	//REFPRES
	public function imprimirLineaPresupuestoRevisionDGAFDGPGE($idPresupuesto, $actividad, $detalleGasto, $renglon, $costo, $cantidadAnual, $cuatrimestre,
			$idPlanificacionAnual, $ruta, $estadoRevision, $idArea, $estado){
		
		if($idArea == 'DGPGE'){
			$archivo = 'abrirPresupuestoRevisionDGPGE';
		}else if($idArea == 'GA'){
			$archivo = 'abrirPresupuestoRevisionGA';
		}else  if($idArea == 'GF'){
			$archivo='abrirPresupuestoRevisionGF';
		}
		
		if($estadoRevision == true){
			$revisado = 'activo';
		}else{
			$revisado = 'inactivo';
		}
	
		return '<tr id="R' . $idPresupuesto . '">' .
				'<td width="30%">' .
				$actividad .$estadoRevision.
				'</td>' .
				'<td width="30%">' .
				$detalleGasto .
				'</td>' .
				'<td width="10%">' .
				$renglon .
				'</td>' .
				'<td width="10%">' .
				$costo .
				'</td>' .
				'<td width="10%">' .
				$cuatrimestre .
				'</td>' .
				'<td width="10%">' .
				$estado .
				'</td>' .
				'<td>' .
				'<div class="'.$revisado.'" >' .
				'<button type="button" class="icono"></button>' .
				'</div>' .
				'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="'.$ruta.'" data-opcion="'.$archivo.'" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idPresupuesto" value="' . $idPresupuesto . '" >' .
				'<input type="hidden" name="idPlanificacionAnual" value="' . $idPlanificacionAnual . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>';
	}
	
	//REFPRES
	public function revisarPresupuesto ($conexion, $idPresupuesto, $estado, $observaciones, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_reforma_presupuestaria.presupuesto_asignado
											SET
												estado='$estado',
												fecha_revision = now(),
												observaciones_revision = '$observaciones',
												revisado = true
											WHERE
												id_presupuesto = $idPresupuesto and
												identificador_revisor = '$identificador';");
	
		return $res;
	}
	
	//REFPRESTMP
	public function revisarPresupuestoTemporal ($conexion, $idPresupuesto, $estado, $observaciones, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_reforma_presupuestaria.presupuesto_asignado_temporal
											SET
												estado='$estado',
												fecha_revision = now(),
												observaciones_revision = '$observaciones',
												revisado = true
											WHERE
												id_presupuesto = $idPresupuesto and
												identificador_revisor = '$identificador';");
	
		return $res;
	}
	
	//REFPRES
	public function asignarAprobadorPlanificacionAnual ($conexion, $idPlanificacionAnual, $identificadorAprobador, $idArea){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_reforma_presupuestaria.planificacion_anual
											SET
												identificador_aprobador='$identificadorAprobador',
												id_area_aprobador = '$idArea'
											WHERE
												id_planificacion_anual = $idPlanificacionAnual;");
	
		return $res;
	}
	
	//REFPRESTMP
	public function asignarAprobadorPlanificacionAnualTemporal ($conexion, $idPlanificacionAnual, $identificadorAprobador, $idArea){
	
		if($idArea == 'DGPGE'){
			$revisor = "identificador_revisor_dgpge='$identificadorAprobador', id_area_revisor_dgpge = '$idArea'";
		}else if($idArea == 'GA'){
			$revisor = "identificador_revisor_ga='$identificadorAprobador', id_area_revisor_ga = '$idArea'";
		}else  if($idArea == 'GF'){
			$revisor = "identificador_revisor_gf='$identificadorAprobador', id_area_revisor_gf = '$idArea'";
		}else{
			$revisor="identificador_aprobador='$identificadorAprobador', id_area_aprobador = '$idArea'";
		}
		
		$res = $conexion->ejecutarConsulta("UPDATE
												g_reforma_presupuestaria.planificacion_anual_temporal
											SET
												$revisor
											WHERE
												id_planificacion_anual = $idPlanificacionAnual;");
		
		return $res;
	}
	
	//REFPRES
	public function asignarAprobadorPresupuesto ($conexion, $idPresupuesto, $identificadorAprobador, $idArea){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_reforma_presupuestaria.presupuesto_asignado
											SET
												identificador_aprobador='$identificadorAprobador',
												id_area_aprobador = '$idArea'
											WHERE
												id_presupuesto = $idPresupuesto;");
	
		return $res;
	}
	
	//REFPRESTMP
	public function asignarAprobadorPresupuestoTemporal ($conexion, $idPresupuesto, $identificadorAprobador, $idArea){
	
		if($idArea == 'DGPGE'){
			$revisor = "identificador_revisor_dgpge='$identificadorAprobador', id_area_revisor_dgpge = '$idArea'";
		}else if($idArea == 'GA'){
			$revisor = "identificador_revisor_ga='$identificadorAprobador', id_area_revisor_ga = '$idArea'";
		}else  if($idArea == 'GF'){
			$revisor = "identificador_revisor_gf='$identificadorAprobador', id_area_revisor_gf = '$idArea'";
		}else{
			$revisor="identificador_aprobador='$identificadorAprobador', id_area_aprobador = '$idArea'";
		}
		
		$res = $conexion->ejecutarConsulta("UPDATE
												g_reforma_presupuestaria.presupuesto_asignado_temporal
											SET
												$revisor
											WHERE
												id_presupuesto = $idPresupuesto;");
	
		return $res;
	}
	
	//APROBADORES PLANIFICACION ANUAL
	//REFPRES
	public function listarProgramacionAnualVistaAprobacion ($conexion, $areaN2, $areaN4, $idGestion,
															$tipo, $anio, $identificadorAprobador, 
															$estado, $idArea){
	
		if($idArea == 'DGPGE'){
			$revisor = "pa.identificador_revisor_dgpge='$identificadorAprobador'";
		}else if($idArea == 'GA'){
			$revisor = "pa.identificador_revisor_ga='$identificadorAprobador'";
		}else  if($idArea == 'GF'){
			$revisor = "pa.identificador_revisor_gf='$identificadorAprobador'";
		}else{
			$revisor="pa.identificador_aprobador = '$identificadorAprobador'";
		}
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_reforma_presupuestaria.vista_planificacion_anual pa
											WHERE
												pa.id_area_n2 = '$areaN2' and
												pa.id_area_n4 = '$areaN4' and
												pa.id_area_unidad = '$idGestion' and
												pa.tipo = '$tipo' and
												pa.estado in ($estado) and
												pa.anio = $anio and
												$revisor
											ORDER BY
												pa.id_objetivo_estrategico, pa.id_objetivo_especifico,
												pa.id_objetivo_operativo, pa.id_area_unidad asc;");
							
		return $res;
	}
	
	//REFPRESTMP
	public function listarProgramacionAnualTemporalVistaAprobacion ($conexion, $areaN2, $areaN4, $idGestion,
															$tipo, $anio, $identificadorAprobador, 
															$estado, $idArea){
		
		if($idArea == 'DGPGE'){
			$revisor = "pa.identificador_aprobador_dgpge='$identificadorAprobador'";
		}else if($idArea == 'GA'){
			$revisor = "pa.identificador_aprobador_ga='$identificadorAprobador'";
		}else  if($idArea == 'GF'){
			$revisor = "pa.identificador_aprobador_gf='$identificadorAprobador'";
		}else{
			$revisor="pa.identificador_aprobador = '$identificadorAprobador'";
		}
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_reforma_presupuestaria.vista_planificacion_anual_temporal pa
											WHERE
												pa.id_area_n2 = '$areaN2' and
												pa.id_area_n4 = '$areaN4' and
												pa.id_area_unidad = '$idGestion' and
												pa.tipo = '$tipo' and
												pa.estado in ($estado) and
												pa.anio = $anio and
												$revisor
											ORDER BY
												pa.id_objetivo_estrategico, pa.id_objetivo_especifico,
												pa.id_objetivo_operativo, pa.id_area_unidad asc;");
		
		return $res;
	}
	
	public function imprimirLineaPresupuestoAprobacion($idPresupuesto, $actividad, $detalleGasto, $renglon, $costo, $cantidadAnual, $cuatrimestre,
			$idPlanificacionAnual, $ruta, $estadoRevision){
		echo $estadoRevision;
		if($estadoRevision == true){
			$revisado = 'activo';
		}else{
			$revisado = 'inactivo';
		}
	
		return '<tr id="R' . $idPresupuesto . '">' .
				'<td width="30%">' .
				$actividad .$estadoRevision.
				'</td>' .
				'<td width="30%">' .
				$detalleGasto .
				'</td>' .
				'<td width="10%">' .
				$renglon .
				'</td>' .
				'<td width="10%">' .
				$costo .
				'</td>' .
				'<td width="10%">' .
				$cantidadAnual .
				'</td>' .
				'<td width="10%">' .
				$cuatrimestre .
				'</td>' .
				'<td>' .
				'<div class="'.$revisado.'" >' .
				'<button type="button" class="icono"></button>' .
				'</div>' .
				'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="'.$ruta.'" data-opcion="abrirPresupuestoAprobacion" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idPresupuesto" value="' . $idPresupuesto . '" >' .
				'<input type="hidden" name="idPlanificacionAnual" value="' . $idPlanificacionAnual . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>';
	}
	
	public function aprobarPlanificacionAnual ($conexion, $idPlanificacionAnual, $estado, $observaciones, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.planificacion_anual
											SET
												estado='$estado',
												fecha_aprobacion = now(),
												observaciones_aprobacion = '$observaciones',
												revisado = true
											WHERE
												id_planificacion_anual=$idPlanificacionAnual and
												identificador_aprobador='$identificador';");
	
		return $res;
	}
	
	//APROBADORES PRESUPUESTO
	public function listarProgramacionAnualVistaAprobacionDGAF ($conexion, $areaN2, $areaN4, $idGestion, $tipo, $anio, $estado){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.vista_planificacion_anual pa
											WHERE
												pa.id_area_n2 = '$areaN2' and
												pa.id_area_n4 = '$areaN4' and
												pa.id_area_unidad = '$idGestion' and
												pa.tipo = '$tipo' and
												pa.estado in ($estado) and
												pa.anio = $anio
											ORDER BY
												pa.id_objetivo_estrategico, pa.id_objetivo_especifico,
												pa.id_objetivo_operativo, pa.id_area_unidad asc;");
					
		return $res;
	}
	
	//REFPRES
	public function aprobarPresupuestoDGPGE ($conexion, $idPresupuesto, $estado, $observaciones, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_reforma_presupuestaria.presupuesto_asignado
											SET
												estado='$estado',
												fecha_aprobacion = now(),
												observaciones_aprobacion = '$observaciones',
												revisado = true
											WHERE
												id_presupuesto = $idPresupuesto and
												identificador_aprobador = '$identificador';");
	
		return $res;
	}
	
	//REFPRESTMP
	public function aprobarPresupuestoTemporalDGPGE ($conexion, $idPresupuesto, $estado, $observaciones, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_reforma_presupuestaria.presupuesto_asignado_temporal
											SET
												estado='$estado',
												fecha_revision_dgpge = now(),
												observaciones_revision_dgpge = '$observaciones',
												revisado = true
											WHERE
												id_presupuesto = $idPresupuesto and
												identificador_revisor_dgpge = '$identificador';");
	
		return $res;
	}
	
	//REFPRESTMP
	public function aprobarPresupuestoTemporalGA ($conexion, $idPresupuesto, $estado, $observaciones, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_reforma_presupuestaria.presupuesto_asignado_temporal
											SET
												estado='$estado',
												fecha_revision_ga = now(),
												observaciones_revision_ga = '$observaciones',
												revisado = true
											WHERE
												id_presupuesto = $idPresupuesto and
												identificador_revisor_ga = '$identificador';");
	
		return $res;
	}
	
	//REFPRESTMP
	public function aprobarPresupuestoTemporalGF ($conexion, $idPresupuesto, $estado, $observaciones, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_reforma_presupuestaria.presupuesto_asignado_temporal
											SET
												estado='$estado',
												fecha_revision_gf = now(),
												observaciones_revision_gf = '$observaciones',
												revisado = true
											WHERE
												id_presupuesto = $idPresupuesto and
												identificador_revisor_gf = '$identificador';");
	
		return $res;
	}
	
	//Filtros Reportes Aprobador
	public function listarObjetivosEspecificosXAnio ($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.objetivo_especifico oes
											WHERE
												oes.estado = 'activo' and
												oes.anio = $anio
											ORDER BY
												oes.nombre asc;");
	
		return $res;
	}
	
	public function listarObjetivosOperativosXAnio ($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.objetivo_operativo oes
											WHERE
												oes.estado = 'activo' and
												oes.anio = $anio
											ORDER BY
												oes.nombre asc;");
	
		return $res;
	}
	
	public function listarProcesoProyectoXAnio ($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.proceso_proyecto pp
											WHERE
												pp.estado = 'activo' and
												pp.anio = $anio
											ORDER BY
												pp.nombre asc;");
	
		return $res;
	}
	
	public function listarComponenteXAnio ($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.componente c
											WHERE
												c.estado = 'activo' and
												c.anio = $anio
											ORDER BY
												c.nombre asc;");
	
		return $res;
	}
	
	//Detalle Funciones para Reporte General
	//Planificacion Anual
	public function listarProcesoProyectoXGestionYTipoReporte ($conexion, $idArea, $tipo, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.proceso_proyecto pp
											WHERE
												pp.estado = 'activo' and
												pp.id_area = '$idArea' and
												pp.tipo = '$tipo' and
												pp.anio = $anio
											ORDER BY
												pp.nombre asc;");
	
		return $res;
	}
	
	
	public function obtenerReportePlanificacionAnual($conexion,$idObjetivoEstrategico, $idAreaN2, $idObjetivoEspecifico, 
			$idAreaN4, $idObjetivoOperativo, $idGestion, $idProceso, $idComponente, $idActividad, $idProvincia, 
			$anio, $estado, $tipo){
		
		$idObjetivoEstrategico = $idObjetivoEstrategico!="" ? "" . $idObjetivoEstrategico . "" : "null";
		$idAreaN2 = $idAreaN2!="" ? "'" . $idAreaN2 . "'" : "null";
		$idObjetivoEspecifico = $idObjetivoEspecifico!="" ? "" . $idObjetivoEspecifico . "" : "null";
		$idAreaN4 = $idAreaN4!="" ? "'" . $idAreaN4 . "'" : "null";
		$idObjetivoOperativo = $idObjetivoOperativo!="" ? "" . $idObjetivoOperativo . "" : "null";
		$idGestion = $idGestion!="" ? "'" . $idGestion . "'" : "null";
		$idProceso = $idProceso!="" ? "" . $idProceso . "" : "null";
		$idComponente = $idComponente!="" ? "" . $idComponente . "" : "null";
		$idActividad = $idActividad!="" ? "" . $idActividad . "" : "null";
		$idProvincia = $idProvincia!="" ? "" . $idProvincia . "" : "null";
		$anio = $anio!="" ? "" . $anio . "" : "null";
		$estado = $estado!="" ? "'" . $estado . "'" : "null";
		$tipo = $tipo!="" ? "'" . $tipo . "'" : "null";
	
		$res = $conexion->ejecutarConsulta("SELECT
												* 
											FROM g_programacion_presupuestaria.planificacion_anual($idObjetivoEstrategico,
												$idAreaN2,$idObjetivoEspecifico,$idAreaN4,$idObjetivoOperativo,
												$idGestion,$idProceso,$idComponente,$idActividad,$idProvincia,
												$anio,$estado, $tipo);"
											);
		
		return $res;
	}
	
	public function obtenerReportePresupuestos($conexion,$idAreaN2, $idAreaN4, $idGestion, $idProceso, 
											$idComponente, $idActividad, $tipo, $idProvincia, $anio, $estado){
	
		$idAreaN2 = $idAreaN2!="" ? "'" . $idAreaN2 . "'" : "null";
		$idAreaN4 = $idAreaN4!="" ? "'" . $idAreaN4 . "'" : "null";
		$idGestion = $idGestion!="" ? "'" . $idGestion . "'" : "null";
		$idProceso = $idProceso!="" ? "" . $idProceso . "" : "null";
		$idComponente = $idComponente!="" ? "" . $idComponente . "" : "null";
		$idActividad = $idActividad!="" ? "" . $idActividad . "" : "null";
		$tipo = $tipo!="" ? "'" . $tipo . "'" : "null";
		$idProvincia = $idProvincia!="" ? "" . $idProvincia . "" : "null";
		$anio = $anio!="" ? "" . $anio . "" : "null";
		$estado = $estado!="" ? "'" . $estado . "'" : "null";
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM 
												g_programacion_presupuestaria.presupuestos($idAreaN2,$idAreaN4,$idGestion,$idProceso,
																	$idComponente,$idActividad,$tipo,$idProvincia,$anio,$estado);"
		);
	
		return $res;
	}
	
	//Cerrar Proceso DGPGE
	public function cerrarProcesoPlanificacionAnual ($conexion, $identificador, $estado, $estadoActual){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.planificacion_anual
											SET
												estado='$estado',
												fecha_cierre = now(),
												revisado = null
											WHERE
												identificador_aprobador = '$identificador' and
												estado = '$estadoActual';");
		
		return $res;
	}
	
	//Cerrar Proceso DGAF
	public function cerrarProcesoPlanificacionAnualPresupuesto ($conexion, $identificador, $estado, $estadoActual){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.presupuesto_asignado
											SET
												estado='$estado',
												fecha_cierre = now(),
												revisado = null
											WHERE
												identificador_aprobador = '$identificador' and
												estado = '$estadoActual';");
	
		return $res;
	}
	
	//Matriz PAC
	
	//REFPRES
	public function obtenerReportePac($conexion,$idAreaN2, $idPrograma,
			$idProyecto, $idActividad, $idProvincia, $anio, $estado){
	
			$idAreaN2 = $idAreaN2!="" ? "'" . $idAreaN2 . "'" : "null";
			$idPrograma = $idPrograma!="" ? "'" . $idPrograma . "'" : "null";
			$idProyecto = $idProyecto!="" ? "'" . $idProyecto . "'" : "null";
			$idActividad = $idActividad!="" ? "'" . $idActividad . "'" : "null";
			$idProvincia = $idProvincia!="" ? "" . $idProvincia . "" : "null";
			$anio = $anio!="" ? "" . $anio . "" : "null";
			$estado = $estado!="" ? "'" . $estado . "'" : "null";
			
			$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_reforma_presupuestaria.pac($idAreaN2,$idPrograma,
													$idProyecto,$idActividad,$idProvincia,$anio,$estado);"
												);
		
			return $res;
	}
	
	//REFPRESTMP
	public function obtenerReportePacTemporal($conexion,$idAreaN2, $idPrograma,
												$idProyecto, $idActividad, $idProvincia, $anio, $estado){
	
		$idAreaN2 = $idAreaN2!="" ? "'" . $idAreaN2 . "'" : "null";
		$idPrograma = $idPrograma!="" ? "'" . $idPrograma . "'" : "null";
		$idProyecto = $idProyecto!="" ? "'" . $idProyecto . "'" : "null";
		$idActividad = $idActividad!="" ? "'" . $idActividad . "'" : "null";
		$idProvincia = $idProvincia!="" ? "" . $idProvincia . "" : "null";
		$anio = $anio!="" ? "" . $anio . "" : "null";
		$estado = $estado!="" ? "'" . $estado . "'" : "null";
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_reforma_presupuestaria.pac_temporal($idAreaN2,$idPrograma,
												$idProyecto,$idActividad,$idProvincia,$anio,$estado);");
		
		return $res;
	}
	
	//REFPRES
	public function obtenerReportePacFortalecimiento($conexion,$idAreaN2, $idPrograma,
			$idProyecto, $idActividad, $idProvincia, $anio, $estado){
	
		$idAreaN2 = $idAreaN2!="" ? "'" . $idAreaN2 . "'" : "null";
		$idPrograma = $idPrograma!="" ? "'" . $idPrograma . "'" : "null";
		$idProyecto = $idProyecto!="" ? "'" . $idProyecto . "'" : "null";
		$idActividad = $idActividad!="" ? "'" . $idActividad . "'" : "null";
		$idProvincia = $idProvincia!="" ? "" . $idProvincia . "" : "null";
		$anio = $anio!="" ? "" . $anio . "" : "null";
		$estado = $estado!="" ? "'" . $estado . "'" : "null";
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_reforma_presupuestaria.pac_fortalecimiento($idAreaN2,$idPrograma,
													$idProyecto,$idActividad,$idProvincia,$anio,$estado);");

		return $res;
	}
	
	//REFPRESTMP
	public function obtenerReportePacFortalecimientoTemporal($conexion,$idAreaN2, $idPrograma,
			$idProyecto, $idActividad, $idProvincia, $anio, $estado){
	
			$idAreaN2 = $idAreaN2!="" ? "'" . $idAreaN2 . "'" : "null";
			$idPrograma = $idPrograma!="" ? "'" . $idPrograma . "'" : "null";
			$idProyecto = $idProyecto!="" ? "'" . $idProyecto . "'" : "null";
			$idActividad = $idActividad!="" ? "'" . $idActividad . "'" : "null";
			$idProvincia = $idProvincia!="" ? "" . $idProvincia . "" : "null";
			$anio = $anio!="" ? "" . $anio . "" : "null";
			$estado = $estado!="" ? "'" . $estado . "'" : "null";
				
			$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_reforma_presupuestaria.pac_fortalecimiento_temporal($idAreaN2,$idPrograma,
													$idProyecto,$idActividad,$idProvincia,$anio,$estado);");

			return $res;
		}
	
	/*
	 public function obtenerReportePac($conexion,$idAreaN2, $idProceso,
			$idActividad, $tipo, $idProvincia, $anio, $estado){
	
			$idAreaN2 = $idAreaN2!="" ? "'" . $idAreaN2 . "'" : "null";
			$idProceso = $idProceso!="" ? "" . $idProceso . "" : "null";
			$idActividad = $idActividad!="" ? "" . $idActividad . "" : "null";
			$tipo = $tipo!="" ? "'" . $tipo . "'" : "null";
			$idProvincia = $idProvincia!="" ? "" . $idProvincia . "" : "null";
			$anio = $anio!="" ? "" . $anio . "" : "null";
			$estado = $estado!="" ? "'" . $estado . "'" : "null";
			
			$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_programacion_presupuestaria.pac($idAreaN2,$idProceso,
													$idActividad,$tipo,$idProvincia,$anio,$estado);"
												);
		
			return $res;
	}
   
   
   CREATE OR REPLACE FUNCTION g_programacion_presupuestaria.pac(_n2 text, _proceso integer, _actividad integer, _tipo text, _provincia integer, _anio integer, _estado text)
  RETURNS SETOF g_programacion_presupuestaria.vista_pac AS
$BODY$
DECLARE
	query text;
BEGIN	
	return query EXECUTE '	
		SELECT
			*
		FROM 
			g_programacion_presupuestaria.vista_pac
		WHERE 		        
			($1 is NULL or id_area_n2 = $1) and 
			($2 is NULL or id_proceso_proyecto = $2) and 
			($3 is NULL or id_actividad = $3) and
			($4 is NULL or tipo = $4) and
			($5 is NULL or id_provincia = $5) and
			($6 is NULL or anio = $6) and
			($7 is NULL or estado_presupuesto = $7)
			
		ORDER BY
			area_n2, proceso_proyecto, actividad'
			
		using _n2, _proceso, _actividad, _tipo, _provincia, _anio, _estado;
	
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100
  ROWS 1000;
ALTER FUNCTION g_programacion_presupuestaria.pac(text, integer, integer, text, integer, integer, text)
  OWNER TO postgres;

	 */
	
	//Detalle Funciones para Reporte General PAC
	public function listarProgramaXMatrizPAC ($conexion, $tipo){
	
		$res = $conexion->ejecutarConsulta("SELECT
				*
				FROM
				g_programacion_presupuestaria.proceso_proyecto pp
				WHERE
				pp.estado = 'activo' and
				pp.id_area = '$idArea' and
				pp.tipo = '$tipo' and
				pp.anio = $anio
				ORDER BY
				pp.nombre asc;");
	
				return $res;
	}
}
?>
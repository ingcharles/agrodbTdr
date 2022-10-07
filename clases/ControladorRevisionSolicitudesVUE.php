<?php

class ControladorRevisionSolicitudesVUE{
	////////////////////// EVALUACION DE PRODUCTOS IMPORTACION //////////////////////////
	/*OK*/
	
	public function asignarRevisionElementosInspeccion ($conexion, $idAsignacion, $idProducto, $revision){
		$res = $conexion->ejecutarConsulta("update
												g_revision_solicitudes.inspeccion
											set
												revision_numero = $revision
											where
												numero_revision = '' and
												id_producto = $idProducto and
												id_asignacion = $idAsignacion;");
		return $res;
	}
	
	/*OKOK*/
	public function guardarDatosInspeccionDocumental ($conexion, $idGrupo, $identificador, $observacion, $estado, $orden, $archivoDocumental = ''){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_revision_solicitudes.revision_documental(
												id_grupo, identificador_inspector, fecha_inspeccion, observacion, estado, orden, ruta_archivo_documental)
											VALUES ($idGrupo, '$identificador', now(), $$$observacion$$, '$estado', $orden, '$archivoDocumental');");
		return $res;
	}

	/*OKOK*/
	public function guardarDatosInspeccionElementos ($conexion, $idGrupo, $identificador, $archivo, $estado, $orden, $observacion = null){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_revision_solicitudes.inspeccion(
												id_grupo, identificador_inspector, fecha_inspeccion, ruta_archivo,
												estado, orden, observacion)
											VALUES ($idGrupo, '$identificador', now(), '$archivo', '$estado', $orden, '$observacion') RETURNING id_inspeccion;");
		return $res;
	}
	
	public function guardarDatosInspeccionTablets ($conexion, $idGrupo, $identificador, $fechaInspeccion, $estado, $orden, $idInspeccionTablet, $identificadorTablet, $versionbd, $serial){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO g_revision_solicitudes.inspeccion(
												id_grupo, identificador_inspector, fecha_inspeccion, estado, orden, id_inspeccion_tablet, identificador_tablet, version_bd, serial, ruta_archivo)
										VALUES ($idGrupo, '$identificador', '$fechaInspeccion', '$estado', $orden, $idInspeccionTablet, '$identificadorTablet', $versionbd, $serial ,'0') RETURNING id_inspeccion;");
		return $res;
	}
	
	public function actualizarObservacionTablets($conexion, $idInspeccion, $observacion){
		
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_revision_solicitudes.inspeccion
											SET
												observacion = '$observacion'
											WHERE
												id_inspeccion = $idInspeccion;");
		
		return $res;
	}
	
	public function guardarDatosInspeccionObservaciones ($conexion, $idInspeccion, $idItemInspeccion, $observacion, $tipoElemento, $idSolicitud){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_revision_solicitudes.inspeccion_observaciones(
								id_inspeccion, id_item_inspeccion, fecha_inspeccion, observacion, tipo_elemento, id_solicitud)
				VALUES ($idInspeccion, $idItemInspeccion, now(), '$observacion', '$tipoElemento', '$idSolicitud');");
		return $res;
	}
	
	////////////////////////////// ASIGNACION DE IMPORTACIONES A INSPECTORES PARA REVISION ///////////////////////////////////
	

	/*OK*/
	public function asignarMontoSolicitud ($conexion, $idGrupo, $idInspector, $monto, $orden){
				
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_revision_solicitudes.financiero (id_grupo, identificador_inspector, monto, fecha_asignacion_monto, orden)
											VALUES($idGrupo, '$idInspector', $monto, now(), $orden) returning id_financiero;");
		return $res;
	}
	
	//Se utiliza para asignacion de técnicos y financieros
	public function guardarNuevoInspector ($conexion,$identificadorInspector, $idCoordinador, $tipoSolicitud, $tipoInspector, $idOperadorTipoOperacion=0, $idHistorialOperacion=0){

		$identificadorInspector = $identificadorInspector != "" ? "'" . $identificadorInspector ."'" : "NULL";
		$idCoordinador = $idCoordinador != "" ? "'" . $idCoordinador ."'" : "NULL";
		
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_revision_solicitudes.asignacion_inspector(identificador_inspector, fecha_asignacion, identificador_asignante, tipo_solicitud, tipo_inspector, id_operador_tipo_operacion, id_historial_operacion)
											VALUES ($identificadorInspector,now(), $idCoordinador, '$tipoSolicitud', '$tipoInspector', '$idOperadorTipoOperacion', '$idHistorialOperacion')
											RETURNING id_grupo;");
		return $res;
	}
	
	/*OKOK*/
	public function listarInspectoresAsignados ($conexion,$idSolicitud, $tipoSolicitud, $tipoInspector){
			
		$res = $conexion->ejecutarConsulta("select
												ac.*,
												fe.nombre,
												fe.apellido
											from
												g_revision_solicitudes.asignacion_coordinador ac,
												g_uath.ficha_empleado fe
											where
												ac.id_solicitud = $idSolicitud and
												ac.tipo_solicitud= '$tipoSolicitud' and
												ac.tipo_inspector= '$tipoInspector' and
												ac.identificador_inspector = fe.identificador;");
		return $res;
	}
	
	/*OKOK*/
	//Revisa si el inspector está asignado a la solicitud para revision
	public function buscarInspectorAsignado ($conexion,$idGrupoSolicitud, $identificadorInspector, $tipoSolicitud, $tipoInspector){
	
		$res = $conexion->ejecutarConsulta("select
												ai.*,
												fe.nombre,
												fe.apellido
											from
												g_revision_solicitudes.asignacion_inspector ai,
												g_revision_solicitudes.grupos_solicitudes gs,
												g_uath.ficha_empleado fe
											where
												ai.id_grupo = gs.id_grupo and
												gs.id_solicitud IN ($idGrupoSolicitud) and
												ai.tipo_solicitud= '$tipoSolicitud' and
												ai.tipo_inspector= '$tipoInspector' and
												ai.identificador_inspector = '$identificadorInspector'and
												ai.identificador_inspector = fe.identificador;");
		
		return $res;
	}
	
	public function eliminarInspectorAsignado ($conexion,$idSolicitud,$identificadorInspector, $tipoSolicitud, $tipoTecnico){
			
		$res = $conexion->ejecutarConsulta("delete from
												g_revision_solicitudes.asignacion_coordinador
											where
												id_solicitud = $idSolicitud and
												identificador_inspector = '$identificadorInspector' and
												tipo_solicitud = '$tipoSolicitud' and
												tipo_inspector = '$tipoTecnico';");
		return $res;
	}
	
	
	///// TRANSACCION BANCARIA /////
	public function obtenerMontoSolicitud ($conexion, $idGrupo, $tipoSolicitud){
		$res = $conexion->ejecutarConsulta("select
												f.*
											from
												g_revision_solicitudes.asignacion_inspector ai,
												g_revision_solicitudes.financiero f
											where
												ai.id_grupo = $idGrupo and
												ai.tipo_solicitud = '$tipoSolicitud' and
												ai.id_asignacion = f.id_asignacion;");
		
		return $res;
	}
	
	//// HISTORIAL //////
	
	public function listarHistorialSolicitud ($conexion, $idSolicitud, $items){
		$res = $conexion->ejecutarConsulta("select 
												i.*,
												p.nombre_comun
											from
												g_revision_solicitudes.asignacion_inspector ai,
												g_revision_solicitudes.inspeccion i,
												g_catalogos.productos p
											where
												ai.id_solicitud = $idSolicitud and
												ai.tipo_inspector = 'Técnico' and
												ai.id_asignacion = i.id_asignacion and
												i.id_item_inspeccion = p.id_producto
											order by fecha_inspeccion asc
											limit $items");
		
		return $res;
	}
	
	public function guardarInspeccionFinanciero ($conexion, $idAsignacion, $identificador, $estado, $observacion, $banco, $montoRecaudado, $nombreBanco ,$numeroFactura=null, $numeroOrdenVue=null){
		
		$res = $conexion->ejecutarConsulta("UPDATE
												g_revision_solicitudes.financiero
											SET
												fecha_inspeccion = now(),
												estado = '$estado',
												observacion = '$observacion',
												codigo_banco = '$banco',
												monto_recaudado = $montoRecaudado,
												numero_factura = '$numeroFactura',
												nombre_banco = '$nombreBanco',
												identificador_verificador = '$identificador',
												numero_orden_vue = '$numeroOrdenVue'
											WHERE
												id_financiero = $idAsignacion;");
				return $res;
	}
	
	
	
	public function buscarSerialOrden ($conexion, $idGrupo, $tipoInspector){
		
		$busqueda = 0;
		switch ($tipoInspector){
			case 'Aprobación': $busqueda = 'inspeccion'; break;
			case 'Técnico': $busqueda = 'inspeccion'; break;
			case 'Documental':   $busqueda = 'revision_documental'; break;
			case 'Financiero':   $busqueda = 'financiero'; break;
		}
		
	
	/*	$res = $conexion->ejecutarConsulta("SELECT 
												COALESCE(
													MAX(
														CAST(orden as  numeric(5))),0)+1 as orden 
											FROM 
												g_revision_solicitudes.".$busqueda." 
											WHERE 
												id_grupo = (SELECT 
															distinct ai.id_grupo 
														FROM 
															g_revision_solicitudes.asignacion_inspector ai,
															g_revision_solicitudes.grupos_solicitudes gs
														WHERE 	
															ai.id_grupo = gs.id_grupo and
															gs.id_solicitud IN ($idSolicitud) and
															tipo_solicitud = '$tipoSolicitud' and
															tipo_inspector = '$tipoInspector');");*/
		
		$res = $conexion->ejecutarConsulta("SELECT
												 COALESCE(
												 		MAX(
												 				CAST(orden as  numeric(5))),0)+1 as orden
														FROM
																g_revision_solicitudes.".$busqueda."
														WHERE
															id_grupo = $idGrupo;");
		
		
		return $res;
	}
	
	
	public function buscarIdImposicionTasa ($conexion, $idGrupoSolicitud, $tipoSolicitud, $tipoInspector){
							
		$res = $conexion->ejecutarConsulta("SELECT
												f.*
											FROM
												g_revision_solicitudes.asignacion_inspector ai,
												g_revision_solicitudes.financiero f
											WHERE
												ai.id_grupo  = f.id_grupo
												and ai.id_grupo = $idGrupoSolicitud
												and ai.tipo_solicitud = '$tipoSolicitud'
												and ai.tipo_inspector = '$tipoInspector'
												and orden = (SELECT 
															max (orden) 
														FROM 
															g_revision_solicitudes.asignacion_inspector ai,
															g_revision_solicitudes.financiero f
														WHERE
															ai.id_grupo  = f.id_grupo
															and ai.id_grupo = $idGrupoSolicitud
															and ai.tipo_solicitud = '$tipoSolicitud'
															and ai.tipo_inspector = '$tipoInspector' );");
		return $res;
	}
	
	public function buscarIdImposicionTasaXSolicitud ($conexion, $idSolicitud, $tipoSolicitud, $tipoInspector){
					
		$res = $conexion->ejecutarConsulta("SELECT
												f.*
											FROM
												g_revision_solicitudes.asignacion_inspector ai,
												g_revision_solicitudes.financiero f,
												g_revision_solicitudes.grupos_solicitudes gs
											WHERE
												ai.id_grupo = gs.id_grupo and
												ai.id_grupo  = f.id_grupo and
												gs.id_solicitud = $idSolicitud and 
												ai.tipo_solicitud = '$tipoSolicitud' and 
												ai.tipo_inspector = '$tipoInspector' and
												monto_recaudado is null and
												f.orden = (	SELECT 
															max (orden) 
														FROM 
															g_revision_solicitudes.asignacion_inspector ai,
															g_revision_solicitudes.financiero f,
															g_revision_solicitudes.grupos_solicitudes gs
														WHERE
															ai.id_grupo = gs.id_grupo and
															ai.id_grupo  = f.id_grupo and
															gs.id_solicitud = $idSolicitud
															and ai.tipo_solicitud = '$tipoSolicitud'
															and ai.tipo_inspector = '$tipoInspector'
															and monto_recaudado is null)
												ORDER BY id_financiero desc;");
		return $res;
	}
	
	public function buscarIdImposicionTasaXSolicitudReverso ($conexion, $idSolicitud, $tipoSolicitud, $tipoInspector){
			
		$res = $conexion->ejecutarConsulta("SELECT
												f.*
											FROM
												g_revision_solicitudes.asignacion_inspector ai,
												g_revision_solicitudes.financiero f,
												g_revision_solicitudes.grupos_solicitudes gs
											WHERE
												ai.id_grupo = gs.id_grupo and
												ai.id_grupo  = f.id_grupo and
												gs.id_solicitud = $idSolicitud and
												ai.tipo_solicitud = '$tipoSolicitud' and
												ai.tipo_inspector = '$tipoInspector' and
												f.orden = (	SELECT
																max (orden)
															FROM
																g_revision_solicitudes.asignacion_inspector ai,
																g_revision_solicitudes.financiero f,
																g_revision_solicitudes.grupos_solicitudes gs
															WHERE
																ai.id_grupo = gs.id_grupo and
																ai.id_grupo  = f.id_grupo and
																gs.id_solicitud = $idSolicitud
																and ai.tipo_solicitud = '$tipoSolicitud'
																and ai.tipo_inspector = '$tipoInspector')
															ORDER BY id_financiero desc;");
				return $res;
	}
	
	public function buscarResultadosInspeccionDocumental ($conexion,$idSolicitud, $identificadorInspector, $tipoSolicitud, $tipoInspector){
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_revision_solicitudes.asignacion_inspector ii,
												g_revision_solicitudes.revision_documental rd
											where
												ii.identificador_inspector = '$identificadorInspector' and
												ii.id_solicitud = $idSolicitud and
												ii.tipo_solicitud= '$tipoSolicitud' and
												ii.tipo_inspector= '$tipoInspector' and
												ii.id_asignacion = rd.id_asignacion;");
	
				return $res;
	}
	
	/*public function buscarResultadosInspeccionFinanciera ($conexion,$idSolicitud, $identificadorInspector, $tipoSolicitud, $tipoInspector){
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_revision_solicitudes.asignacion_inspector ii,
												g_revision_solicitudes.financiero rd
											where
												ii.identificador_inspector = '$identificadorInspector' and
												ii.id_solicitud = $idSolicitud and
												ii.tipo_solicitud= '$tipoSolicitud' and
												ii.tipo_inspector= '$tipoInspector' and
												ii.id_asignacion = rd.id_asignacion;");

				return $res;
	}*/

	public function buscarResultadosInspeccion ($conexion,$idSolicitud, $identificadorInspector, $tipoSolicitud, $tipoInspector){
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_revision_solicitudes.asignacion_inspector ii,
												g_revision_solicitudes.inspeccion rd
											where
												ii.identificador_inspector = '$identificadorInspector' and
												ii.id_solicitud = $idSolicitud and
												ii.tipo_solicitud= '$tipoSolicitud' and
												ii.tipo_inspector= '$tipoInspector' and
												ii.id_asignacion = rd.id_asignacion;");

				return $res;
	}
				
	public function buscarUltimoEstadoSolicitud ($conexion, $idSolcitud, $tipoSolicitud){
		$res = $conexion->ejecutarConsulta("SELECT 
												ai.tipo_inspector
											FROM
												g_revision_solicitudes.asignacion_inspector ai,
												g_revision_solicitudes.grupos_solicitudes gs
											WHERE 
												ai.id_grupo = gs.id_grupo and
												gs.id_solicitud = $idSolcitud
												and ai.tipo_solicitud = '$tipoSolicitud'
												and ai.id_grupo = (SELECT 
																MAX(ai.id_grupo)
															FROM 
																g_revision_solicitudes.asignacion_inspector ai,
																g_revision_solicitudes.grupos_solicitudes gs
															WHERE 
																ai.id_grupo = gs.id_grupo and
																gs.id_solicitud = $idSolcitud
																and ai.tipo_solicitud = '$tipoSolicitud');");
		return $res;
	}
	
	public function buscarEstadoSolicitudXtipoInspector ($conexion, $idSolcitud, $tipoSolicitud, $tipoInspector){
		$res = $conexion->ejecutarConsulta("SELECT
												ai.tipo_inspector,
												ai.identificador_inspector
											FROM
												g_revision_solicitudes.asignacion_inspector ai,
												g_revision_solicitudes.grupos_solicitudes gs
											WHERE
												ai.id_grupo = gs.id_grupo and
												gs.id_solicitud = $idSolcitud
												and ai.tipo_solicitud = '$tipoSolicitud'
												and ai.tipo_inspector = '$tipoInspector'
												and ai.id_grupo = (SELECT
																		MAX(ai.id_grupo)
																	FROM
																		g_revision_solicitudes.asignacion_inspector ai,
																		g_revision_solicitudes.grupos_solicitudes gs
																	WHERE
																		ai.id_grupo = gs.id_grupo and
																		gs.id_solicitud = $idSolcitud
																		and ai.tipo_solicitud = '$tipoSolicitud'
																		and ai.tipo_inspector = '$tipoInspector');");
				return $res;
	}
	
	public function buscarSolicitudRevisionDocumentalXtipoInspector ($conexion, $idSolcitud, $tipoSolicitud, $tipoInspector){
		$res = $conexion->ejecutarConsulta("SELECT
												rd.observacion
											FROM
												g_revision_solicitudes.asignacion_inspector ai,
												g_revision_solicitudes.grupos_solicitudes gs,
												g_revision_solicitudes.revision_documental rd
											WHERE
												ai.id_grupo = gs.id_grupo and
												gs.id_grupo = rd.id_grupo and
												gs.id_solicitud = $idSolcitud
												and ai.tipo_solicitud = '$tipoSolicitud'
												and ai.tipo_inspector = '$tipoInspector'
												and ai.id_grupo = (SELECT
																		MAX(ai.id_grupo)
																	FROM
																		g_revision_solicitudes.asignacion_inspector ai,
																		g_revision_solicitudes.grupos_solicitudes gs
																	WHERE
																		ai.id_grupo = gs.id_grupo and
																		gs.id_solicitud = $idSolcitud
																		and ai.tipo_solicitud = '$tipoSolicitud'
																		and ai.tipo_inspector = '$tipoInspector');");
		return $res;
	}
	
	
	public function guardarGrupo ($conexion,$idSolicitud,$idGrupo, $estado){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO g_revision_solicitudes.grupos_solicitudes VALUES($idGrupo,$idSolicitud,'$estado');");
	
				return $res;
	}
	
	public function buscarIdGrupo ($conexion, $idSolcitud, $tipoSolicitud, $tipoInspector){
										
		$res = $conexion->ejecutarConsulta("SELECT
												ai.*
											FROM
												g_revision_solicitudes.asignacion_inspector ai,
												g_revision_solicitudes.grupos_solicitudes gs
											WHERE
												ai.id_grupo = gs.id_grupo and
												ai.tipo_solicitud = '$tipoSolicitud' and
												ai.tipo_inspector = '$tipoInspector' and
												gs.id_solicitud = $idSolcitud and 
												gs.estado != 'Verificación'
											ORDER BY id_grupo desc;");
						return $res;
	}
	
	public function buscarInspectorAsignadoCoordinador($conexion,$idSolicitud, $identificadorInspector, $tipoSolicitud, $tipoInspector){
		$res = $conexion->ejecutarConsulta("SELECT
												ac.*,
												fe.nombre,
												fe.apellido
											FROM
												g_revision_solicitudes.asignacion_coordinador ac,
												g_uath.ficha_empleado fe
											WHERE
												ac.id_solicitud = $idSolicitud and
												ac.tipo_solicitud= '$tipoSolicitud' and
												ac.tipo_inspector= '$tipoInspector' and
												ac.identificador_inspector = '$identificadorInspector'and
												ac.identificador_inspector = fe.identificador;");
	
				return $res;
	}
	
	public function guardarNuevoInspectorCoordinador($conexion,$identificadorInspector, $idCoordinador, $tipoSolicitud, $idSolicitud,$tipoInspector){
			
		$res = $conexion->ejecutarConsulta("INSERT INTO
											g_revision_solicitudes.asignacion_coordinador(identificador_inspector, fecha_asignacion, identificador_asignante, tipo_solicitud, id_solicitud,tipo_inspector)
											VALUES ('$identificadorInspector',now(), '$idCoordinador', '$tipoSolicitud', $idSolicitud,'$tipoInspector')
											RETURNING id_asignacion_coordinador;");
				return $res;
	}
	
	public function actualizarInspeccionFinancieroMontoRecaudado ($conexion, $idAsignacion){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_revision_solicitudes.financiero
											SET												
												monto_recaudado = null
											WHERE
												id_financiero = $idAsignacion;");
		return $res;
	}
	
}
<?php

class ControladorRevisionSolicitudes{
	////////////////////// EVALUACION DE PRODUCTOS IMPORTACION //////////////////////////
	/*OK*/
	public function enviarImportacion ($conexion, $idImportacion, $estado){
		$res = $conexion->ejecutarConsulta("update
												g_importaciones.importaciones
											set
												estado = '$estado'
											where
												id_importacion = $idImportacion;");
		return $res;
	}
	
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
	public function guardarDatosInspeccionDocumental ($conexion, $idAsignacion, $identificador, $observacion, $estado){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_revision_solicitudes.revision_documental(
												id_asignacion, identificador_inspector, fecha_inspeccion, observacion, estado)
											VALUES ($idAsignacion, '$identificador', now(), '$observacion', '$estado');");
		return $res;
	}

	/*OKOK*/
	public function guardarDatosInspeccionElementos ($conexion, $idAsignacion, $identificador, $idElemento, $archivo, $estado, $observacion){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO g_revision_solicitudes.inspeccion(
												id_asignacion, identificador_inspector, id_item_inspeccion, fecha_inspeccion, ruta_archivo,
												estado, observacion)
											VALUES ($idAsignacion, '$identificador', $idElemento, now(), '$archivo', '$estado', '$observacion');");
		return $res;
	}
	
	public function abrirProductosImportacion ($conexion, $idImportacion){
		$cid = $conexion->ejecutarConsulta("select 
												* 
											from 
												g_importaciones.importaciones_productos 
											where 
												id_importacion = $idImportacion;");
			
		while ($fila = pg_fetch_assoc($cid)){
			$res[] = array(idOperacion=>$fila['id_operacion'],idArea=>$fila['id_area'],estado=>$fila['estado'],
					observacion=>$fila['observacion']);
		}
			
		return $res;
	}
	
	public function evaluarImportacion ($conexion, $idImportacion, $estado, $observacion, $informeRequisitos=null){
		$res = $conexion->ejecutarConsulta("update
												g_importaciones.importaciones
											set
												estado= '$estado',
												observacion = '$observacion',
												informe_requisitos = '$informeRequisitos'
											where
												id_importacion = $idImportacion;");
		return $res;
	}
	
	////////////////////////////// ASIGNACION DE IMPORTACIONES A INSPECTORES PARA REVISION ///////////////////////////////////
	
	/*OK*/
	/*filtro de operaciones por provincia por asignar*/
	public function listarImportacionesRevisionProvincia ($conexion, $provinciaInspector){
	
		$res = $conexion->ejecutarConsulta("select
												distinct i.id_importacion,
												i.identificador_operador,
												i.estado, 
												i.tipo_certificado,
												i.pais_exportacion,
												o.razon_social, o.nombre_representante, o.apellido_representante
											from
												g_importaciones.importaciones i,
												g_operadores.operadores o,
												g_importaciones.importaciones_productos ip
											where
												i.id_importacion = ip.id_importacion and
												i.identificador_operador = o.identificador and
												o.provincia = '$provinciaInspector' and
												i.estado in ('enviado');");
		return $res;
	}
	
	/*OK*/
	public function listarImportacionesAsignadasInspector ($conexion, $provinciaInspector, $identificadorInspector){
	
		$res = $conexion->ejecutarConsulta("select
												distinct i.id_importacion,
												i.identificador_operador,
												i.estado, 
												i.tipo_certificado,
												i.pais_exportacion,
												o.razon_social, 
												o.nombre_representante, 
												o.apellido_representante
											from
												g_importaciones.importaciones i,
												g_operadores.operadores o,
												g_importaciones.importaciones_productos ip,
												g_importaciones.asignacion_inspector ai
											where
												i.id_importacion = ip.id_importacion and
												i.identificador_operador = o.identificador and
												o.provincia = '$provinciaInspector' and
												ai.identificador_inspector = '$identificadorInspector' and
												i.estado in ('asignado', 'inspeccion');");
				return $res;
	}
	
	
	////////////////////////////// REVISION DE IMPORTACIONES EN FINANCIERO ///////////////////////////////////
	
	/*filtro de importaciones por estado*/
	/*OK*/
	public function listarImportacionesRevisionFinanciero ($conexion, $estado='pago'){
	
		$res = $conexion->ejecutarConsulta("select
												distinct i.id_importacion,
												i.identificador_operador,
												i.estado, 
												i.tipo_certificado,
												i.pais_exportacion,
												o.razon_social, o.nombre_representante, o.apellido_representante
											from
												g_importaciones.importaciones i,
												g_operadores.operadores o,
												g_importaciones.importaciones_productos ip
											where
												i.id_importacion = ip.id_importacion and
												i.identificador_operador = o.identificador and
												i.estado in ('$estado');");
				return $res;
	}
	
	
	public function listarInspectoresFinancierosAsignados ($conexion,$idImportacion){
			
		$res = $conexion->ejecutarConsulta("select
												ii.*,
												fe.nombre,
												fe.apellido
											from
												g_importaciones.importaciones_financiero ii,
												g_uath.ficha_empleado fe
											where
												ii.id_importacion = $idImportacion and
												ii.identificador = fe.identificador;");
				return $res;
	}
	
	public function guardarNuevoInspectorFinanciero ($conexion,$idImportacion,$identificadorFinanciero, $idCoordinador){
			
		$res = $conexion->ejecutarConsulta("INSERT INTO g_importaciones.importaciones_financiero(
												id_importacion, identificador, fecha_asignacion, identificador_coordinador)
											VALUES ($idImportacion,'$identificadorFinanciero',now(), '$idCoordinador');");
		return $res;
	}
	
	/*OKOK*/
	public function guardarInspeccionFinanciero ($conexion, $idAsignacion, $identificador, $estado, $observacion, $transaccion){
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_revision_solicitudes.financiero 
											SET
												fecha_inspeccion = now(),
												estado = '$estado',
												observacion = '$observacion',
												numero_transaccion = '$transaccion'
											WHERE 
												id_asignacion = $idAsignacion and 
												identificador_inspector = '$identificador';");
		return $res;
	}
	
	/*OKOK*/
	public function asignarMontoSolicitud ($conexion, $idAsignacion, $idInspector, $monto){
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_revision_solicitudes.financiero (id_asignacion, identificador_inspector, monto, fecha_asignacion_monto)
											VALUES($idAsignacion, '$idInspector', $monto, now());");
		return $res;
	}
	
	/*OK*/
	////////// ASIGNAR A INSPECTOR ///////////////
	public function abrirImportacionInspeccion ($conexion, $idImportacion){
		$cid = $conexion->ejecutarConsulta("select
												i.*,
												o.*
											from
												g_importaciones.importaciones i,
												g_operadores.operadores o
											where
												i.identificador_operador = o.identificador and
												i.id_importacion = $idImportacion;");
	
				while ($fila = pg_fetch_assoc($cid)){
				$res[] = array(
				idImportacion=>$fila['id_importacion'],
				identificador=>$fila['identificador_operador'],
				tipoCertificado=>$fila['tipo_certificado'],
				estado=>$fila['estado'],
		
				nombreExportador=>$fila['nombre_exportador'],
				paisExportacion=>$fila['pais_exportacion'],
		
				razonSocial=>$fila['razon_social'],
				nombreRepresentante=>$fila['nombre_representante'],
				apellidoRepresentante=>$fila['apellido_representante']
			);
		}
	
		return $res;
	}
	
	/*OKOK*/ 
	//Se utiliza para asignacion de técnicos y financieros
	public function guardarNuevoInspector ($conexion,$idSolicitud,$identificadorInspector, $idCoordinador, $tipoSolicitud, $tipoInspector){
			
		$res = $conexion->ejecutarConsulta("INSERT INTO g_revision_solicitudes.asignacion_inspector(
				identificador_inspector, fecha_asignacion, identificador_asignante, tipo_solicitud, id_solicitud, tipo_inspector)
				VALUES ('$identificadorInspector',now(), '$idCoordinador', '$tipoSolicitud', $idSolicitud, '$tipoInspector')
				RETURNING id_asignacion;");
		return $res;
	}
	
	/*OKOK*/
	public function listarInspectoresAsignados ($conexion,$idSolicitud, $tipoSolicitud, $tipoInspector){
			
		$res = $conexion->ejecutarConsulta("select
												ii.*,
												fe.nombre,
												fe.apellido
											from
												g_revision_solicitudes.asignacion_inspector ii,
												g_uath.ficha_empleado fe
											where
												ii.id_solicitud = $idSolicitud and
												ii.tipo_solicitud= '$tipoSolicitud' and
												ii.tipo_inspector= '$tipoInspector' and
												ii.identificador_inspector = fe.identificador;");
		return $res;
	}
	
	/*OKOK*/
	//Revisa si el inspector está asignado a la solicitud para revision
	public function buscarInspectorAsignado ($conexion,$idSolicitud, $identificadorInspector, $tipoSolicitud, $tipoInspector){
		$res = $conexion->ejecutarConsulta("select
												ii.*,
												fe.nombre,
												fe.apellido
											from
												g_revision_solicitudes.asignacion_inspector ii,
												g_uath.ficha_empleado fe
											where
												ii.id_solicitud = $idSolicitud and
												ii.tipo_solicitud= '$tipoSolicitud' and
												ii.tipo_inspector= '$tipoInspector' and
												ii.identificador_inspector = '$identificadorInspector'and
												ii.identificador_inspector = fe.identificador;");
		
		return $res;
	}
	
	public function eliminarInspectorAsignado ($conexion,$idImportacion,$identificadorInspector, $tipoSolicitud, $tipoTecnico){
			
		$res = $conexion->ejecutarConsulta("delete from
												g_importaciones.asignacion_inspector
											where
												id_solicitud = $idImportacion and
												identificador_inspector = '$identificadorInspector' and
												tipo_solicitud = '$tipoSolicitud' and
												tipo_inspector = '$tipoTecnico';");
		return $res;
	}
	
	
	///// TRANSACCION BANCARIA /////
	public function obtenerMontoSolicitud ($conexion, $idSolicitud, $tipoSolicitud){
		$res = $conexion->ejecutarConsulta("select
												f.*
											from
												g_revision_solicitudes.asignacion_inspector ai,
												g_revision_solicitudes.financiero f
											where
												ai.id_solicitud = $idSolicitud and
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
}
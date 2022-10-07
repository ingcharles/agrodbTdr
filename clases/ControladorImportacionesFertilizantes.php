<?php

class ControladorImportacionesFertilizantes{
	
	public function listarImportacionesFertilizantesRevisionProvinciaRS($conexion, $estado){
		
		$consulta = "SELECT 
						distinct id_importacion_fertilizantes as id_solicitud,
						identificador as identificador_operador,
						estado,
						tipo_solicitud as tipo_certificado,
						nombre_pais_origen as pais,
						razon_social,
						id_importacion_fertilizantes as id_vue,
						fecha_creacion
					FROM 
						g_importaciones_fertilizantes.importaciones_fertilizantes 
					WHERE 
						estado = '$estado';";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function listarImportacionesFertilizantesAsignadasInspectorRS ($conexion, $estadoSolicitud, $identificadorInspector, $tipoSolicitud, $tipoInspector){
		
		$res = $conexion->ejecutarConsulta("SELECT
												distinct id_importacion_fertilizantes as id_solicitud,
												identificador as identificador_operador,
												i.estado,
												i.tipo_solicitud as tipo_certificado,
												nombre_pais_origen as pais,
												razon_social,
												id_importacion_fertilizantes as id_vue,
												fecha_creacion
											FROM
												g_importaciones_fertilizantes.importaciones_fertilizantes i,
												g_revision_solicitudes.asignacion_coordinador ac
											WHERE
												i.id_importacion_fertilizantes = ac.id_solicitud and
												ac.identificador_inspector = '$identificadorInspector' and
												ac.tipo_solicitud = '$tipoSolicitud' and
												ac.tipo_inspector = '$tipoInspector' and
												i.estado in ('$estadoSolicitud');");
		return $res;
	}
	
	public function abrirImportacionFertilizantes($conexion, $idSolicitud){
		
		$consulta = "SELECT
						*
					FROM
						g_importaciones_fertilizantes.importaciones_fertilizantes
					WHERE
						id_importacion_fertilizantes = '$idSolicitud';";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function abrirImportacionFertilizantesProductos($conexion, $idSolicitud){
		
		$consulta = "SELECT
						*
					FROM
						g_importaciones_fertilizantes.importaciones_fertilizantes_productos
					WHERE
						id_importacion_fertilizantes = '$idSolicitud'
						and estado = 'activo';";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function abrirDocumentosImportacionFertilizantes($conexion, $idSolicitud){
		
		$consulta = "SELECT
						*
					FROM
						g_importaciones_fertilizantes.documentos_adjuntos
					WHERE
						id_importacion_fertilizantes = '$idSolicitud'
						and estado in ('activo','temporal');";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function actualizarEstadoDocumentoAdjunto($conexion, $idSolicitud, $idDocumentoAdjunto){
		
		$consulta = "UPDATE
						g_importaciones_fertilizantes.documentos_adjuntos
					SET
						estado = 'activo'
					WHERE
						id_importacion_fertilizantes = $idSolicitud
						and id_documento_adjunto = $idDocumentoAdjunto;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function obtenerDocumentoAdjuntoPorNombre($conexion, $idSolicitud, $nombreDocumento){
		
		$consulta = "SELECT
						*
					FROM
						g_importaciones_fertilizantes.documentos_adjuntos
					WHERE
						id_importacion_fertilizantes = $idSolicitud
						and tipo_archivo = '$nombreDocumento'
						and estado = 'temporal';";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function cambiarEstadoImportacionFertilizantes ($conexion, $idImportacionFertilizantes, $estado, $identificador, $observacion = null){
		
		$fechaVigencia = '';
		if($estado == 'aprobado'){
			$fechaVigencia = ", fecha_fin = now() + interval '6' month";
		}
		
		$consulta = "UPDATE
						g_importaciones_fertilizantes.importaciones_fertilizantes
					SET
						estado = '$estado',
						observacion_tecnico = '$observacion',
						identificador_tecnico = '$identificador',
						fecha_inicio = now()
						".$fechaVigencia."
					where
						id_importacion_fertilizantes = $idImportacionFertilizantes;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function enviarImportacionFertilizantes ($conexion, $idImportacionFertilizantes, $estado){
		
		$consulta = "UPDATE
						g_importaciones_fertilizantes.importaciones_fertilizantes
					SET
						estado = '$estado'
					where
						id_importacion_fertilizantes = $idImportacionFertilizantes;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
}
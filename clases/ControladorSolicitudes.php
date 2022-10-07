<?php

class ControladorSolicitudes{


	
	public function listarSolicitudesPendientes ($conexion,$identificador){
		$res = $conexion->ejecutarConsulta("select 
												* 
											from 
												g_solicitudes.solicitudes s,
												g_solicitudes.revisores r
											where
												s.id_solicitud = r.id_solicitud and
												r.estado in ('Pendiente') and
												r.identificador = '".$identificador."'
											order by
												s.fecha_creacion;");
		return $res;
	}
	
	public function abrirSolicitud($conexion,$idSolicitud, $identificador){
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_solicitudes.solicitudes s,
												g_solicitudes.revisores r
											where
												s.id_solicitud = r.id_solicitud 
												and r.identificador = '".$identificador."'
												and r.id_solicitud = ".$idSolicitud.";");
		return $res;
	}
	
	public function listarRevisores ($conexion,$idSolicitud){
	
		/*$res = $conexion->ejecutarConsulta("select 	g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos,
		 r.estado,
				r.comentario
				from 	g_documentacion.documentos_generados dg,
				g_solicitudes.solicitudes s,
				g_solicitudes.revisores r,
				g_usuario.usuarios u,
				g_uath.ficha_empleado fe
				where 	dg.id_solicitud = s.id_solicitud and
				s.id_solicitud = r.id_solicitud and
				r.identificador = u.identificador and
				u.identificador = fe.identificador and
				dg.id_documento = '".$idDocumento."';");*/
	
		$res = $conexion->ejecutarConsulta("select 	r.identificador,
													g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos,
													r.estado,
													r.comentario,
													r.identificador_delegador
											from 	g_solicitudes.revisores r,
													g_uath.ficha_empleado fe
											where 	r.identificador = fe.identificador and
													r.id_solicitud = '".$idSolicitud."';");
		return $res;
	}
	
	public function actualizarEstadoRevisores ($conexion,$idSolicitud){
		$res = $conexion->ejecutarConsulta("update 	g_solicitudes.solicitudes
											set     fecha_envio = now()
											where 	id_solicitud = ".$idSolicitud.";");
		
		$res = $conexion->ejecutarConsulta("update 	g_solicitudes.revisores
											set     estado = 'Sin_notificar',
													situacion = null
											where 	identificador_delegador is not null and
													id_solicitud = ".$idSolicitud.";");
		
		$res = $conexion->ejecutarConsulta("update 	g_solicitudes.revisores
											set     estado = 'Pendiente',
													situacion = 'FALSE'
											where 	identificador_delegador is null and
													id_solicitud = ".$idSolicitud.";");
		return $res;
	}
	
	public function actualizarEstadoSolicitud ($conexion,$idSolicitud,$condicion){
		
		$actualizar = '';
		switch ($condicion){
			case 'ENVIADO': $actualizar = "condicion = 'enviado'"; break;
			case 'REENVIADO': $actualizar = "condicion = 'reenviado'"; break;
			case 'ATENDIDO': $actualizar = "condicion = 'atendido'"; break;
			case 'ASIGNARRESPONSABLE': $actualizar = "condicion = 'asignarResponsable'"; break;
			case 'REVISIONRESPONSABLE': $actualizar = "condicion = 'revisionResponsable'"; break;
			case 'ATENDIDORESPONSABLE': $actualizar = "condicion = 'atentidoResponsable'"; break;
			case 'APROBADO': $actualizar = "condicion = 'aprobado'"; break;
			case 'ARCHIVADO': $actualizar = "condicion = 'archivado'"; break;
			case 'FINALIZADO': $actualizar = "condicion = 'finalizado'"; break;
			
		}
		
		
		$res = $conexion->ejecutarConsulta("update 	g_solicitudes.solicitudes
											set     ".$actualizar."
											where 	id_solicitud = ".$idSolicitud.";");
	
		return $res;
	}
	
	public function condicionSolicitud ($conexion,$idSolicitud){

		$res = $conexion->ejecutarConsulta("SELECT 	
													*
											FROM
													g_solicitudes.solicitudes
											WHERE 	
													id_solicitud = ".$idSolicitud.";");
	
		return $res;
	}
	
	
	public function actualizarEstadoAprobador ($conexion,$idSolicitud, $identificador){
		
		$res = $conexion->ejecutarConsulta("update 	g_solicitudes.solicitudes
											set     fecha_envio = now()
											where 	id_solicitud = ".$idSolicitud.";");
		//CAMBIO
		$res = $conexion->ejecutarConsulta("update 	
													g_solicitudes.revisores
											set     
													estado = 'Sin_notificar',
													situacion = null
											where 	
													identificador_delegador = '$identificador'
													and id_solicitud = '$idSolicitud';");
	
		$res = $conexion->ejecutarConsulta("update 	g_solicitudes.revisores
											set     estado = 'Pendiente',
													situacion = 'FALSE'
											where 	accion='Aprobador' and
													id_solicitud = ".$idSolicitud.";");
		return $res;
	}
	
	
	public function solicitudesTotales ($conexion,$idSolicitud){
	
		$res = $conexion->ejecutarConsulta("select 	count(estado) total
											from	g_solicitudes.revisores
											where 	id_solicitud = ".$idSolicitud." and accion = 'Revisor';");
		return $res;
	}
	
	public function solicitudesAprobadas ($conexion,$idSolicitud){
	
		$res = $conexion->ejecutarConsulta("select 	count(estado) aprobadas
											from	g_solicitudes.revisores
											where 	id_solicitud = ".$idSolicitud." and estado = 'Aprobado' and accion = 'Revisor';");
		return $res;
	}
	
	public function solicitudesSinNotificar ($conexion,$idSolicitud){
	
		$res = $conexion->ejecutarConsulta("select 	count(estado) sin_notificar
											from	g_solicitudes.revisores
											where 	id_solicitud = ".$idSolicitud." and estado = 'Sin_notificar' and accion = 'Revisor';");
		return $res;
	}
	
	public function solicitudesDelegadas ($conexion,$idSolicitud){
	
		$res = $conexion->ejecutarConsulta("select 	count(estado) delegadas
											from	g_solicitudes.revisores
											where 	id_solicitud = ".$idSolicitud." and estado = 'Delegado' and accion = 'Revisor';");
		return $res;
	}
	
	public function solicitudesRechazadas ($conexion,$idSolicitud){
	
		$res = $conexion->ejecutarConsulta("select 	count(estado) rechazadas
											from	g_solicitudes.revisores
											where 	id_solicitud = ".$idSolicitud." and estado = 'Rechazado' and accion = 'Revisor';");
		return $res;
	}
	
	public function solicitudesPendientes ($conexion,$idSolicitud){
	
		$res = $conexion->ejecutarConsulta("select 	count(estado) pendientes
											from	g_solicitudes.revisores
											where 	id_solicitud = ".$idSolicitud." and estado = 'Pendiente' and accion = 'Revisor';");
		return $res;
	}
	
	public function estadoAprobador ($conexion,$idSolicitud){
	
		$res = $conexion->ejecutarConsulta("select 	*
											from	g_solicitudes.revisores
											where 	id_solicitud = ".$idSolicitud." and accion = 'Aprobador';");
		return $res;
	}
	
	
	/*public function buscarAprobador ($conexion,$idSolicitud){
	
		$res = $conexion->ejecutarConsulta("select 	*
											from	g_solicitudes.revisores
											where 	id_solicitud = ".$idSolicitud." and accion = 'Aprobador';");
		return $res;
	}*/
	
	public function buscarUsuarioSolicitud ($conexion,$idSolicitud,$identificador){
	
		$res = $conexion->ejecutarConsulta("select 	
													*
											from	
													g_solicitudes.revisores
											where 	
													id_solicitud = '$idSolicitud' 
													and identificador = '$identificador';");
		return $res;
	}
	
	public function cambioEstadoRevisor ($conexion,$idSolicitud,$idUsuario,$comentario,$estado){
		
	
		$res = $conexion->ejecutarConsulta("update 	g_solicitudes.revisores
											set     estado = '".$estado."',
													comentario = '".$comentario."'
											where 	estado = 'Pendiente'
													and identificador = '".$idUsuario."'
													and id_solicitud = '".$idSolicitud."';");
		return $res;
	}
	
	public function solicitarRevision ($conexion,$idSolicitud,$delegador,$delegado,$estado){
	
		$res = $conexion->ejecutarConsulta("Insert into g_solicitudes.revisores 
														(id_solicitud, identificador, estado, situacion, identificador_delegador,accion)
											values     (".$idSolicitud.",'".$delegado."','Pendiente','FALSE','".$delegador."','Revisor')");
		
		return $res;
	}
	
	public function actualizarNotificacion ($conexion,$idSolicitud,$idUsuario){
	
		$res = $conexion->ejecutarConsulta("update 	g_solicitudes.revisores
											set    	situacion = 'TRUE'
											where 	identificador = '".$idUsuario."'
													and id_solicitud = ".$idSolicitud.";");
		
		return $res;
	}
	
	public function actualizarEstadoReasignado ($conexion,$idSolicitud,$identificador){
	
		$res = $conexion->ejecutarConsulta("update 	
												g_solicitudes.revisores
											set     
												estado = 'Pendiente',
												situacion = 'FALSE'
											where 	
												identificador='$identificador' and
												id_solicitud = '$idSolicitud';");
				return $res;
	}
	
	
	public function actualizarFehaAprobacionSolicitud ($conexion,$idSolicitud){
		$res = $conexion->ejecutarConsulta("update 	g_solicitudes.solicitudes
											set     fecha_aprobacion = now()
											where 	id_solicitud = ".$idSolicitud.";");
		return $res;
	}
	
	public function actualizarObservacionSolicitud ($conexion,$idSolicitud,$observacion){
	
		$res = $conexion->ejecutarConsulta("update
												g_solicitudes.solicitudes
											set
												observacion = '$observacion'
											where
												id_solicitud = $idSolicitud;");
				return $res;
	}
	
	
	
}

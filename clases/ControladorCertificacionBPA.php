<?php

class ControladorCertificacionBPA
{
    public function sumaDiaSemana($fecha,$dias)
    {
        $datestart= strtotime($fecha);
        $datesuma = 15 * 86400;
        $diasemana = date('N',$datestart);
        $totaldias = $diasemana+$dias;
        $findesemana = intval( $totaldias/5) *2 ;
        $diasabado = $totaldias % 5 ;
        if ($diasabado==6) $findesemana++;
        if ($diasabado==0) $findesemana=$findesemana-2;
        
        $total = (($dias+$findesemana) * 86400)+$datestart ;
        return $fechafinal = date('Y-m-d', $total);
    }
    
	/**/
	public function obtenerSolicitudPorEstadoProvincia ($conexion, $estado, $provincia){
		
		$consulta = "SELECT
						id_solicitud, 
						fecha_creacion as fecha_registro, 
						id_solicitud as numero_solicitud,
						identificador_operador
					 FROM
						g_certificacion_bpa.solicitudes
					 WHERE
						estado = '$estado'
						and provincia_revision = '$provincia';";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function obtenerOperadoresCertificacionBPA ($conexion, $estado, $provincia, $asociacion){
		
		$condicion = '';
		if($asociacion == 'Si'){
			$condicion = " and provincia_revision is null ";
		}
		
		$provincia = $provincia != "" ? "'" . $provincia . "'" : "NULL";
		$asociacion = $asociacion != "" ? "'" . $asociacion . "'" : "NULL";
		
		$consulta = "SELECT 
						distinct identificador_operador as identificador, razon_social as nombre_operador
					 FROM 
						g_certificacion_bpa.solicitudes
					 WHERE 
						estado = '$estado'
						".$condicion."
						and ($provincia is NULL or provincia_revision = $provincia)
						and ($asociacion is NULL or es_asociacion = $asociacion)";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function obtenerSitiosOperadorCertificacionBPA ($conexion, $estado, $identificadorOperador, $provincia, $asociacion){
		
		$condicion = '';
		if($asociacion == 'Si'){
			$condicion = " and provincia_revision is null ";
		}
		
		$provincia = $provincia != "" ? "'" . $provincia . "'" : "NULL";
		$asociacion = $asociacion != "" ? "'" . $asociacion . "'" : "NULL";
		
		$consulta = "SELECT
						distinct id_sitio_unidad_produccion as id_sitio, sitio_unidad_produccion as nombre_lugar
					 FROM
						g_certificacion_bpa.solicitudes
					 WHERE
						estado = '$estado'
						".$condicion."
						and identificador_operador = '$identificadorOperador'
						and ($provincia is NULL or provincia_revision = $provincia)
						and ($asociacion is NULL or es_asociacion = $asociacion)";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function obtenerSolicitudesOperadorCertificacionBPA ($conexion, $estado, $identificadorOperador, $provincia, $asociacion){
		
		$condicion = '';
		if($asociacion == 'Si'){
			$condicion = " and provincia_revision is null ";
		}
		
		$provincia = $provincia != "" ? "'" . $provincia . "'" : "NULL";
		$asociacion = $asociacion != "" ? "'" . $asociacion . "'" : "NULL";
		
		$consulta = "SELECT
						distinct id_sitio_unidad_produccion as id_sitio, sitio_unidad_produccion as nombre_lugar, id_solicitud, fecha_creacion
					 FROM
						g_certificacion_bpa.solicitudes
					 WHERE
						estado = '$estado'
						".$condicion."
						and identificador_operador = '$identificadorOperador'
						and ($provincia is NULL or provincia_revision = $provincia)
						and ($asociacion is NULL or es_asociacion = $asociacion)";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	/**/
	
    public function obtenerDatosAsociacion ($conexion, $identificador){
        
        $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_bpa.asociaciones a
											WHERE
												a.identificador =  '$identificador';");
        return $res;
    }
    
    public function abrirSolicitud ($conexion, $idSolicitud){
        
        $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificacion_bpa.solicitudes s
											WHERE
												s.id_solicitud =  $idSolicitud;");
        return $res;
    }
    
    public function obtenerDetalleSitiosAreasProductos ($conexion, $idSolicitud){
    
        $res = $conexion->ejecutarConsulta("SELECT
    												sap.*, s.provincia as nombre_provincia, o.razon_social
    											FROM
    												g_certificacion_bpa.sitios_areas_productos sap
                                                    INNER JOIN g_operadores.sitios s  ON sap.id_sitio = s.id_sitio
                                                    INNER JOIN g_operadores.operadores o ON o.identificador = sap.identificador_sitio
    											WHERE
    												sap.id_solicitud =  $idSolicitud;");
        
        return $res;
    }
    
    public function obtenerDetalleAuditoriasSolicitadas ($conexion, $idSolicitud){
        
        $res = $conexion->ejecutarConsulta("SELECT
    												a.*,
                                                    ta.fase
    											FROM
    												g_certificacion_bpa.auditorias_solicitadas a
                                                    INNER JOIN g_certificacion_bpa.tipos_auditorias ta ON a.id_tipo_auditoria = ta.id_tipo_auditoria
    											WHERE
    												a.id_solicitud =  $idSolicitud and
                                                    a.estado = 'Activo';");
        
        return $res;
    }
    
    public function buscarAuditoriasSolicitadas ($conexion, $idSolicitud, $nombre){
        
        $res = $conexion->ejecutarConsulta("SELECT
                                            	a.*
                                            FROM
                                            	g_certificacion_bpa.auditorias_solicitadas a
                                            WHERE
                                            	a.id_solicitud =  $idSolicitud and
                                            	a.id_tipo_auditoria in (
                                            		SELECT
                                            			id_tipo_auditoria
                                            		FROM
                                            			g_certificacion_bpa.tipos_auditorias
                                            		WHERE
                                                    	tipo_auditoria like '%$nombre%' and
                                                    	estado = 'Activo') and
                                            	a.estado = 'Activo';");
        
        return $res;
    }
    
    public function buscarAuditoriasSolicitadasXFase ($conexion, $idSolicitud, $faseAuditoria){
        
        $res = $conexion->ejecutarConsulta("SELECT
                                            	a.*
                                            FROM
                                            	g_certificacion_bpa.auditorias_solicitadas a
                                            WHERE
                                            	a.id_solicitud =  $idSolicitud and
                                            	a.id_tipo_auditoria in (
                                            		SELECT
                                            			id_tipo_auditoria
                                            		FROM
                                            			g_certificacion_bpa.tipos_auditorias
                                            		WHERE
                                                    	fase = '$faseAuditoria' and
                                                    	estado = 'Activo') and
                                            	a.estado = 'Activo';");
        
        return $res;
    }
    
	/**/
	public function actualizarProvinciaSolicitud($conexion, $idSolicitud, $provincia){
		
		$res = $conexion->ejecutarConsulta("UPDATE
												g_certificacion_bpa.solicitudes
											SET
												provincia_revision = '$provincia'
											WHERE
												id_solicitud = $idSolicitud;");
		
		return $res;
		
	}
	/**/
    
    //Revisión Formularios
    public function actualizarEstadoSolicitud($conexion, $idSolicitud, $estado){
        
        $res = $conexion->ejecutarConsulta("UPDATE
												g_certificacion_bpa.solicitudes
											SET
												estado = '$estado'
											WHERE
												id_solicitud = $idSolicitud;");
        
        return $res;
        
    }
    
    public function actualizarObservacionRevision($conexion, $idSolicitud, $observacion){
        
        $res = $conexion->ejecutarConsulta("UPDATE
												g_certificacion_bpa.solicitudes
											SET
												observacion_revision = '$observacion'
											WHERE
												id_solicitud = $idSolicitud;");
        
        return $res;
        
    }
    
    public function actualizarDatosRevision($conexion, $idSolicitud, $tipoInspector){
        
        $res = $conexion->ejecutarConsulta("UPDATE
												g_certificacion_bpa.solicitudes
											SET
												tipo_revision = '$tipoInspector',
                                                fecha_revision = now()
											WHERE
												id_solicitud = $idSolicitud;");
        
        return $res;
        
    }
    
    public function actualizarFechaMaxRespuesta($conexion, $idSolicitud, $fechaMaxRespuesta){
        
        $res = $conexion->ejecutarConsulta("UPDATE
												g_certificacion_bpa.solicitudes
											SET
												fecha_max_respuesta = '$fechaMaxRespuesta'
											WHERE
												id_solicitud = $idSolicitud;");
        
        return $res;
        
    }
    
    public function actualizarFechaAuditoriaSolicitud($conexion, $idSolicitud, $fechaAuditoria){
        
        $res = $conexion->ejecutarConsulta("UPDATE
												g_certificacion_bpa.solicitudes
											SET
												fecha_auditoria_programada = '$fechaAuditoria'
											WHERE
												id_solicitud = $idSolicitud;");
        
        return $res;
        
    }
    
    public function actualizarFechaAuditoriaComplementariaSolicitud($conexion, $idSolicitud, $fechaAuditoria){
        
        $res = $conexion->ejecutarConsulta("UPDATE
												g_certificacion_bpa.solicitudes
											SET
												fecha_auditoria_complementaria = '$fechaAuditoria'
											WHERE
												id_solicitud = $idSolicitud;");
        
        return $res;
        
    }
    
    public function actualizarFechaAuditoriaEjecutadaSolicitud($conexion, $idSolicitud, $fechaAuditoria){
        
        $res = $conexion->ejecutarConsulta("UPDATE
												g_certificacion_bpa.solicitudes
											SET
												fecha_auditoria = '$fechaAuditoria'
											WHERE
												id_solicitud = $idSolicitud;");
        
        return $res;
        
    }
    
    public function actualizarResolucionSolicitud($conexion, $idSolicitud, $idResolucion){
        
        $res = $conexion->ejecutarConsulta("UPDATE
												g_certificacion_bpa.solicitudes
											SET
												id_resolucion = '$idResolucion'
											WHERE
												id_solicitud = $idSolicitud;");
        
        return $res;
        
    }
    
    public function actualizarPorcentajeAuditoria($conexion, $idSolicitud, $porcentaje){
        
        $res = $conexion->ejecutarConsulta("UPDATE
												g_certificacion_bpa.solicitudes
											SET
												porcentaje_auditoria = $porcentaje
											WHERE
												id_solicitud = $idSolicitud;");
        
        return $res;
        
    }
    
    public function actualizarRutaFormatoPlan($conexion, $idSolicitud, $rutaArchivo){
        
        $res = $conexion->ejecutarConsulta("UPDATE
												g_certificacion_bpa.solicitudes
											SET
												ruta_formato_plan_accion = '$rutaArchivo'
											WHERE
												id_solicitud = $idSolicitud;");
        
        return $res;
        
    }
    
    public function actualizarRutaChecklist($conexion, $idSolicitud, $rutaArchivo){
        
        $res = $conexion->ejecutarConsulta("UPDATE
												g_certificacion_bpa.solicitudes
											SET
												ruta_checklist = '$rutaArchivo'
											WHERE
												id_solicitud = $idSolicitud;");
        
        return $res;
        
    }
    
    public function generarFechasVigencia($conexion,$idSolicitud, $tipoSolicitud, $fechaAuditoria){
        
        if($tipoSolicitud == 'Nacional'){
            //$fechaInicio = date('Y-m-d');
            $fechaFin = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date($fechaAuditoria))) . " + 3 years"));
            
            $res = $conexion->ejecutarConsulta("UPDATE
    												g_certificacion_bpa.solicitudes
    											SET
    												fecha_inicio_vigencia = '$fechaAuditoria',
                                                    fecha_fin_vigencia = '$fechaFin'
    											WHERE
    												id_solicitud = $idSolicitud;");
        
        }else{
            $res = $conexion->ejecutarConsulta("UPDATE
    												g_certificacion_bpa.solicitudes
    											SET
    												fecha_inicio_vigencia = fecha_inicio_equivalente,
                                                    fecha_fin_vigencia = fecha_fin_equivalente
    											WHERE
    												id_solicitud = $idSolicitud;");
        }
        
        return $res;
    }
    
    public function generarNumeroCertificado($conexion, $formato)
    {
        
        $res = $conexion->ejecutarConsulta(" SELECT
                                                max(numero_secuencial) as numero
                                             FROM
                                                g_certificacion_bpa.solicitudes
                                             WHERE
                                                numero_certificado like '%".$formato."%';");
        
        $codigo = pg_fetch_result($res, 0, 'numero');
        
        $incremento = $codigo + 1;
        $secuencial = str_pad($incremento, 5, "0", STR_PAD_LEFT);
        
        return $secuencial;
    }
    
    public function actualizarSecuencialCertificado($conexion, $idSolicitud, $secuencial, $certificado){
        
        $res = $conexion->ejecutarConsulta("UPDATE
												g_certificacion_bpa.solicitudes
											SET
												numero_certificado = '$certificado',
                                                numero_secuencial = '$secuencial'
											WHERE
												id_solicitud = $idSolicitud;");
        
        return $res;
        
    }
    
    public function guardarRutaCertificado($conexion, $idSolicitud, $certificado){
        
        $res = $conexion->ejecutarConsulta("UPDATE
												g_certificacion_bpa.solicitudes
											SET
												ruta_certificado = '$certificado'
											WHERE
												id_solicitud = $idSolicitud;");
        
        return $res;
        
    }
    
    public function actualizarPagoSolicitud($conexion, $idSolicitud, $estadoPago){
        
        $res = $conexion->ejecutarConsulta("UPDATE
												g_certificacion_bpa.solicitudes
											SET
												paso_pago = '$estadoPago'
											WHERE
												id_solicitud = $idSolicitud;");
        
        return $res;
        
    }
    
    public function obtenerCertificadoBPA($conexion,$idSolicitud){
        
        $res=$conexion->ejecutarConsulta("SELECT
						ruta_certificado
					FROM
						g_certificacion_bpa.solicitudes
					WHERE
						id_solicitud='$idSolicitud';");
        
        return $res;
    }
    
    public function actualizarEstadoSitiosSolicitud($conexion, $idSolicitud, $estado){
        
        $res = $conexion->ejecutarConsulta("UPDATE
												g_certificacion_bpa.sitios_areas_productos
											SET
												estado = '$estado'
											WHERE
												id_solicitud = $idSolicitud;");
        
        return $res;
        
    }
    
    
    
    public function listarSolicitudesRevisionRS ($conexion, $estado){
        
        $res = $conexion->ejecutarConsulta("SELECT
                                            	distinct id_solicitud,
                                            	identificador as identificador_operador,
                                            	estado,
                                            	tipo_solicitud as tipo_certificado,
                                            	es_asociacion,
                                            	tipo_explotacion,
                                            	provincia_unidad_produccion,
                                            	fecha_creacion
                                            FROM
                                            	g_certificacion_bpa.solicitudes
                                            WHERE
                                            	estado = '$estado'
                                            ORDER BY
                                            	id_solicitud ASC;");
        return $res;
    }
    
    public function listarSolicitudesAsignadasInspectorRS ($conexion, $estado, $identificadorInspector, $tipoSolicitud, $tipoInspector, $tipoSolicitudBPA){
        
        $res = $conexion->ejecutarConsulta("SELECT
                                            	distinct s.id_solicitud,
                                            	s.identificador as identificador_operador,
                                            	s.estado,
                                            	s.tipo_solicitud as tipo_certificado,
                                            	s.es_asociacion,
                                            	s.tipo_explotacion,
                                            	s.provincia_unidad_produccion,
                                            	s.fecha_creacion
                                            FROM
                                            	g_certificacion_bpa.solicitudes s,
                                            	g_revision_solicitudes.asignacion_coordinador ac
											WHERE
												s.id_solicitud = ac.id_solicitud and
												ac.identificador_inspector = '$identificadorInspector' and
												ac.tipo_solicitud = '$tipoSolicitud' and
												ac.tipo_inspector = '$tipoInspector' and
												s.estado = '$estado' and
												s.tipo_solicitud in ($tipoSolicitudBPA);");
        return $res;
    }
    
    
    public function listaSolicitudes($conexion, $tipo, $identificacion, $numeroSolicitud, $fecha)
    {
        $identificacion = $identificacion != "" ? "'" . $identificacion . "'" : "NULL";
        $numeroSolicitud = $numeroSolicitud != "" ? "'" . $numeroSolicitud . "'" : "NULL";
        $fecha = $fecha != "" ? "'" . $fecha . "'" : "NULL";

        if (($identificacion == "NULL") && ($numeroSolicitud == "NULL") && ($fecha == "NULL")) {
            $busqueda = "and fecha_solicitud >= current_date and fecha_solicitud < current_date+1";
        }

        $consulta = " SELECT 
						  id_solicitud,
						  nombre_propietario as solicitante,
						  case when estado ='enviado' then 'Revisión Documental' when estado ='subsanacion' then 'Subsanar' 
						  when estado ='rechazado' then 'Rechazado' when estado ='pago' then 'Asignación de Tasa'
						  when estado ='inspeccion' then 'Inspección'  when estado ='aprobado' then 'Aprobado'
						  when estado ='verificacion' then 'Por pagar' when estado ='asignadoDocumental' then 'Revisión documental asignada'
						  when estado ='asignadoInspeccion' then 'Asignado a Inspector'
						  end estado, pais_origen_destino

					  FROM
						  g_mercancias_valor_comercial.solicitudes
					WHERE 
						  ($identificacion is NULL or ((case when identificador_propietario = '' then identificador_operador else identificador_propietario end) = $identificacion))
						  and ($numeroSolicitud is NULL or id_solicitud = $numeroSolicitud)
						  and ($fecha is NULL or to_char(fecha_solicitud,'dd/mm/yyyy') = $fecha)
						  $busqueda
						  and tipo_solicitud='$tipo'
						  ORDER BY 1;
					";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }
    
    public function obtenerCantidadSitiosCertificado ($conexion, $idSolicitud){
        
        $consulta = "SELECT 
                        count(distinct id_sitio) num_sitios
                      FROM 
                        g_certificacion_bpa.sitios_areas_productos
                      WHERE
                        id_solicitud=$idSolicitud;";
        
        $res = $conexion->ejecutarConsulta($consulta);
        
        return $res;
    }
    
    public function obtenerDirectorProvincial($conexion, $nombreProvincia){
        
        $consulta = "select
                    	f.identificador,
                    	fe.nombre,
                    	fe.apellido, 
                        fe.nombre ||' '|| fe.apellido as nombre_director,
                        ar.nombre as nombre_area,
                        ar.id_area
                    from
                    	g_estructura.responsables f,
                    	g_uath.ficha_empleado fe,
                        g_estructura.area ar
                    where
                        ar.id_area = f.id_area and
                    	f.estado = 1 and
                    	f.responsable is true and
                    	activo = 1 and
                    	f.identificador = fe.identificador and
                    	f.id_area = (select
                    					a.id_area
                    				from
                    					g_estructura.area a
                    				where
                    					nombre like '%$nombreProvincia%' and 
                    					estado =1 and
                    					categoria_area=3)";
        
        $res = $conexion->ejecutarConsulta($consulta);
        
        return $res;
    }
}
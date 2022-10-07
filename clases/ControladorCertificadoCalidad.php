<?php

class ControladorCertificadoCalidad {

   public function listarSolicitudesDisponibles($conexion, $identificador) {
        $res = $conexion->ejecutarConsulta("SELECT
                                                *
											FROM 
												g_certificados.certificado_calidad
											WHERE 
												identificador_exportador = '$identificador';");
        return $res;
    }
    
  public function guardarCertificadoCalidad($conexion, $idCertificado, $identificadorExportador, $nombreExportador, $nombreImportador, $direccionImportador, 
    									$fechaEmbarque, $numeroTransporte, $idPuertoEmbarque, $nombrePuertoEmbarque, $nombreMedioTransporte, 
    									$idPaisDestino, $nombrePaisDestino, $idPuertoDestino, $nombrePuertoDestino, $idPaisEmbarque, $nombrePaisEmbarque) {
    	    	
    	
    	$res = $conexion->ejecutarConsulta("INSERT INTO g_certificados.certificado_calidad(
											            id_certificado, identificador_exportador, 
											            razon_social_exportador, nombre_importador, direccion_importador, 
											            fecha_embarque, numero_viaje, id_puerto_embarque, 
											            nombre_puerto_embarque, nombre_medio_transporte, id_pais_destino, 
											            nombre_pais_destino, id_puerto_destino, nombre_puerto_destino, 
											            id_pais_embarque, nombre_pais_embarque)
											    VALUES ($idCertificado, '$identificadorExportador', '$nombreExportador', '$nombreImportador', '$direccionImportador', 
											            '$fechaEmbarque', '$numeroTransporte', $idPuertoEmbarque,'$nombrePuertoEmbarque','$nombreMedioTransporte', 
    													$idPaisDestino, '$nombrePaisDestino', $idPuertoDestino, '$nombrePuertoDestino', $idPaisEmbarque, 
    													'$nombrePaisEmbarque') returning id_certificado_calidad;");
    	
    	return $res;
    	
    }
    
    public function  guardarLugarInspeccion($conexion, $idCertificadoCalidad, $fechaInspeccion, $idProvincia, $nombreProvincia, $idArea, $nombreArea){
    	
    	$res = $conexion->ejecutarConsulta("INSERT INTO g_certificados.lugar_inspeccion(
										            id_certificado_calidad, solicitud_fecha_inspeccion, 
										            id_provincia, nombre_provincia, id_area_operacion, nombre_area_operacion)
										    VALUES ($idCertificadoCalidad, '$fechaInspeccion',$idProvincia, '$nombreProvincia', $idArea, '$nombreArea') returning id_lugar_inspeccion;");
    	
    	return $res;
    	
    }
    
    public function  guardarLotesInspeccion($conexion, $idLugarInspeccion, $numeroLote, $idProducto, $nombreProducto, $pesoBruto, $unidadPesoBruto, 
    										$pesoNeto, $unidadPesoNeto, $idVariedad, $nombreVariedad, $idCalidad, $nombreCalidad, $valorFob, $estado){
    	 
    	$res = $conexion->ejecutarConsulta("INSERT INTO g_certificados.lotes_inspeccion(
										            id_lugar_inspeccion, numero_lote, id_producto, 
										            nombre_producto, peso_bruto, unidad_peso_bruto, peso_neto, unidad_peso_neto, 
										            id_variedad_producto, nombre_variedad_producto, id_calidad_producto, 
										            nombre_calidad_producto, valor_fob, estado)
										    VALUES ($idLugarInspeccion, '$numeroLote' , $idProducto, '$nombreProducto', $pesoBruto, '$unidadPesoBruto', $pesoNeto, '$unidadPesoNeto', 
										            $idVariedad, '$nombreVariedad', $idCalidad,'$nombreCalidad', $valorFob, '$estado');");
    	 
    	return $res;
    	 
    }
    
    
    
   public function buscarTipoCertificado($conexion,$codigoCertificado, $idArea) {
    	$res = $conexion->ejecutarConsulta("SELECT
								    			*
								    		FROM
								    			g_certificados.certificados
								    		WHERE
								    			codigo = '$codigoCertificado' and
    											id_area = '$idArea';");
    	return $res;
    }
    
    public function listarCertificadoCalidadImposicionTasa($conexion, $nombreProvincia, $estado='pago'){
        	
    	$res = $conexion->ejecutarConsulta("select
												cc.id_certificado_calidad as id_solicitud,
												cc.identificador_exportador as identificador_operador,
												cc.estado,
												'Certificado calidad' as tipo_certificado,
												cc.nombre_pais_destino as pais,
												cc.razon_social_exportador razon_social
											from
												g_certificados.certificado_calidad cc,
												g_certificados.lugar_inspeccion li
											where
												cc.id_certificado_calidad = li.id_certificado_calidad and
												UPPER(li.nombre_provincia) = UPPER('$nombreProvincia') and
												cc.estado in ('$estado')
											order by 1 asc;");
    			return $res;
    }
    
    public function obtenerSolicitudesCertificadoCalidad($conexion, $provincia, $estado, $tipo, $identificador = null){
    		
    	$columnas = '';
    	$busqueda = '';
    
    	switch ($tipo){
    		case 'OPERADORES':  if($estado == 'inspeccion'){
    								$busqueda = "and loi.estado_inspector is null GROUP BY cc.identificador_exportador, cc.razon_social_exportador ORDER BY MIN(cc.fecha_solicitud) asc ";
    							}else{
    								$busqueda = "GROUP BY cc.identificador_exportador, cc.razon_social_exportador ORDER BY MIN(cc.fecha_solicitud) asc";
    							}
    							$columnas = "distinct cc.identificador_exportador as identificador, cc.razon_social_exportador as nombre_operador, MIN(cc.fecha_solicitud)"; 
    		break;
    		
    		case 'CERTIFICADOCALIDAD': if($estado == 'inspeccion'){
    										$busqueda = "and cc.identificador_exportador= '$identificador' and loi.estado_inspector is null";
    									}else{
    										$busqueda = "and cc.identificador_exportador= '$identificador'";
    									} 
    							 
    							$columnas = "distinct lui.id_area_operacion, lui.nombre_area_operacion, loi.id_lote_inspeccion,loi.numero_lote, loi.id_producto, loi.nombre_producto, cc.fecha_solicitud, lui.id_certificado_calidad"; 
    		break;
    		
    		case 'SITIOS':  if($estado == 'inspeccion'){
    							$busqueda = "and cc.identificador_exportador= '$identificador' and loi.estado_inspector is null";
    						}else{
    							$busqueda = "and cc.identificador_exportador= '$identificador'";
    						}   
    						 						
    						$columnas = "distinct lui.id_area_operacion, lui.nombre_area_operacion"; 
    		break;
    	}
								    			        	    
    	$res = $conexion->ejecutarConsulta("SELECT
												" . $columnas ."
							    			FROM
								    			g_certificados.certificado_calidad cc,
												g_certificados.lugar_inspeccion lui,
												g_certificados.lotes_inspeccion loi
											WHERE
								    			loi.id_lugar_inspeccion = lui.id_lugar_inspeccion and
												lui.id_certificado_calidad = cc.id_certificado_calidad and
												loi.estado IN ($estado)
								    			".$busqueda.";");
    	return $res;
    }
    
    public function obtenerSolicitudesCertificadoCalidadRS($conexion, $estado, $identificadorInspector, $tipoSolicitud, $tipoInspector, $tipo){
    
    	$columnas = '';
    	$busqueda = '';
    
    	switch ($tipo){
    		case 'OPERADORES': $busqueda = "and loi.estado_inspector is null GROUP BY cc.identificador_exportador, cc.razon_social_exportador ORDER BY MIN(cc.fecha_solicitud) asc ";     		
    						   $columnas = "distinct cc.identificador_exportador as identificador, cc.razon_social_exportador as nombre_operador, MIN(cc.fecha_solicitud)";
    		break;
    
    		case 'CERTIFICADOCALIDAD': $busqueda = "and cc.identificador_exportador= '$identificador' and loi.estado_inspector is null";
    								   $columnas = "distinct lui.id_area_operacion, lui.nombre_area_operacion, loi.id_lote_inspeccion,loi.numero_lote, loi.id_producto, loi.nombre_producto, cc.fecha_solicitud, lui.id_certificado_calidad";
    		break;
    
    		case 'SITIOS': $busqueda = "and cc.identificador_exportador= '$identificador' and loi.estado_inspector is null";
    				       $columnas = "distinct lui.id_area_operacion, lui.nombre_area_operacion";
    		break;
    	}
    
    	$res = $conexion->ejecutarConsulta("SELECT
												" . $columnas ."
							    			FROM
								    			g_certificados.certificado_calidad cc,
								    			g_certificados.lugar_inspeccion lui,
								    			g_certificados.lotes_inspeccion loi,
								    			g_revision_solicitudes.asignacion_coordinador ac
							    			WHERE
								    			loi.id_lugar_inspeccion = lui.id_lugar_inspeccion and
								    			lui.id_certificado_calidad = cc.id_certificado_calidad and
								    			loi.id_lote_inspeccion = ac.id_solicitud and
    											ac.identificador_inspector = '$identificadorInspector' and
												ac.tipo_solicitud = '$tipoSolicitud' and
												ac.tipo_inspector = '$tipoInspector' and
    											loi.estado = '$estado'
								    			".$busqueda.";");
    	return $res;
    }
    
    public function buscarLotesCertificadoCalidad($conexion, $idSolicitud){  	 
    	 
    	$res = $conexion->ejecutarConsulta("SELECT
												loi.*,
    											cc.identificador_exportador,
    											cc.razon_social_exportador
											FROM
												g_certificados.certificado_calidad cc,
												g_certificados.lugar_inspeccion lui,
												g_certificados.lotes_inspeccion loi
											WHERE
												loi.id_lugar_inspeccion = lui.id_lugar_inspeccion and
												lui.id_certificado_calidad = cc.id_certificado_calidad and 
												loi.id_lote_inspeccion IN ($idSolicitud);");
    	return $res;
    }
    
    public function actualizarEstadoLote($conexion, $idSolicitud, $estado, $agenciaVerificadora=null){
    	
    	if($estado == 'pago'){
    		
    		$res = $conexion->ejecutarConsulta("UPDATE
								    				g_certificados.lotes_inspeccion
								    			SET
								    				estado = '$estado',
    												identificador_verificadora = '$agenciaVerificadora'
								    			WHERE
								    				id_lote_inspeccion = $idSolicitud;");
    	}else{
    		
    		$res = $conexion->ejecutarConsulta("UPDATE
    												g_certificados.lotes_inspeccion
							    				SET
							    					estado = '$estado'
							    				WHERE
							    					id_lote_inspeccion = $idSolicitud;");
    	}
    
    	
    	return $res;
    }
    
    public function actualizarEstadoLoteInspector($conexion, $idSolicitud, $estado){
    	     
    		$res = $conexion->ejecutarConsulta("UPDATE
								    				g_certificados.lotes_inspeccion
								    			SET
								    				estado_inspector = '$estado'
								    			WHERE
								    				id_lote_inspeccion = $idSolicitud;");
     	 
    	return $res;
    }
   
    
    public function buscarSolicitudesCalidadFinancieroVerificacion($conexion, $identificador, $estado, $provincia, $tipoFormulario){
    	    
    	$res = $conexion->ejecutarConsulta("SELECT
								    			distinct ai.id_grupo,
								    			array_to_string(ARRAY(
								    									SELECT
															    			id_solicitud
															    		FROM
															    			g_revision_solicitudes.grupos_solicitudes gss
															    		WHERE
															    			gss.id_grupo = gs.id_grupo ),', ') as id_solicitud,
													cc.identificador_exportador
								    			FROM
									    			g_revision_solicitudes.grupos_solicitudes gs,
									    			g_revision_solicitudes.asignacion_inspector ai,
									    			g_certificados.lugar_inspeccion lui,
													g_certificados.lotes_inspeccion loi,
    												g_certificados.certificado_calidad cc
								    			WHERE
									    			gs.id_grupo = ai.id_grupo and
									    			loi.id_lote_inspeccion = gs.id_solicitud and
									    			loi.id_lugar_inspeccion = lui.id_lugar_inspeccion and
    												lui.id_certificado_calidad = cc.id_certificado_calidad and  												
													ai.tipo_inspector = 'Financiero' and
    												ai.tipo_solicitud = '$tipoFormulario' and
									    			UPPER(lui.nombre_provincia) = UPPER('$provincia') and
									    			loi.estado = '$estado' and
									    			cc.identificador_exportador = '$identificador';");
    			return $res;
    }
    
    public function listarCertificadoCalidadDisponibles($conexion, $estado, $provincia) {
    	$res = $conexion->ejecutarConsulta("SELECT
												cc.identificador_exportador as identificador_operador,
												cc.id_certificado_calidad as id_solicitud
											FROM
												g_certificados.certificado_calidad cc,
												g_certificados.lugar_inspeccion li,
    											g_certificados.lotes_inspeccion loi
											WHERE
												cc.id_certificado_calidad = li.id_certificado_calidad and
    											li.id_lugar_inspeccion = loi.id_lugar_inspeccion and
												UPPER(li.nombre_provincia) = UPPER('$provincia') and 
												loi.estado = '$estado'");
    	return $res;
    }
    
    public function obtenerSolicitudCertificadoCalidadXGrupoLotes($conexion, $idSolicitud){
    
    	$res = $conexion->ejecutarConsulta("SELECT
												distinct ca.*
											FROM
												g_certificados.certificado_calidad ca,
												g_certificados.lugar_inspeccion lui,
												g_certificados.lotes_inspeccion loi												
											WHERE
												loi.id_lugar_inspeccion = lui.id_lugar_inspeccion and
												lui.id_certificado_calidad = ca.id_certificado_calidad
												and loi.id_lote_inspeccion IN ($idSolicitud)
    										ORDER BY 1");
    	return $res;
    }
    
    public function obtenerLugarXGrupoLotes($conexion, $idLotes, $idCertificadoCalidad){
    
    	$res = $conexion->ejecutarConsulta("SELECT
								    			distinct(li.*)
								    		FROM
								    			g_certificados.lugar_inspeccion li,
    											g_certificados.lotes_inspeccion loi
								    		WHERE
    											li.id_lugar_inspeccion = loi.id_lugar_inspeccion and
								    			loi.id_lote_inspeccion IN ($idLotes) and
    											li.id_certificado_calidad = $idCertificadoCalidad ;");
    	return $res;
    }
    
    public function obtenerLoteCertificadoCalidad($conexion, $idLote ,$idLugarSolicitud){
    
    	$res = $conexion->ejecutarConsulta("SELECT
								    			*
								    		FROM
								    			g_certificados.lotes_inspeccion
								    		WHERE
								    			id_lote_inspeccion IN ($idLote) and
    											id_lugar_inspeccion = $idLugarSolicitud;");
    	return $res;
    }
    
    public function obtenerLoteCertificadoCalidadIndividual($conexion, $idLugarSolicitud){
    
    	$res = $conexion->ejecutarConsulta("SELECT
								    			*
								    		FROM
								    			g_certificados.lotes_inspeccion
								    		WHERE
								    			id_lugar_inspeccion = $idLugarSolicitud;");
    	return $res;
    }
    
    public function obtenerEmpresasVerificadoras($conexion){
    
    	$res = $conexion->ejecutarConsulta("SELECT
								    			*
								    		FROM
								    			g_certificados.empresas_verificadoras;");
    	return $res;
    }
    
    public function obtenerDatosInspeccionAgenciaVerificadora($conexion, $idLoteInspeccion){
    	
    	$res = $conexion->ejecutarConsulta("SELECT
												ca.razon_social_exportador,
												ca.nombre_importador,
												loi.numero_lote,
												loi.nombre_calidad_producto
											FROM
												g_certificados.certificado_calidad ca,
												g_certificados.lugar_inspeccion lui,
												g_certificados.lotes_inspeccion loi
											WHERE
												loi.id_lugar_inspeccion = lui.id_lugar_inspeccion and
												lui.id_certificado_calidad = ca.id_certificado_calidad and
												loi.id_lote_inspeccion IN ($idLoteInspeccion)");
    	return $res;
    	
    }
    
    public function obtenerSolicitudCertificadoCalidad($conexion, $idSolicitud){
    
    	$res = $conexion->ejecutarConsulta("SELECT
								    			*
								    		FROM
								    			g_certificados.certificado_calidad
								    		WHERE
								    			id_certificado_calidad = $idSolicitud;");
    	return $res;
    }
    
    public function obtenerLugarCertificadoCalidad($conexion, $idSolicitud){
    
    	$res = $conexion->ejecutarConsulta("SELECT
								    			*
								    		FROM
								    			g_certificados.lugar_inspeccion
								    		WHERE
								    			id_certificado_calidad = $idSolicitud;");
    	return $res;
    }
    
    public function guardarInspeccionVerificadora($conexion, $loteInspeccion, $fechaInspeccion, $vapor, $muestraInspector, $contraMuestra, $tipoInspeccion, $tipoCacao, $higiene, 
    												$seguridadAlimenticia, $buenaFermentacion, $ligramenteFermentado, $granoVioleta, $granoPizarroso, $mohos, $daniosInsectos, 
    												$vulnerado, $total, $defectoMultiple, $defectoPartido, $defectoPlanoGranza, $impurezaCacao, $materiaExtrania, $tipoTrinitario, 
    												$pesoCacao, $numeroPepas, $humedad, $observacion, $identifiadorInspector){
    	
    	$res = $conexion->ejecutarConsulta("INSERT INTO g_certificados.inspecciones_verificadora(
																			            id_lote_inspeccion, fecha_inspeccion, 
																			            vapor, muestra_inspector, contra_muestra, tipo_inspeccion, tipo_cacao, 
																			            higiene, seguridad_alimenticia, buena_fermentacion, ligramente_fermentado, 
																			            grano_violeta, grano_pizarroso, mohos, danios_insectos, vulnerado, 
																			            total, defecto_multiple, defecto_partido, defecto_plano_granza, 
																			            impureza_cacao, materia_extrania, tipo_trinitario, peso_cacao, 
																			            numero_pepas, humedad, observacion, identifiador_inspector)
    												VALUES ($loteInspeccion, '$fechaInspeccion', '$vapor', '$muestraInspector', '$contraMuestra', '$tipoInspeccion', '$tipoCacao', '$higiene', 
    														'$seguridadAlimenticia', $buenaFermentacion, $ligramenteFermentado, $granoVioleta, $granoPizarroso, $mohos, $daniosInsectos, 
    														$vulnerado, $total, $defectoMultiple, $defectoPartido, $defectoPlanoGranza, $impurezaCacao, $materiaExtrania, $tipoTrinitario, 
    														$pesoCacao, $numeroPepas, $humedad, '$observacion', '$identifiadorInspector');");
    	
    	return $res;
    	
    }
    
    public function  obtenerDatosResultadoAgenciaVerificadora($conexion, $idSolicitud){
    	
    	$res = $conexion->ejecutarConsulta("SELECT
								    			*
								    		FROM
								    			g_certificados.inspecciones_verificadora
								    		WHERE
								    			id_lote_inspeccion = $idSolicitud;");
    	
    	return $res;
    }
    
    public function actualizarResultadoInspeccionResponsable($conexion, $idSolicitud, $estado, $observacion, $identificador){
    	     
    		$res = $conexion->ejecutarConsulta("UPDATE
    												g_certificados.lotes_inspeccion
    											SET
    												estado = '$estado',
    												observacion_responsable = '$observacion',
    												identificador_responsable = '$identificador'
							    				WHERE
							    					id_lote_inspeccion = $idSolicitud;");
    	
    	return $res;
    }

}

<?php

class ControladorTransitoInternacional{
	
	public function listarTransitoInternacionalRevisionProvinciaRS($conexion, $estado, $provincia){
		
		$consulta = "SELECT 
						distinct id_transito_internacional as id_solicitud,
						identificador_importador as identificador_operador,
						estado,
						nombre_documento as tipo_certificado,
						nombre_pais_origen as pais,
						nombre_importador,
						req_no as id_vue,
						fecha_creacion
					FROM 
						g_transito_internacional.transito_internacional 
					WHERE 
						estado = '$estado' and
						UPPER(provincia_revision) = UPPER('$provincia') 
                    ORDER BY
                        fecha_creacion;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function listarTransitoInternacionalAsignadasInspectorRS ($conexion, $estadoSolicitud, $identificadorInspector, $tipoSolicitud, $tipoInspector, $provincia){
		
		$res = $conexion->ejecutarConsulta("SELECT
												distinct id_transito_internacional as id_solicitud,
												identificador_importador as identificador_operador,
												i.estado,
												i.nombre_documento as tipo_certificado,
												nombre_pais_origen as pais,
												nombre_importador,
												req_no as id_vue,
												fecha_creacion
											FROM
												g_transito_internacional.transito_internacional i,
												g_revision_solicitudes.asignacion_coordinador ac
											WHERE
												i.id_transito_internacional = ac.id_solicitud and
												ac.identificador_inspector = '$identificadorInspector' and
												ac.tipo_solicitud = '$tipoSolicitud' and
												ac.tipo_inspector = '$tipoInspector' and
												i.estado in ('$estadoSolicitud') and
						                        UPPER(i.provincia_revision) = UPPER('$provincia')
                                            ORDER BY
                                                fecha_creacion;");
		return $res;
	}
	
	public function abrirTransitoInternacional($conexion, $idSolicitud){
		
		$consulta = "SELECT
						*
					FROM
						g_transito_internacional.transito_internacional
					WHERE
						id_transito_internacional = '$idSolicitud';";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function buscarTransitoInternacionalVUE($conexion, $identificador, $idVue){
	    
	    $consulta = "SELECT
						*
					FROM
						g_transito_internacional.transito_internacional
					WHERE
						identificador_importador = '$identificador'
						and req_no = '$idVue';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function asignarDocumentoRequisitosTransitoInternacional ($conexion, $idSolicitud, $informeRequisitos){
	    $res = $conexion->ejecutarConsulta("update
												g_transito_internacional.transito_internacional
											set
												informe_requisitos = '$informeRequisitos'
											where
												id_transito_internacional = $idSolicitud;");
	    return $res;
	}
	
	public function abrirTransitoInternacionalProductos($conexion, $idSolicitud){
		
		$consulta = "SELECT
						*
					FROM
						g_transito_internacional.transito_detalle_productos
					WHERE
						id_transito_internacional = '$idSolicitud';";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function abrirDocumentosTransitoInternacional($conexion, $idSolicitud){
		
		$consulta = "SELECT
						*
					FROM
						g_transito_internacional.documentos_adjuntos
					WHERE
						id_transito_internacional = '$idSolicitud';";
		
		$cid = $conexion->ejecutarConsulta($consulta);
		
		while ($fila = pg_fetch_assoc($cid)){
		    $res[] = array(
		        idTransitoInternacional=>$fila['id_transito_internacional'],
		        tipoArchivo=>$fila['tipo_archivo'],
		        rutaArchivo=>$fila['ruta_archivo'],
		        reqNo=>$fila['req_no']);
		}
		
		return $res;
	}
	
	public function actualizarEstadoDocumentoAdjunto($conexion, $idSolicitud, $idDocumentoAdjunto){
		
		$consulta = "UPDATE
						g_transito_internacional.documentos_adjuntos
					SET
						estado = 'activo'
					WHERE
						id_transito_internacional = $idSolicitud
						and id_documento_adjunto = $idDocumentoAdjunto;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function obtenerDocumentoAdjuntoPorNombre($conexion, $idSolicitud, $nombreDocumento){
		
		$consulta = "SELECT
						*
					FROM
						g_transito_internacional.documentos_adjuntos
					WHERE
						id_transito_internacional = $idSolicitud
						and tipo_archivo = '$nombreDocumento'
						and estado = 'temporal';";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function cambiarEstadoTransitoInternacional ($conexion, $idTransitoInternacional, $estado, $identificador, $observacion = null){
		
		$fechaVigencia = '';
		if($estado == 'aprobado'){
			$fechaVigencia = ", fecha_fin_vigencia = now() + interval '3' day";
		}
		
		$consulta = "UPDATE
						g_transito_internacional.transito_internacional
					SET
						estado = '$estado',
						observacion_tecnico = '$observacion',
						identificador_tecnico = '$identificador',
						fecha_inicio_vigencia = now()
						".$fechaVigencia."
					where
						id_transito_internacional = $idTransitoInternacional;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function enviarTransitoInternacional ($conexion, $idTransitoInternacional, $estado){
		
		$consulta = "UPDATE
						g_transito_internacional.transito_internacional
					SET
						estado = '$estado'
					where
						id_transito_internacional = $idTransitoInternacional;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function guardarRutaCertificado($conexion, $idSolicitud, $certificado){
	    
	    $res = $conexion->ejecutarConsulta("UPDATE
												g_transito_internacional.transito_internacional
											SET
												informe_requisitos = '$certificado'
											WHERE
												id_transito_internacional = $idSolicitud;");
	    
	    return $res;
	    
	}
	
	
	/*ok*/
	public function guardarNuevoTransitoInternacional(
	    $conexion, $reqNo, $numeroDocumento, 
	    $nombreDocumento, $codigoFuncionDocumento, $fechaSolicitud,
        $idCiudadSolicitud, $codigoCiudadSolicitud, $nombreCiudadSolicitud, 
	    $codigoTipoProducto, $nombreTipoProducto,
        $identificadorSolicitante,$nombreSolicitante, $representanteLegalSolicitante, 
	    $idProvinciaSolicitante, $codigoProvinciaSolicitante, $nombreProvinciaSolicitante,
        $idCantonSolicitante, $codigoCantonSolicitante, $nombreCantonSolicitante, 
	    $idParroquiaSolicitante, $codigoParroquiaSolicitante, $nombreParroquiaSolicitante, 
	    $direccionSolicitante, $telefonoSolicitante, $faxSolicitante, $correoSolicitante, 
	    $clasificacionImportador, $identificadorImportador, $nombreImportador, 
	    $direccionImportador, $correoImportador, $representanteLegalImportador, 
	    $telefonoImportador, $celularImportador, 
	    $codigoRegimenAduanero, $nombreRegimenAduanero, 
	    $idPaisOrigen, $codigoPaisOrigen, $nombrePaisOrigen, 
	    $idPaisProcedencia, $codigoPaisProcedencia, $nombrePaisProcedencia, 
	    $idPaisDestino, $codigoPaisDestino, $nombrePaisDestino,
	    $idUbicacionEnvio, $codigoUbicacionEnvio, $nombreUbicacionEnvio, 
	    $idPuntoIngreso, $codigoPuntoIngreso, $nombrePuntoIngreso, 
	    $idPuntoSalida, $codigoPuntoSalida, $nombrePuntoSalida,
        $idMedioTransporte, $codigoMedioTransporte, $nombreMedioTransporte, 
	    $placaVehiculo, $rutaSeguir, $estado, $codigoSolicitud,
	    $idProvinciaRevision, $provinciaRevision){

	        
	    $res = $conexion->ejecutarConsulta("INSERT INTO
													g_transito_internacional.transito_internacional(
                                                    	fecha_creacion, req_no, numero_documento, 
                                                        nombre_documento, codigo_funcion_documento, fecha_solicitud, 
                                                    	id_ciudad_solicitud, codigo_ciudad_solicitud, nombre_ciudad_solicitud, 
                                                        codigo_tipo_producto, nombre_tipo_producto, 
                                                        identificador_solicitante, nombre_solicitante, representante_legal_solicitante, 
                                                        id_provincia_solicitante, codigo_provincia_solicitante, nombre_provincia_solicitante, 
                                                    	id_canton_solicitante, codigo_canton_solicitante, nombre_canton_solicitante, 
                                                        id_parroquia_solicitante, codigo_parroquia_solicitante, nombre_parroquia_solicitante, 
                                                        direccion_solicitante, telefono_solicitante, fax_solicitante, correo_solicitante, 
                                                        clasificacion_importador, identificador_importador, nombre_importador, 
                                                        direccion_importador, correo_importador, representante_legal_importador, 
                                                        telefono_importador, celular_importador, 
                                                        codigo_regimen_aduanero, nombre_regimen_aduanero, 
                                                        id_pais_origen, codigo_pais_origen, nombre_pais_origen, 
                                                        id_pais_procedencia, codigo_pais_procedencia, nombre_pais_procedencia, 
                                                        id_pais_destino, codigo_pais_destino, nombre_pais_destino, 
                                                        id_ubicacion_envio, codigo_ubicacion_envio, nombre_ubicacion_envio, 
                                                        id_punto_ingreso, codigo_punto_ingreso, nombre_punto_ingreso, 
                                                        id_punto_salida, codigo_punto_salida, nombre_punto_salida, 
                                                    	id_medio_transporte, codigo_medio_transporte, nombre_medio_transporte, 
                                                        placa_vehiculo, ruta_seguir, estado, codigo_certificado,
                                                        id_provincia_revision, provincia_revision)
										          VALUES (now(), '$reqNo', '$numeroDocumento', 
                                                        '$nombreDocumento', '$codigoFuncionDocumento', '$fechaSolicitud',
                                                	    $idCiudadSolicitud, '$codigoCiudadSolicitud', '$nombreCiudadSolicitud', 
                                                        '$codigoTipoProducto', '$nombreTipoProducto',
                                                        '$identificadorSolicitante', $$$nombreSolicitante$$, $$$representanteLegalSolicitante$$, 
                                                        $idProvinciaSolicitante, '$codigoProvinciaSolicitante', '$nombreProvinciaSolicitante',
                                                	    $idCantonSolicitante, '$codigoCantonSolicitante', '$nombreCantonSolicitante', 
                                                        $idParroquiaSolicitante, '$codigoParroquiaSolicitante', '$nombreParroquiaSolicitante', 
                                                        $$$direccionSolicitante$$, '$telefonoSolicitante', '$faxSolicitante', '$correoSolicitante', 
                                                        '$clasificacionImportador', '$identificadorImportador', $$$nombreImportador$$, 
                                                        $$$direccionImportador$$, '$correoImportador', $$$representanteLegalImportador$$, 
                                                        '$telefonoImportador', '$celularImportador', 
                                                        '$codigoRegimenAduanero', '$nombreRegimenAduanero', 
                                                        $idPaisOrigen, '$codigoPaisOrigen', '$nombrePaisOrigen', 
                                                        $idPaisProcedencia, '$codigoPaisProcedencia', '$nombrePaisProcedencia', 
                                                        $idPaisDestino, '$codigoPaisDestino', '$nombrePaisDestino', 
                                                        $idUbicacionEnvio, '$codigoUbicacionEnvio', '$nombreUbicacionEnvio', 
                                                        $idPuntoIngreso, '$codigoPuntoIngreso', '$nombrePuntoIngreso', 
                                                        $idPuntoSalida, '$codigoPuntoSalida', '$nombrePuntoSalida',
                                                	    $idMedioTransporte, '$codigoMedioTransporte', '$nombreMedioTransporte', 
                                                        '$placaVehiculo', $$$rutaSeguir$$, '$estado', '$codigoSolicitud',
                                                        $idProvinciaRevision, '$provinciaRevision')
											     RETURNING id_transito_internacional;");
	        
                                                	    /*$idCiudadEmision, $codigoCiudadEmision, $nombreCiudadEmision,*/
                                                	    /*$idCiudadEmision, '$codigoCiudadEmision', '$nombreCiudadEmision',*/

		  return $res;  
	}
	
	/*ok*/
	public function guardarTransitoInternacionalProductos(     $conexion, $idTransitoInternacional, $reqNo, 
	                                                           $subpartidaArancelaria, $subpartidaArancelariaDescripcion,
                                                    	       $idTipoProducto, $nombreTipoProducto, 
	                                                           $idSubtipoProducto, $nombreSubtipoProducto,
                                                    	       $idProducto, $codigoProducto, $nombreProducto, 
	                                                           $idUnidadPeso, $nombreUnidadPeso,
                                                    	       $idUnidadCantidad, $nombreUnidadCantidad, 
	                                                           $cantidadProducto, $pesoKilos){
	    
       $res = $conexion->ejecutarConsulta("INSERT INTO g_transito_internacional.transito_detalle_productos(
                                        	    id_transito_internacional, fecha_creacion, 
                                                req_no, subpartida_arancelaria, subpartida_arancelaria_descripcion, 
                                                id_tipo_producto, nombre_tipo_producto, id_subtipo_producto, nombre_subtipo_producto, 
                                                id_producto, codigo_producto, nombre_producto, id_unidad_peso, nombre_unidad_peso, 
                                                id_unidad_cantidad, nombre_unidad_cantidad, cantidad_producto, peso_kilos)
                                        	VALUES ($idTransitoInternacional, now(), '$reqNo', '$subpartidaArancelaria', '$subpartidaArancelariaDescripcion', '$idTipoProducto', '$nombreTipoProducto', 
                                                '$idSubtipoProducto', '$nombreSubtipoProducto', '$idProducto', '$codigoProducto', '$nombreProducto', '$idUnidadPeso', '$nombreUnidadPeso', '$idUnidadCantidad', 
                                                '$nombreUnidadCantidad', '$cantidadProducto', '$pesoKilos');");
										            
	   return $res;
	}
	
	public function abrirTransitoInternacionalArchivoIndividual($conexion, $idTransitoInternacional, $tipoArchivo){
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_transito_internacional.documentos_adjuntos
											WHERE
												id_transito_internacional = $idTransitoInternacional
												and tipo_archivo = '$tipoArchivo';");
	    
	    return $res;
	}
	
	
	public function guardarTransitoInternacionalArchivos($conexion, $idTransitoInternacional, $tipoArchivo, $rutaArchivo, $reqNo){
	    
	    $documento = $this->abrirTransitoInternacionalArchivoIndividual($conexion, $idTransitoInternacional, $tipoArchivo);
	    
	    if(pg_num_rows($documento)== 0){
	        $res = $conexion->ejecutarConsulta("INSERT INTO g_transito_internacional.documentos_adjuntos(
														id_transito_internacional, tipo_archivo, ruta_archivo, req_no, fecha_creacion)
												VALUES ($idTransitoInternacional, '$tipoArchivo', '$rutaArchivo', '$reqNo', now());");
	    }
	    	    
	    return $res;
	}
	
	public function  generarNumeroSolicitud($conexion,$codigo){ //aqui me quede!!!
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												MAX(codigo_certificado) as numero
											FROM
												g_transito_internacional.transito_internacional
											WHERE
												codigo_certificado LIKE '$codigo';");
	    return $res;
	}
	
	public function actualizarDatosTransitoInternacional(   $conexion, $idTransitoInternacional, $reqNo, $numeroDocumento, 
                                                    	    $nombreDocumento, $codigoFuncionDocumento, $fechaSolicitud,
                                                            $idCiudadSolicitud, $codigoCiudadSolicitud, $nombreCiudadSolicitud, 
                                                    	    $codigoTipoProducto, $nombreTipoProducto,
                                                            $identificadorSolicitante, $nombreSolicitante, $representanteLegalSolicitante, 
                                                    	    $idProvinciaSolicitante, $codigoProvinciaSolicitante, $nombreProvinciaSolicitante,
                                                            $idCantonSolicitante, $codigoCantonSolicitante, $nombreCantonSolicitante, 
                                                    	    $idParroquiaSolicitante, $codigoParroquiaSolicitante, $nombreParroquiaSolicitante, 
                                                    	    $direccionSolicitante, $telefonoSolicitante, $faxSolicitante, $correoSolicitante, 
                                                    	    $clasificacionImportador, $identificadorImportador, $nombreImportador, 
                                                    	    $direccionImportador, $correoImportador, $representanteLegalImportador, 
                                                    	    $telefonoImportador, $celularImportador, 
                                                    	    $codigoRegimenAduanero, $nombreRegimenAduanero, 
                                                    	    $idPaisOrigen, $codigoPaisOrigen, $nombrePaisOrigen, 
                                                    	    $idPaisProcedencia, $codigoPaisProcedencia, $nombrePaisProcedencia, 
                                                    	    $idPaisDestino, $codigoPaisDestino, $nombrePaisDestino, 
                                                    	    $idUbicacionEnvio, $codigoUbicacionEnvio, $nombreUbicacionEnvio, 
                                                    	    $idPuntoIngreso, $codigoPuntoIngreso, $nombrePuntoIngreso, 
                                                    	    $idPuntoSalida, $codigoPuntoSalida, $nombrePuntoSalida,
                                                    	    $idMedioTransporte, $codigoMedioTransporte, $nombreMedioTransporte, 
	                                                        $placaVehiculo, $rutaSeguir, $estado,
	                                                        $idProvinciaRevision, $provinciaRevision){
	    
	        $res = $conexion->ejecutarConsulta("UPDATE
												    g_transito_internacional.transito_internacional
    											SET
    												numero_documento = '$numeroDocumento', 
                                                    nombre_documento = '$nombreDocumento', 
                                                    codigo_funcion_documento = '$codigoFuncionDocumento', 
                                                    fecha_solicitud = '$fechaSolicitud', 
                                                    id_ciudad_solicitud = $idCiudadSolicitud, 
                                                    codigo_ciudad_solicitud = '$codigoCiudadSolicitud', 
                                                    nombre_ciudad_solicitud = '$nombreCiudadSolicitud', 
                                                    codigo_tipo_producto = '$codigoTipoProducto', 
                                                    nombre_tipo_producto = '$nombreTipoProducto', 
                                                     
                                                    identificador_solicitante = '$identificadorSolicitante', 
                                                    nombre_solicitante = $$$nombreSolicitante$$, 
                                                    representante_legal_solicitante = $$$representanteLegalSolicitante$$, 
                                                    id_provincia_solicitante = $idProvinciaSolicitante, 
                                                    codigo_provincia_solicitante = '$codigoProvinciaSolicitante', 
                                                    nombre_provincia_solicitante = '$nombreProvinciaSolicitante', 
                                                    id_canton_solicitante = $idCantonSolicitante, 
                                                    codigo_canton_solicitante = '$codigoCantonSolicitante', 
                                                    nombre_canton_solicitante = '$nombreCantonSolicitante', 
                                                    id_parroquia_solicitante = $idParroquiaSolicitante, 
                                                    codigo_parroquia_solicitante = '$codigoParroquiaSolicitante',                 		
                                                    nombre_parroquia_solicitante = '$nombreParroquiaSolicitante', 
                                                    direccion_solicitante = $$$direccionSolicitante$$, 
                                                    telefono_solicitante = '$telefonoSolicitante', 
                                                    fax_solicitante = '$faxSolicitante', 
                                                    correo_solicitante = '$correoSolicitante', 
                                                    clasificacion_importador = '$clasificacionImportador', 
                                                    identificador_importador = '$identificadorImportador', 
                                                    nombre_importador = $$$nombreImportador$$, 
                                                    
                                                    direccion_importador = $$$direccionImportador$$, 
                                                    correo_importador = '$correoImportador', 
                                                    representante_legal_importador = $$$representanteLegalImportador$$, 
                                                    telefono_importador = '$telefonoImportador', 
                                                    celular_importador = '$celularImportador', 
                                                    codigo_regimen_aduanero = '$codigoRegimenAduanero', 
                                                    nombre_regimen_aduanero = '$nombreRegimenAduanero', 
                                                    id_pais_origen = $idPaisOrigen, 
                                                    codigo_pais_origen = '$codigoPaisOrigen', 
                                                    nombre_pais_origen = '$nombrePaisOrigen', 
                                                    id_pais_procedencia = $idPaisProcedencia, 
                                                    codigo_pais_procedencia = '$codigoPaisProcedencia', 
                                                    nombre_pais_procedencia = '$nombrePaisProcedencia', 
                                                    id_pais_destino = $idPaisDestino, 
                                                    codigo_pais_destino = '$codigoPaisDestino', 
                                                    nombre_pais_destino = '$nombrePaisDestino', 
                                                    id_ubicacion_envio = $idUbicacionEnvio, 
                                                    codigo_ubicacion_envio = '$codigoUbicacionEnvio', 
                                                    nombre_ubicacion_envio = '$nombreUbicacionEnvio', 
                                                    id_punto_ingreso = $idPuntoIngreso, 
                                                    codigo_punto_ingreso = '$codigoPuntoIngreso', 
                                                    nombre_punto_ingreso = '$nombrePuntoIngreso', 
                                                    id_punto_salida = $idPuntoSalida, 
                                                    codigo_punto_salida = '$codigoPuntoSalida', 
                                                    nombre_punto_salida = '$nombrePuntoSalida', 
                                                    id_medio_transporte = $idMedioTransporte, 
                                                    codigo_medio_transporte = '$codigoMedioTransporte', 
                                                    nombre_medio_transporte = '$nombreMedioTransporte', 
                                                    placa_vehiculo = '$placaVehiculo', 
                                                    ruta_seguir = $$$rutaSeguir$$, 
                                                    estado = '$estado', 
                                                    id_provincia_revision = $idProvinciaRevision, 
                                                    provincia_revision = '$provinciaRevision'
    											WHERE
    												id_transito_internacional = $idTransitoInternacional
    												and req_no = '$reqNo';");
	        
	        /*$idCiudadEmision, $codigoCiudadEmision, $nombreCiudadEmision,*/
	                                               /*id_ciudad_emision = $idCiudadEmision, 
                                                    codigo_ciudad_emision = '$codigoCiudadEmision', 
                                                    nombre_ciudad_emision = '$nombreCiudadEmision',*/
	        
	        return $res;
	}
	
	public function eliminarProductosTransitoInternacional($conexion, $idTransitoInternacional){
	    $res = $conexion->ejecutarConsulta("DELETE FROM
												g_transito_internacional.transito_detalle_productos
											WHERE
												id_transito_internacional = $idTransitoInternacional;");
	    
	    return $res;
	}
	
	public function eliminarArchivosAdjuntos($conexion, $idTransitoInternacional, $idVue){
	    $res = $conexion->ejecutarConsulta("DELETE FROM
													g_transito_internacional.documentos_adjuntos
												WHERE
													id_transito_internacional = $idTransitoInternacional
													and req_no = '$idVue';");
	    
	    return $res;
	}
	
	public function enviarProductosTransitoInternacional ($conexion, $idTransitoInternacional, $estado){
	    $res = $conexion->ejecutarConsulta("update
												g_transito_internacional.transito_detalle_productos
											set
												estado = '$estado'
											where
												id_transito_internacional = $idTransitoInternacional;");
	    return $res;
	}
	
	public function actualizarDatosTransitoInternacionalPuntos($conexion, $idTransitoInternacional, $idPuntoIngreso, $codigoPuntoIngreso, $nombrePuntoIngreso, $idPuntoSalida, $codigoPuntoSalida, $nombrePuntoSalida, $idVue){
	        
	    echo "UPDATE
    												g_transito_internacional.transito_internacional
    											SET
    												id_punto_ingreso = $idPuntoIngreso,
                                                    codigo_punto_ingreso = '$codigoPuntoIngreso',
                                                    nombre_punto_ingreso = '$nombrePuntoIngreso',
                                                    id_punto_salida = $idPuntoSalida,
                                                    codigo_punto_salida = '$codigoPuntoSalida',
                                                    nombre_punto_salida = '$nombrePuntoSalida'
    											WHERE
    												id_transito_internacional = $idTransitoInternacional
    												and req_no = '$idVue';";
	    
	        $res = $conexion->ejecutarConsulta("UPDATE
    												g_transito_internacional.transito_internacional
    											SET
    												id_punto_ingreso = $idPuntoIngreso, 
                                                    codigo_punto_ingreso = '$codigoPuntoIngreso', 
                                                    nombre_punto_ingreso = '$nombrePuntoIngreso', 
                                                    id_punto_salida = $idPuntoSalida, 
                                                    codigo_punto_salida = '$codigoPuntoSalida', 
                                                    nombre_punto_salida = '$nombrePuntoSalida'
    											WHERE
    												id_transito_internacional = $idTransitoInternacional
    												and req_no = '$idVue';");
	        return $res;
	}
	
	public function buscarTransitoInternacionalProductoVUE ($conexion, $identificador, $idVue, $producto){
	    
	    $res = $conexion->ejecutarConsulta("select
												p.*
											from
												g_transito_internacional.transito_internacional i,
												g_transito_internacional.transito_detalle_productos p
											where
												i.identificador_importador = '$identificador' and
												i.req_no = '$idVue' and
												i.id_transito_internacional = p.id_transito_internacional and
												p.id_producto = $producto;");
	    
	    return $res;
	}
	
	public function numeroProductosTransitoInternacional ($conexion, $identificador, $idVue){
	    
	    $res = $conexion->ejecutarConsulta("select
												count(p.id_transito_internacional) as cantidad
											from
												g_transito_internacional.transito_internacional i,
												g_transito_internacional.transito_detalle_productos p
											where
												i.identificador_importador = '$identificador' and
												i.req_no = '$idVue' and
												i.id_transito_internacional = p.id_transito_internacional;");
	    
	    return $res;
	}
	
	public function abrirTransitoInternacionalReporte ($conexion, $idSolicitud){
	    $cid = $conexion->ejecutarConsulta("SELECT
                        						*
                        					FROM
                        						g_transito_internacional.transito_internacional ti
                                                INNER JOIN g_transito_internacional.transito_detalle_productos tidp ON ti.id_transito_internacional = tidp.id_transito_internacional
                        					WHERE
                        						ti.id_transito_internacional = '$idSolicitud';");
	    
	    while ($fila = pg_fetch_assoc($cid)){
	        $res[] = array(
	            id_transito_internacional=>$fila['id_transito_internacional'],
	            fechaInicio=>$fila['fecha_inicio_vigencia'],
	            fechaVigencia=>$fila['fecha_fin_vigencia'],
	            idVue=>$fila['req_no'],
	            idArea=>$fila['codigo_tipo_producto'],	            
	            observacionesTecnico=>$fila['observacion_tecnico'],
	            
	            razonSocialImportador=>$fila['nombre_importador'],
	            identificadorImportador=>$fila['identificador_importador'],
	            representanteLegalImportador=>$fila['representante_legal_importador'],
	            direccionImportador=>$fila['direccion_importador'],
	            telefonoImportador=>$fila['telefono_importador'],
	            emailImportador=>$fila['correo_importador'],
	            
	            razonSocialSolicitante=>$fila['nombre_solicitante'],
	            identificadorSolicitante=>$fila['identificador_solicitante'],
	            representanteLegalSolicitante=>$fila['representante_legal_solicitante'],
	            direccionSolicitante=>$fila['direccion_solicitante'],
	            telefonoSolicitante=>$fila['telefono_solicitante'],
	            emailSolicitante=>$fila['correo_solicitante'],
	            
	            id_pais_origen=>$fila['id_pais_origen'],
	            paisOrigen=>$fila['nombre_pais_origen'],
	            paisProcedencia=>$fila['nombre_pais_procedencia'],
	            paisDestino=>$fila['nombre_pais_destino'],
	            puntoIngreso=>$fila['nombre_punto_ingreso'],
	            puntoSalida=>$fila['nombre_punto_salida'],
	            ubicacionEnvio=>$fila['nombre_ubicacion_envio'],
	            rutaSeguir=>$fila['ruta_seguir'],
	            placasVehiculo=>$fila['placa_vehiculo'],
	            
	            subpartida_arancelaria=>$fila['subpartida_arancelaria'],
	            nombre_tipo_producto=>$fila['nombre_tipo_producto'],
	            nombre_subtipo_producto=>$fila['nombre_subtipo_producto'],
	            id_producto=>$fila['id_producto'],
	            nombre_producto=>$fila['nombre_producto'],
	            cantidad_producto=>$fila['cantidad_producto'],
	            nombre_unidad_cantidad=>$fila['nombre_unidad_cantidad'],
	            peso_kilos=>$fila['peso_kilos'],
	            nombre_unidad_peso=>$fila['nombre_unidad_peso']
	        );
	    }
	    
	    return $res;
	}
}
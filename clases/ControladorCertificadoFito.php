<?php

class ControladorCertificadoFito
{
    public function abrirSolicitud ($conexion, $idSolicitud){
        
        $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificado_fitosanitario.certificado_fitosanitario cf
                                                INNER JOIN g_operadores.operadores o ON cf.identificador_solicitante = o.identificador
											WHERE
												cf.id_certificado_fitosanitario =  $idSolicitud;");
        return $res;
    }
    
    public function obtenerDetalleExportadoresProductos ($conexion, $idSolicitud){
    
        $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificado_fitosanitario.exportadores_productos ep
											WHERE
												ep.id_certificado_fitosanitario = $idSolicitud
                                                and ep.estado_exportador_producto NOT IN ('Rechazado')
                                            ORDER BY
                                                ep.nombre_producto ASC;");
        
        return $res;
    }
    
    public function actualizarEstadoCertificado($conexion, $estado, $idSolicitud, $identificador){

        $res = $conexion->ejecutarConsulta("UPDATE
												g_certificado_fitosanitario.certificado_fitosanitario
											SET
												estado_certificado = '$estado',
                                                fecha_revision = now(),
                                                tipo_revision = 'Pago',
                                                identificador_revision = '$identificador',
                                                observacion_revision = 'Proceso de Asignación de Tasa/Facturación'
											WHERE
												id_certificado_fitosanitario = $idSolicitud;");
        
        return $res;
        
    }
    
    public function actualizarEstadoExportadoresProductos($conexion, $estado, $idSolicitud){
        
        $res = $conexion->ejecutarConsulta("UPDATE
												g_certificado_fitosanitario.exportadores_productos
											SET
												estado_exportador_producto = '$estado'
											WHERE
												id_certificado_fitosanitario = $idSolicitud and
                                                estado_exportador_producto in ('Creado', 'DocumentalAprobada');");
        
        return $res;
        
    }
    
    public function obtenerSolicitudPorEstadoProvincia ($conexion, $estado, $provincia){
        
        $busqueda = "";
        
        if($estado == 'verificacion'){
            $busqueda = " and forma_pago not in ('saldo')";
        }
        
        $consulta = "SELECT
						id_certificado_fitosanitario as id_solicitud,
						fecha_creacion_certificado as fecha_registro,
						codigo_certificado as numero_solicitud,
						identificador_solicitante as identificador_operador
					 FROM
						g_certificado_fitosanitario.certificado_fitosanitario cf
					 WHERE
						estado_certificado = '$estado'
						and nombre_provincia_origen = '$provincia'
                        and ((tipo_certificado in ('otros')" . $busqueda . ") or (tipo_certificado in ('ornamentales', 'musaceas') and (descuento = 'Si' or forma_pago not in ('saldo'))));";
        
        $res = $conexion->ejecutarConsulta($consulta);
        
        return $res;
    }
	
	public function buscarCertificadoFitosanitario ($conexion, $codigoCertificado){

        $consulta = "SELECT
						*
					FROM
						g_certificado_fitosanitario.certificado_fitosanitario
					WHERE
						codigo_certificado = '$codigoCertificado';";
        
        $res = $conexion->ejecutarConsulta($consulta);
        
        return $res;
    }
    
    public function obtenerCertificadoFitosanitarioPago ($conexion){
        
        $consulta = "SELECT
						*
					FROM
						g_certificado_fitosanitario.certificado_fitosanitario
					WHERE
                        estado_certificado = 'pago'
						and tipo_certificado in ('ornamentales', 'musaceas')
                        and descuento = 'No';";
        
        $res = $conexion->ejecutarConsulta($consulta);
        
        return $res;
    }
    
    public function actualizarEstadoSolicitudXIdCertificadoFitosanitario ($conexion, $idCertificadoFitosanitario, $estado){
        
        $consulta = "UPDATE 
                        g_certificado_fitosanitario.certificado_fitosanitario
                     SET 
                        estado_certificado = '" . $estado . "' 
                     WHERE 
                        id_certificado_fitosanitario = '" . $idCertificadoFitosanitario . "';";
        
        $res = $conexion->ejecutarConsulta($consulta);
        
        return $res;
    }
 
    public function obtenerDocumentoDescuentoPorIdCertificadoPorTipoAdjunto ($conexion, $idCertificadoFitosanitario, $tipoAdjunto){
        
        $consulta = "SELECT 
                        id_documento_adjunto
                        , id_certificado_fitosanitario
                        , tipo_adjunto
                        , ruta_adjunto
                     FROM 
                        g_certificado_fitosanitario.documentos_adjuntos
                     WHERE
                        id_certificado_fitosanitario = '" . $idCertificadoFitosanitario . "'
                        and tipo_adjunto = '" . $tipoAdjunto . "';";
        
        $res = $conexion->ejecutarConsulta($consulta);
        
        return $res;
    }
	
    /*public function actualizarEstadoExportadoresProductosXIdCertificadoFitosanitario($conexion, $idCertificadoFitosanitario, $estado){
        
        $consulta = "UPDATE
    						g_certificado_fitosanitario.exportadores_productos
    					SET
    						estado_exportador_producto = '" . $estado . "' 
    					WHERE
    						id_certificado_fitosanitario = '" . $idCertificadoFitosanitario . "';";
        
        $res = $conexion->ejecutarConsulta($consulta);
        
        return $res;
    }*/
    
    public function actualizarFechaAprobacionCertificado($conexion, $fechaAprobacionCertificado, $idCertificadoFitosanitario){
        
        $consulta = "UPDATE
                        g_certificado_fitosanitario.certificado_fitosanitario
                     SET
                        fecha_aprobacion_certificado = '" . $fechaAprobacionCertificado . "'
                     WHERE
                        id_certificado_fitosanitario = '" . $idCertificadoFitosanitario . "';";
        
        $res = $conexion->ejecutarConsulta($consulta);
        
        return $res;
    }
        
}
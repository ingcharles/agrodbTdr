<?php

class ControladorModificacionProductoRia
{
    public function obtenerSolicitudPorEstadoProvincia ($conexion, $estado, $provincia){
        
        if($estado == 'pago'){
            $condicion = " and sp.descuento = 'Si'";
        }
        
        $consulta = "SELECT
                        sp.id_solicitud_producto AS id_solicitud
                        , sp.identificador_operador AS identificador_operador
                        , sp.numero_solicitud AS numero_solicitud
                        , sp.fecha_creacion AS fecha_registro
					 FROM
						g_modificacion_productos.solicitudes_productos sp
                        INNER JOIN g_operadores.operadores o ON o.identificador = sp.identificador_operador
					 WHERE
						sp.estado_solicitud_producto = '" . $estado . "'
						and upper(o.provincia) = UPPER ('" . $provincia . "')
                        " . $condicion . ";";
        
        $res = $conexion->ejecutarConsulta($consulta);
        
        return $res;
    }
        
    public function abrirSolicitud ($conexion, $idSolicitud){
        
        $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_modificacion_productos.solicitudes_productos sp
											WHERE
												sp.id_solicitud_producto =  $idSolicitud;");
        return $res;
    }
    
    public function obtenerSolicitudesProductosPago ($conexion){
        
        $consulta = "SELECT
						*
					FROM
						g_modificacion_productos.solicitudes_productos
					WHERE
                        estado_solicitud_producto = 'pago'
                        and descuento is null;";
        
        $res = $conexion->ejecutarConsulta($consulta);
        
        return $res;
    }
    
    public function actualizarEstadoSolicitudPorIdSolicitudProducto ($conexion, $idSolicitudProducto, $estado){
        
        $consulta = "UPDATE
                        g_modificacion_productos.solicitudes_productos
                     SET
                        estado_solicitud_producto = '" . $estado . "'
                     WHERE
                        id_solicitud_producto = '" . $idSolicitudProducto . "';";
        
        $res = $conexion->ejecutarConsulta($consulta);
        
        return $res;
    }
    
    public function abrirSolicitudPorNumeroSolicitud ($conexion, $numeroSolicitud){
        
        $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_modificacion_productos.solicitudes_productos sp
											WHERE
												sp.numero_solicitud =  '" . $numeroSolicitud . "';");
        return $res;
    }
    
    public function abrirInformacionGeneralSolicitudPorIdSolicitud ($conexion, $idSolicitud){
        
        $res = $conexion->ejecutarConsulta("SELECT
                                            	a.nombre as nombre_area
                                            	, tp.nombre as nombre_tipo_producto
                                            	, stp.nombre as nombre_subtipo_producto
                                            	, p.nombre_comun as nombre_producto
                                            	, pi.numero_registro
                                            FROM
                                            	g_modificacion_productos.solicitudes_productos sp
                                            	INNER JOIN g_estructura.area a ON a.id_area = sp.id_area
                                            	INNER JOIN g_catalogos.tipo_productos tp ON tp.id_tipo_producto = sp.id_tipo_producto
                                            	INNER JOIN g_catalogos.subtipo_productos stp ON stp.id_subtipo_producto = sp.id_subtipo_producto
                                            	INNER JOIN g_catalogos.productos p ON p.id_producto = sp.id_producto
                                            	INNER JOIN g_catalogos.productos_inocuidad pi ON pi.id_producto = p.id_producto
                                            WHERE
                                            	sp.id_solicitud_producto = '" . $idSolicitud . "';");
        return $res;
    }

    public function obtenerIdentificadorAsignarAplicacion($conexion){
        $consulta = "SELECT 
                        distinct
                        t1.id_operador
                    FROM
                        (g_programas.aplicaciones_registradas ar
                        INNER JOIN g_programas.aplicaciones a ON a.id_aplicacion = ar.id_aplicacion and a.codificacion_aplicacion = 'PRG_MOD_PRODUCTO') as t
                        RIGHT JOIN (g_catalogos.productos_inocuidad pi
                         INNER JOIN g_usuario.usuarios u ON pi.id_operador = u.identificador) as t1 ON t.identificador = t1.id_operador
                    WHERE
                        t.identificador is null;";

        $res = $conexion->ejecutarConsulta($consulta);

        return $res;
    }
    
}
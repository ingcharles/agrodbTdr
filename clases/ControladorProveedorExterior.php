<?php
class ControladorProveedorExterior {
    
    public function obtenerDatosProveedorExteriorPorIdProveedorExterior($conexion, $idProveedorExterior){
        
        $consulta = "SELECT 
                    	id_proveedor_exterior
                    	, identificador_operador
                    	, id_provincia_operador
                    	, nombre_provincia_operador
                    	, nombre_fabricante
                    	, nombre_pais_fabricante
                    	, direccion_fabricante
                    	, servicio_oficial
                    	, codigo_creacion_solicitud
                    	, codigo_aprobacion_solicitud
                    	, estado_solicitud
                    	, identificador_revisor
                    	, fecha_aprobacion_solicitud
                    	, fecha_creacion_solicitud
                    FROM 
                    	g_proveedores_exterior.proveedor_exterior
                    WHERE
                    	id_proveedor_exterior = $idProveedorExterior";
        
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
        
    }
    
    public function obtenerDatosProductosProveedorExteriorPorIdProveedorExterior($conexion, $idProveedorExterior){
        
        $consulta = "SELECT 
                    	id_producto_proveedor
                    	, id_proveedor_exterior
                    	, id_subtipo_producto
                    	, nombre_subtipo_producto
                    	, fecha_creacion_producto_proveedor
                    FROM 
                    	g_proveedores_exterior.productos_proveedor
                    WHERE
                    	id_proveedor_exterior = $idProveedorExterior";
        
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
        
    }
    
    public function obtenerAdjuntosProveedorExteriorPorIdProveedorExteriorPorTipoAdjunto($conexion, $idProveedorExterior, $tipoAdjunto){
        
        $consulta = "SELECT 
                        id_documento_adjunto
                        , id_proveedor_exterior
                        , tipo_adjunto
                        , ruta_adjunto
                        , estado_adjunto
                    FROM 
                        g_proveedores_exterior.documentos_adjuntos
                    WHERE 
                        id_proveedor_exterior = $idProveedorExterior
                        and tipo_adjunto = '" . $tipoAdjunto . "'
                        and estado_adjunto = 'Activo'";
        
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
        
    }
    
}
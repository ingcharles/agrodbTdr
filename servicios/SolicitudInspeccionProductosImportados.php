<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 4/6/2017
 * Time: 17:38
 */
class SolicitudInspeccionProductosImportados extends Servicio
{
    public function ejecutarServicio($registro)
    {
		if ($this->provincia != null || $this->provincia != '') {													  
        $consulta = 'SELECT row_to_json(res) FROM ( SELECT array_to_json(array_agg(row_to_json(listado))) FROM (' .
            "SELECT
                    dda.permiso_importacion AS pfi,
                    --pi.estado,
                    dda.id_vue AS dda,
                    o.razon_social AS \"razonSocial\", -- Tarjeta #002.3
                    dda.pais_exportacion AS \"paisOrigen\",
                    dda.tipo_certificado AS \"tipoCertificado\",
                    (
						    SELECT array_to_json(array_agg(row_to_json(l_a))) FROM (
						        SELECT ip.nombre_producto as nombre, 
                                    ip.peso AS cantidad, 
                                    ip.unidad_peso AS unidad,
                                    sp.nombre AS \"subTipo\"
                                FROM g_importaciones.importaciones_productos ip,
                                g_catalogos.productos p,
                                g_catalogos.subtipo_productos sp,
                                g_dda.destinacion_aduanera_productos dap
                                WHERE pi.id_importacion = ip.id_importacion AND 
                                ip.id_producto = p.id_producto AND 
                                p.id_subtipo_producto = sp.id_subtipo_producto AND
                                dap.id_destinacion_aduanera = dda.id_destinacion_aduanera AND
                                ip.id_producto = dap.id_producto
					) l_a) AS \"productoList\"
                FROM
                    g_dda.destinacion_aduanera dda
					INNER JOIN g_catalogos.lugares_inspeccion li ON dda.lugar_inspeccion = li.id_lugar,
                    g_importaciones.importaciones pi,
                    g_operadores.operadores o
                WHERE
                    pi.id_vue = dda.permiso_importacion AND
                    pi.identificador_operador = o.identificador AND
                    dda.contador_inspeccion = 1 AND -- Tarjeta #002.2
                    dda.estado = 'inspeccion' AND
                    dda.tipo_certificado IN ('VEGETAL','ANIMAL') AND
					upper(li.nombre_provincia) = upper('" . $this->provincia . "')"
            . ') as listado ) AS res;';
        return pg_fetch_assoc($this->conexion->ejecutarConsulta($consulta));
		} else {
    		throw new Exception('Error: ¡No se ha definido provincia!');
    	}
    }
}
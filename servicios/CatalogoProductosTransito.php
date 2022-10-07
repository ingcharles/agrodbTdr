<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 4/6/2017
 * Time: 17:38
 *
 * Catálogo para formulario 02
 * Lista los productos que están autorizados para tránsito con sus partidas arancelarias
 *
 */
class CatalogoProductosTransito extends Servicio
{
    public function ejecutarServicio($registro)
    {
        $consulta = 'SELECT row_to_json(res) FROM ( SELECT array_to_json(array_agg(row_to_json(listado))) FROM (' .
            "SELECT 
               DISTINCT p.partida_arancelaria AS \"partidaArancelaria\",
               p.nombre_comun AS \"nombreComun\",
               sp.nombre AS \"subtipo\"
            FROM 
               g_catalogos.tipo_productos tp, 
               g_catalogos.subtipo_productos sp, 
               g_catalogos.productos p, 
               g_requisitos.requisitos r, 
               g_requisitos.requisitos_comercializacion rc, 
               g_requisitos.requisitos_asignados ra 
            WHERE 
               p.id_subtipo_producto = sp.id_subtipo_producto and 
               sp.id_tipo_producto = tp.id_tipo_producto and 
               rc.id_producto = p.id_producto and 
               ra.id_requisito_comercio = rc.id_requisito_comercio and 
               ra.requisito = r.id_requisito and 
               r.id_area = 'SV' and 
               r.tipo = 'Tránsito' and 
               r.estado = 1 and 
               ra.estado = 'activo' 
            ORDER BY 
            1 "
            . ') as listado ) AS res;';
        return pg_fetch_assoc($this->conexion->ejecutarConsulta($consulta));
    }
}
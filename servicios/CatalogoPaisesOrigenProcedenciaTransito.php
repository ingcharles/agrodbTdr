<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 4/6/2017
 * Time: 17:38
 *
 * Catálogo para formulario 02
 * Lista los paises de origen / procedencia segun los requisitos de comercialización de productos
 *
 */
class CatalogoPaisesOrigenProcedenciaTransito extends Servicio
{
    public function ejecutarServicio($registro)
    {
        $consulta = 'SELECT row_to_json(res) FROM ( SELECT array_to_json(array_agg(row_to_json(listado))) FROM (' .
            "SELECT 
               distinct rc.id_localizacion AS \"idPais\",
                rc.nombre_pais AS \"nombre\"
            FROM 
               g_catalogos.productos p, 
               g_requisitos.requisitos r, 
               g_requisitos.requisitos_comercializacion rc, 
               g_requisitos.requisitos_asignados ra
            WHERE 
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
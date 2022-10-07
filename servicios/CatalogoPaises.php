<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 4/6/2017
 * Time: 17:38
 *
 * Catálogo para formulario
 *  CONTROL 02,
 *  CONTROL 03
 *
 * Lista los paises
 *
 */
class CatalogoPaises extends Servicio
{
    public function ejecutarServicio($registro)
    {
        $consulta = 'SELECT row_to_json(res) FROM ( SELECT array_to_json(array_agg(row_to_json(listado))) FROM (' .
            "SELECT 
                l.id_localizacion AS \"idPais\",
                upper(l.nombre) as nombre
            FROM 
               g_catalogos.localizacion l
            WHERE 
               l.categoria = 0 
            ORDER BY 
            1 "
            . ') as listado ) AS res;';
        return pg_fetch_assoc($this->conexion->ejecutarConsulta($consulta));
    }
}
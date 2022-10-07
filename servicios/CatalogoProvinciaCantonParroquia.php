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
class CatalogoProvinciaCantonParroquia extends Servicio
{
    public function ejecutarServicio($registro)
    {
        $consulta = 'SELECT row_to_json(res) FROM ( SELECT array_to_json(array_agg(row_to_json(listado))) FROM (' .
            "SELECT 
               l.id_localizacion as \"idGuia\",
               upper(l.nombre) as nombre,
               (SELECT array_to_json(array_agg(row_to_json(listado_2))) FROM (
                SELECT 
                   lc.id_localizacion as \"idGuia\",
                   upper(lc.nombre) as nombre,
                       (SELECT array_to_json(array_agg(row_to_json(listado_3))) FROM (
                        SELECT 
                           lp.id_localizacion as \"idGuia\",
                           upper(lp.nombre) as nombre
                        FROM 
                           g_catalogos.localizacion lp
                        WHERE 
                           lp.id_localizacion_padre = lc.id_localizacion
                           AND lp.categoria = 4 --Categoría de parroquia 
                        ORDER BY 
                        2 ) AS listado_3	
                       ) AS \"parroquiaList\"
                FROM 
                   g_catalogos.localizacion lc
                WHERE 
                   lc.id_localizacion_padre = l.id_localizacion
                   AND lc.categoria = 2 --Categoría de cantón 
                ORDER BY 
                2 ) AS listado_2	
               ) AS \"cantonList\"
            FROM 
               g_catalogos.localizacion l
            WHERE 
               l.categoria = 1 --Categoría de país 
            ORDER BY 
            2 "
            . ') as listado ) AS res;';
        return pg_fetch_assoc($this->conexion->ejecutarConsulta($consulta));
    }
}
<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 4/6/2017
 * Time: 17:38
 *
 * Catálogo para formulario
 *  CONTROL 02
 *  CONTROL 03
 * Lista los puerto para entrada o salida de Ecuador
 *
 */
class CatalogoCodigosProvinciaCanton extends Servicio
{
    public function ejecutarServicio($registro)
    {
        $consulta = 'SELECT row_to_json(res) FROM ( SELECT array_to_json(array_agg(row_to_json(listado))) FROM (' .
            "   SELECT
                    p.nombre_puerto AS \"nombre\",
                    (
						    SELECT array_to_json(array_agg(row_to_json(l_a))) FROM (
                                SELECT
                                    li.nombre
                                FROM
                                    g_catalogos.lugares_inspeccion li
                                WHERE
                                    li.id_puerto = p.id_puerto
					) l_a) AS \"lugarInspeccionList\"
                FROM
                    g_catalogos.puertos p
                WHERE
                    p.id_pais = 66 -- Código para Ecuador
                    AND p.nombre_provincia IS NOT NULL
                ORDER BY
                    1"
            . ') as listado ) AS res;';
        return pg_fetch_assoc($this->conexion->ejecutarConsulta($consulta));
    }
}
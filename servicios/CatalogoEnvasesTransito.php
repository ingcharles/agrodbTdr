<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 4/6/2017
 * Time: 17:38
 *
 * Catálogo para formulario 02
 * Lista los puerto para entrada o salida de Ecuador
 *
 */
class CatalogoEnvasesTransito extends Servicio
{
    public function ejecutarServicio($registro)
    {
        $consulta = 'SELECT row_to_json(res) FROM ( SELECT array_to_json(array_agg(row_to_json(listado))) FROM (' .
            "   SELECT
                    te.nombre_envase AS \"nombre\"
                FROM
                    g_catalogos.tipos_envase te
                WHERE
                    te.estado = 'activo' AND te.id_area = 'SV' -- Código para Ecuador
                ORDER BY
                    1"
            . ') as listado ) AS res;';
        return pg_fetch_assoc($this->conexion->ejecutarConsulta($consulta));
    }
}
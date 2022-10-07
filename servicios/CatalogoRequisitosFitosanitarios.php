<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 4/6/2017
 * Time: 17:38
 *
 *
 */
class CatalogoRequisitosFitosanitarios extends Servicio
{
    public function ejecutarServicio($registro)
    {
        if ($this->provincia != null || $this->provincia != '') {
            $consulta = 'SELECT row_to_json(res) FROM ( SELECT array_to_json(array_agg(row_to_json(listado))) FROM (' .
                "
                
                "
                . ') AS listado ) AS res;';

            return pg_fetch_assoc($this->conexion->ejecutarConsulta($consulta));
        } else {
            throw new Exception('Error: ¡No se ha definido provincia!');
        }
    }
}
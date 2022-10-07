<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 4/6/2017
 * Time: 17:38
 *
 * Catálogo para formulario Certificación f12
 * lugares donde se hacen tratamiento en una provincia
 *
 */
class CatalogoPlantasTratamiento extends Servicio
{
    public function ejecutarServicio($registro)
    {
        if ($this->provincia != null || $this->provincia != '') {
            $consulta = 'SELECT row_to_json(res) FROM ( SELECT array_to_json(array_agg(row_to_json(listado))) FROM (' .
                "SELECT
                distinct a.id_area AS \"idArea\",
                 a.nombre_area AS area,
                 s.nombre_lugar AS sitio,
                 a.id_sitio AS \"idSitio\",
                 s.direccion,
                 s.parroquia,
                 s.canton,
                 s.provincia,
                 s.identificador_operador AS \"rucCi\"
             FROM
                 g_operadores.operadores o,
                 g_operadores.operaciones op,
                 g_catalogos.tipos_operacion tp,
                 g_operadores.productos_areas_operacion pao,
                 g_operadores.areas a,
                 g_operadores.sitios s
             WHERE
                 o.identificador = op.identificador_operador
                 AND op.id_tipo_operacion = tp.id_tipo_operacion
                 AND op.id_operacion = pao.id_operacion
                 AND pao.id_area = a.id_area
                 AND a.id_sitio = s.id_sitio
                 AND tp.id_area = 'SV'
                 AND tp.codigo = 'TRA'
			     AND a.estado = 'creado'
                 AND op.estado not in ('noHabilitado')
                 AND upper(s.provincia) = upper('" . $this->provincia . "')
             ORDER BY
                 a.nombre_area"
                . ') AS listado ) AS res;';
            return pg_fetch_assoc($this->conexion->ejecutarConsulta($consulta));
        } else {
            throw new Exception('Error: ¡No se ha definido provincia!');
        }
    }
}

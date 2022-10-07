<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 4/6/2017
 * Time: 17:38
 *
 * Catálogo para formulario Certificación12
 * Lista operadores que poseen la actividad de acopiadores en sanidad vegetal en una provincia
 *
 */
class CatalogoProductosFlorFollaje extends Servicio
{
    public function ejecutarServicio($registro)
    {
        if ($this->provincia != null || $this->provincia != '') {
            $consulta = 'SELECT row_to_json(res) FROM ( SELECT array_to_json(array_agg(row_to_json(listado))) FROM (' .
                    "SELECT
                        distinct o.identificador AS \"rucCi\",
                        s.id_sitio AS \"idSitio\", 
                        a.id_area  AS \"idArea\",  
                        pr.id_producto AS \"idProducto\",  
                        pr.nombre_comun  AS \"nombreComun\", 
                        pr.nombre_cientifico AS \"nombreCientifico\",
                        stp.id_subtipo_producto  AS \"idSubtipo\",
                        stp.nombre  AS \"subtipo\", 
                        tpr.id_tipo_producto AS \"idTipo\",
                        tpr.nombre  AS \"tipo\"
                    FROM
                         g_operadores.operadores o, 
                         g_operadores.operaciones op,  
                         g_catalogos.productos pr, 
                         g_catalogos.subtipo_productos stp, 
                         g_catalogos.tipo_productos tpr,
                         g_catalogos.tipos_operacion tp, 
                         g_operadores.productos_areas_operacion pao, 
                         g_operadores.areas a, g_operadores.sitios s
                    WHERE
                         o.identificador = op.identificador_operador  and  
                         op.id_producto = pr.id_producto and  
                         pr.id_subtipo_producto = stp.id_subtipo_producto and 
                         stp.id_tipo_producto = tpr.id_tipo_producto and 
                         op.id_tipo_operacion = tp.id_tipo_operacion and 
                         op.id_operacion = pao.id_operacion and
                         pao.id_area = a.id_area and 
                         a.id_sitio = s.id_sitio and 
                         tp.id_area = 'SV' and  
                         tp.codigo = 'ACO' and
                         tpr.codificacion_tipo_producto = 'PRD_FLO_FOLL_COR' and
                         upper(s.provincia) = upper('" . $this->provincia . "')
                     ORDER BY
                         o.identificador, pr.nombre_comun"
                        . ') AS listado ) AS res;';

            return pg_fetch_assoc($this->conexion->ejecutarConsulta($consulta));
        } else {
            throw new Exception('Error: ¡No se ha definido provincia!');
        }
    }
}
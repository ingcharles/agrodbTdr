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
class CatalogoProductoresMango extends Servicio
{
    public function ejecutarServicio($registro)
    {
        if($this->provincia != null || $this->provincia != '') {
            $consulta = 'SELECT row_to_json(res) FROM ( SELECT array_to_json(array_agg(row_to_json(listado))) FROM (' .
                "   SELECT 
                     distinct o.identificador AS \"rucCi\",
                     case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre 
                FROM
                     g_operadores.operadores o, 
                     g_operadores.operaciones op, 
                     g_catalogos.tipos_operacion tp, 
                     g_operadores.productos_areas_operacion pao, 
                     g_operadores.areas a, g_operadores.sitios s,
                     g_catalogos.productos pr, 
                     g_catalogos.subtipo_productos stp, 
                     g_catalogos.tipo_productos tpr
                WHERE
                     o.identificador = op.identificador_operador  AND 
                     op.id_tipo_operacion = tp.id_tipo_operacion AND 
                     op.id_operacion = pao.id_operacion AND 
                     pao.id_area = a.id_area AND 
                     a.id_sitio = s.id_sitio AND 
                     op.id_producto = pr.id_producto AND 
                     pr.id_subtipo_producto = stp.id_subtipo_producto AND 
                     stp.id_tipo_producto = tpr.id_tipo_producto AND 
                     tp.id_area = 'SV' AND  
                     tp.codigo = 'PRO' AND 
                     upper(s.provincia) = upper('" . $this->provincia . "') AND 
                     tpr.codificacion_tipo_producto = 'PRD_FR_HO_TU_FRE'  
                     and pr.nombre_comun ilike '%Mango%'
                ORDER BY
                    o.identificador"
                . ') AS listado ) AS res;';

            return pg_fetch_assoc($this->conexion->ejecutarConsulta($consulta));
        } else {
            throw new Exception('Error: ¡No se ha definido provincia!');
        }
    }
    //TODO: descomentar la línea del query cuando ya hayan productores de piña
}
<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 4/6/2017
 * Time: 17:38
 *
 * Catálogo para formulario Certificación12
 * Lista sitios y centros de acopio en una provincia
 *
 */
class CatalogoSitiosProduccionMango extends Servicio
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
                 g_operadores.sitios s,
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
                 pr.id_subtipo_producto = stp.id_subtipo_producto  AND 
                 stp.id_tipo_producto = tpr.id_tipo_producto AND 
                 tp.id_area = 'SV' AND  
                 tp.codigo = 'PRO' AND 
                 upper(s.provincia) = upper('" . $this->provincia . "') AND
                 tpr.codificacion_tipo_producto = 'PRD_FR_HO_TU_FRE'  
                 AND pr.nombre_comun ilike '%Mango%'
             ORDER BY
                 a.nombre_area"
                . ') AS listado ) AS res;';
            return pg_fetch_assoc($this->conexion->ejecutarConsulta($consulta));
            //TODO: descomentar la línea del query cuando ya hayan productores de piña
        } else {
            throw new Exception('Error: ¡No se ha definido provincia!');
        }
    }
}
<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 4/6/2017
 * Time: 17:38
 */
class SolicitudSeguimientosCuarentenarios extends Servicio
{
    public function ejecutarServicio($registro)
    {
        $consulta = 'SELECT row_to_json(res) FROM ( SELECT array_to_json(array_agg(row_to_json(listado))) FROM (' .
            "SELECT
                    sc.id_seguimiento_cuarentenario AS \"idSeguimientoCuarentenario\", 
                    da.identificador_operador AS \"rucOperador\",
                    case when razon_social = '' then nombre_representante ||' '|| apellido_representante else razon_social end \"razonSocial\",
                    sc.numero_seguimientos AS \"numeroSeguimientosPlanificados\",
                    sc.numero_plantas AS \"numeroPlantasIngreso\",
                    da.id_pais_exportador AS \"codigoPaisOrigen\",
                    da.pais_exportacion AS \"paisOrigen\",
					da.id_vue AS \"numeroPermisoDDA\",
                    (
                        SELECT array_to_json(array_agg(row_to_json(l_a))) FROM (
                            SELECT 
                                ddap.id_producto as \"idProducto\",
                                ddap.nombre_producto as producto, 
                                ddap.peso, 
                                ddap.unidad_peso AS unidad,
                                sp.nombre AS \"subtipo\"
                            FROM 
                                g_dda.destinacion_aduanera_productos ddap,
                                g_catalogos.productos p,
                                g_catalogos.subtipo_productos sp
                            WHERE 
                                ddap.id_destinacion_aduanera = da.id_destinacion_aduanera AND 
                                ddap.id_producto = p.id_producto AND 
                                p.id_subtipo_producto = sp.id_subtipo_producto
                        ) l_a
                    ) AS \"solicitudControlF04ProductoList\"
                FROM
                    g_seguimiento_cuarentenario.seguimientos_cuarentenarios sc,
                    g_dda.destinacion_aduanera da,
					g_operadores.operadores op
                WHERE
                    sc.id_destinacion_aduanera = da.id_destinacion_aduanera AND
					da.identificador_operador = op.identificador AND
                    sc.estado = 'abierto'"
            . ') as listado ) AS res;';
        return pg_fetch_assoc($this->conexion->ejecutarConsulta($consulta));
    }
}
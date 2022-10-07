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
class CatalogoRutasTrampasVigilancia extends Servicio
{
    public function ejecutarServicio($registro)
    {
        if ($this->provincia != null || $this->provincia != '') {
            $consulta = 'SELECT row_to_json(res) FROM ( SELECT array_to_json(array_agg(row_to_json(listado))) FROM (' .
                "   SELECT 
                    adt.id_provincia AS \"idProvincia\",
                    lpr.nombre AS provincia,
                    adt.id_canton AS \"idCanton\",
                    lca.nombre AS canton,
                    adt.id_parroquia AS \"idParroquia\",
                    lpa.nombre AS parroquia,
                    adt.id_lugar_instalacion AS \"idLugarInstalacion\",
                    li.nombre_lugar_instalacion AS \"lugarInstalacion\",
                    adt.numero_lugar_instalacion AS \"numeroLugarInstalacion\",
                    tt.nombre_tipo_trampa AS \"tipoTrampa\",
                    adt.codigo_trampa  AS \"codigoTrampa\",
                    adt.coordenadax AS \"coordenadaX\",
                    adt.coordenaday AS \"coordenadaY\",
                    adt.coordenadaz AS \"coordenadaZ\",
                    to_char(adt.fecha_instalacion_trampa, 'YYYY-MM-DD') AS \"fechaInstalacion\",
                    adt.estado_trampa AS \"estadoTrampa\",
                    pl.nombre_plaga AS \"nombrePlaga\"
                FROM
                    g_administracion_trampas.administracion_trampas adt
					INNER JOIN g_catalogos.plaga pl ON adt.id_plaga = pl.id_plaga,
                    g_catalogos.localizacion lpr,
                    g_catalogos.localizacion lca,
                    g_catalogos.localizacion lpa,
                    g_catalogos.lugar_instalacion li,
                    g_catalogos.tipo_trampa tt
                WHERE
                    adt.id_area_trampa = 1 -- Área de vigilancia
                    AND lpr.id_localizacion = adt.id_provincia
                    AND lca.id_localizacion = adt.id_canton
                    AND lpa.id_localizacion = adt.id_parroquia
                    AND li.id_lugar_instalacion = adt.id_lugar_instalacion
                    AND tt.id_tipo_trampa = adt.id_tipo_trampa
                    AND adt.estado_trampa = 'activo'
                    AND upper(lpr.nombre) = upper('$this->provincia')"
                . ') as listado ) AS res;';
            return pg_fetch_assoc($this->conexion->ejecutarConsulta($consulta));
        } else {
            throw new Exception('Error: ¡No se ha definido provincia!');
        }
    }
}
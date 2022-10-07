<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 4/6/2017
 * Time: 17:38
 */
class FormularioIngresosTransito extends Servicio
{
    public function ejecutarServicio($registro)
    {
    	if ($this->provincia != null || $this->provincia != '') {
        $consulta = 'SELECT row_to_json(res) FROM ( SELECT array_to_json(array_agg(row_to_json(listado))) FROM (' .
                "SELECT                
                    cf02.id AS \"idIngreso\",
                    cf02.nombre_razon_social AS \"nombreRazonSocial\",
                    cf02.ruc_ci AS \"rucCi\",
                    cf02.pais_origen AS \"paisOrigen\",
                    cf02.pais_procedencia AS \"paisProcedencia\",
                    cf02.pais_destino AS \"paisDestino\",
                    cf02.punto_ingreso AS \"puntoIngreso\",
                    cf02.punto_salida AS \"puntoSalida\",
                    cf02.placa_vehiculo AS \"placaVehiculo\",
                    cf02.dda,
                    cf02.precinto_sticker AS \"precintoSticker\",
                    to_char(cf02.fecha_ingreso, 'YYYY-MM-DD') AS \"fechaIngreso\",
                    ( 
                    SELECT array_to_json(array_agg(row_to_json(l_a))) FROM (
                        SELECT
                            cf02dp.id,
                            cf02dp.partida_arancelaria AS \"partidaArancelaria\",
                            cf02dp.producto  AS \"descripcionProducto\",
                            cf02dp.cantidad,
                            cf02dp.tipo_envase AS \"tipoEnvase\"
                        FROM
                            f_inspeccion.controlf02_detalle_productos cf02dp
                        WHERE
                            cf02.id = cf02dp.id_padre
					) l_a) AS \"formularioIngresoTransitoProductoList\"
                FROM
                    f_inspeccion.controlf02 cf02 
					INNER JOIN g_catalogos.puertos pu ON cf02.id_punto_salida::Integer = pu.id_puerto
                WHERE
                    cf02.estado = 'Ingreso' and 
					upper(pu.nombre_provincia) = upper('" . $this->provincia . "')"
            . ') as listado ) AS res;';
        return pg_fetch_assoc($this->conexion->ejecutarConsulta($consulta));
    	} else {
    		throw new Exception('Error: ¡No se ha definido provincia!');
    	}
    }
}
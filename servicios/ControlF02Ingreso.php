<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 9/6/2017
 * Time: 22:33
 */
class ControlF02Ingreso extends Servicio
{
    private $tabla = 'f_inspeccion.controlf02';
    private $tablaDetalleProductos = '_detalle_productos';

    public function ejecutarServicio($registro)
    {
        $campos = array(
            'id_tablet' => $registro->id,
            'nombre_razon_social' => $registro->nombreRazonSocial,
            'ruc_ci' => $registro->rucCi,
            'id_pais_origen' => $registro->idPaisOrigen,
            'pais_origen' => $registro->paisOrigen,
            'id_pais_procedencia' => $registro->idPaisProcedencia,
            'pais_procedencia' => $registro->paisProcedencia,
            'id_pais_destino' => $registro->idPaisDestino,
            'pais_destino' => $registro->paisDestino,
            'id_punto_ingreso' => $registro->idPuntoIngreso,
            'punto_ingreso' => $registro->puntoIngreso,
            'id_punto_salida' => $registro->idPuntoSalida,
            'punto_salida' => $registro->puntoSalida,
            'placa_vehiculo' => $registro->placaVehiculo,
            'dda' => $registro->dda,
            'precinto_sticker' => $registro->precintoSticker,
            'estado' => 'Ingreso',

            'fecha_ingreso' => $registro->fechaIngreso,
            'usuario_id_ingreso' => $registro->usuarioIdIngreso,
            'usuario_ingreso' => $registro->usuarioIngreso,
            'tablet_id_ingreso' => $this->tabletId,
            'tablet_version_base_ingreso' => $this->databaseVersion,
        );

        $id = pg_fetch_row($this->conexion->ejecutarConsulta(
            $this->construirConsulta(
                $this->tabla,
                $campos
            )
        )
        );
        if ($id != null || $id != '') {
            $this->ejecutarServicioDetalleProductos($id[0], $registro->controlF02ProductoList);
        } /*else {
            throw new Exception('Error: ¡Existen datos duplicados!');
        }*/
    }


    private function ejecutarServicioDetalleProductos($id, $productos)
    {
        foreach ($productos as $producto) {
            $campos = array(
                'id_padre' => $id,
                'id_tablet' => $producto->id,
                'partida_arancelaria' => $producto->partidaArancelaria,
                'producto' => $producto->descripcionProducto,
                'subtipo' => $producto->subtipo,
                'cantidad' => $producto->cantidad,
                'tipo_envase' => $producto->tipoEnvase,
            );
            $this->conexion->ejecutarConsulta(
                $this->construirConsulta(
                    $this->tabla . $this->tablaDetalleProductos,
                    $campos
                )
            );
        }
    }

}
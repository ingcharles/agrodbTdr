<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 9/6/2017
 * Time: 22:33
 */
class MoscaF03 extends Servicio
{
    private $tabla = 'f_inspeccion.moscaf03';
    private $tablaDetalleOrdenes = 'f_inspeccion.moscaf03_detalle_ordenes';

    public function ejecutarServicio($registro)
    {
        $campos = array(
            'id_tablet' => $registro->id,
            'codigo_provincia' => $registro->codigoProvincia,
            'nombre_provincia' => $registro->provincia,
            'codigo_canton' => $registro->codigoCanton,
            'nombre_canton' => $registro->canton,
            'codigo_parroquia' => $registro->codigoParroquia,
            'nombre_parroquia' => $registro->parroquia,
            'codigo_lugar_muestreo' => $registro->codigoLugarMuestreo,
            'nombre_lugar_muestreo' => $registro->lugarMuestreo,
            'semana' => $registro->semana,
            'coordenada_x' => $registro->coordenadaX,
            'coordenada_y' => $registro->coordenadaY,
            'coordenada_z' => $registro->coordenadaZ,
            'fecha_inspeccion' => $registro->fechaCreacion,
            'usuario_id' => $registro->usuarioId,
            'usuario' => $registro->usuario,
            'tablet_id' => $this->tabletId,
            'tablet_version_base' => $this->databaseVersion,
        );
        $id = pg_fetch_row(
            $this->conexion->ejecutarConsulta(
                $this->construirConsulta(
                    $this->tabla,
                    $campos
                )
            )
        );
        if($id != null || $id != ''){
            $this->ejecutarServicioDetalleOrdenesLaboratorio($id[0], $registro->moscaF03OrdenList);
        } /*else {
            throw new Exception('Error: ¡Existen datos duplicados!');
        }*/
    }

    private function ejecutarServicioDetalleOrdenesLaboratorio($id, $ordenes)
    {
        foreach ($ordenes as $orden) {
            $campos = array(
                'id_padre' => $id,
                'id_tablet' => $orden->id,
                'aplicacion_producto_quimico' => $orden->aplicacionProductoQuimico,
                'codigo_muestra' => $orden->codigoMuestra,
                'descripcion_sintomas' => $orden->descripcionSintomas,
                'especie_vegetal' => $orden->especie,
                'sitio_muestreo' => $orden->sitioMuestreo,
                'numero_frutos_colectados' => $orden->numeroFrutosColectados,
            );
            $this->conexion->ejecutarConsulta(
                $this->construirConsulta(
                    $this->tablaDetalleOrdenes,
                    $campos
                )
            );
        }
    }
}
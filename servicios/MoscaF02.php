<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 9/6/2017
 * Time: 22:33
 */
class MoscaF02 extends Servicio
{
    private $tabla = 'f_inspeccion.moscaf02';

    public function ejecutarServicio($registro)
    {
        $campos = array(
            'id_tablet' => $registro->id,
            'nombre_asociacion_productor' => $registro->nombreAsociacionProductor,
            'identificador' => $registro->identificador,
            'telefono' => $registro->telefono,
            'codigo_provincia' => $registro->codigoProvincia,
            'provincia' => $registro->provincia,
            'codigo_canton' => $registro->codigoCanton,
            'canton' => $registro->canton,
            'codigo_parroquia' => $registro->codigoParroquia,
            'parroquia' => $registro->parroquia,
            'sitio' => $registro->sitio,
            'especie' => $registro->especie,
            'variedad' => $registro->variedad,
            'area_produccion_estimada' => $registro->areaProduccionEstimada,
            'coordenada_x' => $registro->coordenadaX,
            'coordenada_y' => $registro->coordenadaY,
            'coordenada_z' => $registro->coordenadaZ,
            'observaciones' => $registro->observaciones,
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
        }/* else {
            throw new Exception('Error: ¡Existen datos duplicados!');
        }*/

    }
}
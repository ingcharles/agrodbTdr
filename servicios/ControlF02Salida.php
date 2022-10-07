<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 9/6/2017
 * Time: 22:33
 */
class ControlF02Salida extends Servicio
{
    private $tabla = 'f_inspeccion.controlf02';

    public function ejecutarServicio($registro)
    {
        $campos = array(
            'estado_precinto' => $registro->estadoPrecinto,
            'tipo_verificacion' => $registro->tipoVerificacion,
            'estado' => 'Salida',
            'fecha_salida' => $registro->fechaSalida,
            'usuario_id_salida' => $registro->usuarioIdSalida,
            'usuario_salida' => $registro->usuarioSalida,
            'tablet_id_salida' => $this->tabletId,
            'tablet_version_base_salida' => $this->databaseVersion,
        );

        $condiciones = array(
            'id' => $registro->idIngreso,
        );

        $id = pg_fetch_row(
            $this->conexion->ejecutarConsulta(
                $this->construirActualizacion(
                    $this->tabla,
                    $campos,
                    $condiciones
                )
            )
        );
        if ($id != null || $id != '') {

        } else {
            throw new Exception('Error: ¡No existe registro a actualizar!');
        }
    }
}
<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 9/6/2017
 * Time: 22:33
 */
class CertificacionF06 extends Servicio
{
    private $tabla = 'f_inspeccion.certificacionf06';
    //private $tablaDetalleOrdenes = 'f_inspeccion.certificacionf02_detalle_ordenes';

    public function ejecutarServicio($registro)
    {
        $campos = array(
            'id_tablet' => $registro->id,
            'numero_reporte' => $registro->numeroReporte,
            'razon_social' => $registro->razonSocial,
            'id_provincia' => $registro->idProvincia,
            'provincia' => $registro->provincia,
            'id_canton' => $registro->idCanton,
            'canton' => $registro->canton,
            'id_parroquia' => $registro->idParroquia,
            'parroquia' => $registro->parroquia,
            'cultivo' => $registro->cultivo,
            'pregunta1' => $registro->pregunta1,
            'pregunta2' => $registro->pregunta2,
            'pregunta3' => $registro->pregunta3,
            'pregunta4' => $registro->pregunta4,
            'pregunta5' => $registro->pregunta5,
            'pregunta6' => $registro->pregunta6,
            'pregunta7' => $registro->pregunta7,
            'pregunta8' => $registro->pregunta8,
            'pregunta9' => $registro->pregunta9,
            'pregunta10' => $registro->pregunta10,
            'pregunta11' => $registro->pregunta11,
            'pregunta12' => $registro->pregunta12,
            'pregunta13' => $registro->pregunta13,
            'pregunta14' => $registro->pregunta14,
            'pregunta15' => $registro->pregunta15,
            'pregunta16' => $registro->pregunta16,
            'pregunta17' => $registro->pregunta17,
            'pregunta18' => $registro->pregunta18,
            'pregunta19' => $registro->pregunta19,
            'pregunta20' => $registro->pregunta20,
            'pregunta21' => $registro->pregunta21,
            'representante' => $registro->representante,
            'resultado' => $registro->resultado,
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
        if ($id != null || $id != '') {
            //$this->ejecutarServicioDetalleOrdenesLaboratorio($id[0], $registro->certificacionF02OrdenList);
        } /*else {
            throw new Exception('Error: ¡Existen datos duplicados!');
        }*/
    }

    /*private function ejecutarServicioDetalleOrdenesLaboratorio($id, $ordenes)
    {
        foreach ($ordenes as $orden) {
            $campos = array(
                'id_padre' => $id,
                'id_tablet' => $orden->id,
                //'actividad_origen' => $orden->actividadOrigen,
                'analisis' => $orden->analisisSolicitado,
                'codigo_muestra' => $orden->codigoMuestra,
                'conservacion' => $orden->conservacion,
                'tipo_muestra' => $orden->tipoMuestra,
                //'descripcion_sintomas' => $orden->descripcionSintomas,
                //'fase_fenologica' => $orden->faseFenologica,
                //'nombre_producto' => $orden->nombreProducto,
                //'peso_muestra' => $orden->pesoMuestra,
                //'prediagnostico' => $orden->prediagnostico,
                //'tipo_cliente' => $orden->tipoCliente,
            );
            $this->conexion->ejecutarConsulta(
                $this->construirConsulta(
                    $this->tablaDetalleOrdenes,
                    $campos
                )
            );
        }
    }*/

}
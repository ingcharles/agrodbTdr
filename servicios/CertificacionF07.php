<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 9/6/2017
 * Time: 22:33
 */
class CertificacionF07 extends Servicio
{
    private $tabla = 'f_inspeccion.certificacionf07';
    //private $tablaDetalleOrdenes = 'f_inspeccion.certificacionf02_detalle_ordenes';

    public function ejecutarServicio($registro)
    {
        $campos = array(
            'id_tablet' => $registro->id,
            'numero_reporte' => $registro->numeroReporte,
            'ruc' => $registro->ruc,
            'razon_social' => $registro->razonSocial,
            'provincia' => $registro->provincia,
            'canton' => $registro->canton,
            'parroquia' => $registro->parroquia,
            'id_sitio_produccion' => $registro->idSitioProduccion,
            'sitio_produccion' => $registro->sitioProduccion,
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
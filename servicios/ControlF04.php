<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 9/6/2017
 * Time: 22:33
 */
class ControlF04 extends Servicio
{
    private $tabla = 'f_inspeccion.controlf04';
    private $tablaDetalleOrdenes = 'f_inspeccion.controlf04_detalle_ordenes';

    public function ejecutarServicio($registro)
    {
        $campos = array(
            'id_tablet' => $registro->id,
            'id_seguimiento_cuarentenario' => $registro->idSeguimientoCuarentenario,
            'ruc_operador' => $registro->rucOperador,
            'razon_social' => $registro->razonSocial,
            'codigo_pais_origen' => $registro->codigoPaisOrigen,
            'pais_origen' => $registro->paisOrigen,
            'producto' => $registro->producto,
            'subtipo_producto' => $registro->subtipo,
            'peso' => $registro->peso,
            'numero_plantas_ingreso' => $registro->numeroPlantasIngreso,
            'codigo_provincia' => $registro->codigoProvincia,
            'provincia' => $registro->provincia,
            'codigo_canton' => $registro->codigoCanton,
            'canton' => $registro->canton,
            'codigo_parroquia' => $registro->codigoParroquia,
            'parroquia' => $registro->parroquia,
            'nombre_scpe' => $registro->nombreScpe,
            'tipo_operacion' => $registro->tipoOperacion,
            'tipo_cuarentena_condicion_produccion' => $registro->tipoCuarentenaCondicionProduccion,
            'fase_seguimiento' => $registro->faseSeguimiento,
            'codigo_lote' => $registro->codigoLote,
            'numero_seguimientos_planificados' => $registro->numeroSeguimientosPlanificados,
            'cantidad_total' => $registro->cantidadTotal,
            'cantidad_vigilada' => $registro->cantidadVigilada,
            'actividad' => $registro->actividad,
            'etapa_cultivo' => $registro->etapaCultivo,
            'registro_monitoreo_plagas' => $registro->registroMonitoreoPlagas,
            'ausencia_plagas' => $registro->ausenciaPlagas,
            'cantidad_afectada' => $registro->cantidadAfectada,
            'porcentaje_incidencia' => $registro->porcentajeIncidencia,
            'porcentaje_severidad' => $registro->porcentajeSeveridad,
            'fase_desarrollo_plaga' => $registro->faseDesarrolloPlaga,
            'organo_afectado' => $registro->organoAfectado,
            'distribucion_plaga' => $registro->distribucionPlaga,
            'poblacion' => $registro->poblacion,
            'descripcion_sintomas' => $registro->descripcionSintomas,
            'envio_muestra' => $registro->envioMuestra,
            'resultado_inspeccion' => $registro->resultadoInspeccion,
            'numero_plantas_inspeccion' => $registro->numeroPlantasInspeccion,
            'observaciones' => $registro->observaciones,
            'dicatamen_final' => $registro->dictamenFinal,
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
            $this->ejecutarServicioDetalleOrdenesLaboratorio($id[0], $registro->controlF04OrdenList);
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
                'analisis' => $orden->analisisSolicitado,
                'codigo_muestra' => $orden->codigoMuestra,
                'conservacion' => $orden->conservacion,
                'tipo_muestra' => $orden->tipoMuestra,
                'descripcion_sintomas' => $orden->descripcionSintomas,
                'prediagnostico' => $orden->prediagnostico,
                'aplicacion_producto_quimico' => $orden->aplicacionProductoQuimico,
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
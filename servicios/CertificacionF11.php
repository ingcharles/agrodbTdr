<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 9/6/2017
 * Time: 22:33
 */
class CertificacionF11 extends Servicio
{
    private $tabla = 'f_inspeccion.certificacionf11';
    private $tablaDetalleEnvios = 'f_inspeccion.certificacionf11_detalle_envios';
    private $tablaDetalleResultados = 'f_inspeccion.certificacionf11_detalle_resultados';
    private $tablaDetalleOrdenes = 'f_inspeccion.certificacionf11_detalle_ordenes';

    public function ejecutarServicio($registro)
    {
        $campos = array(
            'id_tablet' => $registro->id,
            'numero_reporte' => $registro->numeroReporte,
            'ruc' => $registro->ruc,
            'exportador' => $registro->exportador,
            'sitio_inspeccion' => $registro->sitioInspeccion,
            'provincia' => $registro->provincia,
            'canton' => $registro->canton,
            'parroquia' => $registro->parroquia,
            'importador' => $registro->importador,
            'direccion' => $registro->direccion,
            'medio_transporte' => $registro->medioTransporte,
            'fecha_embarque' => $registro->fechaEmbarque,
            'observaciones' => $registro->observaciones,
            'representante' => $registro->representante,
            'fecha_vigencia' => $registro->fechaVigencia,
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
            $this->ejecutarServicioDetalleEnvios($id[0], $registro->certificacionF11EnvioList);
            $this->ejecutarServicioDetalleResultados($id[0], $registro->certificacionF11ResultadoList);
            $this->ejecutarServicioDetalleOrdenes($id[0], $registro->certificacionF11OrdenList);
        } /*else {
            throw new Exception('Error: ¡Existen datos duplicados!');
        }*/
    }

    private function ejecutarServicioDetalleEnvios($id, $envios)
    {
        foreach ($envios as $envio) {
            $campos = array(
                'id_padre' => $id,
                'id_tablet' => $envio->id,
                'ruc_operador' => $envio->rucOperador,
                'operador' => $envio->operador,
                'id_sitio' => $envio->idSitio,
                'sitio' => $envio->sitio,
                'provincia' => $envio->provincia,
                'canton' => $envio->canton,
                'parroquia' => $envio->parroquia,
                'id_tipo_producto' => $envio->idTipoProducto,
                'tipo_producto' => $envio->tipoProducto,
                'id_subtipo_producto' => $envio->idSubtipoProducto,
                'subtipo_producto' => $envio->subtipoProducto,
                'id_producto' => $envio->idProducto,
                'producto' => $envio->producto,
                'pais_destino' => $envio->paisDestino,
                'peso_neto' => $envio->pesoNeto,
                'unidad_cantidad_total' => $envio->unidadCantidadTotal,
                'cantidad_total' => $envio->cantidadTotal,
                'unidad_cantidad_inspeccionada' => $envio->unidadCantidadInspeccionada,
                'cantidad_inspeccionada' => $envio->cantidadInspeccionada,
                'requiere_tratamiento' => $envio->requiereTratamiento,
                'fecha_tratamiento' => $envio->fechaTratamiento,
                'tratamiento' => $envio->tratamiento,
                'otros' => $envio->otros,
                'producto_quimico' => $envio->productoQuimico,
                'unidad_duracion_tratamiento' => $envio->unidadDuracionTratamiento,
                'duracion_tratamiento' => $envio->duracionTratamiento,
                'temperatura' => $envio->temperatura,
                'concentracion' => $envio->concentracion,
                'incumplimiento_requisito' => $envio->incumplimientoRequisito,
                'detalles' => $envio->detalles,
                'medida_adoptada' => $envio->medidaAdoptada,
            );
            $this->conexion->ejecutarConsulta(
                $this->construirConsulta(
                    $this->tablaDetalleEnvios,
                    $campos
                )
            );
        }
    }

    private function ejecutarServicioDetalleResultados($id, $resultados)
    {
        foreach ($resultados as $resultado) {
            $campos = array(
                'id_padre' => $id,
                'id_tablet' => $resultado->id,
                'ruc_operador' => $resultado->rucOperador,
                'operador' => $resultado->operador,
                'id_sitio' => $resultado->idSitio,
                'sitio' => $resultado->sitio,
                'id_producto' => $resultado->idProducto,
                'producto' => $resultado->producto,
                'plaga' => $resultado->plaga,
                'individuos' => $resultado->individuos,
                'estado' => $resultado->estado,
                'analisis_laboratorio' => $resultado->analisisLaboratorio,
            );
            $this->conexion->ejecutarConsulta(
                $this->construirConsulta(
                    $this->tablaDetalleResultados,
                    $campos
                )
            );
        }
    }

    private function ejecutarServicioDetalleOrdenes($id, $ordenes)
    {
        foreach ($ordenes as $orden) {
            $campos = array(
                'id_padre' => $id,
                'id_tablet' => $orden->id,
                'tipo_muestra' => $orden->tipoMuestra,
                'conservacion' => $orden->conservacion,
                'codigo_muestra' => $orden->codigoMuestra,
                'analisis' => $orden->analisisSolicitado,
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





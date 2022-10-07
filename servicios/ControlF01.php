<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 9/6/2017
 * Time: 22:33
 */
class ControlF01 extends Servicio
{
    private $tabla = 'f_inspeccion.controlf01';
    private $tablaDetalleLotes = 'f_inspeccion.controlf01_detalle_lotes';
    private $tablaDetalleProductos = 'f_inspeccion.controlf01_detalle_productos_ingresados';
    private $tablaDetalleOrdenes = 'f_inspeccion.controlf01_detalle_ordenes';

    public function ejecutarServicio($registro)
    {
        $campos = array(
            'id_tablet' => $registro->id,
            'dda' => $registro->dda,
            'pfi' => $registro->pfi,
            'dictamen_final' => $registro->dictamenFinal,
            'observaciones' => $registro->observaciones,
            'envio_muestra' => $registro->envioMuestra,
            'usuario_id' => $registro->usuarioId,
            'usuario' => $registro->usuario,
            'fecha_inspeccion' => $registro->fechaCreacion,
            'tablet_id' => $this->tabletId,
            'tablet_version_base' => $this->databaseVersion,
            'pregunta01' => $registro->pregunta01,
            'pregunta02' => $registro->pregunta02,
            'pregunta03' => $registro->pregunta03,
            'pregunta04' => $registro->pregunta04,
            'pregunta05' => $registro->pregunta05,
            'pregunta06' => $registro->pregunta06,
            'pregunta07' => $registro->pregunta07,
            'pregunta08' => $registro->pregunta08,
            'pregunta09' => $registro->pregunta09,
            'pregunta10' => $registro->pregunta10,
            'pregunta11' => $registro->pregunta11,
            'categoria_riesgo' => $registro->categoriaRiesgo,
            'seguimiento_cuarentenario' => $registro->seguimientoCuarentenario,
            'provincia' => $registro->provincia,
            'peso_ingreso' => $registro->pesoIngreso,
            'numero_embalajes_envio' => $registro->numeroEmbalajesEnvio,
            'numero_embalajes_inspeccionados' => $registro->numeroEmbalajesInspeccionados,
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
            $this->ejecutarServicioDetalleLotes($id[0], $registro->controlF01LoteList);
            $this->ejecutarServicioDetalleProductos($id[0], $registro->controlF01ProductoList);
            $this->ejecutarServicioDetalleOrdenesLaboratorio($id[0], $registro->controlF01OrdenList);
        } /*else {
            throw new Exception('Error: ¡Existen datos duplicados!');
        }*/

    }

    private function ejecutarServicioDetalleLotes($id, $lotes)
    {
        foreach ($lotes as $lote) {
            $campos = array(
                'id_padre' => $id,
                'id_tablet' => $lote->id,
                'descripcion' => $lote->descripcion,
                'numero_cajas' => $lote->numeroCajas,
                'cajas_muestra' => $lote->cajasMuestra,
                'porcentaje_inspeccion' => $lote->porcentajeInspeccion,
                'ausencia_suelo' => $lote->ausenciaSuelo,
                'ausencia_contaminantes' => $lote->ausenciaContaminantes,
                'ausencia_sintomas' => $lote->ausenciaSintomas,
                'ausencia_plagas' => $lote->ausenciaPlagas,
                'dictamen' => $lote->dictamen,
            );
            $this->conexion->ejecutarConsulta(
                $this->construirConsulta(
                    $this->tablaDetalleLotes,
                    $campos
                )
            );
        }
    }

    private function ejecutarServicioDetalleProductos($id, $productos)
    {
        foreach ($productos as $producto) {
            $campos = array(
                'id_padre' => $id,
                'id_tablet' => $producto->id,
                'nombre' => $producto->nombre,
                'cantidad_declarada' => $producto->cantidadDeclarada,
                'cantidad_ingresada' => $producto->cantidadIngresada,
                'unidad' => $producto->unidad,
                'subtipo' => $producto->subTipo,
            );
            $this->conexion->ejecutarConsulta(
                $this->construirConsulta(
                    $this->tablaDetalleProductos,
                    $campos
                )
            );
        }
    }

    private function ejecutarServicioDetalleOrdenesLaboratorio($id, $ordenes)
    {
        foreach ($ordenes as $orden) {
            $campos = array(
                'id_padre' => $id,
                'id_tablet' => $orden->id,
                'actividad_origen' => $orden->actividadOrigen,
                'analisis' => $orden->analisis,
                'codigo_muestra' => $orden->codigoMuestra,
                'conservacion' => $orden->conservacion,
                'tipo_muestra' => $orden->tipoMuestra,
                'descripcion_sintomas' => $orden->descripcionSintomas,
                'fase_fenologica' => $orden->faseFenologica,
                'nombre_producto' => $orden->nombreProducto,
                'peso_muestra' => $orden->pesoMuestra,
                'prediagnostico' => $orden->prediagnostico,
                'tipo_cliente' => $orden->tipoCliente,
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
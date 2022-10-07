<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 9/6/2017
 * Time: 22:33
 */
class ControlF03 extends Servicio
{
    private $tabla = 'f_inspeccion.controlf03';
    private $tablaDetalleOrdenes = 'f_inspeccion.controlf03_detalle_ordenes';

    public function ejecutarServicio($registro)
    {
        $campos = array(
            'id_tablet' => $registro->id,
            'id_punto_control' => $registro->idPuntoControl,
            'punto_control' => $registro->puntoControl,
            'area_inspeccion' => $registro->areaInspeccion,
            'identidad_embalaje' => $registro->identidadEmbalaje,
            'id_pais_origen' => $registro->idPaisOrigen,
            'pais_origen' => $registro->paisOrigen,
            'numero_embalajes' => $registro->numeroEmbalajes,
            'numero_unidades' => $registro->numeroUnidades,
            'marca_autorizada' => $registro->marcaAutorizada,
            'marca_autorizada_descripcion' => $registro->marcaAutorizadaDescripcion,
            'marca_legible' => $registro->marcaLegible,
            'marca_legible_descripcion' => $registro->marcaLegibleDescripcion,
            'ausencia_dano_insectos' => $registro->ausenciaDanoInsectos,
            'ausencia_dano_insectos_descripcion' => $registro->ausenciaDanoInsectosDescripcion,
            'ausencia_insectos_vivos' => $registro->ausenciaInsectosVivos,
            'ausencia_insectos_vivos_descripcion' => $registro->ausenciaInsectosVivosDescripcion,
            'ausencia_corteza' => $registro->ausenciaCorteza,
            'ausencia_corteza_descripcion' => $registro->ausenciaCortezaDescripcion,
            'razon_social' => $registro->razonSocial,
            'manifesto' => $registro->manifesto,
            'producto' => $registro->producto,
            'envio_muestra' => $registro->envioMuestra,
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
            $this->ejecutarServicioDetalleOrdenesLaboratorio($id[0], $registro->controlF03OrdenList);
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
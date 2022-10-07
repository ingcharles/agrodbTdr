<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 9/6/2017
 * Time: 22:33
 */
class VigilanciaF01 extends Servicio
{
    private $tabla = 'f_inspeccion.vigilanciaf01';
    private $tablaDetalleTrampas = 'f_inspeccion.vigilanciaf01_detalle_trampas';
    private $tablaDetalleOrdenes = 'f_inspeccion.vigilanciaf01_detalle_ordenes';

    public function ejecutarServicio($registro)
    {
        $campos = array(
            'id_tablet' => $registro->id,
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
            $this->ejecutarServicioDetalleTrampas($id[0], $registro->vigilanciaF01TrampaList);
            $this->ejecutarServicioDetalleOrdenesLaboratorio($id[0], $registro->vigilanciaF01OrdenList);
        } else {
            throw new Exception('Error: ¡Existen datos duplicados!');
        }
    }


    public function ejecutarServicioDetalleTrampas($id,$trampas)
    {
        foreach ($trampas as $trampa) {
            $campos = array(
                'id_padre' => $id,
                'id_tablet' => $trampa->id,
                'fecha_instalacion' => $trampa->fechaInstalacion,
                'codigo_trampa' => $trampa->codigoTrampa,
                'tipo_trampa' => $trampa->tipoTrampa,
                'id_provincia' => $trampa->idProvincia,
                'nombre_provincia' => $trampa->nombreProvincia,
                'id_canton' => $trampa->idCanton,
                'nombre_canton' => $trampa->nombreCanton,
                'id_parroquia' => $trampa->idParroquia,
                'nombre_parroquia' => $trampa->nombreParroquia,
                'estado_trampa' => $trampa->estadoTrampa,
                'coordenada_x' => $trampa->coordenadaX,
                'coordenada_y' => $trampa->coordenadaY,
                'coordenada_z' => $trampa->coordenadaZ,
                'id_lugar_instalacion' => $trampa->idLugarInstalacion,
                'nombre_lugar_instalacion' => $trampa->nombreLugarInstalacion,
                'numero_lugar_instalacion' => $trampa->numeroLugarInstalacion,
                'fecha_inspeccion' => $trampa->fechaCreacion,
                'semana' => $trampa->semana,
                'usuario_id' => $trampa->usuarioId,
                'usuario' => $trampa->usuario,
                'propiedad_finca' => $trampa->propiedadFinca,
                'condicion_trampa' => $trampa->condicionTrampa,
                'especie' => $trampa->especie,
                'procedencia' => $trampa->procedencia,
                'condicion_cultivo' => $trampa->condicionCultivo,
                'etapa_cultivo' => $trampa->etapaCultivo,
                'exposicion' => $trampa->exposicion,
                'cambio_feromona' => $trampa->cambioFeromona,
                'cambio_papel' => $trampa->cambioPapel,
                'cambio_aceite' => $trampa->cambioAceite,
                'cambio_trampa' => $trampa->cambioTrampa,
                'numero_especimenes' => $trampa->numeroEspecimenes,
                'diagnostico_visual' => $trampa->diagnosticoVisual,
                'fase_plaga' => $trampa->fasePlaga,
                'observaciones' => $trampa->observaciones,
                'envio_muestra' => $trampa->envioMuestra,
                'tablet_id' => $this->tabletId,
                'tablet_version_base' => $this->databaseVersion,
            );
            $id2 = pg_fetch_row(
                $this->conexion->ejecutarConsulta(
                    $this->construirConsulta(
                        $this->tablaDetalleTrampas,
                        $campos
                    )
                )
            );

            if($id2 != null || $id2 != ''){
            } else {
                throw new Exception('Error: ¡Existen datos duplicados!');
            }
        }
    }


    private function ejecutarServicioDetalleOrdenesLaboratorio($id, $ordenes)
    {
        foreach ($ordenes as $orden) {
            $campos = array(
                'id_padre' => $id,
                'id_tablet' => $orden->id,
                'analisis' => $orden->analisisSolicitado,
                'codigo_muestra' => $orden->codigoMuestra,
                'nombre_producto' => $orden->productoPara,
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
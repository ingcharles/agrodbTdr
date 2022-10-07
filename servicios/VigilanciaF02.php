<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 9/6/2017
 * Time: 22:33
 */
class VigilanciaF02 extends Servicio
{
    private $tabla = 'f_inspeccion.vigilanciaf02';
    private $tablaDetalleOrdenes = 'f_inspeccion.vigilanciaf02_detalle_ordenes';

    public function ejecutarServicio($registro)
    {
        $campos = array(
            'id_tablet' => $registro->id,
            'codigo_provincia' => $registro->codigoProvincia,
            'nombre_provincia' => $registro->nombreProvincia,
            'codigo_canton' => $registro->codigoCanton,
            'nombre_canton' => $registro->nombreCanton,
            'codigo_parroquia' => $registro->codigoParroquia,
            'nombre_parroquia' => $registro->nombreParroquia,
            'nombre_propietario_finca' => $registro->nombrePropietarioFinca,
            'localidad_via' => $registro->localidadVia,
            'coordenada_x' => $registro->coordenadaX,
            'coordenada_y' => $registro->coordenadaY,
            'coordenada_z' => $registro->coordenadaZ,
            'denuncia_fitosanitaria' => $registro->denunciaFitosanitaria,
            'nombre_denunciante' => $registro->nombreDenunciante,
            'telefono_denunciante' => $registro->telefonoDenunciante,
            'direccion_denunciante' => $registro->direccionDenunciante,
            'correo_electronico_denunciante' => $registro->correoElectronicoDenunciante,
            'especie_vegetal' => $registro->especieVegetal,
            'cantidad_total' => $registro->cantidadTotal,
            'cantidad_vigilada' => $registro->cantidadVigilada,
            'unidad' => $registro->unidad,
            'sitio_operacion' => $registro->sitioOperacion,
            'condicion_produccion' => $registro->condicionProduccion,
            'etapa_cultivo' => $registro->etapaCultivo,
            'actividad' => $registro->actividad,
            'manejo_sitio_operacion' => $registro->manejoSitioOperacion,
            'ausencia_plaga' => $registro->ausenciaPlagas,
            'plaga_diagnostico_visual_prediagnostico' => $registro->plagaDiagnosticoVisualPrediagnostico,
            'cantidad_afectada' => $registro->cantidadAfectada,
            'porcentaje_incidencia' => $registro->porcentajeIncidencia,
            'porcentaje_severidad' => $registro->porcentajeSeveridad,
            'tipo_plaga' => $registro->tipoPlaga,
            'fase_desarrollo_plaga' => $registro->faseDesarrolloPlaga,
            'organo_afectado' => $registro->organoAfectado,
            'distribucion_plaga' => $registro->distribucionPlaga,
            'poblacion' => $registro->poblacion,
            'diagnostico_visual' => $registro->diagnosticoVisual,
            'descripcion_sintomas_p' => $registro->descripcionSintomas,
            'envio_muestra' => $registro->envioMuestra,
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
            $this->ejecutarServicioDetalleOrdenesLaboratorio($id[0], $registro->vigilanciaF02OrdenList);
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
                'nombre_producto' => $orden->productoPara,
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

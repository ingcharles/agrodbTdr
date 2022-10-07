<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 9/6/2017
 * Time: 22:33
 */
class CertificacionF01 extends Servicio
{
    private $tabla = 'f_inspeccion.certificacionf01';
    private $tablaDetalleGrupos = 'f_inspeccion.certificacionf01_detalle_grupos';
    private $tablaDetalleOrdenes = 'f_inspeccion.certificacionf01_detalle_ordenes';

    public function ejecutarServicio($registro)
    {
        $campos = array(
            'id_tablet' => $registro->id,
            'numero_reporte' => $registro->numeroReporte,
            'semana_evaluacion' => $registro->semanaEvaluacion,
            'semana_cosecha' => $registro->semanaCosecha,
            'ruc' => $registro->ruc,
            'razon_social' => $registro->razonSocial,
            'id_predio' => $registro->idPredio,
            'nombre_predio' => $registro->nombrePredio,
            'coordenada_x' => $registro->coordenadaX,
            'coordenada_y' => $registro->coordenadaY,
            'coordenada_z' => $registro->coordenadaZ,
            'direccion' => $registro->direccion,
            'provincia' => $registro->provincia,
            'canton' => $registro->canton,
            'parroquia' => $registro->parroquia,
            'identificacion_lote' => $registro->identificacionLote,
            'material_vegetal' => $registro->materialVegetal,
            'variedad' => $registro->variedad,
            'numero_plantas' => $registro->numeroPlantas,
            'superficie' => $registro->superficie,
            'tamano_muestra' => $registro->tamanoMuestra,
            'numero_grupos' => $registro->numeroGrupos,
            'tiempo_cosecha' => $registro->tiempoCosecha,
            'limpieza_drenaje' => $registro->limpiezaDrenaje,
            'uso_cebos' => $registro->usoCebos,
            'uso_trampas' => $registro->usoTrampas,
            'eliminacion_moluscos' => $registro->eliminacionMoluscos,
            'aplicacion_jabon' => $registro->aplicacionJabon,
            'infraestructura' => $registro->infraestructura,
            'grado' => $registro->grado,
            'personal' => $registro->personal,
            'trazabilidad_lote' => $registro->trazabilidadLote,
            'promedio_grupos' => $registro->promedioGrupos,
            'decision_tomada' => $registro->decisionTomada,
            'grupos_afectados' => $registro->gruposAfectados,
            'indice_presencia' => $registro->indicePresencia,
            'representante' => $registro->representante,
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
            $this->ejecutarServicioDetalleGrupos($id[0], $registro->certificacionF01GrupoList);
            $this->ejecutarServicioDetalleOrdenesLaboratorio($id[0], $registro->certificacionF01OrdenList);
        } /*else {
            throw new Exception('Error: ¡Existen datos duplicados!');
        }*/
    }

    private function ejecutarServicioDetalleGrupos($id, $grupos)
    {
        foreach ($grupos as $grupo) {
            $campos = array(
                'id_padre' => $id,
                'id_tablet' => $grupo->id,
                'grupo' => $grupo->grupo,
                'numero_caracoles' => $grupo->numeroCaracoles,
            );
            $this->conexion->ejecutarConsulta(
                $this->construirConsulta(
                    $this->tablaDetalleGrupos,
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
                //'actividad_origen' => $orden->actividadOrigen,
                'analisis' => $orden->analisis,
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
    }

}
<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 9/6/2017
 * Time: 22:33
 */
class CertificacionF10 extends Servicio
{
    private $tabla = 'f_inspeccion.certificacionf10';

    public function ejecutarServicio($registro)
    {
        $campos = array(
            'id_tablet' => $registro->id,
            'numero_reporte' => $registro->numeroReporte,
            'numero_reporte_inspeccion' => $registro->numeroReporteInspeccion,
            'ruc' => $registro->ruc,
            'exportador' => $registro->exportador,
            'comprador' => $registro->comprador,
            'lote' => $registro->lote,
            'calidad' => $registro->calidad,
            'sacos' => $registro->sacos,
            'vapor' => $registro->vapor,
            'id_destino' => $registro->idDestino,
            'destino' => $registro->destino,
            'id_centro_acopio' => $registro->idCentroAcopio,
            'centro_acopio' => $registro->centroAcopio,
            'fecha_analisis' => $registro->fechaAnalisis,
            'muestra_inspector' => $registro->muestraInspector,
            'contra_muestra' => $registro->contraMuestra,
            'tipo_inspeccion' => $registro->tipoInspeccion,
            'tipo_cacao' => $registro->tipoCacao,
            'tipo_produccion' => $registro->tipoProduccion,
            'inspeccion_adicional' => $registro->inspeccionAdicional,
            'fermentacion' => $registro->fermentacion,
            'fermentados' => $registro->fermentados,
            'grano_violeta' => $registro->granoVioleta,
            'grano_pizarroso' => $registro->granoPizarroso,
            'mohos' => $registro->mohos,
            'danados_insectos' => $registro->danadosInsectos,
            'vulnerado' => $registro->vulnerado,
            'trinitario' => $registro->trinitario,
            'multiples' => $registro->multiples,
            'partidos' => $registro->partidos,
            'plano_granza' => $registro->planoGranza,
            'total_defectos' => $registro->totalDefectos,
            'impurezas_cacao' => $registro->impurezasCacao,
            'materia_extrana' => $registro->materiaExtrana,
            'peso_pepas' => $registro->pesoPepas,
            'pepas_gramos' => $registro->pepasGramos,
            'humedad' => $registro->humedad,
            'medidor_humedad' => $registro->medidorHumedad,
            'balanza_utilizada' => $registro->balanzaUtilizada,
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
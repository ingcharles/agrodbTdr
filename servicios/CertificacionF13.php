<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 9/6/2017
 * Time: 22:33
 */
class CertificacionF13 extends Servicio
{
    private $tabla = 'f_inspeccion.certificacionf13';
    private $tablaDetalleGuias = 'f_inspeccion.certificacionf13_detalle_guias';
    private $tablaDetalleResultados = 'f_inspeccion.certificacionf13_detalle_resultados';
    private $tablaDetalleOrdenes = 'f_inspeccion.certificacionf13_detalle_ordenes';

    public function ejecutarServicio($registro)
    {
        $campos = array(
            'id_tablet' => $registro->id,
            'numero_reporte' => $registro->numeroReporte,
            'ruc_agencia_carga' => $registro->rucAgenciaCarga,
            'agencia_carga' => $registro->agenciaCarga,
            'eeuu' => $registro->eeuu,
            'rusia' => $registro->rusia,
            'holanda' => $registro->holanda,
            'chile' => $registro->chile,
            'otros' => $registro->otros,
            'totales' => $registro->totales,
            'representante' => $registro->representante,
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
            $this->ejecutarServicioDetalleGuia($id[0], $registro->certificacionF13GuiaList);
            $this->ejecutarServicioDetalleResultados($id[0], $registro->certificacionF13ResultadoList);
            $this->ejecutarServicioDetalleOrden($id[0], $registro->certificacionF13OrdenList);
        } /*else {
            throw new Exception('Error: ¡Existen datos duplicados!');
        }*/
    }

    private function ejecutarServicioDetalleGuia($id, $guias)
    {
        foreach ($guias as $guia) {
            $campos = array(
                'id_padre' => $id,
                'id_tablet' => $guia->id,
                'guia_madre' => $guia->guiaMadre,
                'guia_hija' => $guia->guiaHija,
                'id_destino' => $guia->idDestino,
                'destino' => $guia->destino,
                'ruc_exportador' => $guia->rucExportador,
                'exportador' => $guia->exportador,
                'id_centro_acopio' => $guia->idCentroAcopio,
                'centro_acopio' => $guia->centroAcopio,
                'provincia' => $guia->provincia,
                'canton' => $guia->canton,
                'parroquia' => $guia->parroquia,
                'id_tipo_producto' => $guia->idTipoProducto,
                'tipo_producto' => $guia->tipoProducto,
                'id_subtipo_producto' => $guia->idSubtipoProducto,
                'subtipo_producto' => $guia->subtipoProducto,
                'id_producto' => $guia->idProducto,
                'producto' => $guia->producto,
                'cajas' => $guia->cajas,
                'cajas_inpeccion' => $guia->cajasInpeccion,
                'codigo_finca' => $guia->codigoFinca,
                'adhesivo_inspeccionado' => $guia->adhesivoInspeccionado,
                'observaciones' => $guia->observaciones,
                'medida_adoptada' => $guia->medidaAdoptada,
                'cajas_detenidas' => $guia->cajasDetenidas,
            );
            $this->conexion->ejecutarConsulta(
                $this->construirConsulta(
                    $this->tablaDetalleGuias,
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
                'ruc_exportador' => $resultado->rucExportador,
                'exportador' => $resultado->exportador,
                'id_destino' => $resultado->idDestino,
                'destino' => $resultado->destino,
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

    private function ejecutarServicioDetalleOrden($id, $ordenes)
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
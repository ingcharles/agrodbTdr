<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 9/6/2017
 * Time: 22:33
 */
class CertificacionF02 extends Servicio
{
    private $tabla = 'f_inspeccion.certificacionf02';
    private $tablaDetalleOrdenes = 'f_inspeccion.certificacionf02_detalle_ordenes';

    public function ejecutarServicio($registro)
    {
        $campos = array(
            'id_tablet' => $registro->id,
            'numero_reporte' => $registro->numeroReporte,
            'ruc_exportador' => $registro->rucExportador,
            'razon_social_exportador' => $registro->razonSocialExportador,
            'ruc_acopiador' => $registro->rucAcopiador,
            'acopiador' => $registro->acopiador,
            'codigo_registro_pallet_rechazado' => $registro->codigoRegistroPalletRechazado,
            'ruc_empresa_tratamiento_pallet' => $registro->rucEmpresaTratamientoPallet,
            'nombre_empresa_tratamiento_pallet' => $registro->nombreEmpresaTratamientoPallet,
            'numero_factura_guia_remision' => $registro->numeroFacturaGuiaRemision,
            'id_lugar_rechazo' => $registro->idLugarRechazo,
            'lugar_rechazo' => $registro->lugarRechazo,
            'sellos_ilegibles' => $registro->sellosIlegibles,
            'presencia_corteza' => $registro->presenciaCorteza,
            'plagas' => $registro->plagas,
            'registro_tratamiento' => $registro->registroTratamiento,
            'otros' => $registro->otros,
            'cantidad_embalajes_rechazados' => $registro->cantidadEmbalajesRechazados,
            'nombre_interesado_representante_exportador' => $registro->nombreInteresadoRepresentanteExportador,
            'observaciones' => $registro->observaciones,
            'fecha_inspeccion' => $registro->fechaCreacion,
            'usuario_id' => $registro->usuarioId,
            'usuario' => $registro->usuario,
            'tablet_id' => $this->tabletId,
            'tablet_version_base' => $this->databaseVersion,
            'envio_muestra' => $registro->envioMuestra,
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
            $this->ejecutarServicioDetalleOrdenesLaboratorio($id[0], $registro->certificacionF02OrdenList);
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
    }

}
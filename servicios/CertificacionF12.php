<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 9/6/2017
 * Time: 22:33
 */
class CertificacionF12 extends Servicio
{
    private $tabla = 'f_inspeccion.certificacionf12';
    private $tablaDetalleMuestras = 'f_inspeccion.certificacionf12_detalle_muestras';

    public function ejecutarServicio($registro)
    {
        $campos = array(
            'id_tablet' => $registro->id,
            'numero_reporte' => $registro->numeroReporte,
            'ruc_empresa_tratamiento' => $registro->rucEmpresaTratamiento,
            'razon_social_empresa_tratamiento' => $registro->razonSocialEmpresaTratamiento,
            'id_planta_tratamiento' => $registro->idPlantaTratamiento,
            'planta_tratamiento' => $registro->plantaTratamiento,
            'turno' => $registro->turno,
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
            $this->ejecutarServicioDetalleMuestras($id[0], $registro->certificacionF12MuestraList);
        } /*else {
            throw new Exception('Error: ¡Existen datos duplicados!');
        }*/
    }

    private function ejecutarServicioDetalleMuestras($id, $muestras)
    {
        foreach ($muestras as $muestra) {
            $campos = array(
                'id_padre' => $id,
                'id_tablet' => $muestra->id,
                'ruc_empresa_finca' => $muestra->rucEmpresaFinca,
                'razon_social_empresa_finca' => $muestra->razonSocialEmpresaFinca,
                'id_finca' => $muestra->idFinca,
                'finca' => $muestra->finca,
                'lote' => $muestra->lote,
                'variedad' => $muestra->variedad,
                'numero_gavetas' => $muestra->numeroGavetas,
                'numero_frutos_muestra' => $muestra->numeroFrutosMuestra,
                'larvas_vivas' => $muestra->larvasVivas,
                'larvas_muertas' => $muestra->larvasMuertas,
                'guia_remision' => $muestra->guiaRemision,
                'destino' => $muestra->destino,
                'numero_camiones' => $muestra->numeroCamiones,
                'observaciones' => $muestra->observaciones,
            );
            $this->conexion->ejecutarConsulta(
                $this->construirConsulta(
                    $this->tablaDetalleMuestras,
                    $campos
                )
            );
        }
    }

}
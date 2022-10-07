<?php
session_start();
$tituloReporte = 'Reporte de Seguimiento Cuarentenario';
$archivoSalida = 'REPORTE_SEGUIMIENTO_CUARENTENARIO.xls';
$campos = array(
    'fecha_inspeccion',
    'razon_social',
    'nombre_scpe',
    'usuario',
    'actividad',
    'pais_origen',
    'subtipo_producto',
    'producto',
    'peso',
    'tipo_operacion',
    'tipo_cuarentena_condicion_produccion',
    'fase_seguimiento',
    'codigo_lote',
    'numero_seguimientos_planificados',
    'numero_plantas_ingreso',
    'numero_plantas_inspeccion',
    'registro_monitoreo_plagas',
    'ausencia_plagas',
    'cantidad_afectada',
    'porcentaje_incidencia',
    'porcentaje_severidad',
    'fase_desarrollo_plaga',
    'organo_afectado',
    'distribucion_plaga',
    'envio_muestra',
    'resultado_inspeccion',
    'observaciones',
    'provincia',
);
$tablas = 'controlf04';
require 'baseGeneradorReporte.php';
if ($incluirDatosLaboratorio) {
    incluirDatosLaboratorio($campos, $tablas, $cr->camposLaboratorio);
}
$res = $cr->generarReporteSeguimientoCuarentenario($conexion, $incluirDatosLaboratorio, $fechaInicio, $fechaFin);
require 'baseGeneradorReporteHTML.php';
?>
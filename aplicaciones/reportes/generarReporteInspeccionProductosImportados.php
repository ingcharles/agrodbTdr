<?php
session_start();
$tituloReporte = 'Reporte de Formulario de Inspección de Productos Importados';
$archivoSalida = 'REPORTE_INSPECCION_PRODUCTOS_IMPORTADOS.xls';
$campos = array(
    'dda',
    'pfi',
    'dictamen_final',
    'observaciones',
    'envio_muestra',
    'usuario_id',
    'usuario',
    'fecha_inspeccion',
    'pregunta01',
    'pregunta02',
    'pregunta03',
    'pregunta04',
    'pregunta05',
    'pregunta06',
    'pregunta07',
    'pregunta08',
    'pregunta09',
    'pregunta10',
    'pregunta11',
    'categoria_riesgo',
    'seguimiento_cuarentenario',
    'peso_ingreso',
    'numero_embalajes_envio',
    'numero_embalajes_inspeccionados',
);
$tablas = 'controlf01';
require 'baseGeneradorReporte.php';
if ($incluirDatosLaboratorio) {
    incluirDatosLaboratorio($campos, $tablas, $cr->camposLaboratorio);
}
$res = $cr->generarReporteInspeccionProductosImportados($conexion, $incluirDatosLaboratorio, $fechaInicio, $fechaFin);
require 'baseGeneradorReporteHTML.php';
?>
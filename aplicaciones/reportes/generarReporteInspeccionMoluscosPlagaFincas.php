<?php
session_start();
$tituloReporte = 'Reporte de Inspección de Piña en Fincas';
$archivoSalida = 'REPORTE_INSPECCION_PINA_FINCAS.xls';
$campos = array(
    'numero_reporte',
    'fecha_inspeccion',
    'semana_evaluacion',
    'semana_cosecha',
    'ruc',
    'razon_social',
    'nombre_predio',
    'provincia',
    'canton',
    //'parroquia',
    'identificacion_lote',
    'material_vegetal',
    'variedad',
    'numero_plantas',
    'superficie',
    'tamano_muestra',
    'tiempo_cosecha',
    'limpieza_drenaje',
    'uso_cebos',
    'uso_trampas',
    'eliminacion_moluscos',
    'aplicacion_jabon',
    'infraestructura',
    'grado',
    'personal',
    'trazabilidad_lote',
    //que es número de grupos?????
    'numero_grupos',
    'grupo',//
    'numero_caracoles',//
    'promedio_grupos',
    'grupos_afectados',
    'indice_presencia',
    'decision_tomada',
    'representante',
    'observaciones',
    //'usuario_id',
    'usuario',
);
$tablas = array('certificacionf01', 'certificacionf01_detalle_grupos');
require 'baseGeneradorReporte.php';
if ($incluirDatosLaboratorio) {
    incluirDatosLaboratorio($campos, $tablas, $cr->camposLaboratorio);
}
$res = $cr->generarReporteInspeccionMoluscosPlagaFincas($conexion,$incluirDatosLaboratorio, $fechaInicio, $fechaFin);
require 'baseGeneradorReporteHTML.php';
?>
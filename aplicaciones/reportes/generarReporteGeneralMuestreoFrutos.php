<?php
session_start();
$tituloReporte = 'Reporte de General de Muestreo de Frutos';
$archivoSalida = 'REPORTE_MUESTREO_FRUTOS.xls';
$campos = array(
    //'codigo_muestra',
    'fecha_inspeccion',
    'semana',
    'nombre_provincia',
    'nombre_canton',
    'nombre_parroquia',
    'coordenada_x',
    'coordenada_y',
    'coordenada_z',
    'especie_vegetal',
    'sitio_muestreo',
    'nombre_lugar_muestreo',
    'numero_frutos_colectados',
    'usuario',
);
$tablas = 'moscaf03';
require 'baseGeneradorReporte.php';
if ($incluirDatosLaboratorio) {
    incluirDatosLaboratorio($campos, $tablas, $cr->camposLaboratorio);
}
$res = $cr->generarReporteGeneralMuestreoFrutos($conexion, $incluirDatosLaboratorio, $fechaInicio, $fechaFin);
require 'baseGeneradorReporteHTML.php';
?>
<?php
session_start();
$tituloReporte = 'Reporte de Formulario de Trampeo de Mosca';
$archivoSalida = 'REPORTE_TRAMPEO_MOSCA.xls';
$campos = array(
    'codigo_trampa',
    'nombre_provincia',
    'nombre_canton',
    'nombre_parroquia',
    'estado_trampa',
    'nombre_lugar_instalacion',
    'numero_lugar_instalacion',
    'tipo_trampa',
    'nombre_tipo_atrayente',
    'fecha_instalacion',
    'fecha_inspeccion',
    'coordenada_x',
    'coordenada_y',
    'coordenada_z',
    'semana',
    'exposicion',
    'condicion',
    'cambio_trampa',
    'cambio_plug',
    'especie_principal',
    'estado_fenologico_principal',
    'especie_colindante',
    'estado_fenologico_colindante',
    'numero_especimenes',
    'envio_muestra',
    //'usuario_id',
    'usuario',
    'observaciones',
);
$tablas = 'moscaf01_detalle_trampas';
require 'baseGeneradorReporte.php';
if ($incluirDatosLaboratorio) {
    incluirDatosLaboratorio($campos, $tablas, $cr->camposLaboratorio, 'mosca01_detalle_ordenes');
}
$res = $cr->generarReporteGeneralTrampeoMosca($conexion, $incluirDatosLaboratorio, $fechaInicio, $fechaFin);

require 'baseGeneradorReporteHTML.php';

<?php
session_start();
$tituloReporte = 'Reporte de Formulario de Trampeo - Vigilancia';
$archivoSalida = 'REPORTE_TRAMPEO_VIGILANCIA.xls';
$campos = array(
    'fecha_instalacion',
    'codigo_trampa',
    'nombre_provincia',
    'nombre_canton',
    'nombre_parroquia',
    'coordenada_x',
    'coordenada_y',
    'coordenada_z',
    'exposicion',
    'estado_trampa',
    'cambio_trampa',
    'cambio_feromona',
    'cambio_papel',
    'cambio_aceite',
    'especie',
    'procedencia',
    'etapa_cultivo',
    'nombre_lugar_instalacion',
    //'numero_lugar_instalacion',
    'condicion_cultivo',
    'numero_especimenes',
    'fase_plaga',
    'envio_muestra',
    //'diagnostico_visual',
    'fecha_inspeccion',
    'observaciones',
    'usuario',
    //'semana',
    //'condicion_trampa',
	'ruta_foto'
);
$tablas = 'vigilanciaf01_detalle_trampas';
require 'baseGeneradorReporte.php';
if ($incluirDatosLaboratorio) {
    incluirDatosLaboratorio($campos, $tablas, $cr->camposLaboratorio);
}
$res = $cr->generarReporteGeneralTrampeoVigilancia($conexion, $incluirDatosLaboratorio, $fechaInicio, $fechaFin);
require 'baseGeneradorReporteHTML.php';
?>




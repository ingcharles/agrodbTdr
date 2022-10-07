<?php
session_start();
$tituloReporte = 'Reporte General de Caracterización Frutícola';
$archivoSalida = 'REPORTE_CARACTERIZACION_FRUTICOLA.xls';
$campos = array(
    'fecha_inspeccion',
    'nombre_asociacion_productor',
    'identificador',
    'telefono',
    'provincia',
    'canton',
    'parroquia',
    'sitio',
    'especie',
    'variedad',
    'area_produccion_estimada',
    'coordenada_x',
    'coordenada_y',
    'coordenada_z',
    'observaciones',
    'usuario',
);
$tablas = 'moscaf02';
require 'baseGeneradorReporte.php';
$incluirDatosLaboratorio = false; //no tiene datos de laboratorio
if ($incluirDatosLaboratorio) {
    incluirDatosLaboratorio($campos, $tablas, $cr->camposLaboratorio);
}
$res = $cr->generarReporteGeneralCaracterizacionFruticola($conexion, $incluirDatosLaboratorio, $fechaInicio, $fechaFin);

require 'baseGeneradorReporteHTML.php';


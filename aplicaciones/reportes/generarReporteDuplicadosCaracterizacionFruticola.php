<?php
session_start();

$tituloReporte = 'Reporte de Duplicados de Caracterización Frutícola';
$archivoSalida = 'REPORTE_INSPECCION_PRODUCTOS_IMPORTADOS.xls';
$criterio = $_POST['criterio'];
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
);
$tablas = 'moscaf02';
require 'baseGeneradorReporte.php';
$incluirDatosLaboratorio = false; //no tiene datos de laboratorio
if ($incluirDatosLaboratorio) {
    incluirDatosLaboratorio($campos, $tablas, $cr->camposLaboratorio);
}
$res = $cr->generarReporteGeneralCaracterizacionFruticola($conexion, $incluirDatosLaboratorio, $fechaInicio, $fechaFin, $criterio);
require 'baseGeneradorReporteHTML.php';
?>






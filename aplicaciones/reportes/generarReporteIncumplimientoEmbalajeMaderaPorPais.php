<?php
session_start();
$tituloReporte = 'Reporte de Incumplimientos en Embalajes de Madera por País y Punto de Control';
$archivoSalida = 'REPORTE_INCUMPLIMIENTOS_EMBALAJE_MADERA.xls';
$pais = $_POST['pais'];
$puntoControl = $_POST['puntoControl'];
$incumplimientos = $_POST['incumplimientos'];
$campos = array_merge(
    array(
        'id',
        'pais_origen',
        'punto_control'
    ),
    $incumplimientos
);
$tablas = 'controlf03';
require 'baseGeneradorReporte.php';
/*if ($incluirDatosLaboratorio) {
    incluirDatosLaboratorio($campos, $tablas, $cr->camposLaboratorio);
}*/
$res = $cr->generarReporteIncumplimientoEmbalajeMaderaPorPais($conexion, $fechaInicio, $fechaFin, $pais, $puntoControl);
require 'baseGeneradorReporteHTML.php';
?>
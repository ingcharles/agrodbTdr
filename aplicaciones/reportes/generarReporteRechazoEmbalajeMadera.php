<?php
session_start();
$tituloReporte = 'Reporte de Tránsito Internacional';
$archivoSalida = 'REPORTE_RECHAZO_EMBALAJE_MADERA.xls';
$campos = array(
    'ruc_exportador',
    'razon_social_exportador',
    'codigo_registro_pallet_rechazado',
    'ruc_empresa_tratamiento_pallet',
    'nombre_empresa_tratamiento_pallet',
    'numero_factura_guia_remision',
    'lugar_rechazo',
    'fecha_inspeccion',
    'sellos_ilegibles',
    'presencia_corteza',
    'plagas',
    'registro_tratamiento',
    'otros',
    'cantidad_embalajes_rechazados',
    'observaciones',
    'nombre_interesado_representante_exportador',
    'usuario',
);
$tablas = 'certificacionf02';
require 'baseGeneradorReporte.php';
if ($incluirDatosLaboratorio) {
    incluirDatosLaboratorio($campos, $tablas, $cr->camposLaboratorio);
}
$res = $cr->generarReporteRechazoEmbalajeMadera($conexion, $incluirDatosLaboratorio, $fechaInicio, $fechaFin);
require 'baseGeneradorReporteHTML.php';
?>



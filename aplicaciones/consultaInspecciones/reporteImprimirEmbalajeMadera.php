<?php
session_start();
$tituloReporte = 'Acta de Rechazo de Embalajes de Madera';
$archivoSalida = 'REPORTE_RECHAZO_EMBALAJE_MADERA.xls';
$campos = array(
	'numero_reporte',
    'fecha_inspeccion',
    'ruc_exportador',
    'razon_social_exportador',
    'ruc_acopiador',
    'acopiador',
    'codigo_registro_pallet_rechazado',
    'ruc_empresa_tratamiento_pallet',
    'nombre_empresa_tratamiento_pallet',
    'numero_factura_guia_remision',
    'lugar_rechazo',
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
    incluirDatosLaboratorio($campos, $tablas, $cci->camposLaboratorio);
}
$res = $cci->generarReporteRechazoEmbalajeMadera($conexion, $incluirDatosLaboratorio, $fechaInicio, $fechaFin, $identificadorUsuario, $nombreUsuario,$tipoFormulario);
require 'baseGeneradorReporteHTML.php';
?>
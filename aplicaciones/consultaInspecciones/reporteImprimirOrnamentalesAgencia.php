<?php
session_start();
$tituloReporte = 'Reporte de inspección en agencias de carga';
$archivoSalida = 'REPORTE_INSPECCION_AGENCIAS_CARGA.xls';

$campos = array(
      'numero_reporte',
    'fecha_inspeccion',
    //'usuario',
    //'ruc_agencia_carga', //Se ha deshabilitado debido al problema que existen con las agencias de carga
    'agencia_carga',
    'guia_madre',
    'guia_hija',
    'destino',
    'ruc_exportador',
    'exportador',
    'centro_acopio',
    'provincia',
    'canton',
    'parroquia',
    'tipo_producto',
    'subtipo_producto',
    'producto',
    'cajas',
    'cajas_inpeccion',

    'codigo_finca',
    'adhesivo_inspeccionado',
    'observaciones',
    'medida_adoptada',
    'cajas_detenidas',
    'producto',
    'plaga',
    'individuos',
    'estado',
    'analisis_laboratorio',

    'eeuu',
    'rusia',
    'holanda',
    'chile',
    'otros',
    'totales',
    'representante',
    'observaciones',

    'usuario',
);
$tablas = array('certificacionf13', 'certificacionf13_detalle_guias', 'certificacionf13_detalle_resultados');
require 'baseGeneradorReporte.php';
if ($incluirDatosLaboratorio) {
	incluirDatosLaboratorio($campos, $tablas, $cci->camposLaboratorio);
}

$res = $cci->generarReporteInspeccionAgenciasCarga($conexion, $incluirDatosLaboratorio, $fechaInicio, $fechaFin, $identificadorUsuario, $nombreUsuario,$tipoFormulario);
require 'baseGeneradorReporteHTML.php';
?>

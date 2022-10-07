<?php
session_start();
$tituloReporte = 'Reporte de General de Monitoreo de Vigilancia';
$archivoSalida = 'REPORTE_GENERAL_MONITOREO_VIGILANCIA.xls';
$actividad = $_POST['actividad'];
$especie = $_POST['especie'];
$diagnostico = $_POST['diagnostico'];
$incidencia = $_POST['incidencia'];
$severidad = $_POST['severidad'];
$campos = array(
    'fecha_inspeccion',
    //'codigo_provincia',
    'nombre_provincia',
    //'codigo_canton',
    'nombre_canton',
    //'codigo_parroquia',
    'nombre_parroquia',
    'nombre_propietario_finca',
    'localidad_via',
    'coordenada_x',
    'coordenada_y',
    'denuncia_fitosanitaria',
    'nombre_denunciante',
    'telefono_denunciante',
    'direccion_denunciante',
    'correo_electronico_denunciante',
    'especie_vegetal',
    'cantidad_total',
    'cantidad_vigilada',
    'unidad',
    'sitio_operacion',
    'condicion_produccion',
    'etapa_cultivo',
    'actividad',
    'manejo_sitio_operacion',
    'ausencia_plaga',
    'plaga_diagnostico_visual_prediagnostico',
    'cantidad_afectada',
    'porcentaje_incidencia',
    'porcentaje_severidad',
    'tipo_plaga',
    'fase_desarrollo_plaga',
    'organo_afectado',
    'distribucion_plaga',
    'poblacion',
    'diagnostico_visual',
    'descripcion_sintomas_p',
    'envio_muestra',
    'usuario',
    'observaciones',
    'ruta_foto',
    'longitud_imagen',
    'latitud_imagen',
);
$tablas = 'vigilanciaf02';
require 'baseGeneradorReporte.php';
if ($incluirDatosLaboratorio) {
    incluirDatosLaboratorio($campos, $tablas, $cr->camposLaboratorio);
}
$res = $cr->generarReporteGeneralMonitoreoVigilancia($conexion, $incluirDatosLaboratorio, $fechaInicio, $fechaFin, $actividad, $especie, $diagnostico, $incidencia, $severidad);
require 'baseGeneradorReporteHTML.php';
?>




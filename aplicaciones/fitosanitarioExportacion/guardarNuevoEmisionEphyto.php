<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorWebServicesEphyto.php';
require_once '../../clases/ControladorFitosanitarioExportacion.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try {
    
    $conexion = new Conexion();
    $cws = new ControladorWebServicesEphyto();
    $cfe = new ControladorFitosanitarioExportacion();
    
    $numeroCertificado = $_POST['id_vue'];
    
    $fecha_desde = '';
    $fecha_hasta = '';
    $status = 'APPROVED';
    
    try {
        
        $cliente = $cws->conexionWebServicesFitosanitario();
        
        $obtencionXml = $cliente->Recupera_certificado_oficial($numeroCertificado);
        
        $confirmacion = $cliente->Confirmacion_certificado($numeroCertificado);
        
        $mensaje['estado'] = 'exito';
        $mensaje['mensaje'] = 'Documento enviado.';
        
        //$conexion->desconectar();
        echo json_encode($mensaje);
    } catch (Exception $ex) {
        //pg_close($conexion);
        $mensaje['estado'] = 'error';
        $mensaje['mensaje'] = "Error al ejecutar sentencia";
        echo json_encode($mensaje);
    }
} catch (Exception $ex) {
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = 'Error de conexión a la base de datos';
    echo json_encode($mensaje);
}
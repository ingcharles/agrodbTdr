<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorWebServicesEphyto.php';
require_once '../../clases/ControladorFitosanitarioExportacion.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try {
    
    $conexion = new Conexion();
    $cc = new ControladorCatalogos();
    $cws = new ControladorWebServicesEphyto();
    $cfe = new ControladorFitosanitarioExportacion();
    
    $idVue = $_POST['id_vue'];
    
    $identificadorFitosanitario = array('numero_certificado' => $idVue);

    try {
        
        $cliente = $cws->conexionWebServiceHub();
        
        $xml = $cfe->buscarFitosanitarioExportacionPorIdentificador($conexion, $identificadorFitosanitario);
        
        $fitosanitario = pg_fetch_assoc($cfe->buscarFitosanitarioExportacionVUE($conexion, $idVue));
        
        $paisOrigen = pg_fetch_assoc($cc->obtenerNombreLocalizacion($conexion, $fitosanitario['id_pais_origen']));
        $paisDestino = pg_fetch_assoc($cc->obtenerNombreLocalizacion($conexion, $fitosanitario['id_pais_destino']));
                
        $datosEntradaFitosanitario = array(
            'From'    => $paisOrigen['codigo'],
            'To'     => $paisDestino['codigo'],
            'CertificateType'  => '851',
            'CertificateStatus' => '70',
            'NPPOCertificateNumber' => $idVue,
            'Content' => $xml
        );
        
        $cliente->DeliverEnvelope(array('env'=>$datosEntradaFitosanitario));
        
        //$arrayDatosRecepcion = $cfe->obj2array($datosRecepcion);
        
        $cfe->confirmacionRecepcionFitosanitarioExportacion($conexion, $numeroCertificado);
        
        $cfe->actualizarEstadoRecepcionCertificadoFitosanitarioExportacion($conexion, $identificadorFitosanitario);
                        
        $mensaje['estado'] = 'exito';
        $mensaje['mensaje'] = 'Documento enviado.';
        
        $conexion->desconectar();
        echo json_encode($mensaje);
    } catch (Exception $ex) {
        pg_close($conexion);
        $mensaje['estado'] = 'error';
        $mensaje['mensaje'] = "Error al ejecutar sentencia";
        echo json_encode($mensaje);
    }
} catch (Exception $ex) {
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = 'Error de conexión a la base de datos';
    echo json_encode($mensaje);
}
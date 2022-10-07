<?php

require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorFitosanitarioExportacion.php';

$conexion = new Conexion();
$certificadoFitosanitario = new ControladorFitosanitarioExportacion();

try {
    
    $wsdl = "https://uat-hub.ephytoexchange.org/hub/DeliveryService?wsdl";
    $localCert = "certificado/nppo-ec.pem";
    
    $clientOptions = array(
        'local_cert' => $localCert,
        'passphrase' => 'nppoECp12',
        'soap_version' => SOAP_1_1,
        'encoding' => 'UTF-8',
        ‘location’ => 'https://uat-hub.ephytoexchange.org/hub/DeliveryService'
    );
        
    $client = new SoapClient($wsdl, $clientOptions);
    
    echo '<pre>';
        print_r($client->__getFunctions());
    echo '</pre>';
    
    /*$resultadoCertificado = $client->GetActiveNppos();    
    
    echo '<pre>';
    print_r($resultadoCertificado);
    echo '</pre>';*/
    
    $certificadosDisponibles = $certificadoFitosanitario->buscarFitosanitarioExportacionPorFechaEstado($conexion, array('estado'=>'aprobado'));
    
    print_r($certificadosDisponibles);
    
    foreach ($certificadosDisponibles as $certificado){
                
        $xml = $certificadoFitosanitario->buscarFitosanitarioExportacionPorIdentificador($conexion, array('numero_certificado' => $certificado));
                
        $datosEntradaFitosanitario = array(
            'From'    => 'EC',
            'To'     => 'EC',
            'CertificateType'  => '851',
            'CertificateStatus' => '70',
            'NPPOCertificateNumber' => '20009992201600000139P',
            'Content' => $xml
        );
        
        $client->DeliverEnvelope(array('env'=>$datosEntradaFitosanitario));
        
    }
    
    
} catch (SoapFault $fault) {
    echo ' ERROR: ' . $fault->faultcode . "-" . $fault->faultstring . $fault->__toString() . '</br>';
}
?>
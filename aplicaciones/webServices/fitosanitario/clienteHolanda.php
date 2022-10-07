<?php
require_once '../../../clases/ControladorFitosanitarioExportacion.php';

$certificadoFitosanitario = new ControladorFitosanitarioExportacion();

$parametrosConsulta = array();
$parametrosFuncionServicio = array();

$pagina = '';
$fecha_desde = '';
$fecha_hasta = '';
$status = 'APPROVED';

$clientOptions = array(
    'login' => 'EC_AgCa_01',
    'password' => 'eCerttest2201?'
);

$client = new SoapClient("http://client-export-acc.minlnv.nl/CMS-Services-context-root/APEcert?WSDL", $clientOptions);

try {
    
    // $resultado = $client->ping();
    $resultadoCertificado = $client->find_certificates_by_update_date_and_status($pagina, $fecha_desde, $fecha_hasta, $status);
    $numeroCertificado = $resultadoCertificado[5];
    
    echo '<pre>';
    print_r($resultadoCertificado);
    echo '</pre>';
    
    //foreach ($resultadoCertificado as $numeroCertificado) {
        $resultadoCertificadoSinFirma = $client->get_official_certificate_xml($numeroCertificado);
        // $resultadoCertificadoConFirma = $client->get_signed_official_certificate_xml($numeroCertificado);
        
        $arrayResultadoCertificadoSinFirma = $certificadoFitosanitario->xml2array($resultadoCertificadoSinFirma);
        // $arrayResultadoCertificadoConFirma = $certificadoFitosanitario->xml2array($resultadoCertificadoConFirma);
        
        echo '-------------------------------------------------------CERTTIFICADO SIN FIRMA---------------------------------------------------------------------------';
        
        echo '<pre>';
        print_r($arrayResultadoCertificadoSinFirma);
        echo '</pre>';
        
        /*
         * echo '----------------------------------------------------CERTTIFICADO CON FIRMA---------------------------------------------------------------------------';
         *
         * echo '<pre>';
         * print_r($arrayResultadoCertificadoConFirma);
         * echo '</pre>';
         */
        
        // TODO:ALMACENAR EN BASE DE DATOS GUIA
        
        // $client->acknowledge_certificate($numeroCertificado);
        
        /*
         * $archivo=$numeroCertificado.".txt";
         *
         * $file=fopen($archivo,"w");
         * fwrite($file, $arrayResultadoCertificadoSinFirma);
         */
    //}
} catch (SoapFault $fault) {
    echo ' ERROR: ' . $fault->faultcode . "-" . $fault->faultstring . $fault->__toString() . '</br>';
}
?>
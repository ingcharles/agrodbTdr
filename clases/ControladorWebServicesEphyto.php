<?php
class ControladorWebServicesEphyto{

    public function conexionWebServicesHolanda(){
        
        $clientOptions = array(
            'login' => 'EC_AgCa_01',
            'password' => 'eCerttest2201?'
        );
        
        $client = new SoapClient("http://client-export-acc.minlnv.nl/CMS-Services-context-root/APEcert?WSDL", $clientOptions);
        
        return $client;
    }    
    
    public function conexionWebServicesFitosanitario(){
        
        $clientOptions = array(
            'login' => 'pruebaHolanda', 
            'password' => '$rUe*aHol@nda'
        );
        
        $cliente = new SoapClient("http://192.168.20.9/agrodbPrueba/aplicaciones/webServices/fitosanitario/fitosanitarioExportacion.php?wsdl", $clientOptions);
        
        return $cliente;
        
    }
    
    public function conexionWebServiceHub(){
        
        $wsdl = "https://uat-hub.ephytoexchange.org/hub/DeliveryService?wsdl";
        $localCert = "../webServices/fitosanitario/certificado/nppo-ec.pem";
        
        $clientOptions = array(
            'local_cert' => $localCert,
            'passphrase' => 'nppoECp12',
            'soap_version' => SOAP_1_1,
            'encoding' => 'UTF-8',
            'location' => 'https://uat-hub.ephytoexchange.org/hub/DeliveryService'
        );
        
        $cliente = new SoapClient($wsdl, $clientOptions);
        
        return $cliente;
    }
}

?>
<?php

class webServicesBSGCedula{
	
	public function obj2array($obj) {
		$out = array();
		foreach ($obj as $key => $val) {
			switch (true) {
				case is_object($val):
					$out[$key] = $this->obj2array($val);
					break;
				case is_array($val):
					$out[$key] = $this->obj2array($val);
					break;
				default:
					$out[$key] = $val;
			}
		}
		return $out;
	}
	
	
	public function consultarWebServicesCedula($urlWebService, $parametros, $identificador){
		
		
		
		$par = array();
		
		$digest = $parametros["Digest"];
		$nonce = $parametros["Nonce"];
		$fechaInicio = $parametros["Fecha"];
		$fechaFin = $parametros["FechaF"];
		
		$client = new SoapClient($urlWebService, $par);
		
		$parametrosConsulta = array();
		$parametrosConsulta['Cedula'] = $identificador; 
		$parametrosConsulta['Usuario'] = 'subsecinf1';
		$parametrosConsulta['Contrasenia'] = '$UbInSeo2s';
		
		$parametrosFuncionServicio['BusquedaPorCedula'] = $parametrosConsulta;
		
		$ns = "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd";
		$user = "1722551049";
		
		$xml2 = '<wss:Security xmlns:wss="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
        <wss:UsernameToken>
            <wss:Username>' . $user . '</wss:Username>
            <wss:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">' . $digest . '</wss:Password>
            <wss:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">' . $nonce . '</wss:Nonce>
            <wsu:Created xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">' . $fechaInicio . '</wsu:Created>
         </wss:UsernameToken>
       <wsu:Timestamp xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="Timestamp-2">
       <wsu:Created>' . $fechaInicio . '</wsu:Created>
       <wsu:Expires>' . $fechaFin . '</wsu:Expires>
      </wsu:Timestamp>
      </wss:Security>';
		
		$headVar = new SoapVar($xml2, XSD_ANYXML);
		$headers = new SoapHeader('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd', 'Security', $headVar, true);
		
		$error = 0;
		try {
			$client->__setSoapHeaders($headers);
			$resultdo = $client->__call(BusquedaPorCedula, $parametrosFuncionServicio);
		} catch (SoapFault $fault) {
			$error = 1;
			print("alert(' ERROR: " . $fault->faultcode . "-" . $fault->faultstring . $fault->__toString() . ".');");
		}
		
		$resultadoWebServices = $this->obj2array($resultdo);
		//print_r($resultadoWebServices["return"]);
		return $resultadoFinal = $resultadoWebServices["return"];	

		
	}
			
}


?>
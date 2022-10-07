<?php

namespace Agrodb\Core;

class ServiciosGubernamentales {

    protected $USUARIO_ACCESO = '1103876296';
    protected $URL_WEB_SERVICES_ACCESO = 'https://www.bsg.gob.ec/sw/STI/BSGSW08_Acceder_BSG?wsdl';
    //define('URL_WEB_SERVICES_CEDULA_IDENTIFICADOR','https://www.bsg.gob.ec/sw/RC/BSGSW01_Consultar_Cedula?wsdl');
    protected $URL_WEB_SERVICES_CEDULA_IDENTIFICADOR = 'https://www.bsg.gob.ec/sw/RC/BSGSW03_Consultar_Ciudadano?wsdl';
    protected $URL_WEB_SERVICES_SRI_IDENTIFICADOR = 'https://www.bsg.gob.ec/sw/SRI/BSGSW01_Consultar_RucSRI?wsdl';
    protected $URL_WEB_SERVICES_SENECYT = 'https://www.bsg.gob.ec/sw/SENESCYT/BSGSW04_Consultar_Titulos?wsdl';
    protected $URL_WEB_SERVICES_ANT = 'https://www.bsg.gob.ec/sw/ANT/BSGSW01_Consultar_MatriculaLic?wsdl';
    
    /**
     * Error
     *
     * Contiene errores globales de la clase
     *
     * @var string
     * @access protected
     */
    protected $error = '';
    
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
	
	public function consultarWebServicesAutenticacion($urlWebService){
	
		$servicioConsultar = $urlWebService;
		$servicioAutorizacion = $this->URL_WEB_SERVICES_ACCESO;
	
		$par = array(); //TODO: Ver que es este parametro
	
		$parametrosAcceso = array();
	
		$parametrosAcceso["Cedula"] = $this->USUARIO_ACCESO;// cedula de acceso
		$parametrosAcceso["Urlsw"] = $urlWebService;
	
		$parametrosSeguridad["ValidarPermisoPeticion"] = $parametrosAcceso;
	
		$clienteWbServicesSeguridad = new \SoapClient($this->URL_WEB_SERVICES_ACCESO, $par);
		$error = 0;
	
		try {
				
			$resultadoSeguridadObj = $clienteWbServicesSeguridad->ValidarPermiso($parametrosSeguridad);
				
		} catch (\SoapFault $fault) {
			$error = 1;
			print("
            alert(' ERROR: " . $fault->faultcode . "-" . $fault->faultstring . ".');
            ");
		}
	
		$arrayResultadoSeguridad = $this->obj2array($resultadoSeguridadObj);
			
		//echo '<pre>';
		//	print_r($arrayResultadoSeguridad["return"]);
		//echo '</pre>';
			
		return	$result = $arrayResultadoSeguridad["return"];
	
	}
	
	public function crearCabeceraSeguridadWebServices($datosAutorizacion){
		
		$digest = $datosAutorizacion["Digest"];
		$nonce = $datosAutorizacion["Nonce"];
		$fechaInicio = $datosAutorizacion["Fecha"];
		$fechaFin = $datosAutorizacion["FechaF"];
		
		$ns = "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd";
		$usuario = $this->USUARIO_ACCESO;
		
		$xml2 = '<wss:Security xmlns:wss="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
        			<wss:UsernameToken>
            			<wss:Username>' . $usuario . '</wss:Username>
           				<wss:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">' . $digest . '</wss:Password>
            			<wss:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">' . $nonce . '</wss:Nonce>
            			<wsu:Created xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">' . $fechaInicio . '</wsu:Created>
         			</wss:UsernameToken>
       				<wsu:Timestamp xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="Timestamp-2">
       					<wsu:Created>' . $fechaInicio . '</wsu:Created>
       					<wsu:Expires>' . $fechaFin . '</wsu:Expires>
      				</wsu:Timestamp>
      			</wss:Security>';
		
		$headVar = new \SoapVar($xml2, XSD_ANYXML);
		$cabeceraSeguridad = new \SoapHeader('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd', 'Security', $headVar, true);
		
		return $cabeceraSeguridad;
	}
	
	
	public function consultarWebServicesCedula($cabeceraSeguridad, $identificadorConsulta){
	
		$par = array();
	
		$client = new \SoapClient($this->URL_WEB_SERVICES_CEDULA_IDENTIFICADOR, $par);
	
		$parametrosConsulta = array();
		$parametrosConsulta['NUI'] = $identificadorConsulta;
		$parametrosConsulta['Usuario'] = 'agrocalidad1';
		$parametrosConsulta['Contrasenia'] = 'F-LbLi@+moa3';
		$parametrosConsulta['CodigoInstitucion'] = '3';
		$parametrosConsulta['CodigoAgencia'] = '117';
	
		$parametrosFuncionServicio['BusquedaPorCedula'] = $parametrosConsulta;
	
		$error = 0;
		try {
			$client->__setSoapHeaders($cabeceraSeguridad);
			
			//$resultdo = $client->__call(BusquedaPorCedula, $parametrosFuncionServicio);
			$resultdo = $client->__call('BusquedaPorNui', $parametrosFuncionServicio);
		} catch (\SoapFault $fault) {
			$error = 1;
			print("alert(' ERROR: " . $fault->faultcode . "-" . $fault->faultstring . $fault->__toString() . ".');");
		}
	
		$resultadoWebServices = $this->obj2array($resultdo);
	
		/*echo '<pre>';
			print_r($resultadoWebServices["return"]);
		echo '</pre>';*/
	
		return $resultadoFinal = $resultadoWebServices["return"];
	
	
	}
	
	public function consultarWebServicesRUC($cabeceraSeguridad, $identificadorConsulta, $tipoConsulta){
	
		$par = array();
	
		$client = new \SoapClient($this->URL_WEB_SERVICES_SRI_IDENTIFICADOR, $par);
	
	
		$parametrosConsulta = array();
		$parametrosConsulta['numeroRuc'] = $identificadorConsulta;
		$parametrosConsulta['fuenteDatos'] = 'T';
		if($tipoConsulta == 'obtenerDatos'){
			$parametrosConsulta['fuenteDatos'] = '';
		}		
			
		$parametrosFuncionServicio[$tipoConsulta] = $parametrosConsulta;
			
		$error = 0;
		try {
			$client->__setSoapHeaders($cabeceraSeguridad);	
						
			$resultado = $client->__call($tipoConsulta, $parametrosFuncionServicio);
					
		} catch (\SoapFault $fault) {
			$error = 1;
			print("alert(' ERROR: " . $fault->faultcode . "-" . $fault->faultstring . $fault->__toString() . ".');");
		}
			
		$resultadoWebServices = $this->obj2array($resultado);
					
		if(isset($resultadoWebServices["return"]["actividadEconomica"]))
			//$resultadoFinal = array_slice($resultadoWebServices["return"], 0,1,true)+array(CodigoError=>'000',Error=>'NO ERROR')+array_slice($resultadoWebServices["return"],1,count($resultadoWebServices["return"])-1,true);
			$resultadoFinal =array_merge($resultadoWebServices["return"],array('CodigoError'=>'000','Error'=>'NO ERROR'));
		else
			$resultadoFinal = array('CodigoError'=>'001','Error'=>'RUC NO ENCONTRADO');
		
		/*echo '<pre>';
			print_r($resultadoFinal);
		echo '</pre>';*/
	
		return $resultadoFinal;
	
	
	}
	
	public function consultarWebServicesSenecyt($cabeceraSeguridad, $identificadorConsulta){
				
		$par = array();
		$client = new \SoapClient($this->URL_WEB_SERVICES_SENECYT, $par);
		
		$parametrosConsulta = array();
		$parametrosFuncionServicio = array();
		
		$parametrosConsulta['CedulaTitulado'] = $identificadorConsulta;		
		$parametrosFuncionServicio['ConsultadeTitulosRequest'] = $parametrosConsulta;
		
		$error = 0;
		try {
			$client->__setSoapHeaders($cabeceraSeguridad);
			$resultdo = $client->__call('ConsultadeTitulosRequest', $parametrosFuncionServicio);
		} catch (\SoapFault $fault) {
			$error = 1;
			print("alert(' ERROR: " . $fault->faultcode . "-" . $fault->faultstring . $fault->__toString() . ".');");
		}
				
		$resultadoWebServices = $this->obj2array($resultdo);
		
		if(isset($resultadoWebServices['detalleGraduado']['niveltitulos']))
			$resultadoFinal =array_merge(array('datos' => $resultadoWebServices['detalleGraduado']['niveltitulos']),array('CodigoError'=>'000','Error'=>'NO ERROR'));		
		else
			$resultadoFinal = array('CodigoError'=>'001','Error'=>'TITULO NO ENCONTRADO');
		
		/*echo '<pre>';
		print_r($resultadoFinal);
		echo '</pre>';*/
		
		return $resultadoFinal;
	}
	
	public function consultarWebServicesANT($cabeceraSeguridad, $identificadorConsulta){
		
		$par = array();
		
		$client = new \SoapClient($this->URL_WEB_SERVICES_ANT, $par);
		
		$parametrosConsulta = array();
		$parametrosConsulta['Placa'] = $identificadorConsulta;
		$parametrosConsulta['Canal'] = '1';
		$parametrosConsulta['Usuario'] = 'MINTEL';
		
		$parametrosFuncionServicio['Solicita_Matricula'] = $parametrosConsulta;
		
		$error = 0;
		try {
			$client->__setSoapHeaders($cabeceraSeguridad);
			
			$resultdo = $client->__call('Solicita_Matricula', $parametrosFuncionServicio);
		} catch (\SoapFault $fault) {
			$error = 1;
			print("alert(' ERROR: " . $fault->faultcode . "-" . $fault->faultstring . $fault->__toString() . ".');");
		}
		
		$resultadoWebServices = $this->obj2array($resultdo);
		
		if($resultadoWebServices["return"]["cod_error"] == 0)
			$resultadoFinal =array_merge($resultadoWebServices["return"],array('CodigoError'=>'000','Error'=>'NO ERROR'));
		else
			$resultadoFinal = array('CodigoError'=>'001','Error'=>$resultadoWebServices["return"]["mensaje"]);
		
	//	echo '<pre>';
	//	print_r($resultadoFinal);
	//	echo '</pre>';
		
		return $resultadoFinal;
		
		
	}
	
	
}

?>
<?php

class webServicesBSGAutenticacion{
	
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
	
	
	public function consultarWebServicesAutenticacion($urlWebService, $autorizacionWebService, $parametros){
		
		$servicioConsultar = $urlWebService;
		$servicioAutorizacion = $autorizacionWebService;
		
		$par = array(); //TODO: Ver que es este parametro
		
		$parametrosAcceso = array();
		
		$parametrosAcceso["Cedula"] = $parametros;// cedula de acceso
		$parametrosAcceso["Urlsw"] = $urlWebService;
		$parametrosSeguridad["ValidarPermisoPeticion"] = $parametrosAcceso;
		
		$clienteWbServicesSeguridad = new SoapClient($autorizacionWebService, $par);
		$error = 0;
		
		try {
			
			$resultadoSeguridadObj = $clienteWbServicesSeguridad->ValidarPermiso($parametrosSeguridad);
			
		} catch (SoapFault $fault) {
			$error = 1;
			print("
            alert(' ERROR: " . $fault->faultcode . "-" . $fault->faultstring . ".');
            ");
		}
		
		 $arrayResultadoSeguridad = $this->obj2array($resultadoSeguridadObj);
		 //print_r($arrayResultadoSeguridad["return"]);
		 return	$result = $arrayResultadoSeguridad["return"];

		
	}
			
}


?>
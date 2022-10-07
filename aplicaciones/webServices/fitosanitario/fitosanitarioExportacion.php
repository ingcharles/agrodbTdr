<?php

require_once '../../../aplicaciones/general/nusoap.php';
require_once 'funcionFitosanitarioWebServices.php';

$server = new nusoap_server();

$server->configureWSDL('Consulta certificados fitosanitarios de exportación', 'urn:certificado_fitosanitario_exportacion');


if (!isset($_SERVER['PHP_AUTH_USER'])) {
	header('WWW-Authenticate: Basic realm="Agrocalidad"');
	header('HTTP/1.0 401 Unauthorized');
	echo 'Proceso cancelado.';
	exit;
} else {

	if ($_SERVER['PHP_AUTH_USER'] == 'pruebaHolanda' && $_SERVER['PHP_AUTH_PW']=='$rUe*aHol@nda' ) {
	    
	    //------------------------------------------WEB SERVICES DE BUSQUEDA DE CERTIFIACDOS------------------------------------------------------------

		// Parametros de entrada
		/*$server->wsdl->addComplexType('datosEntradaFitosanitario',
				'complexType',
				'struct',
				'all',
				'',
				array('fecha_desde'  => array('name' => 'fecha_desde','type' => 'xsd:string'),
						'fecha_hasta'  => array('name' => 'fecha_hasta','type' => 'xsd:string'),
						'estado'       => array('name' => 'estado','type' => 'xsd:string'))
		);*/

		// Parametros de Salida
		$server->wsdl->addComplexType('datoSalidaFitosanitario',
				'complexType',
				'array',
				'',
				'SOAP-ENC:Array',
		    array(),
		    array(
		        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:array_php[]')
		    ));


		$server->register('Certificado_actualizacion_fecha_estado', // nombre del metodo o funcion
				//array('datosEntradaFitosanitario' => 'tns:datosEntradaFitosanitario'), // parametros de entrada
		        array('fecha_desde' => 'xsd:string', 'fecha_hasta' => 'xsd:string', 'estado' => 'xsd:string'),
				array('return' => 'tns:datoSalidaFitosanitario'), // parametros de salida
				'urn:certificado_fitosanitario_exportacion', // namespace
				'urn:fitosanitario#Certificado_actualizacion_fecha_estado', // soapaction debe ir asociado al nombre del metodo
				'rpc', // style
				'encoded', // use
				'La siguiente funcion recibe los parametros de fecha inicio, fecha fin y estado del registros. Retorna los datos relaciones con los fitosanitarios de exportación.' // documentation
		);


		//------------------------------------------WEB SERVICES DE RECUPERAR CERTIFICADOS OFICIALES NO FIRMADOS------------------------------------------------------------

		// Parametros de entrada
		/*$server->wsdl->addComplexType(  'datosEntradaFitosanitarioXmlNoFirmado',
				'complexType',
				'struct',
				'all',
				'',
				array('numero_certificado'   => array('name' => 'numero_certificado','type' => 'xsd:string'))
		);*/

		// Parametros de Salida
		/*$server->wsdl->addComplexType('datoSalidaFitosanitarioXml',
		 'complexType',
				'string',
				'all',
				'',
				array('mensaje'   => array('name' => 'mensaje','type' => 'xsd:string'))
		);*/


		$server->register('Recupera_certificado_oficial', // nombre del metodo o funcion
				//array('datosEntradaFitosanitarioXmlNoFirmado' => 'tns:datosEntradaFitosanitarioXmlNoFirmado'), // parametros de entrada
		        array('numero_certificado' => 'xsd:string'),
				//array('return' => 'tns:datoSalidaFitosanitarioXml'), // parametros de salida
				array("return" => "xsd:string"),
				'urn:certificado_fitosanitario_exportacion', // namespace
				'urn:fitosanitarioXml#Recupera_certificado_oficial', // soapaction debe ir asociado al nombre del metodo
				'rpc', // style
				'encoded', // use
				'La siguiente funcion recibe el parametro de identificador. Retorna el XML relacionado al fitosanitario de exportación no firmado.' // documentation
		);
		
		//------------------------------------------WEB SERVICES DE RECUPERAR CERTIFICADOS OFICIALES FIRMADOS------------------------------------------------------------
		
		
		// Parametros de entrada
		/*$server->wsdl->addComplexType(  'datosEntradaFitosanitarioXmlFirmado',
		    'complexType',
		    'struct',
		    'all',
		    '',
		    array('numero_certificado'   => array('name' => 'numero','type' => 'xsd:string'))
		    );*/
		
		// Parametros de Salida
		/*$server->wsdl->addComplexType('datoSalidaFitosanitarioXml',
		 'complexType',
		 'string',
		 'all',
		 '',
		 array('mensaje'   => array('name' => 'mensaje','type' => 'xsd:string'))
		 );*/
		
		
		$server->register(  'Recupera_certificados_firmados', // nombre del metodo o funcion
		    //array('datosEntradaFitosanitarioXmlFirmado' => 'tns:datosEntradaFitosanitarioXmlFirmado'), // parametros de entrada
		    array('numero_certificado' => 'xsd:string'),
		    //array('return' => 'tns:datoSalidaFitosanitarioXml'), // parametros de salida
		    array("return" => "xsd:string"),
		    'urn:certificado_fitosanitario_exportacion', // namespace
		    'urn:fitosanitarioXml#Recupera_certificados_firmados', // soapaction debe ir asociado al nombre del metodo
		    'rpc', // style
		    'encoded', // use
		    'La siguiente funcion recibe el parametro de identificador. Retorna el XML relacionado al fitosanitario de exportación firmado.' // documentation
		    );
		
		//------------------------------------------CONFIRMACION DE RECEPCION DEL CERTIFICADO------------------------------------------------------------
		
		
		// Parametros de entrada
		$server->wsdl->addComplexType(  'datosEntradaConfirmacionFitosanitario',
		    'complexType',
		    'struct',
		    'all',
		    '',
		    array('numero_certificado'   => array('name' => 'numero','type' => 'xsd:string'))
		    );
			
		
		$server->register(  'Confirmacion_certificado', // nombre del metodo o funcion
		    //array('datosEntradaConfirmacionFitosanitario' => 'tns:datosEntradaConfirmacionFitosanitario'), // parametros de entrada
		    array('numero_certificado' => 'xsd:string'),
		    array("return" => "xsd:string"),
		    'urn:certificado_fitosanitario_exportacion', // namespace
		    'urn:fitosanitarioXml#Confirmacion_certificado', // soapaction debe ir asociado al nombre del metodo
		    'rpc', // style
		    'encoded', // use
		    'La siguiente funcion recibe el parametro de identificador. Retorna el mensaje de recepción del consumo del certificado fitosanitario de exportación.' // documentation
		    );


		$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
		//$server->service($HTTP_RAW_POST_DATA);
		$server->service(file_get_contents("php://input"));

	}else{
		header('WWW-Authenticate: Basic realm="Agrocalidad"');
		header('HTTP/1.0 401 Unauthorized');
		echo 'Proceso cancelado.';
		exit;
	}

}

?>
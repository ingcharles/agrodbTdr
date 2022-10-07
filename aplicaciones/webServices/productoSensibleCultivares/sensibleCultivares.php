<?php

require_once '../../../aplicaciones/general/nusoap.php';
require_once 'funcionSensibleCultivares.php';

$server = new nusoap_server();

$server->configureWSDL('Consulta productos sensibles y cultivares', 'urn:productosSensiblesCultivares');

// Parametros de entrada
/*$server->wsdl->addComplexType(  'datosEntrada',
		'complexType',
		'struct',
		'all',
		'',
		array('idVue'   => array('name' => 'idVue','type' => 'xsd:string'))
);*/
// Parametros de Salida
$server->wsdl->addComplexType('datoSalida',
								'complexType',
								'struct',
								'all',
								'',
								array('mensaje'   => array('name' => 'mensaje','type' => 'xsd:string'))
);


$server->register(  'buscarImportacionProductoSensibleCultivares', // nombre del metodo o funcion
		//array('idVue' => 'xsd:string'), // parametros de entrada
		array(),
		array('return' => 'tns:datoSalida'), // parametros de salida
		'urn:productosSensiblesCultivares', // namespace
		'urn:producto#buscarImportacionProductoSensibleCultivares', // soapaction debe ir asociado al nombre del metodo
		'rpc', // style
		'encoded', // use
		'La siguiente funcion recibe los parametros de idVue y retorna los datos relaciones con los productos sensibles y cultivares.' // documentation
);


$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);

?>
<?php

require_once '../../../aplicaciones/general/nusoap.php';
require_once 'funcionPartidasArancelariasRoce.php';

$server = new nusoap_server();

$server->configureWSDL('Consulta partidas arancelarias de operadores de comercio exterior.', 'urn:partidasArancelariasPorRoce');

// Parametros de entrada
/*$server->wsdl->addComplexType(  'datoEntrada',
								'complexType',
								'struct',
								'all',
								'',
								array('identificadorOperador'   => array('name' => 'identificadorOperador','type' => 'xsd:string'))
							);*/

// Parametros de Salida
$server->wsdl->addComplexType('datoSalida',
								'complexType',
								'struct',
								'all',
								'',
								array('mensaje'   => array('name' => 'mensaje','type' => 'xsd:string'))
);


$server->register(  'buscarPartidasArancelariasPorOperadorComercioExterior', // nombre del metodo o funcion
					array("identificadorOperador" => "xsd:string"), // parametros de entrada
					array('return' => 'tns:datoSalida'), // parametros de salida
					'urn:partidasArancelariasPorRoce', // namespace
					'urn:partidas#buscarPartidasArancelariasPorOperadorComercioExterior', // soapaction debe ir asociado al nombre del metodo
					'rpc', // style
					'encoded', // use
					'La siguiente funcion recibe el parametro de identificación del ROCE y retorna los datos relaciones con las partidas arancelarias de los productos de importación.' // documentation
				);


$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
//$server->service($HTTP_RAW_POST_DATA);
$server->service(file_get_contents("php://input"));
?>
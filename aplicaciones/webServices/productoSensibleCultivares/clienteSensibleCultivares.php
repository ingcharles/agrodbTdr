<?php

 /*	require_once '../../../aplicaciones/general/nusoap.php';

	$cliente = new nusoap_client('http://182.112.155.173/agrodbPrueba/aplicaciones/webServices/productoSensibleCultivares/sensibleCultivares.php');
     
	$datosEntrada = array('idVue'=> "01009994201600000494P");
 
	$resultado = $cliente->call('buscarImportacionProductoSensibleCultivares',$datosEntrada);
	     
	echo '<pre>';
		print_r($resultado);
	echo '</pre>';*/

	set_time_limit(300);
	//default_socket_timeout = 300

	$cliente = new SoapClient(null,array("location"=>"http://181.112.155.173/agrodbPrueba/aplicaciones/webServices/productoSensibleCultivares/sensibleCultivares.php?wsdl","uri"=>""));
	
	$respuesta = $cliente->buscarImportacionProductoSensibleCultivares();
	
	echo '<pre>';
		return print_r($respuesta);
	echo '<pre>';
?>
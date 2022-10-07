<?php

	//---------------------------------------------------------------------------OPCION 1---------------------------------------------------------------------
	
 	/*require_once '../../../aplicaciones/general/nusoap.php';

	$cliente = new nusoap_client('http://181.112.155.173/agrodbPrueba/aplicaciones/webServices/operadores/partidasArancelariasPorOperador.php?wsdl');
     
	$datosEntrada = array('identificadorOperador'=> "0990004196001");
 
	$resultado = $cliente->call('buscarPartidasArancelariasPorOperadorComercioExterior',$datosEntrada);
	     
	echo '<pre>';
		print_r($resultado);
	echo '</pre>';*/
	
	//---------------------------------------------------------------------------OPCION 2---------------------------------------------------------------------

	/*$cliente = new SoapClient(null,array("location"=>"http://181.112.155.173/agrodbPrueba/aplicaciones/webServices/operadores/partidasArancelariasPorOperador.php?wsdl","uri"=>""));
	
	$respuesta = $cliente->buscarPartidasArancelariasPorOperadorComercioExterior("0990004196001");
	
	echo '<pre>';
		return print_r($respuesta);
	echo '<pre>';*/
	
	//---------------------------------------------------------------------------OPCION 3---------------------------------------------------------------------
	
		$operadorce="0990004196001";
        $client = new SoapClient("http://181.112.155.173/agrodbPrueba/aplicaciones/webServices/operadores/partidasArancelariasPorOperador.php?wsdl");
        $result = $client->buscarPartidasArancelariasPorOperadorComercioExterior($operadorce);
		
		echo $result->mensaje;
?>
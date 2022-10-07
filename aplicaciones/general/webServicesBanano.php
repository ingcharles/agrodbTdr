<?php

class webServicesBanano{

	public function verificarCupo($autorizacion, $cantidad, $producto, $identificador, $partidaArancelaria){
	
		try{
			
			$cliente = new SoapClient(null,array("location"=>"http://192.168.1.63/unibanano/vista/webservice/servicio.php?wsdl","uri"=>""));
															  
			$respuesta = $cliente->ConsultaSaldoFito($autorizacion, $cantidad, $producto,$identificador, $partidaArancelaria);
			return $respuesta;
			
		}catch (SoapFault $e) {
			echo "Error en el servicio.<br/>
			$e<hr/>";
		}
	
	}
	
	public function enviarUtilizacionCupo($autorizacion, $cantidad, $producto, $identificacion, $partida, $numeroFitosanitario, $estado, $paisDestino, $fechaEmision){
			
			$cliente = new SoapClient(null,array("location"=>"http://192.168.1.63/unibanano/vista/webservice/servicio.php?wsdl","uri"=>""));
			$cliente->registroKilosFito($autorizacion, $cantidad, $producto, $identificacion, $partida, $numeroFitosanitario, $estado, $paisDestino, $fechaEmision);
		
		try{
				
		}catch (SoapFault $e) {
			echo "Error en el servicio.<br/>
			$e<hr/>";
		}
	}
	
}


?>

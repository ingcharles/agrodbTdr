<?php
/*$cliente = new SoapClient(null,array("location"=>"http://192.168.20.5/agrodb/servicioWeb.php","uri"=>""));
 try{
$var = $cliente->obtenerOperador('1308669496');
echo $var['identificador'];
var_dump($var);
}catch (SoapFault $e) {
echo "Error: {$e->faultstring}";
}*/


$mensaje = array();



try{


	try {

		
		try{
			$cliente = new SoapClient(null,array("location"=>"http://192.168.20.5/agrodb/servicioWeb.php","uri"=>""));
			$var = $cliente->obtenerOperador($_POST['numero']);
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $var;
			
		}catch (SoapFault $e) {
			echo "Error: {$e->faultstring}";
		}
		
		echo json_encode($mensaje);
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>
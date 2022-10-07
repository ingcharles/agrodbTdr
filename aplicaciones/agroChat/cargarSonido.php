<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorChat.php';

$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$usuario = $_POST['usuario'];
$archivo = fopen('chatOpciones.txt', 'r');
$opcion = false;
$valor="";
try{
		
	try {
		
		while (!feof($archivo)) {
			$linea= fgets($archivo);
			if (stristr($linea,$usuario)) {		
				$valor=$linea;
				$opcion= true;
			}			
		}
		fclose($archivo);
	
			
		if($opcion){
			$valor = explode("=",$valor);
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $valor[1];
		} else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'true';
		}
	
					
		echo json_encode($mensaje);
	} catch (Exception $ex){
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = 'Error al ejecutar sentencia';
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}


?>
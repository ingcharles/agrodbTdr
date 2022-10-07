<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
    $idItem=$_POST["item_id"];
						
	try {
				
		if (count($idItem) != 0){
			$conexion = new Conexion();
			$cpoa = new ControladorPAPP();
			
			for ($i = 0; $i < count($idItem); $i++) {
		    	$cpoa->enviarSeguimiento($conexion, $idItem[$i], 2);		    	
			}
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'El seguimiento trimestral ha sido enviado satisfactoriamente';
			
			$conexion->desconectar();
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'No existe un seguimiento creado para envío.';
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

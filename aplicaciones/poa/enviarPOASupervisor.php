<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
    $item_id=$_POST["item_id"];
    $count = count($item_id);
						
	try {
				
		if ($count != 0){
			$conexion = new Conexion();
			$cpoa = new ControladorPAPP();
			
			for ($i = 0; $i < $count; $i++) {
		    	$cpoa->actualizarPlanta($conexion,$_SESSION['usuario'], $item_id[$i],2);
			}
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'La Proforma ha sido enviada satisfactoriamente';
			//echo 'El POA ha sido enviado satisfactoriamente';
			
			$conexion->desconectar();
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'No existe una Proforma para envío.';
			//echo 'No existe un POA para envío.';
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

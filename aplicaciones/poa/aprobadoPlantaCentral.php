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
		$conexion = new Conexion();
		$cpoa = new ControladorPAPP();
		for ($i = 0; $i < $count; $i++) {
	    //$cpoa->actualizarEstado($conexion,$_SESSION['usuario'], $item_id[$i],4,'S/N');
	    $cpoa->actualizarEstado($conexion,$_SESSION['usuario'], $item_id[$i],4,null);
	    
	    $cpoa->revisarPAPP($conexion,$item_id[$i], 'false');
		}
		
		$mensaje['estado'] = 'exito';
		//$mensaje['mensaje'] = 'El POA ha sido enviado satisfactoriamente'.$count;
		
		echo 'La Proforma ha sido aprobada satisfactoriamente';
		
		$conexion->desconectar();
		//echo json_encode($mensaje);
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

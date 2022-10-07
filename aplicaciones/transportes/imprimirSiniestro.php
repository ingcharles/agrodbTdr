<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	$datos = array('id_siniestro' => htmlspecialchars ($_POST['id'],ENT_NOQUOTES,'UTF-8'),
				   'conductor' => htmlspecialchars ($_POST['conductor'],ENT_NOQUOTES,'UTF-8'));

	
	try {
		$conexion = new Conexion();
		$cv = new ControladorVehiculos();
		
		if($datos['conductor']!=""){
			$cv->actualizarDatosSiniestroImpresion($conexion, $datos['id_siniestro']);
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'Por favor seleccione un conductor.';
		}
		
		
		
		$conexion->desconectar();
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
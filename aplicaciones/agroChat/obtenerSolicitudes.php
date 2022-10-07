<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorChat.php';


$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

//$data  = json_decode($_POST['data'], true);

$usuario= $_POST['usuario'];


$conexion = new Conexion();
$cc = new ControladorChat();

try{
		
	try {
		
		$items = array();		
		
		$res = $cc->obtenerSolicitudes($conexion,$usuario);
				
		$fila=pg_num_rows($res);
		
		
					
		if($fila>0){
				
			while($solicitud=pg_fetch_assoc($res)){
				
									
				$items[] = array(id_solicitud => $solicitud['id_solicitud'],contacto=>$solicitud['contacto'],estado=>$solicitud['estado'], estado_solicitud=>$solicitud['estado_solicitud'],  recepcion=>$solicitud['recepcion']);	
				
			}			
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = $items;
		  }else{
				$mensaje['estado'] = 'vacio';
				$mensaje['mensaje'] = 'No existen coincidencias';			
		  }
			
			
		  if(!empty($items)){
		      echo json_encode($mensaje);
		  }
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
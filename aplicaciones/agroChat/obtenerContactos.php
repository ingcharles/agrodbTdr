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
		
		$res = $cc->listarContactos ($conexion,$usuario);
				
		$fila=pg_num_rows($res);
		
		
					
		if($fila>0){
				
			while($contacto=pg_fetch_assoc($res)){
				
				if($contacto['fotografia']!=""){
					$foto=$contacto['fotografia'];
				}else{
					$foto='aplicaciones/agroChat/img/user2.png';
				}
					
				$items[] = array(contacto => $contacto['contacto'],fotografia=>$foto,estado=>$contacto['estado'], fecha=>$contacto['fecha']);	
				
			}			
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = $items;
		  }else{
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'vacio';			
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
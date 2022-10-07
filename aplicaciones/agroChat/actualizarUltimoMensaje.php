<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorChat.php';


$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$usuario= $_POST['usuario'];
$contacto= $_POST['contacto'];
$fecha= $_POST['fecha'];
$tipo= $_POST['tipo'];

$conexion = new Conexion();
$cc = new ControladorChat();

try{
		
	try {
	    
		$conexion->ejecutarConsulta("begin;");
		
		if($tipo=='#vc_'){
		    $fila=$cc->fechaUltimoMensaje($conexion,$usuario,$contacto, $fecha);	
		} else {
		    $fila=$cc->fechaUltimoMensajeGrupo($conexion,$usuario,$contacto, $fecha);	
		}
		$conexion->ejecutarConsulta("commit;");
			
		if($fila){	
				
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Consulta ejecutada con éxito';
		
		}else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = 'Ha ocurrido un error al actualizar fecha mensaje';
				
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
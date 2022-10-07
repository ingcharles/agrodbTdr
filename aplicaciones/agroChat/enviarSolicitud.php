<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorChat.php';


$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$data  = json_decode($_POST['data'], true);

$usuario= $_POST['usuario'];
$contacto= $_POST['contacto'];

$conexion = new Conexion();
$cc = new ControladorChat();

try{
		
	try {
		
		$items = array();		
		
		$conexion->ejecutarConsulta("begin;");
		
		$res=$cc->enviarSolicitud($conexion,$usuario, $contacto);	
		
		$conexion->ejecutarConsulta("commit;");
			
		if($res){
			$fila = pg_fetch_row($res);
			if($fila<=0){
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Consulta ejecutada con éxito';
			} else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = 'solicitud pendiente';
			}
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
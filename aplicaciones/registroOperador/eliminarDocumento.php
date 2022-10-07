<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	$datos = array( 'idDocumento' => htmlspecialchars ($_POST['idDocumento'],ENT_NOQUOTES,'UTF-8'));
	          
	//print_r($_POST);
		 
	try {
		$conexion = new Conexion();
		$cr = new ControladorRegistroOperador();
		
		$cr->eliminarDocumentoAnexo($conexion, $datos['idDocumento']);
						
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'El documento ha sido eliminado.';
		
		
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
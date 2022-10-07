<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacaciones.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$conexion = new Conexion();
	$cv = new ControladorVacaciones();
	
	$identificador=$_SESSION['usuario'];
	$rutaArchivo=$_POST['archivo'];
	$id_registro=$_POST['id_registro'];
	
	try {	
						
		$cv->actualizarCertificadoPermiso($conexion,$id_registro,$rutaArchivo);
		
		//Registro de observaciones del proceso
		$cv->agregarObservacion($conexion, "El usuario ".$identificador." ha cargado un certificado de respaldo para el permiso.",
				$id_registro, $identificador);
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'El documento se ha registrado satisfactoriamente';
			
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
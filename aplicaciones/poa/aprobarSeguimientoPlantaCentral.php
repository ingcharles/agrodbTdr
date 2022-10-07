<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
    $idSeguimiento=$_POST['idSeguimiento'];
    				
	try {
		$conexion = new Conexion();
		$cpoa = new ControladorPAPP();
		
		for ($i = 0; $i < count($idSeguimiento); $i++) {
	    	$cpoa->revisionSeguimientoTrimestralPlanta($conexion, $idSeguimiento[$i], $_SESSION['usuario'], 4);
		}
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'El seguimiento trimestral ha sido aprobado.';
		
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

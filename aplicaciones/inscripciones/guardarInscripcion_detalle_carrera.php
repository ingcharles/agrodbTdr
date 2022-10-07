<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorInscripciones.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	$inspeccion = htmlspecialchars ($_POST['inscripcion'],ENT_NOQUOTES,'UTF-8');
	$equipo = htmlspecialchars ($_POST['equipo'],ENT_NOQUOTES,'UTF-8');
	$tipoCarrera = htmlspecialchars ($_POST['tipoCarrera'],ENT_NOQUOTES,'UTF-8');
	$estado = htmlspecialchars ($_POST['estado'],ENT_NOQUOTES,'UTF-8');
	
	try {
		$conexion = new Conexion();
		$ci = new ControladorInscripciones();
		
		$idCausa = pg_fetch_row($ci->guardarInscripcionCarrera($conexion, $inspeccion, $equipo, $tipoCarrera, $estado));
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'GRACIAS!!!';
		
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
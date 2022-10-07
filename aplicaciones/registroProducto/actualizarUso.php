<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRequisitos.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$idUso = htmlspecialchars ($_POST['idUso'],ENT_NOQUOTES,'UTF-8');	
	$idArea = trim(htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8'));
	$nombreCientifico = trim(htmlspecialchars ($_POST['nombreCientifico'],ENT_NOQUOTES,'UTF-8'));
	$nombreComun = trim(htmlspecialchars ($_POST['nombreComun'],ENT_NOQUOTES,'UTF-8'));
		
	try {
		$conexion = new Conexion();
		$cr = new ControladorRequisitos();
		$cr->actualizarUsoInocuidad($conexion, $idUso, $nombreCientifico, $nombreComun, $idArea);
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos fuerón actualizados';
		
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

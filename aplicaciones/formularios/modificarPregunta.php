<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFormularios.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{

	$idPregunta = htmlspecialchars ($_POST['idPregunta'],ENT_NOQUOTES,'UTF-8');
	$nombre = htmlspecialchars ($_POST['nombre'],ENT_NOQUOTES,'UTF-8');
    $ayuda = htmlspecialchars ($_POST['ayuda'],ENT_NOQUOTES,'UTF-8');
	try {
		$conexion = new Conexion();
		$cf = new ControladorFormularios();

		$cf->actualizarPregunta($conexion, $idPregunta, $nombre, $ayuda);

		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos fueron actualizados';

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
<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFormularios.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	$formulario = htmlspecialchars ($_POST['formulario'],ENT_NOQUOTES,'UTF-8');
	$categoria = htmlspecialchars ($_POST['categoria'],ENT_NOQUOTES,'UTF-8');
	$pregunta = htmlspecialchars ($_POST['pregunta'],ENT_NOQUOTES,'UTF-8');
    $ayuda = htmlspecialchars ($_POST['ayuda'],ENT_NOQUOTES,'UTF-8');

    $tipoPregunta = htmlspecialchars ($_POST['tipoPregunta'],ENT_NOQUOTES,'UTF-8');
	
	try {
		$conexion = new Conexion();
		$cf = new ControladorFormularios();
		
		$idPregunta = pg_fetch_row($cf->ingresarNuevaPregunta($conexion, $formulario, $categoria, $pregunta,$tipoPregunta, $ayuda));
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $cf->imprimirLineaPregunta($idPregunta[0], $pregunta, $categoria);
		
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
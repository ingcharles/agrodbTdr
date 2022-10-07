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
	$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');

    $ponderacion = htmlspecialchars ($_POST['ponderacion'],ENT_NOQUOTES,'UTF-8');
	
	try {
		$conexion = new Conexion();
		$cf = new ControladorFormularios();
		
		$idOpcion = pg_fetch_row($cf->ingresarNuevaOpcion($conexion, $formulario, $categoria, $pregunta,$opcion, $ponderacion));
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $cf->imprimirLineaOpcion($idOpcion[0], $opcion, $ponderacion);
		
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
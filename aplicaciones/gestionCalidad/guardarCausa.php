<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorGestionCalidad.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	$hallazgo = htmlspecialchars ($_POST['hallazgo'],ENT_NOQUOTES,'UTF-8');
	$causa = htmlspecialchars ($_POST['causa'],ENT_NOQUOTES,'UTF-8');
	
	try {
		$conexion = new Conexion();
		$cgc = new ControladorGestionCalidad();
		
		$idCausa = pg_fetch_row($cgc->ingresarNuevaCausa($conexion, $hallazgo, $causa));
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $cgc->imprimirLineaCausa($idCausa[0], $causa);
		
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
<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorResoluciones.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{

	$nivel = htmlspecialchars ($_POST['nivel'],ENT_NOQUOTES,'UTF-8');
	$numero = htmlspecialchars ($_POST['numero'],ENT_NOQUOTES,'UTF-8');
	$contenido = htmlspecialchars ($_POST['contenido'],ENT_NOQUOTES,'UTF-8');
	$idEstructura = htmlspecialchars ($_POST['estructura'],ENT_NOQUOTES,'UTF-8');
	
	try {
		$conexion = new Conexion();
		$cr = new ControladorResoluciones();

		$cr->actualizarEstructura($conexion, $idEstructura, $nivel, $numero, $contenido);

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
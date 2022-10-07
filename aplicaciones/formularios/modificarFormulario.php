<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFormularios.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{

	$formulario = htmlspecialchars ($_POST['idFormulario'],ENT_NOQUOTES,'UTF-8');
	$nombreFormulario = htmlspecialchars ($_POST['nombre'],ENT_NOQUOTES,'UTF-8');
	$codigoFormulario = htmlspecialchars ($_POST['codigo'],ENT_NOQUOTES,'UTF-8');
	$descripcionFormulario = htmlspecialchars ($_POST['descripcion'],ENT_NOQUOTES,'UTF-8');
	

	try {
		$conexion = new Conexion();
		$cf = new ControladorFormularios();

		$cf->actualizarFormulario($conexion, $formulario, $nombreFormulario, $codigoFormulario, $descripcionFormulario);

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
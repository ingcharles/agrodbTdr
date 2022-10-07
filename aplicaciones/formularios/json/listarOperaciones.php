<?php
session_start();
require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorFormularios.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido  un error!';


try{
	
// 	$idFormulario = htmlspecialchars ($_POST['id_formulario'],ENT_NOQUOTES,'UTF-8');
// 	$idOperacion = htmlspecialchars ($_POST['id_operacion'],ENT_NOQUOTES,'UTF-8');
// 	$idProducto = htmlspecialchars ($_POST['id_producto'],ENT_NOQUOTES,'UTF-8');
	
	$provincia = htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8');
	$operador = htmlspecialchars ($_POST['parametroBusqueda'],ENT_NOQUOTES,'UTF-8');
	
	
	try {
		$conexion = new Conexion();
		$cf = new ControladorFormularios();
		
		//$provincia = '';//TODO: Determinar la provincia del inspector para filtar los operadores a su provincia
		
		$operaciones = $cf->jsonListarOperaciones($conexion, $operador, $provincia);
		
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $operaciones[array_to_json];
		
		
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
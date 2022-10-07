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
	
	try {
		$conexion = new Conexion();
		$cf = new ControladorFormularios();
		
		$idCategoria = pg_fetch_row($cf->ingresarNuevaCategoria($conexion, $formulario, $categoria));
		
		$mensaje['estado'] = 'exito';
		//$mensaje['mensaje'] = $idCategoria[0];
		$mensaje['mensaje'] = $cf->imprimirLineaCategoria($idCategoria[0], $categoria, $formulario);
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
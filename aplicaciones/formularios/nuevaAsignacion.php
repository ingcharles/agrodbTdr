<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFormularios.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	$operacion = htmlspecialchars ($_POST['operacion'],ENT_NOQUOTES,'UTF-8');
	$formulario = htmlspecialchars ($_POST['formulario'],ENT_NOQUOTES,'UTF-8');
    $nombreFormulario = htmlspecialchars ($_POST['nombreFormulario'],ENT_NOQUOTES,'UTF-8');
    try {
		$conexion = new Conexion();
		$cf = new ControladorFormularios();
		
		$idFormularioAsignado = pg_fetch_row($cf->asignarFormulario($conexion, $operacion, $formulario));
		
		$mensaje['estado'] = 'exito';
        $mensaje['formulario'] = $formulario;
		$mensaje['mensaje'] = $cf->imprimirLineaFormulario($idFormularioAsignado[0], $nombreFormulario);
		
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
<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorOrdenamiento.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	$idRegistro = htmlspecialchars ($_POST['idRegistro'],ENT_NOQUOTES,'UTF-8');
	$accion = htmlspecialchars ($_POST['accion'],ENT_NOQUOTES,'UTF-8');
	$tabla = htmlspecialchars ($_POST['tabla'],ENT_NOQUOTES,'UTF-8');
	
	try {
		$conexion = new Conexion();
		$cf = new ControladorOrdenamiento();
		
		switch ($accion){
			case 'SUBIR':
				$cf->aumentarOrden($conexion, $idRegistro, $tabla);
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = $idRegistro;
				break;
			case 'BAJAR':
				$cf->disminuirOrden($conexion, $idRegistro, $tabla);
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = $idRegistro;
				break;
			default:
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = 'Nada se ha ejecutado';
				
		}
		
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
<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMensualizacionDecimos.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!'.$_POST['boton'];


try{
	$identificador = htmlspecialchars ($_POST['identificador'],ENT_NOQUOTES,'UTF-8');
	
	$datos = array(	'anio' => htmlspecialchars ($_POST['anio'],ENT_NOQUOTES,'UTF-8'),
					'rutaMensualizacionDecimo' => htmlspecialchars ($_POST['archivoMensualizacionDecimo'],ENT_NOQUOTES,'UTF-8'), 
					'respuestaMensualizacionDecimo' =>  htmlspecialchars ($_POST['mensualizacionDecimo'],ENT_NOQUOTES,'UTF-8'));

	try {
		$conexion = new Conexion();
		$cms = new ControladorMensualizacionDecimos();

		if ($identificador != ''){
			$cms->guardarMensualizacionDecimos($conexion, $identificador, $datos['anio'],  $datos['respuestaMensualizacionDecimo'],  $datos['rutaMensualizacionDecimo']);
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente.';
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Su sesión expiró, por favor ingrese nuevamente al sistema";
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
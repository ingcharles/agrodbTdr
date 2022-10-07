<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	//ids de operaciones
	$operacion = array_keys(array_count_values($_POST['id'])); 
	$idCoordinador = $_POST['idCoordinador'];
	$identificadorInspector = $_POST['inspector'];
	
	try {
		$conexion = new Conexion();
		$cr = new ControladorRegistroOperador();
		
		//Operaciones - Inspectores
		for ($i = 0; $i < count ($operacion); $i++) {
			$res= $cr->guardarNuevoInspector($conexion, $operacion[$i], $identificadorInspector, $idCoordinador);
			$res= $cr->enviarOperacion($conexion, $operacion[$i], 'asignado');
		}
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
		
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
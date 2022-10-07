<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorLotes.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	try {
		$conexion = new Conexion();		
		$cl = new ControladorLotes();
		
		$registro=explode(",",$_POST['id']);
		
		for ($i = 0; $i < count ($registro); $i++) {
			$res=$cl->ObtenerRegistro($conexion, $registro[$i]);
			$fila=pg_fetch_assoc($res);
			$estado=$fila['estado'];
			if($estado!=2){
			$cl->estadoRegistro($conexion,9, $registro[$i]);
			} else{
				$cl->estadoRegistro($conexion,$estado, $registro[$i]);
			}
			
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
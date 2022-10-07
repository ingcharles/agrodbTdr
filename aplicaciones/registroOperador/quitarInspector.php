<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$idOperacion = $_POST['idOperacion'];
	$idInspector = $_POST['idInspector'];
	try {
		$conexion = new Conexion();
		$cr = new ControladorRegistroOperador();
		
		$cr->eliminarInspectorAsignado($conexion, $idOperacion, $idInspector);
		
		$res = pg_num_rows($cr->listarInspectoresAsignados($conexion, $idOperacion));
		
		if ($res==0){
			$res= $cr->enviarOperacion($conexion, $idOperacion, 'enviado');

			$mensaje['mensaje'] = 'Debe asignar la solicitud a un nuevo inspector.';
		}else{
			$mensaje['mensaje'] = 'El inspector ha sido eliminado satisfactoriamente.';
		}
		$mensaje['estado'] = 'exito';
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

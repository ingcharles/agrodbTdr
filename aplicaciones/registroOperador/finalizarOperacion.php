<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	$datos = array('identificador' => htmlspecialchars ($_POST['identificador'],ENT_NOQUOTES,'UTF-8'),
				   'idSolicitud' => htmlspecialchars ($_POST['idSolicitud'],ENT_NOQUOTES,'UTF-8'),
				   'resultado' => htmlspecialchars ($_POST['resultado'],ENT_NOQUOTES,'UTF-8'),
				   'observacion' => htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8'));

	
	try {
		$conexion = new Conexion();
		$cr = new ControladorRegistroOperador();
		
		$solicitud = $cr-> abrirOperacion($conexion, $datos['identificador'], $datos['idSolicitud']);
		
		if($solicitud[0]['informe']!=''){
			$cr->finalizarSolicitud($conexion, $datos['idSolicitud'], $datos['resultado'], $datos['observacion']);
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
			//$mensaje['mensaje'] = $solicitud[0]['informe'].'-'.$datos['idSolicitud'].'-'.$datos['idSolicitud'];
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'Por favor cargue el informe de inspección.';
			//$mensaje['mensaje'] = $solicitud[0]['informe'].'-'.$datos['idSolicitud'].'-'.$datos['identificador'];
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
<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!'.$_POST['boton'];


try{
	$identificadorUsuarioRegistro = htmlspecialchars ($_POST['identificadorUsuarioRegistro'],ENT_NOQUOTES,'UTF-8');
	
	$datos = array('id_siniestro' => htmlspecialchars ($_POST['siniestro'],ENT_NOQUOTES,'UTF-8'),
			       'tipo_siniestro' => htmlspecialchars ($_POST['tipo_siniestro'],ENT_NOQUOTES,'UTF-8'),
				   'observacion_siniestro' => htmlspecialchars ($_POST['observaciones'],ENT_NOQUOTES,'UTF-8'),
				   'fecha_siniestro' => htmlspecialchars ($_POST['fecha'],ENT_NOQUOTES,'UTF-8'),
				   'lugar_siniestro' => htmlspecialchars ($_POST['lugar_siniestro'],ENT_NOQUOTES,'UTF-8'),
				   'magnitud_danio_siniestro' => htmlspecialchars ($_POST['magnitud_siniestro'],ENT_NOQUOTES,'UTF-8'),
				   'conductor' => htmlspecialchars ($_POST['ocupante'],ENT_NOQUOTES,'UTF-8'));
	
	try {
		$conexion = new Conexion();
		$cv = new ControladorVehiculos();
	
		if ($identificadorUsuarioRegistro != ''){
			$cv->actualizarDatosSiniestro($conexion, $datos['id_siniestro'], $datos['tipo_siniestro'], $datos['fecha_siniestro'], $datos['lugar_siniestro'],$datos['observacion_siniestro'],$datos['conductor'],$datos['magnitud_danio_siniestro'], $identificadorUsuarioRegistro);
			
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
<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorFinanciero.php';
	
	$mensaje = array();
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$identificadorUsuarioRegistro = htmlspecialchars ($_POST['identificadorUsuarioRegistro'],ENT_NOQUOTES,'UTF-8');
	
	$datos = array( 'observacion' => htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8'));
	          
	$ordenPago = $_POST['id'];
	
	 
	try {
		$conexion = new Conexion();
		$cf = new ControladorFinanciero();
		
		if ($identificadorUsuarioRegistro != ''){			
			$cf->darBajaOrdenPago($conexion, $ordenPago, $datos['observacion']);
					
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
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
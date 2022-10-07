<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$identificador = htmlspecialchars ($_POST['identificador'],ENT_NOQUOTES,'UTF-8');
	
	$datos = array(	'declaracionJuramentada' => htmlspecialchars ($_POST['declaracionJuramentada'],ENT_NOQUOTES,'UTF-8'),
					'fechaDeclaracion' => htmlspecialchars ($_POST['fecha_declaracion'],ENT_NOQUOTES,'UTF-8'));

	try {
		$conexion = new Conexion();
		$cc = new ControladorCatastro();

		if ($identificador != ''){
			$cc->modificarDeclaracionJuramentada ($conexion, '', '', '','Finalizado','',$identificador,'');
			$cc->guardarDeclaracionJuramentada ($conexion, $identificador, $datos['declaracionJuramentada'],$datos['fechaDeclaracion']);
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente.';

		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Su sesión expiró, por favor ingrese nuevamente al sistema";
		}
			
		$conexion->desconectar();
		echo json_encode($mensaje);
	} catch (Exception $ex){
		$err = preg_replace( "/\r|\n/", " ", $conexion->mensajeError);
		$conexion->ejecutarLogsTryCatch($ex.'--'.$err);
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$conexion->ejecutarLogsTryCatch($ex);
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>
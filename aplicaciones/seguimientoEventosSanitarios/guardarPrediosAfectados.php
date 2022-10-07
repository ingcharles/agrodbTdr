<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEventoSanitario.php';

$ruta = 'seguimientoEventosSanitarios';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$conexion = new Conexion();
	$cpco = new ControladorEventoSanitario();
	
	$identificador = $_SESSION['usuario'];
	
	$idEventoSanitario = htmlspecialchars ($_POST['idEventoSanitario'],ENT_NOQUOTES,'UTF-8');
	$prediosAfectados  = htmlspecialchars ($_POST['prediosAfectados'],ENT_NOQUOTES,'UTF-8');
	$cuantosPrediosAfectados = htmlspecialchars ($_POST['cuantosPrediosAfectados'],ENT_NOQUOTES,'UTF-8');
	
	try{
			
		if(($identificador != null) || ($identificador != '')){
			
			$conexion->ejecutarConsulta("begin;");
			
			$cpco->modificarEventoSanitarioPrediosAfectados($conexion, $idEventoSanitario, $prediosAfectados, $cuantosPrediosAfectados, $identificador);
				
			$conexion->ejecutarConsulta("commit;");
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Se han guardado los datos.';
						
			$conexion->desconectar();
	
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'Su sesión ha expirado, por favor ingrese nuevamente al sistema para continuar.';
		
			$conexion->desconectar();
		}
		
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
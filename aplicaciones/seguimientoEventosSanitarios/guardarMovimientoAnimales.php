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
	$movimientoAnimal  = htmlspecialchars ($_POST['movimientoAnimal'],ENT_NOQUOTES,'UTF-8');
	$enfermaronMovimientoAnimal = htmlspecialchars ($_POST['enfermaronMovimientoAnimal'],ENT_NOQUOTES,'UTF-8');
	
	try{	
		if(($identificador != null) || ($identificador != '')){
			
			$conexion->ejecutarConsulta("begin;");
			
			$cpco->modificarEventoSanitarioMovimientosAnimales($conexion, $idEventoSanitario, $movimientoAnimal, $enfermaronMovimientoAnimal, $identificador);
				
			$conexion->ejecutarConsulta("commit;");
			
			$conexion->desconectar();
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Se han guardado los datos.';
				
	
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
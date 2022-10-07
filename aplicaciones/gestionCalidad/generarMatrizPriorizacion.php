<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorGestionCalidad.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	$hallazgo = htmlspecialchars ($_POST['hallazgo'],ENT_NOQUOTES,'UTF-8');
	$nuevoEstado = 'Por definir causa raíz';
	try {
		$conexion = new Conexion();
		$cgc = new ControladorGestionCalidad();
		
		
		//TODO: empezar transacción BEGIN
		$cgc->generarMatrizDePriorizacion($conexion, $hallazgo);
		$cgc->cambiarEstadoDeHallazgo($conexion, $hallazgo, $nuevoEstado);
		
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'La matriz de priorización se ha generado con éxito';//
		$mensaje['nuevoEstado'] = $nuevoEstado;
		$mensaje['matriz']= $cgc->imprimirMatrizDePriorizacion($conexion, $hallazgo);
		
		//TODO: finalizar transacción COMMIT;
		$conexion->desconectar();
		echo json_encode($mensaje);
	} catch (Exception $ex){
		//TODO: finalizar transacción ROLLBACK;
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
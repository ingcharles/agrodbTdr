<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastroProducto.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

    $idModificacionIdentificador = htmlspecialchars ($_POST['idModificacionIdentificador'],ENT_NOQUOTES,'UTF-8');
    $identificadorResponsable = $_SESSION['usuario'];
    $habilitarModificacionIdentificador = htmlspecialchars ($_POST['modificarIdentificador'],ENT_NOQUOTES,'UTF-8');
    $observacionModificacion = htmlspecialchars ($_POST['observacionModificacion'],ENT_NOQUOTES,'UTF-8');
	
	try {
		$conexion = new Conexion();
		$cp = new ControladorCatastroProducto();		
		
		$cp->actualizarHabilibitacionOperadorModificacionIdentificador($conexion, $idModificacionIdentificador, $habilitarModificacionIdentificador, $observacionModificacion, $identificadorResponsable);
				
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos fueron actualizados';

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
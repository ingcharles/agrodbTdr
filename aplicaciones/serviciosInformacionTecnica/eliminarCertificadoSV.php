<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorServiciosInformacionTecnica.php';



$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	try {
		$conexion = new Conexion();
		$controladorInformacion = new ControladorServiciosInformacionTecnica();
		
		$registro=explode(",",$_POST['id']);
		$guardarDetalle="";
		
		for ($i = 0; $i < count ($registro); $i++) {
		    $guardarDetalle.= "(".$registro[$i].",9),";
		}
		
		$trim ="";
		$trim = rtrim($guardarDetalle,",");		
		$controladorInformacion->eliminarCertificados($conexion,$trim);
		
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
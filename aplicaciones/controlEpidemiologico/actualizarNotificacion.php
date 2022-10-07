<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorControlEpidemiologico.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!'.$_POST['boton'];


try{
	
	$idNotificacion = htmlspecialchars (trim($_POST['idNotificacion']),ENT_NOQUOTES,'UTF-8');
	$identificadorNotificante = htmlspecialchars (trim($_POST['identificadorNotificante']),ENT_NOQUOTES,'UTF-8');
	$nombreNotificante = htmlspecialchars (trim($_POST['nombreNotificante']),ENT_NOQUOTES,'UTF-8');
	$apellidoNotificante = htmlspecialchars (trim($_POST['apellidoNotificante']),ENT_NOQUOTES,'UTF-8');
	$telefonoNotificante = htmlspecialchars (trim($_POST['telefonoNotificante']),ENT_NOQUOTES,'UTF-8');
	$celularNotificante = htmlspecialchars (trim($_POST['celularNotificante']),ENT_NOQUOTES,'UTF-8');
	$codigoSitio = htmlspecialchars (trim($_POST['sitio']),ENT_NOQUOTES,'UTF-8');
	$idEspecie = htmlspecialchars (trim($_POST['especie']),ENT_NOQUOTES,'UTF-8');
	$especie = htmlspecialchars (trim($_POST['nombreEspecie']),ENT_NOQUOTES,'UTF-8');
	$poblacionAfectada = htmlspecialchars (trim($_POST['poblacionAfectada']),ENT_NOQUOTES,'UTF-8');
	$patologia = htmlspecialchars (trim($_POST['patologia']),ENT_NOQUOTES,'UTF-8');
		

	try {
		$conexion = new Conexion();
		$ce = new ControladorControlEpidemiologico();
				
		$ce->actualizarNotificacion($conexion, $idNotificacion, $identificadorNotificante, $nombreNotificante, 
									$apellidoNotificante, $telefonoNotificante, $celularNotificante, $codigoSitio,
									$idEspecie, $especie, $poblacionAfectada, $patologia);
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente.';

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
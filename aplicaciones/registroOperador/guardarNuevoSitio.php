<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$datos = array('nombreSitio' => htmlspecialchars ($_POST['nombreSitio'],ENT_NOQUOTES,'UTF-8'), 
			    'superficieTotal' => htmlspecialchars ($_POST['superficieTotal'],ENT_NOQUOTES,'UTF-8'),
				'provincia' => htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8'),
				'canton' => htmlspecialchars ($_POST['canton'],ENT_NOQUOTES,'UTF-8'),
				'parroquia' => htmlspecialchars ($_POST['parroquia'],ENT_NOQUOTES,'UTF-8'),
				'direccion' => htmlspecialchars ($_POST['direccion'],ENT_NOQUOTES,'UTF-8'),
				'referencia' => htmlspecialchars ($_POST['referencia'],ENT_NOQUOTES,'UTF-8'),
				'telefono' => htmlspecialchars ($_POST['telefono'],ENT_NOQUOTES,'UTF-8'),
				'archivo' => htmlspecialchars ($_POST['archivo'],ENT_NOQUOTES,'UTF-8'),
				'latitud' => htmlspecialchars ($_POST['latitud'],ENT_NOQUOTES,'UTF-8'),
				'longitud' => htmlspecialchars ($_POST['longitud'],ENT_NOQUOTES,'UTF-8'),
				'zona' => htmlspecialchars ($_POST['zona'],ENT_NOQUOTES,'UTF-8'),
				'archivo' => htmlspecialchars ($_POST['archivo'],ENT_NOQUOTES,'UTF-8'));
	
	$nombreArea= $_POST['hNombreArea'];
	$tipoArea= $_POST['hTipoArea'];
	$superficie= $_POST['hSuperficie'];
	$codigoArea = $_POST['hCodigo'];
	
	$identificador = $_SESSION['usuario'];
	
	try {
		$conexion = new Conexion();
		$cr = new ControladorRegistroOperador();
		$cc = new ControladorCatalogos();
		
		$res = $cc -> obtenerNombreLocalizacion($conexion, $datos['provincia']);
		$provincia = pg_fetch_assoc($res);
		
		$res = $cc -> obtenerNombreLocalizacion($conexion, $datos['canton']);
		$canton = pg_fetch_assoc($res);
		
		$res = $cc -> obtenerNombreLocalizacion($conexion, $datos['parroquia']);
		$parroquia = pg_fetch_assoc($res);
		
		$qSecuencialSitio = $cr->obtenerSecuencialSitio($conexion, $provincia['nombre'], $identificador);
		$secuencialSitio = str_pad(pg_fetch_result($qSecuencialSitio, 0, 'valor'), 2, "0", STR_PAD_LEFT);
		
		$qIdSitio = $cr->guardarNuevoSitio($conexion, $datos['nombreSitio'], $provincia['nombre'], $canton['nombre'], $parroquia['nombre'], $datos['direccion'], $datos['referencia'], $datos['superficieTotal'], $identificador, $datos['telefono'], $datos['latitud'], $datos['longitud'], $secuencialSitio, $datos['archivo'], $datos['zona'], substr($provincia['codigo_vue'],1));
		$idSitio = pg_fetch_assoc($qIdSitio);
		
		
		for ($i = 0; $i < count ($nombreArea); $i++) {
			$qSecuencialArea = $cr-> obtenerSecuencialArea($conexion, $identificador, $codigoArea[$i], $provincia['nombre']);
			$secuencialArea = str_pad(pg_fetch_result($qSecuencialArea, 0, 'valor'), 2, "0", STR_PAD_LEFT);
			$area = $cr -> guardarNuevaArea($conexion, $nombreArea[$i], $tipoArea[$i], $superficie[$i], $idSitio['id_sitio'], $codigoArea[$i], $secuencialArea);
		}
		
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
<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
		$nombreArea = $_POST['nombreArea'];
		$tipoArea = $_POST['tipoArea'];
		$superficie = $_POST['superficie'];
		$idSitio = $_POST['idSitioP'];
		$codigoArea = $_POST['codigoTipoArea'];
		$codigoProvincia = $_POST['codigoProvincia'];
		
		$identificador = $_SESSION['usuario'];
		
		
	try {
		$conexion = new Conexion();
		$cr = new ControladorRegistroOperador();
		$cc = new ControladorCatalogos();
		
		$res = $cc -> obtenerNombreLocalizacion($conexion, $codigoProvincia);
		$provincia = pg_fetch_assoc($res);
		
		$qSecuencialArea = $cr-> obtenerSecuencialArea($conexion, $identificador, $codigoArea, $provincia['nombre']);
		$secuencial = str_pad(pg_fetch_result($qSecuencialArea, 0, 'valor'), 2, "0", STR_PAD_LEFT);
			

		$cr->guardarNuevaArea($conexion, $nombreArea, $tipoArea, $superficie, $idSitio, $codigoArea, $secuencial);
		
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'El area ha sido actualizado satisfactoriamente.';
		
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

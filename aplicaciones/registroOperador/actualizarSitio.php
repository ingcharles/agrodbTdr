<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	$datos = array('nombreSitio' => htmlspecialchars ($_POST['nombreSitio'],ENT_NOQUOTES,'UTF-8'),
			'superficieTotal' =>  htmlspecialchars ($_POST['superficieTotal'],ENT_NOQUOTES,'UTF-8'),
			'provincia' => htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8'),
			'canton' => htmlspecialchars ($_POST['canton'],ENT_NOQUOTES,'UTF-8'),
			'parroquia' => htmlspecialchars ($_POST['parroquia'],ENT_NOQUOTES,'UTF-8'),
			'direccion' => htmlspecialchars ($_POST['direccion'],ENT_NOQUOTES,'UTF-8'),
			'referencia' => htmlspecialchars ($_POST['referencia'],ENT_NOQUOTES,'UTF-8'),
			'latitud' => htmlspecialchars ($_POST['latitud'],ENT_NOQUOTES,'UTF-8'),
			'longitud' => htmlspecialchars ($_POST['longitud'],ENT_NOQUOTES,'UTF-8'),
			'zona' => htmlspecialchars ($_POST['zona'],ENT_NOQUOTES,'UTF-8'),
			'telefono' => htmlspecialchars ($_POST['telefono'],ENT_NOQUOTES,'UTF-8'));
	
		$idSitio = $_POST['idSitioS'];
		$identificador = $_SESSION['usuario'];
		
	try {
		$conexion = new Conexion();
		$cr = new ControladorRegistroOperador();
		$cc  = new ControladorCatalogos();
		
		$sitiosOperacion = $cr->verificarSitioOperacion($conexion, $idSitio);
		$sitio = $cr->abrirSitio($conexion, $idSitio);
		
		
		if( pg_num_rows($sitiosOperacion) > 0){
			$cr->actualizarSitioEnUso($conexion, $datos['nombreSitio'], $datos['superficieTotal'], $datos['referencia'], $datos['telefono'], $idSitio, $identificador);
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'El sitio ha sido actualizado satisfactoriamente.';
		}else{
			$qLocalizacion = $cc->obtenerNombreLocalizacion($conexion, $datos['provincia']);
			$provincia = pg_fetch_assoc($qLocalizacion);
			
			$qLocalizacion = $cc->obtenerNombreLocalizacion($conexion, $datos['canton']);
			$canton = pg_fetch_assoc($qLocalizacion);
			
			$qLocalizacion = $cc->obtenerNombreLocalizacion($conexion, $datos['parroquia']);
			$parroquia = pg_fetch_assoc($qLocalizacion);
			
			if(pg_fetch_result($sitio, 0, 'provincia')!= $provincia['nombre']){
				$qSecuencialSitio = $cr->obtenerSecuencialSitio($conexion, $provincia['nombre'], $identificador);
				$secuencialSitio = str_pad(pg_fetch_result($qSecuencialSitio, 0, 'valor'), 2, "0", STR_PAD_LEFT);
			}else{
				$secuencialSitio = pg_fetch_result($sitio, 0, 'codigo');
			}
			
	
			$cr->actualizarSitio($conexion, $datos['nombreSitio'], $datos['superficieTotal'], $provincia['nombre'], $canton['nombre'], $parroquia['nombre'], $datos['direccion'], $datos['referencia'], $datos['latitud'], $datos['longitud'], $datos['zona'], $datos['telefono'], $idSitio, $identificador, substr($provincia['codigo_vue'],1), $secuencialSitio);
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'El sitio ha sido actualizado satisfactoriamente.';
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

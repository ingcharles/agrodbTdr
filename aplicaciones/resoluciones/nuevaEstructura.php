<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorResoluciones.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	$resolucion = htmlspecialchars ($_POST['resolucion'],ENT_NOQUOTES,'UTF-8');
	$nivel = htmlspecialchars ($_POST['nivel'],ENT_NOQUOTES,'UTF-8');
	$numero = htmlspecialchars ($_POST['numero'],ENT_NOQUOTES,'UTF-8');
	$contenido = htmlspecialchars ($_POST['contenido'],ENT_QUOTES,'UTF-8');
	$idEstructuraPadre = htmlspecialchars ($_POST['idEstructuraPadre'],ENT_NOQUOTES,'UTF-8');
	
	$tipo = ($idEstructuraPadre == 'null')? 'estructurasPadre': 'estructuras';
	
	try {
		$conexion = new Conexion();
		$cr = new ControladorResoluciones();
		
		$idEstructura = pg_fetch_row($cr->ingresarNuevaEstructura($conexion, $resolucion, $nivel, $numero, $contenido, $idEstructuraPadre ));
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $cr->imprimirLineaEstructura($idEstructura[0], $nivel . ' ' . $numero, $resolucion,$tipo);
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
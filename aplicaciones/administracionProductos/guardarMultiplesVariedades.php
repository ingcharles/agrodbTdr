<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRequisitos.php';
require_once '../../clases/ControladorAuditoria.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{

	
	$idProducto = htmlspecialchars ($_POST['producto'],ENT_NOQUOTES,'UTF-8');
	$idOperacion = htmlspecialchars ($_POST['tipoOperacion'],ENT_NOQUOTES,'UTF-8');
	$siNoMultiple = htmlspecialchars ($_POST['siNoMultiple'],ENT_NOQUOTES,'UTF-8');
	
	
	
	try {
		$conexion = new Conexion();
		$cr = new ControladorRequisitos();
		/*$ca = new ControladorAuditoria();*/
		
		
		if(pg_num_rows($cr->buscarMultiplesVariedades($conexion, $idProducto, $idOperacion))==0){
			
									
			$cr->guardarMultiplesVariedades($conexion, $idProducto, $idOperacion, $siNoMultiple);
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido guardados correctamente.';
			
		}
		else {
			$mensaje['mensaje'] = 'El producto, operación y variedad han sido ingresados previamente.';
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
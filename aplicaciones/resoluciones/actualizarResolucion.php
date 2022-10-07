<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorResoluciones.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!'.$_POST['boton'];


try{
	
	$datos = array(	'numero' => htmlspecialchars ($_POST['numero'],ENT_NOQUOTES,'UTF-8'),
					'nombre' => htmlspecialchars ($_POST['nombre'],ENT_NOQUOTES,'UTF-8'),
					'fecha' => htmlspecialchars ($_POST['fecha'],ENT_NOQUOTES,'UTF-8'),
					'observacion' => htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8'),
					'estado' => htmlspecialchars ($_POST['estadoDocumento'],ENT_NOQUOTES,'UTF-8'));

	try {
		$conexion = new Conexion();
		$cr = new ControladorResoluciones();
				
			$cr->actualizarDatosResolucion($conexion, $datos['numero'],$datos['nombre'], $datos['fecha'], $datos['observacion'], $datos['estado']);
			
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
<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try {

	$institucion = $_POST['institucion'];
	$tipo_cuenta = $_POST['tipo_cuenta'];
	$numero_cuenta = $_POST['numero_cuenta'];

	try {
		$conexion = new Conexion();
		$cc = new ControladorCatastro();

		$cc -> actualizarDatosBanco($conexion, $_SESSION['usuario'], $institucion, $tipo_cuenta, $numero_cuenta);
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';

		$conexion -> desconectar();
		echo json_encode($mensaje);
	} catch (Exception $ex) {
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
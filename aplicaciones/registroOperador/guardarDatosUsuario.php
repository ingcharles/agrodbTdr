<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorUsuarios.php';


$mensaje = array();
$mensaje['estado'] = 'inicio';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$alias = pg_escape_string($_POST['alias']);
$claveActual = pg_escape_string($_POST['claveActual']);
$claveNueva1 = pg_escape_string($_POST['claveNueva1']);
$claveNueva2 = pg_escape_string($_POST['claveNueva2']);
if($claveNueva1 == $claveNueva2){
	
		try {
			$conexion = new Conexion();
			$cu = new ControladorUsuarios();
			$resultado =  $cu->verificarUsuario($conexion, $_SESSION['usuario']);
			$fila=pg_fetch_assoc($resultado);
			if(trim($claveActual) == ""){
				$claveNueva1 = "'".$fila['clave']."'";
			} else {
				if($fila['clave']!=md5($claveActual)){
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = 'La contraseña ingresada en el campo actual no es correcta.';
				}
				if(trim($claveNueva1) == ""){
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = 'Por favor ingrese una nueva contraseña';
				}
				if(trim($claveNueva2) == ""){
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = 'Por favor ingrese una nueva contraseña';
				}
				if(trim($claveNueva1) != trim($claveNueva2)){
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = 'La nueva contraseña no coincide';
				}
				$claveNueva1 = "md5('".$claveNueva1."')";
			}
			if ($mensaje['estado']!='error') {
				$cu->actualizarUsuario($conexion, $_SESSION['usuario'], $alias, $claveNueva1);
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'El cambio se efectuo correctamente.';
			}
			$conexion->desconectar();
			echo json_encode($mensaje);
		} catch (Exception $ex){
			pg_close($conexion);
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Error al ejecutar sentencia";
			echo json_encode($mensaje);
		}

} else {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = "Las claves nuevas no coinciden";

	echo json_encode($mensaje);
}

?>
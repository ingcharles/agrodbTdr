<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
//require_once('../../FirePHPCore/FirePHP.class.php'); borrado
//ob_start(); borrado


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
$archivo= $_POST['archivo'];


try{

	$id_relacion_discapacidad = $_POST['id_relacion_discapacidad'];
	$opcion= $_POST['opcion'];
	$porcentaje = $_POST['porcentaje']; 
	$carnet= $_POST['carnet'];
	$archivo= $_POST['archivo'];
	$id_discapacidad_enfermedad=$_POST['id_discapacidad_enfermedad'];
	$usuario_seleccionado=$_SESSION['usuario_seleccionado'];
		
		try {
				$conexion = new Conexion();
				$cc = new ControladorCatastro();
				if($opcion=='Actualizar')
				{
					$cc->actualizarDiscapacidad($conexion, $id_relacion_discapacidad, $porcentaje, $carnet, $archivo);
				
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
					
					$conexion->desconectar();
					echo json_encode($mensaje);
					
				}
				if($opcion=='Nuevo')
				{
					$cc->crearDiscapacidad($conexion, $usuario_seleccionado, $_SESSION['usuario'], $id_discapacidad_enfermedad, $porcentaje, $carnet, $archivo);
				
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';
					
					$conexion->desconectar();
					echo json_encode($mensaje);
				}
				
			} catch (Exception $ex){
					pg_close($conexion);
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = "Error al ejecutar sentencia";
					echo json_encode($mensaje);
			}
/*	}catch (Exception $ex){
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Error al subir el archivo";
			echo json_encode($mensaje);
	}*/
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>
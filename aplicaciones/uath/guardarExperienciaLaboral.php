<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
//require_once('../../FirePHPCore/FirePHP.class.php'); borrado
//ob_start(); borrado


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$opcion=$_POST['opcion'];
	$tipo_institucion = $_POST['tipo_institucion'];
	$institucion = $_POST['institucion'];
	$unidad_administrativa = $_POST['unidad_administrativa'];
	$puesto = $_POST['puesto'];
	$fecha_ingreso = date_format (DateTime::createFromFormat('d/m/Y',$_POST['fecha_ingreso']),'Y/m/d');
	$fecha_salida=$_POST['fecha_salida'];
	if($fecha_salida!='')
	$fecha_salida= date_format (DateTime::createFromFormat('d/m/Y',$fecha_salida),'Y/m/d');
	
	$archivo= $_POST['archivo'];
	$motivo_ingreso =$_POST['motivo_ingreso'];
	$motivo_salida =$_POST['motivo_salida'];
	$usuario=$_POST['usuario'];
	$id_experiencia_laboral=$_POST['id'];

		try {
				$conexion = new Conexion();
				$cc = new ControladorCatastro();
				if($opcion=='Nuevo')
				{
				    $cc->crearExperienciaLaboral($conexion, $usuario, $tipo_institucion, $institucion, $unidad_administrativa, $puesto, $fecha_ingreso, $fecha_salida, $archivo, $motivo_salida, 'Ingresado',$motivo_ingreso);
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';
				}
				if($opcion=='Actualizar')
				{
				    $cc->actualizarExperienciaLaboral($conexion, $id_experiencia_laboral, $tipo_institucion, $institucion, $unidad_administrativa, $puesto, $fecha_ingreso, $fecha_salida, $archivo, $motivo_salida, 'Modificado',$motivo_ingreso);
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
				}
				
				
				$conexion->desconectar();
				echo json_encode($mensaje);
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
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
	$nivel_instruccion = $_POST['nivel_instruccion'];
	$num_certificado = trim($_POST['num_certificado']);
	$institucion = $_POST['institucion'];
	$anios_estudio = $_POST['años_estudio'];
	$archivo= $_POST['archivo'];
	$carrera = $_POST['carrera'];
	$titulo= $_POST['titulo'];
	$pais=$_POST['pais'];
	$estado= $_POST['estado'];
	$usuario=$_POST['usuario'];
	$id_datos_academicos=$_POST['academico_seleccionado'];
	$egresado=(isset($_POST['egresado'])) ? $_POST['egresado']:'No';
	
	
	if($anios_estudio ==''){
		$anios_estudio = 0;
	}
	
	if($horas ==''){
		$horas = 0;
	}

		try {
				$conexion = new Conexion();
				$cc = new ControladorCatastro();
				if($opcion=='Nuevo')
				{
				    $cc->crearDatosAcademicos($conexion, $usuario, $nivel_instruccion, $num_certificado, $institucion, $anios_estudio, $carrera, $titulo, $pais, $archivo,'Ingresado',$egresado);
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';
				}
				if($opcion=='Actualizar')
				{
				    $cc->actualizarDatosAcademicos($conexion, $id_datos_academicos, $nivel_instruccion, $num_certificado, $institucion, $anios_estudio, $carrera, $titulo, $pais, $archivo, 'Modificado',$egresado);
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
				}
				
				
				$conexion->desconectar();
				echo json_encode($mensaje);
				} catch (Exception $ex){
					pg_close($conexion);
					echo json_encode($mensaje);
				}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>
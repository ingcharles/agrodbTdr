<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
//require_once('../../FirePHPCore/FirePHP.class.php'); borrado
//ob_start(); borrado
//$firephp = FirePHP::getInstance(true); borrado	
		

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$cedula = $_POST['cedula'];
	$nombre = $_POST['nombreFamiliar'];
	$apellido = $_POST['apellidoFamiliar'];
	$relacion = $_POST['relacion'];
	$nacimiento=date_format (DateTime::createFromFormat('d/m/Y',$_POST['nacimiento']),'Y/m/d');  
	$edad = $_POST['edad'];
	$calle_principal= $_POST['calle_principal'];
	$numero=$_POST['numero'];
	$calle_secundaria= $_POST['calle_secundaria'];
	$referencia = $_POST['referencia'];
	$telefono = $_POST['telefono'];
	$celular = $_POST['celular'];
	$telefono_oficina = $_POST['telefono_oficina'];
	$extension= $_POST['extension'];
	$usuario=$_POST['usuario'];
	$representante_discapacitado=$_POST['representante_discapacitado'];
	$carnet_conadis_familiar=$_POST['carnet_conadis_familiar'];
	$contacto_emergencia=$_POST['contactoEmergencia'];
	$tipo_documento=$_POST['tipo_documento'];
	$nivel_instruccion=$_POST['nivel_instruccion'];
	
	if($contacto_emergencia=='')
		$contacto_emergencia='false';
		
		try {
				$conexion = new Conexion();
				$cc = new ControladorCatastro();
				
				$cc->crearDatosFamiliares($conexion, $cedula, $usuario, $nombre, $apellido, $relacion, $nacimiento, $edad, $calle_principal, $numero,$calle_secundaria, $referencia,$telefono,
				    $celular, $telefono_oficina, $extension,$representante_discapacitado,$carnet_conadis_familiar,$contacto_emergencia,$tipo_documento,$nivel_instruccion );
				
				$mensaje['estado'] = 'exito';
		        $mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
		
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
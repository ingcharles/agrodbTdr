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
	$usuario_seleccionado=$_POST['usuario_seleccionado'];
	$posee_discapacidad=$_POST['posee_discapacidad'];
	$carnet_conadis_familiar=$_POST['carnet_conadis_familiar'];
	$contacto_emergencia=$_POST['contactoEmergencia'];
	$nivel_instruccion = $_POST['nivel_instruccion'];
	if($contacto_emergencia=='')
		$contacto_emergencia='false';
		try {
				$conexion = new Conexion();
				$cc = new ControladorCatastro();
				$cc->actualizarDatosFamiliares($conexion, $usuario, $nombre, $apellido, $relacion, $nacimiento, $edad, $calle_principal, $numero,   $calle_secundaria, $referencia,
				    $telefono,$celular, $telefono_oficina, $extension, $usuario_seleccionado,$posee_discapacidad,$carnet_conadis_familiar,$contacto_emergencia,$nivel_instruccion);
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
				$conexion->desconectar();
				echo json_encode($mensaje);

				} catch (Exception $ex){
					pg_close($conexion);
					$error=$ex->getMessage();
					$mensaje['estado'] = 'error';
					$error_code=0;
					$error_code= $error_code + stristr($error, 'duplicate key')!=FALSE?1:0;
					$error_code= $error_code + stristr($error, 'identificador_familiar')!=FALSE?2:0;
					switch($error_code){
						case 0:		$mensaje['mensaje'] = "No se puede ejecutar la sentencia";	
							break;
						case 3:		$mensaje['mensaje'] = "Error: Ya existe un familiar con ese número de cédula";
							break;
					}
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
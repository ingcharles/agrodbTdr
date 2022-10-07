<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!'.$_POST['boton'];


try{
	$identificadorUsuarioRegistro = htmlspecialchars ($_POST['identificadorUsuarioRegistro'],ENT_NOQUOTES,'UTF-8');
	
	$datos = array(	'id_gasolinera' => htmlspecialchars ($_POST['id_gasolinera'],ENT_NOQUOTES,'UTF-8'),
					'nombreGasolinera' => htmlspecialchars ($_POST['nombreGasolinera'],ENT_NOQUOTES,'UTF-8'), 
					'direccion' => htmlspecialchars ($_POST['direccion'],ENT_NOQUOTES,'UTF-8'),
					'cupo' => htmlspecialchars ($_POST['cupo'],ENT_NOQUOTES,'UTF-8'),
					'contacto' =>  htmlspecialchars ($_POST['contacto'],ENT_NOQUOTES,'UTF-8'), 
					'telefono' => htmlspecialchars ($_POST['telefono'],ENT_NOQUOTES,'UTF-8'), 
					'observaciones' =>  htmlspecialchars ($_POST['observaciones'],ENT_NOQUOTES,'UTF-8'),
			   	 	'extra' =>  htmlspecialchars ($_POST['extra'],ENT_NOQUOTES,'UTF-8'),
					'super' =>  htmlspecialchars ($_POST['super'],ENT_NOQUOTES,'UTF-8'),
					'diesel' =>  htmlspecialchars ($_POST['diesel'],ENT_NOQUOTES,'UTF-8'),
					'ecopais' =>  htmlspecialchars ($_POST['ecopais'],ENT_NOQUOTES,'UTF-8'));

	try {
		$conexion = new Conexion();
		$cv = new ControladorVehiculos();

		if ($identificadorUsuarioRegistro != ''){
			$cv->actualizarDatosGasolinera($conexion, $datos['id_gasolinera'],$datos['nombreGasolinera'], $datos['direccion'], $datos['cupo'], $datos['contacto'], $datos['telefono'], $datos['observaciones'], ($datos['extra']==""?0:$datos['extra']),($datos['super']==""?0:$datos['super']),($datos['diesel']==""?0:$datos['diesel']),($datos['ecopais']==""?0:$datos['ecopais']), $identificadorUsuarioRegistro);
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente.';
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Su sesión expiró, por favor ingrese nuevamente al sistema";
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
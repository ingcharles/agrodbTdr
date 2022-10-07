<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	$identificadorUsuarioRegistro = htmlspecialchars ($_POST['identificadorUsuarioRegistro'],ENT_NOQUOTES,'UTF-8');
	
	$datos = array( 'id_movilizacion' => htmlspecialchars ($_POST['id_movilizacion'],ENT_NOQUOTES,'UTF-8'),
					'placa' => htmlspecialchars ($_POST['placa'],ENT_NOQUOTES,'UTF-8'),
					'kilometraje' =>  htmlspecialchars ($_POST['km_final'],ENT_NOQUOTES,'UTF-8'),
					'observacion' => htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8'),
					'razonIncrementoKm' => htmlspecialchars ($_POST['razonKilometraje'],ENT_NOQUOTES,'UTF-8'));

	
	try {
		$conexion = new Conexion();
		$cv = new ControladorVehiculos();
		
		if ($identificadorUsuarioRegistro != ''){			
		//	$res = $cv-> abrirVehiculo($conexion, $datos['placa']);
		//	$vehiculo = pg_fetch_assoc($res);
				
		//	$km = $datos['kilometraje'] - $vehiculo['kilometraje_actual'];
		//	$km_final = $vehiculo['kilometraje_actual'] + $km;
			
			$cv ->finalizarMovilizacion($conexion, $datos['id_movilizacion'], $datos['kilometraje'], $datos['observacion'], $identificadorUsuarioRegistro, $datos['razonIncrementoKm']);
			$cv ->actualizarKilometrajeVehiculo($conexion, $datos['placa'], $datos['kilometraje'], 'Actual');
		//	$cv ->actualizarKilometrajeVehiculo($conexion, $datos['placa'], $km_final, 'Actual');
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
			
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
<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$identificadorUsuarioRegistro = htmlspecialchars ($_POST['identificadorUsuarioRegistro'],ENT_NOQUOTES,'UTF-8');
	
	$datos = array( 'placa' => htmlspecialchars ($_POST['placa'],ENT_NOQUOTES,'UTF-8'), 
				    'ordenTrabajo' => htmlspecialchars ($_POST['ordenTrabajo'],ENT_NOQUOTES,'UTF-8'),
					'idMantenimiento' => htmlspecialchars ($_POST['id'],ENT_NOQUOTES,'UTF-8'),
					'kilometrajeFinal' => htmlspecialchars ($_POST['kilometrajeFinal'],ENT_NOQUOTES,'UTF-8'),
					'razonIncrementoKm' => htmlspecialchars ($_POST['razonKilometraje'],ENT_NOQUOTES,'UTF-8'));
	
	try {	
			
			$conexion = new Conexion();
			$cv = new ControladorVehiculos();
			
			if ($identificadorUsuarioRegistro != ''){
				//Obtener datos mantenimiento
				$res= $cv-> abrirMantenimiento($conexion, $datos['idMantenimiento']);
				$mantenimiento = pg_fetch_assoc($res);
				
				$tipo = explode('-',$mantenimiento['tipo_mantenimiento']);
				
				//Actualizar kilometraje final para vehículo
				if($tipo[1]=='Preventivo')
					$cv->actualizarKilometrajeVehiculo($conexion,$datos['placa'], $datos['kilometrajeFinal'],'Inicial');
				else
					$cv->actualizarKilometrajeVehiculo($conexion,$datos['placa'], $datos['kilometrajeFinal'],'Actual');
				
				//Cambio de estado en vehículo
				$cv -> actualizarEstadoVehiculo($conexion, $datos['placa'], 'Liberar');
				
				//Agregar número de orden de trabajo
				$cv -> actualizarOrdenTrabajoKilometraje($conexion, $datos['idMantenimiento'], $datos['ordenTrabajo'], $datos['kilometrajeFinal'], $identificadorUsuarioRegistro, $datos['razonIncrementoKm']);
				
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
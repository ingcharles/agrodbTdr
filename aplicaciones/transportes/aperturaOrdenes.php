<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$identificadorUsuarioRegistro = htmlspecialchars ($_POST['identificadorUsuarioRegistro'],ENT_NOQUOTES,'UTF-8');	
	$numeroOrden = htmlspecialchars ($_POST['numeroOrden'],ENT_NOQUOTES,'UTF-8');
	$tipoOrden = htmlspecialchars ($_POST['tipoOrden'],ENT_NOQUOTES,'UTF-8');
	$usuarioSolicitante = htmlspecialchars ($_POST['usuarioSolicitante'],ENT_NOQUOTES,'UTF-8');
	$glpi = htmlspecialchars ($_POST['glpi'],ENT_NOQUOTES,'UTF-8');
	 
	try {
		$conexion = new Conexion();
		$cv = new ControladorVehiculos();
	
		if ($identificadorUsuarioRegistro != ''){			
			
			switch ($tipoOrden){
				case 'Combustible':{						
					$cv->reaperturaOrden($conexion, $tipoOrden, $numeroOrden, $usuarioSolicitante, $glpi, $identificadorUsuarioRegistro);
						
					break;
				}
			
				case 'Mantenimiento':{			
					$cv->reaperturaOrden($conexion, $tipoOrden, $numeroOrden, $usuarioSolicitante, $glpi, $identificadorUsuarioRegistro);
					$cv->eliminarMantenimientoDetalle($conexion, $numeroOrden);
						
					break;
				}
			
				case 'Movilizacion':{			
					$cv->reaperturaOrden($conexion, $tipoOrden, $numeroOrden, $usuarioSolicitante, $glpi, $identificadorUsuarioRegistro);
						
					break;
				}
			
				default:{
					echo 'El tipo de orden seleccionado no es correcto.';
						
					break;
				}
			}
			
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
<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$identificadorUsuarioRegistro = htmlspecialchars ($_POST['identificadorUsuarioRegistro'],ENT_NOQUOTES,'UTF-8');	
	$idMovilizacion = htmlspecialchars ($_POST['idMovilizacion'],ENT_NOQUOTES,'UTF-8');	
	$localizacion = htmlspecialchars ($_POST['sitio'],ENT_NOQUOTES,'UTF-8');
	$fechaDesde = htmlspecialchars ($_POST['fechaDesde'],ENT_NOQUOTES,'UTF-8');
	$fechaHasta = htmlspecialchars ($_POST['fechaHasta'],ENT_NOQUOTES,'UTF-8');
	 
	try {
		$conexion = new Conexion();
		$cv = new ControladorVehiculos();
	
		if ($identificadorUsuarioRegistro != ''){	

			$ruta = $cv->buscarMovilizacionRutasFechas($conexion, $idMovilizacion, $localizacion, $fechaDesde, $fechaHasta);
			
			if(pg_num_rows($ruta) == 0){		
				$cv->guardarMovilizacionRutas($conexion, $idMovilizacion, $localizacion, $fechaDesde, $fechaHasta);
					
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
			}else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = "La ruta elegida ya se encuentra registrada, por favor seleccione una nueva.";
			}
			
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
<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$identificadorUsuarioRegistro = htmlspecialchars ($_POST['identificadorUsuarioRegistro'],ENT_NOQUOTES,'UTF-8');
	
	$datos = array( 'id_siniestro' => htmlspecialchars ($_POST['id_siniestro'],ENT_NOQUOTES,'UTF-8'),
					'montoTerceros' => htmlspecialchars ($_POST['montoTerceros'],ENT_NOQUOTES,'UTF-8'),
					'valorTotal' => htmlspecialchars ($_POST['valorTotal'],ENT_NOQUOTES,'UTF-8'));
	
	$concepto = $_POST['sConcepto'];
	$subTotal = $_POST['sTotal'];
	
	try {
		$conexion = new Conexion();
		$cv = new ControladorVehiculos();
		
		if ($identificadorUsuarioRegistro != ''){
			$res= $cv-> abrirSiniestro($conexion, $datos['id_siniestro']);
			$siniestro = pg_fetch_assoc($res);
			
			if ($siniestro['kilometraje_final'] != ''){			
				$cv->actualizarDatosSiniestroMonto($conexion, $datos['id_siniestro'], $datos['montoTerceros'], $datos['valorTotal'], $identificadorUsuarioRegistro);
				$cv->actualizarDatosSiniestroCierreFase($conexion, $datos['id_siniestro'], 3, $identificadorUsuarioRegistro);
				
				for ($i = 0; $i < count($concepto); $i++) {
					$cv -> ingresarDetalleSiniestro($conexion, $datos['id_siniestro'], $concepto[$i], $subTotal[$i]);
				}
				
				$localizacion = $cv->obtenerLocalizacionVehiculo($conexion, $siniestro['placa']);
				$cv->actualizarLocalizacionSiniestro($conexion, $datos['id_siniestro'], pg_fetch_result($localizacion, 0, 'localizacion'));
				
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
				
			}else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = "Debe registrar la salida del taller en ''Habilitar Vehículo'' para continuar.";
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
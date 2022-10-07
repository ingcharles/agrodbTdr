<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';
require_once '../../clases/ControladorReportes.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!'.$_POST['boton'];


try{
	
	$identificadorUsuarioRegistro = htmlspecialchars ($_POST['identificadorUsuarioRegistro'],ENT_NOQUOTES,'UTF-8');
	
	$datos = array( 'movilizacion' => htmlspecialchars ($_POST['id_movilizacion'],ENT_NOQUOTES,'UTF-8'),
					'vehiculo' => htmlspecialchars ($_POST['vehiculo'],ENT_NOQUOTES,'UTF-8'),
					'km_actual' => htmlspecialchars ($_POST['km_actual'],ENT_NOQUOTES,'UTF-8'),
					'km_inicial' => htmlspecialchars ($_POST['km_inicial'],ENT_NOQUOTES,'UTF-8'),
					'conductor' => htmlspecialchars ($_POST['ocupante'],ENT_NOQUOTES,'UTF-8'),
					'idVehiculo' => htmlspecialchars ($_POST['id_vehiculo'],ENT_NOQUOTES,'UTF-8'));

	$jefeTransportes = $_POST['jefeTransportes'];
	$salvoconducto = $_POST['salvoconducto'];
	$responsableSalvoconducto = $_POST['responsableSalvoconducto'];
	$cargo = $_POST['cargo'];
	
	try {
		$conexion = new Conexion();
		$cv = new ControladorVehiculos();
		
		$vehiculo = pg_fetch_assoc($cv->abrirVehiculo($conexion,  $datos['vehiculo']));
		
		$proximoMantenimiento = ($vehiculo['kilometraje_inicial'] + 5000) - $vehiculo['kilometraje_actual'];
		
		if($proximoMantenimiento == 0){
			$mensajeMantenimiento = 'Ha completado los 5000 Kms. de recorrido';
		}else if($proximoMantenimiento < 0){
			$mensajeMantenimiento = 'Ha sobrepasado el recorrido con '.abs($proximoMantenimiento).' Kms.';
		}else{
			$mensajeMantenimiento = 'Dispone de '. $proximoMantenimiento . ' Kms.';
		}
		
		if ($identificadorUsuarioRegistro != ''){
			
			if ($datos['conductor'] != ''){
		
				$cv->actualizarDatosMovilizacion($conexion, $datos['movilizacion'], $datos['vehiculo'], $datos['conductor'], $datos['km_inicial'], $datos['idVehiculo'], $identificadorUsuarioRegistro);
				$cv->actualizarEstadoVehiculo($conexion, $datos['vehiculo'], 'Movilizacion');
				
				$idMovilizacion = $datos['movilizacion'];
				
				///JASPER///
				$jru = new ControladorReportes();
								
				$filename = $idMovilizacion.'.pdf';
				
				$parameters['parametrosReporte'] = array(
					'id_movilizacion'=> $idMovilizacion,
					'jefeTransporte'=> $jefeTransportes,
					'mensajeMantenimiento'=> $mensajeMantenimiento
				);
				
				if($salvoconducto == 1){
					$parameters['parametrosReporte'] += array('responsableSalvoconducto'=> $responsableSalvoconducto);
					$parameters['parametrosReporte'] += array('cargo'=> $cargo);
					
					$ReporteJasper='aplicaciones/transportes/reportes/salvoconducto.jrxml';
									
				}else{
					$ReporteJasper='aplicaciones/transportes/reportes/movilizacion.jrxml';
				}
				
				$salidaReporte = 'aplicaciones/transportes/comprobante/ordenMovilizaciones/'.$filename;			
				
				$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'logoRecortado');
				$cv -> guardarRutaDocumento($conexion, $idMovilizacion, $salidaReporte, 'movilizaciones');
				
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente.';
			
			}else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = "Por favor seleccione un conductor para la movilización.";
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

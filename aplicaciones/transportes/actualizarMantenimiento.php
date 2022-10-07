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
	
	$datos = array('id_mantenimiento' => htmlspecialchars ($_POST['id_mantenimiento'],ENT_NOQUOTES,'UTF-8'),
			'motivo' => htmlspecialchars ($_POST['motivo'],ENT_NOQUOTES,'UTF-8'),
			'taller' => htmlspecialchars ($_POST['taller'],ENT_NOQUOTES,'UTF-8'),
			'conductor' => htmlspecialchars ($_POST['ocupante'],ENT_NOQUOTES,'UTF-8'));
	
	$jefeTransportes = $_POST['jefeTransportes'];
	
	try {
		$conexion = new Conexion();
		$cv = new ControladorVehiculos();
	
		if ($identificadorUsuarioRegistro != ''){
			$cv->actualizarDatosMantenimiento($conexion, $datos['id_mantenimiento'], $datos['motivo'], $datos['taller'], $datos['conductor'], $identificadorUsuarioRegistro);
			
			$idMantenimiento = $datos['id_mantenimiento'];
				
			///JASPER///
				
			//Ruta del reporte compilado por Jasper y generado por IReports
			
			$jru = new ControladorReportes();
			
			$filename = $idMantenimiento.'.pdf';
			$ReporteJasper='aplicaciones/transportes/reportes/mantenimiento.jrxml';
			$salidaReporte = 'aplicaciones/transportes/comprobante/ordenMantenimientos/'.$filename;
			
			$parameters['parametrosReporte'] = array(
				'id_mantenimiento'=> $idMantenimiento,
				'jefeTransporte'=> $jefeTransportes
			);
			
			$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'defecto');
			$cv -> guardarRutaDocumento($conexion, $idMantenimiento, $salidaReporte, 'mantenimientos');
				
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
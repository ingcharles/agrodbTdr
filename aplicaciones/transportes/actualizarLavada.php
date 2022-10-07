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
			'taller' => htmlspecialchars ($_POST['taller'],ENT_NOQUOTES,'UTF-8'));
	
	$jefeTransportes = $_POST['jefeTransportes'];
	
	$conductor = htmlspecialchars ($_POST['ocupante'],ENT_NOQUOTES,'UTF-8');
	
	try {
		$conexion = new Conexion();
		$cv = new ControladorVehiculos();
	
		if ($identificadorUsuarioRegistro != ''){
			$cv->actualizarDatosMantenimiento($conexion, $datos['id_mantenimiento'], $datos['motivo'], $datos['taller'], $conductor, $identificadorUsuarioRegistro);
			
			$idLavado = $datos['id_mantenimiento'];
				
			///JASPER///
			
			//Ruta del reporte compilado por Jasper y generado por IReports
				
			$jru = new ControladorReportes();
				
			$filename = $idLavado.'.pdf';
			$ReporteJasper='aplicaciones/transportes/reportes/lavada.jrxml';
			$salidaReporte = 'aplicaciones/transportes/comprobante/ordenLavadas/'.$filename;
			
			$parameters['parametrosReporte'] = array(
				'id_mantenimiento'=> $idLavado,
				'jefeTransporte'=> $jefeTransportes
			);
				
			$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte, 'defecto');
			$cv -> guardarRutaDocumento($conexion, $idLavado, $salidaReporte, 'mantenimientos');
			
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
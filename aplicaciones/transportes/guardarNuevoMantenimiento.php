<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';
require_once '../../clases/ControladorReportes.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
	
try{
	$identificadorUsuarioRegistro = htmlspecialchars ($_POST['identificadorUsuarioRegistro'],ENT_NOQUOTES,'UTF-8');
	
	$datos = array('tipo' => htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8'),
					'motivo' => htmlspecialchars ($_POST['motivo'],ENT_NOQUOTES,'UTF-8'), 
					'vehiculo' => htmlspecialchars ($_POST['vehiculo'],ENT_NOQUOTES,'UTF-8'),
					'conductor' =>  htmlspecialchars ($_POST['ocupante'],ENT_NOQUOTES,'UTF-8'), 
					'kilometraje' => htmlspecialchars ($_POST['kilometraje'],ENT_NOQUOTES,'UTF-8'),
					'taller' => htmlspecialchars ($_POST['taller'],ENT_NOQUOTES,'UTF-8'),
					'idVehiculo' => htmlspecialchars ($_POST['id_vehiculo'],ENT_NOQUOTES,'UTF-8'));
	
	$jefeTransportes = $_POST['jefeTransportes'];
	
	try {
		$conexion = new Conexion();
		$cv = new ControladorVehiculos();
		
		if ($identificadorUsuarioRegistro != ''){
		    $res = $cv->generarNumeroMantenimiento($conexion, '%'.$_SESSION ['codigoLocalizacion'].'%', "'".'MAN-'.$_SESSION['codigoLocalizacion'].'-'."'");
		    $mantenimiento = pg_fetch_assoc($res);
		    $incremento = $mantenimiento['numero'] + 1;
		    $numero = 'MAN-'.$_SESSION['codigoLocalizacion'].'-'.str_pad($incremento, 8, "0", STR_PAD_LEFT);
		    
			$qMantenimiento = $cv -> guardarNuevoMantenimiento($conexion,$numero, $datos['motivo'], $datos['vehiculo'], $datos['conductor'], $datos['kilometraje'], $datos['taller'],$_SESSION['nombreLocalizacion'],'Mantenimiento-'.$datos['tipo'], $datos['idVehiculo'], $identificadorUsuarioRegistro);
			$idMantenimiento = pg_fetch_result($qMantenimiento, 0, 'id_mantenimiento');
			
			$cv ->actualizarEstadoVehiculo($conexion, $datos['vehiculo'], 'Mantenimiento');
			
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
			$mensaje['mensaje'] = 'Los datos han sido creados satisfactoriamente';
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
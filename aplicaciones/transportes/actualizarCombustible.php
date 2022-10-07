<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';
require_once '../../clases/ControladorReportes.php';

//Vehiculo Direccion Ejecutiva
$DIR_EJE = 'PEI-1418';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$identificadorUsuarioRegistro = htmlspecialchars ($_POST['identificadorUsuarioRegistro'],ENT_NOQUOTES,'UTF-8');
	
	$datos = array('id_combustible' => htmlspecialchars ($_POST['id_combustible'],ENT_NOQUOTES,'UTF-8'),
					'kilometraje' =>  htmlspecialchars ($_POST['kilometraje'],ENT_NOQUOTES,'UTF-8'),
					'observacion' => htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8'),
					'gasolinera' => htmlspecialchars ($_POST['gasolinera'],ENT_NOQUOTES,'UTF-8'),
					'combustible' => htmlspecialchars ($_POST['combustible'],ENT_NOQUOTES,'UTF-8'),
					'conductor' => htmlspecialchars ($_POST['ocupante'],ENT_NOQUOTES,'UTF-8'),
					'fechaDespacho' => htmlspecialchars ($_POST['fechaDespacho'],ENT_NOQUOTES,'UTF-8'),
					'montoSolicitado' => htmlspecialchars ($_POST['montoSolicitado'],ENT_NOQUOTES,'UTF-8'),
					'galonesSolicitados' => htmlspecialchars ($_POST['galonesSolicitados'],ENT_NOQUOTES,'UTF-8'));

	$jefeTransportes = $_POST['jefeTransportes'];
	
	try {
		$conexion = new Conexion();
		$cv = new ControladorVehiculos();
		
		if ($identificadorUsuarioRegistro != ''){
			$cv->actualizarDatosCombustible($conexion, $datos['id_combustible'], $datos['kilometraje'],$datos['observacion'], $datos['gasolinera'], $datos['combustible'], $datos['conductor'], $datos['fechaDespacho'], $identificadorUsuarioRegistro, $datos['montoSolicitado'], $datos['galonesSolicitados']);
			
			$idCombustible = $datos['id_combustible'];
			
			///JASPER///
				
			//Ruta del reporte compilado por Jasper y generado por IReports
			
			$jru = new ControladorReportes();
				
			$filename = $idCombustible.'.pdf';
				
			$ReporteJasper= 'aplicaciones/transportes/reportes/combustible.jrxml';
			$salidaReporte = 'aplicaciones/transportes/comprobante/ordenCombustible/'.$filename;
			
			$parameters['parametrosReporte'] = array(
				'id_combustible'=> $idCombustible,
				'jefeTransporte'=> $jefeTransportes
			);
				
			$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'defecto');
			$cv -> guardarRutaDocumento($conexion, $idCombustible, $salidaReporte, 'combustible');
			
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
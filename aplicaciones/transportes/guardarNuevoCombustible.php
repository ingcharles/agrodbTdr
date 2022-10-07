<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorReportes.php';
//Vehiculo Direccion Ejecutiva
$DIR_EJE = 'PEI-1418';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$identificadorUsuarioRegistro = htmlspecialchars ($_POST['identificadorUsuarioRegistro'],ENT_NOQUOTES,'UTF-8');
	
	$datos = array(	'placa' => htmlspecialchars ($_POST['vehiculo'],ENT_NOQUOTES,'UTF-8'),
					'kilometraje' => htmlspecialchars ($_POST['kilometraje'],ENT_NOQUOTES,'UTF-8'),
					'conductor' =>  htmlspecialchars ($_POST['ocupante'],ENT_NOQUOTES,'UTF-8'), 
					'combustible' =>  htmlspecialchars ($_POST['combustible'],ENT_NOQUOTES,'UTF-8'),
					'gasolinera' => htmlspecialchars ($_POST['gasolinera'],ENT_NOQUOTES,'UTF-8'),
					'fechaDespacho' => htmlspecialchars ($_POST['fechaDespacho'],ENT_NOQUOTES,'UTF-8'),
					'idVehiculo' => htmlspecialchars ($_POST['id_vehiculo'],ENT_NOQUOTES,'UTF-8'),
					'montoSolicitado' => htmlspecialchars ($_POST['montoSolicitado'],ENT_NOQUOTES,'UTF-8'),
					'galonesSolicitados' => htmlspecialchars ($_POST['galonesSolicitados'],ENT_NOQUOTES,'UTF-8'));
	
	$jefeTransportes = $_POST['jefeTransportes'];

	try {
		$conexion = new Conexion();
		$cv = new ControladorVehiculos();
		$cu = new ControladorUsuarios();
		
		//Datos validación número órdenes de combustible		
		//Provincia del Administrador de Transportes
		$provinciaAdmin = pg_fetch_assoc($cu->obtenerProvincia($conexion, $identificadorUsuarioRegistro));
		$idProvincia = $provinciaAdmin['id_localizacion'];
		$nombreProvincia = $provinciaAdmin['nombre'];
		
		$kilometrajeSolicitud = $_POST['kilometrajeSolicitud'];
		
		if ($identificadorUsuarioRegistro != ''){
			//Revisión de órdenes previas para el vehículo
			$ordenesPrevias = ($cv->buscarOrdenesCombustible($conexion, $datos['placa'], $idProvincia, $kilometrajeSolicitud));
			
			if ((pg_num_rows($ordenesPrevias) == 0) || ($DIR_EJE == $datos['placa'])){
			    $res = $cv->generarNumeroCombustible($conexion, '%'.$_SESSION ['codigoLocalizacion'].'%', "'".'COM-'.$_SESSION['codigoLocalizacion'].'-'."'");
				$combustible = pg_fetch_assoc($res);
				$incremento = $combustible['numero'] + 1;
				$numero = 'COM-'.$_SESSION['codigoLocalizacion'].'-'.str_pad($incremento, 8, "0", STR_PAD_LEFT);
				
				$qCombustible = $cv -> guardarNuevoCombustible($conexion,$numero, $datos['placa'], $datos['kilometraje'],$datos['conductor'],$datos['combustible'],$datos['gasolinera'],$_SESSION['nombreLocalizacion'], $datos['fechaDespacho'], $datos['idVehiculo'],$identificadorUsuarioRegistro, $datos['montoSolicitado'], $datos['galonesSolicitados'], $idProvincia, $nombreProvincia, $kilometrajeSolicitud);
				$idCombustible = pg_fetch_result($qCombustible, 0, 'id_combustible');
				//$cv ->actualizarEstadoVehiculo($conexion, $datos['placa'], 'Combustible');
						
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
				$mensaje['mensaje'] = "El vehículo " . $datos['placa'] . " ya dispone de una orden generada para el día " . date("Y-m-d") .  ", razón por la cual no se puede generar una segunda orden.";
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
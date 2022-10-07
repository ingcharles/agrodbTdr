<?php
session_start();

require_once '../../general/webServicesBSGAutenticacion.php';
require_once '../../general/webServicesBSGCedula.php';

$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Ha ocurrido un error!';



try{
	
	$webServicesAutenticacion = new webServicesBSGAutenticacion();
	$webServicesCedula = new webServicesBSGCedula();
	
	try {
			try {
				$resultadoAutenticacion = $webServicesAutenticacion->consultarWebServicesAutenticacion('https://pru.bsg.gob.ec/sw/RC/BSGSW01_Consultar_Cedula?wsdl', 'https://pru.bsg.gob.ec/sw/STI/BSGSW08_Acceder_BSG?wsdl', '1722551049');
			} catch (Exception $e) {
				echo $e;
			}
			
			//$resultadoConsulta = $webServicesCedula->consultarWebServicesCedula('https://pru.bsg.gob.ec/sw/RC/BSGSW01_Consultar_Cedula?wsdl', $resultadoAutenticacion, $_POST['numero']);
				
			if($resultadoConsulta['CodigoError'] == '000'){
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = $resultadoConsulta;
			}else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = $resultadoConsulta['Error'];
			}	

			
			
		echo json_encode($mensaje);
	} catch (Exception $ex){
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = 'Error al ejecutar sentencia';
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>
<?php
session_start();

require_once '../../clases/ControladorServiciosGubernamentales.php';

$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$identificadorConsulta = $_POST['numero'];
$tipoIdentificacion = $_POST['clasificacion'];
$tipoAcceso = false;

switch ($tipoIdentificacion){
	
	case 'Cédula':
		$tipoAcceso = true;
		//$rutaWebervices = 'https://www.bsg.gob.ec/sw/RC/BSGSW01_Consultar_Cedula?wsdl';
		$rutaWebervices = 'https://www.bsg.gob.ec/sw/RC/BSGSW03_Consultar_Ciudadano?wsdl';
	break;
	
	case 'Natural':		
	case 'Juridica':
	case 'Publica':
		$tipoAcceso = true;
		$rutaWebervices = 'https://www.bsg.gob.ec/sw/SRI/BSGSW01_Consultar_RucSRI?wsdl';
	break;
	
	case "Pasaporte":
			$tipoAcceso = true;
	break;
	
	case "Refugiado":
			$tipoAcceso = true;
	break;
	
	case 'Senecyt':
		$tipoAcceso = true;
		$rutaWebervices = 'https://www.bsg.gob.ec/sw/SENESCYT/BSGSW04_Consultar_Titulos?wsdl';
	break;
	
	case 'AntMatriculaLicencia':
		$tipoAcceso = true;
		$rutaWebervices = 'https://www.bsg.gob.ec/sw/ANT/BSGSW01_Consultar_MatriculaLic?wsdl';
	break;
		
}


try{
	
	$webServices = new ControladorServiciosGubernamentales();
	
	try {
		
		if($tipoAcceso){
			
			try {
			
				//$resultadoAutenticacion = $webServicesAutenticacion->consultarWebServicesAutenticacion('https://pru.bsg.gob.ec/sw/RC/BSGSW01_Consultar_Cedula?wsdl', 'https://pru.bsg.gob.ec/sw/STI/BSGSW08_Acceder_BSG?wsdl');
				$resultadoAutenticacion = $webServices->consultarWebServicesAutenticacion($rutaWebervices);
					
			} catch (Exception $e) {
				echo $e;
			}
				
			$cabeceraSeguridad = $webServices->crearCabeceraSeguridadWebServices($resultadoAutenticacion);
			
			switch ($tipoIdentificacion){
			
				case 'Cédula':
					$resultadoConsulta = $webServices->consultarWebServicesCedula($cabeceraSeguridad, $identificadorConsulta);
					$mensaje['valores'] = $resultadoConsulta;
				break;
			
				case 'Natural':
				case 'Juridica':
				case 'Publica':
					//Tipos de funciones del SRI: obtenerCompleto, obtenerDatos, obtenerSimple
					$resultadoConsulta = $webServices->consultarWebServicesRUC($cabeceraSeguridad, $identificadorConsulta, 'obtenerCompleto');
					$mensaje['valores'] = $resultadoConsulta;
				break;
				
				case "Pasaporte":
				
					if (strlen($identificadorConsulta)>=7 && strlen($identificadorConsulta)<=13)
						$resultadoConsulta=array(CodigoError=>'000',Error=>'NO ERROR');
					else
						$resultadoConsulta=array(CodigoError=>'001',Error=>'PASAPORTE DEBE TENER ENTRE 7 A 13 DIGITOS');
				
				break;
							
				case "Refugiado":
						
					if (strlen($identificadorConsulta)==10)
						$resultadoConsulta=array(CodigoError=>'000',Error=>'NO ERROR');
					else
						$resultadoConsulta=array(CodigoError=>'001',Error=>'DOCUMENTO REFUGIADO DEBE TENER 10 DIGITOS');
				break;
				
				case "Senecyt":
					$titulos = array();
					$resultadoConsulta = $webServices->consultarWebServicesSenecyt($cabeceraSeguridad, $identificadorConsulta);
					
					if(count($resultadoConsulta['datos']) != 0){
						foreach ($resultadoConsulta['datos'] as $dato){
							if(count($resultadoConsulta['datos']) == 1){
								$titulos[] = array(titulo => $dato);
							}else{
								$titulos[] = $dato;
							}
							
						}
					}
					$mensaje['valores'] = $titulos;
				break;
				
				case "AntMatriculaLicencia":
					$resultadoConsulta = $webServices->consultarWebServicesANT($cabeceraSeguridad, $identificadorConsulta);
					$mensaje['valores'] = $resultadoConsulta;
				break;
			}

			if($resultadoConsulta['CodigoError'] == '000'){
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = $resultadoConsulta['Error'];
				
			}else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = $resultadoConsulta['Error'];
			}
			
		}	
		echo json_encode($mensaje);
	} catch (Exception $ex){
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = 'Error al ejecutar sentencia';
		echo json_encode($mensaje);
		$conexion->ejecutarLogsTryCatch($ex);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
	$conexion->ejecutarLogsTryCatch($ex);
}
?>
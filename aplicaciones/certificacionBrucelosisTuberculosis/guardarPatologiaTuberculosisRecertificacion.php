<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorBrucelosisTuberculosis.php';
	
	$mensaje = array();
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Ha ocurrido un error!';	
	
	$ruta='certificacionBrucelosisTuberculosis';
	
	try{
	
		$identificador = $_SESSION['usuario'];
	
		$idCertificacionBT = htmlspecialchars ($_POST['idRecertificacionBT'],ENT_NOQUOTES,'UTF-8');
		$numInspeccion = htmlspecialchars ($_POST['numInspeccion'],ENT_NOQUOTES,'UTF-8');
		
		$perdidaPeso = htmlspecialchars ($_POST['perdidaPeso'],ENT_NOQUOTES,'UTF-8');
		$perdidaApetito = htmlspecialchars ($_POST['perdidaApetito'],ENT_NOQUOTES,'UTF-8');
		$problemasRespiratorios = htmlspecialchars ($_POST['problemasRespiratorios'],ENT_NOQUOTES,'UTF-8');
		$tosIntermitente = htmlspecialchars ($_POST['tosIntermitente'],ENT_NOQUOTES,'UTF-8');
		$abultamiento = htmlspecialchars ($_POST['abultamiento'],ENT_NOQUOTES,'UTF-8');		
		$fiebreFluctuante = htmlspecialchars ($_POST['fiebreFluctuante'],ENT_NOQUOTES,'UTF-8');
	
		$conexion = new Conexion();
		$cbt = new ControladorBrucelosisTuberculosis();
	
		try {
				
			if(($identificador != null) || ($identificador != '')){
				
				$patologiaTuberculosis = $cbt->buscarPatologiaTuberculosisRecertificacionBT($conexion, $idCertificacionBT, $numInspeccion);
		
				if(pg_num_rows($patologiaTuberculosis) == 0){
					$conexion->ejecutarConsulta("begin;");
					
					$idPatologiaTuberculosis = pg_fetch_result($cbt->guardarPatologiaTuberculosisRecertificacionBT($conexion, 
																		$idCertificacionBT, $identificador,
																		$perdidaPeso, $perdidaApetito,
																		$problemasRespiratorios, $tosIntermitente,
																		$abultamiento, $fiebreFluctuante, $numInspeccion), 
																		0, 'id_recertificacion_bt_patologia_tuberculosis');				
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = $cbt->imprimirLineaPatologiaTuberculosisRecertificacionBT($idPatologiaTuberculosis, 
																		$perdidaPeso, $perdidaApetito, $problemasRespiratorios, 
																		$tosIntermitente, $abultamiento, $fiebreFluctuante, $ruta,
																		$numInspeccion);
					
					$conexion->ejecutarConsulta("commit;");
				
				}else{
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = "La información de patología de tuberculosis el predio ya ha sido ingresada.";
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
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
	
		$idCertificacionBT = htmlspecialchars ($_POST['idCertificacionBT'],ENT_NOQUOTES,'UTF-8');
		$numInspeccion = htmlspecialchars ($_POST['numInspeccion'],ENT_NOQUOTES,'UTF-8');
		
		$pruebasTuberculosisLeche = htmlspecialchars ($_POST['pruebasTuberculosisLeche'],ENT_NOQUOTES,'UTF-8');
		$resultadoTuberculosisLeche = htmlspecialchars ($_POST['resultadoTuberculosisLeche'],ENT_NOQUOTES,'UTF-8');
	
		$conexion = new Conexion();
		$cbt = new ControladorBrucelosisTuberculosis();
	
		try {
				
			if(($identificador != null) || ($identificador != '')){
				
				$pruebaTuberculosis = $cbt->buscarPruebaTuberculosisLecheCertificacionBT($conexion, $idCertificacionBT, 
																		$pruebasTuberculosisLeche, $resultadoTuberculosisLeche,
																		$numInspeccion);
		
				if(pg_num_rows($pruebaTuberculosis) == 0){
					$conexion->ejecutarConsulta("begin;");
					
					$idPruebaTuberculosisLeche = pg_fetch_result($cbt->guardarPruebaTuberculosisLecheCertificacionBT($conexion, 
																		$idCertificacionBT, $identificador, $pruebasTuberculosisLeche, 
																		$resultadoTuberculosisLeche, $numInspeccion), 
																		0, 'id_certificacion_bt_prueba_tuberculosis_leche');				
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = $cbt->imprimirLineaPruebaTuberculosisLecheCertificacionBT($idPruebaTuberculosisLeche, 
																		$pruebasTuberculosisLeche, $resultadoTuberculosisLeche, $ruta,
																		$numInspeccion);
					
					$conexion->ejecutarConsulta("commit;");
				
				}else{
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = "La información de pruebas de tuberculosis en leche el predio ya ha sido ingresada.";
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
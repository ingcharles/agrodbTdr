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
		
		$idProcedenciaAgua = htmlspecialchars ($_POST['procedenciaAgua'],ENT_NOQUOTES,'UTF-8');
		$procedenciaAgua = htmlspecialchars ($_POST['nombreProcedenciaAgua'],ENT_NOQUOTES,'UTF-8');
		
		$conexion = new Conexion();
		$cbt = new ControladorBrucelosisTuberculosis();
	
		try {
				
			if(($identificador != null) || ($identificador != '')){
				
				$procedenciaAguaPredio = $cbt->buscarProcedenciaAguaCertificacionBT($conexion, $idCertificacionBT, $idProcedenciaAgua, $numInspeccion);
		
				if(pg_num_rows($procedenciaAguaPredio) == 0){
					$conexion->ejecutarConsulta("begin;");
					
					$idProcedenciaAguaPredio = pg_fetch_result($cbt->guardarProcedenciaAguaCertificacionBT($conexion, $idCertificacionBT, 
																	$identificador, $idProcedenciaAgua, $procedenciaAgua, $numInspeccion), 
																	0, 'id_certificacion_bt_procedencia_agua');				
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = $cbt->imprimirLineaProcedenciaAguaCertificacionBT($idProcedenciaAguaPredio, 
																	$procedenciaAgua, $ruta, $numInspeccion);
					
					$conexion->ejecutarConsulta("commit;");
				
				}else{
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = "La información de la procedencia de animales ya ha sido ingresada.";
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
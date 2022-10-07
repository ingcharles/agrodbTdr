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
		
		$idTipoProduccion = htmlspecialchars ($_POST['tipoProduccion'],ENT_NOQUOTES,'UTF-8');
		$tipoProduccion = htmlspecialchars ($_POST['nombreTipoProduccion'],ENT_NOQUOTES,'UTF-8');
		$idDestinoLeche = htmlspecialchars ($_POST['destinoLeche'],ENT_NOQUOTES,'UTF-8');	
		$destinoLeche = htmlspecialchars ($_POST['nombreDestinoLeche'],ENT_NOQUOTES,'UTF-8');
		$idTipoExplotacion = htmlspecialchars ($_POST['tipoExplotacion'],ENT_NOQUOTES,'UTF-8');
		$tipoExplotacion = htmlspecialchars ($_POST['nombreTipoExplotacion'],ENT_NOQUOTES,'UTF-8');
	
		$conexion = new Conexion();
		$cbt = new ControladorBrucelosisTuberculosis();
	
		try {
				
			if(($identificador != null) || ($identificador != '')){
				
				$informacionProduccion = $cbt->buscarProduccionExplotacionDestinoCertificacionBT($conexion, 
															$idCertificacionBT, $idTipoProduccion,  
															$idDestinoLeche, $idTipoExplotacion, $numInspeccion);
		
				if(pg_num_rows($informacionProduccion) == 0){
					$conexion->ejecutarConsulta("begin;");
					
					$idProduccion = pg_fetch_result($cbt->guardarProduccionExplotacionDestinoCertificacionBT($conexion, 
																	$identificador, $idCertificacionBT, 
																	$idTipoProduccion, $tipoProduccion, 
																	$idDestinoLeche, $destinoLeche, 
																	$idTipoExplotacion, $tipoExplotacion, $numInspeccion), 
																	0, 'id_certificacion_bt_produccion');				
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = $cbt->imprimirLineaProduccionCertificacionBT($idProduccion, $tipoProduccion, 
																				$destinoLeche, $tipoExplotacion, $ruta,
																				$numInspeccion);
					
					$conexion->ejecutarConsulta("commit;");
				
				}else{
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = "La información del predio ya ha sido ingresada.";
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
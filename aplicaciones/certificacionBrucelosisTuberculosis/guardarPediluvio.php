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
		
		$idPediluvio = htmlspecialchars ($_POST['pediluvio'],ENT_NOQUOTES,'UTF-8');
		$pediluvio = htmlspecialchars ($_POST['nombrePediluvio'],ENT_NOQUOTES,'UTF-8');
	
		$conexion = new Conexion();
		$cbt = new ControladorBrucelosisTuberculosis();
	
		try {
				
			if(($identificador != null) || ($identificador != '')){
				
				$registroPediluvio = $cbt->buscarPediluvioCertificacionBT($conexion, $idCertificacionBT, $idPediluvio, $numInspeccion);
		
				if(pg_num_rows($registroPediluvio) == 0){
					$conexion->ejecutarConsulta("begin;");
					
					$idPediluvioCertificacionBT = pg_fetch_result($cbt->guardarPediluvioCertificacionBT($conexion, 
																	$idCertificacionBT, $identificador, 
																	$idPediluvio, $pediluvio, $numInspeccion), 
																	0, 'id_certificacion_bt_pediluvio');				
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = $cbt->imprimirLineaPediluvioCertificacionBT($idPediluvioCertificacionBT,
																						$pediluvio, $ruta, $numInspeccion);
					
					$conexion->ejecutarConsulta("commit;");
				
				}else{
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = "La información del pediluvio ya ha sido ingresada.";
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
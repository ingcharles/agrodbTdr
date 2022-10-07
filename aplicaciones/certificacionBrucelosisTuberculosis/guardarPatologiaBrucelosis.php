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
		
		$retencionPlacenta = htmlspecialchars ($_POST['retencionPlacenta'],ENT_NOQUOTES,'UTF-8');
		$nacimientoTernerosDebiles = htmlspecialchars ($_POST['nacimientoTernerosDebiles'],ENT_NOQUOTES,'UTF-8');
		$problemasEsterilidad = htmlspecialchars ($_POST['problemasEsterilidad'],ENT_NOQUOTES,'UTF-8');
		$metritisPostParto = htmlspecialchars ($_POST['metritisPostParto'],ENT_NOQUOTES,'UTF-8');
		$hinchazonArticulaciones = htmlspecialchars ($_POST['hinchazonArticulaciones'],ENT_NOQUOTES,'UTF-8');		
		$epididimitisOrquitis = htmlspecialchars ($_POST['epididimitisOrquitis'],ENT_NOQUOTES,'UTF-8');
	
		$conexion = new Conexion();
		$cbt = new ControladorBrucelosisTuberculosis();
	
		try {
				
			if(($identificador != null) || ($identificador != '')){
				
				$patologiaBrucelosis = $cbt->buscarPatologiaBrucelosisCertificacionBT($conexion, $idCertificacionBT, $numInspeccion);
		
				if(pg_num_rows($patologiaBrucelosis) == 0){
					$conexion->ejecutarConsulta("begin;");
					
					$idPatologiaBrucelosis = pg_fetch_result($cbt->guardarPatologiaBrucelosisCertificacionBT($conexion, 
																		$idCertificacionBT, $identificador, $retencionPlacenta, 
																		$nacimientoTernerosDebiles, $problemasEsterilidad, 
																		$metritisPostParto, $hinchazonArticulaciones, $epididimitisOrquitis,
																		$numInspeccion), 
																		0, 'id_certificacion_bt_patologia_brucelosis');				
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = $cbt->imprimirLineaPatologiaBrucelosisCertificacionBT($idPatologiaBrucelosis, 
																		$retencionPlacenta, $nacimientoTernerosDebiles, 
																		$problemasEsterilidad, $metritisPostParto, 
																		$hinchazonArticulaciones, $epididimitisOrquitis, $ruta,
																		$numInspeccion);
					
					$conexion->ejecutarConsulta("commit;");
				
				}else{
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = "La información de patología de brucelosis el predio ya ha sido ingresada.";
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
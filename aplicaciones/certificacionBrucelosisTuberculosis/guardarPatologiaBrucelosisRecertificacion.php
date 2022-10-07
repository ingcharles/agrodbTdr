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
	
		$idRecertificacionBT = htmlspecialchars ($_POST['idRecertificacionBT'],ENT_NOQUOTES,'UTF-8');
		$numInspeccion = htmlspecialchars ($_POST['numInspeccion'],ENT_NOQUOTES,'UTF-8');
		
		$retencionPlacenta = htmlspecialchars ($_POST['retencionPlacenta'],ENT_NOQUOTES,'UTF-8');
		$nacimientoTernerosDebiles = htmlspecialchars ($_POST['nacimientoTernerosDebiles'],ENT_NOQUOTES,'UTF-8');
		$problemasEsterilidad = htmlspecialchars ($_POST['problemasEsterilidad'],ENT_NOQUOTES,'UTF-8');
		$metritisPostParto = htmlspecialchars ($_POST['metritisPostParto'],ENT_NOQUOTES,'UTF-8');
		$abortos = htmlspecialchars ($_POST['abortos'],ENT_NOQUOTES,'UTF-8');		
		$fiebre = htmlspecialchars ($_POST['fiebreBovinos'],ENT_NOQUOTES,'UTF-8');
	
		$conexion = new Conexion();
		$cbt = new ControladorBrucelosisTuberculosis();
	
		try {
				
			if(($identificador != null) || ($identificador != '')){
				
				$patologiaBrucelosis = $cbt->buscarPatologiaBrucelosisRecertificacionBT($conexion, $idRecertificacionBT, $numInspeccion);
		
				if(pg_num_rows($patologiaBrucelosis) == 0){
					$conexion->ejecutarConsulta("begin;");
					
					$idPatologiaBrucelosis = pg_fetch_result($cbt->guardarPatologiaBrucelosisRecertificacionBT($conexion, 
																		$idRecertificacionBT, $identificador, $retencionPlacenta, 
																		$nacimientoTernerosDebiles,  
																		$metritisPostParto, $abortos, $fiebre,
																		$numInspeccion), 
																		0, 'id_recertificacion_bt_patologia_brucelosis');				
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = $cbt->imprimirLineaPatologiaBrucelosisRecertificacionBT($idPatologiaBrucelosis, 
																		$retencionPlacenta, $nacimientoTernerosDebiles, 
																		$metritisPostParto, 
																		$abortos, $fiebre, $ruta,
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
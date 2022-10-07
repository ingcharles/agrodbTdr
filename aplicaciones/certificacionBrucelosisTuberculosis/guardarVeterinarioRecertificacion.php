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
		
		$nombreVeterinario = htmlspecialchars ($_POST['nombreVeterinario'],ENT_NOQUOTES,'UTF-8');
		$telefonoVeterinario = htmlspecialchars ($_POST['telefonoVeterinario'],ENT_NOQUOTES,'UTF-8');
		$celularVeterinario = htmlspecialchars ($_POST['celularVeterinario'],ENT_NOQUOTES,'UTF-8');	
		$correoElectronicoVeterinario = htmlspecialchars ($_POST['correoElectronicoVeterinario'],ENT_NOQUOTES,'UTF-8');
		$idFrecuenciaVisitaVeterinario = htmlspecialchars ($_POST['frecuenciaVisitaVeterinario'],ENT_NOQUOTES,'UTF-8');
		$frecuenciaVisitaVeterinario = htmlspecialchars ($_POST['nombreFrecuenciaVisitaVeterinario'],ENT_NOQUOTES,'UTF-8');
	
		$conexion = new Conexion();
		$cbt = new ControladorBrucelosisTuberculosis();
	
		try {
				
			if(($identificador != null) || ($identificador != '')){
				
				$veterinario = $cbt->buscarVeterinarioRecertificacionBT($conexion, $idRecertificacionBT, $numInspeccion);
		
				if(pg_num_rows($veterinario) == 0){
					$conexion->ejecutarConsulta("begin;");
					
					$idVeterinario = pg_fetch_result($cbt->guardarVeterinarioRecertificacionBT($conexion, $idRecertificacionBT, $identificador,
																$nombreVeterinario, $telefonoVeterinario, $celularVeterinario,
																$correoElectronicoVeterinario, $idFrecuenciaVisitaVeterinario,
																$frecuenciaVisitaVeterinario, $numInspeccion), 
																0, 'id_recertificacion_bt_veterinario');				
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = $cbt->imprimirLineaVeterinarioRecertificacionBT($idVeterinario, $nombreVeterinario, $telefonoVeterinario, 
														$celularVeterinario, $correoElectronicoVeterinario, 
														$frecuenciaVisitaVeterinario, $ruta, $numInspeccion);
					
					$conexion->ejecutarConsulta("commit;");
				
				}else{
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = "La información del veterinario ya ha sido ingresada.";
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
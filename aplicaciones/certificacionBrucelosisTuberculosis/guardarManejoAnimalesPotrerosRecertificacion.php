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
		
		$pastosComunales = htmlspecialchars ($_POST['pastosComunales'],ENT_NOQUOTES,'UTF-8');
		$arriendaPotreros = htmlspecialchars ($_POST['arriendaPotreros'],ENT_NOQUOTES,'UTF-8');
		$arriendaPotrerosOtroPredio = htmlspecialchars ($_POST['arriendaPotrerosOtroPredio'],ENT_NOQUOTES,'UTF-8');
		$utilizaEstiercol = htmlspecialchars ($_POST['utilizaEstiercol'],ENT_NOQUOTES,'UTF-8');
		$feriaExposicion = htmlspecialchars ($_POST['feriaExposicion'],ENT_NOQUOTES,'UTF-8');
		$desinfectaAnimales = htmlspecialchars ($_POST['desinfectaAnimales'],ENT_NOQUOTES,'UTF-8');
		$trabajadoresAnimalesPredio = htmlspecialchars ($_POST['trabajadoresAnimalesPredio'],ENT_NOQUOTES,'UTF-8');
		$programaPrediosLibres = htmlspecialchars ($_POST['programaPrediosLibres'],ENT_NOQUOTES,'UTF-8');
	
		$conexion = new Conexion();
		$cbt = new ControladorBrucelosisTuberculosis();
	
		try {
				
			if(($identificador != null) || ($identificador != '')){
				
				$manejoAnimal = $cbt->buscarManejoAnimalesPotrerosRecertificacionBT($conexion, $idRecertificacionBT, $numInspeccion);
		
				if(pg_num_rows($manejoAnimal) == 0){
					$conexion->ejecutarConsulta("begin;");
					
					$idManejoAnimal = pg_fetch_result($cbt->guardarManejoAnimalesPotrerosRecertificacionBT($conexion, 
																	$idRecertificacionBT, $identificador,
																	$pastosComunales, $arriendaPotreros, 
																	$arriendaPotrerosOtroPredio,
																	$feriaExposicion, 
																	$desinfectaAnimales,  
																	$programaPrediosLibres, $numInspeccion), 
																	0, 'id_certificacion_bt_manejo_animales_predio');				
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = $cbt->imprimirLineaManejoAnimalesPotrerosRecertificacionBT($idManejoAnimal, 
																		$pastosComunales, 
																		$arriendaPotreros, 
																		$arriendaPotrerosOtroPredio,
																		$feriaExposicion, 
																		$desinfectaAnimales, 
																		$programaPrediosLibres, $ruta, $numInspeccion);
					
					$conexion->ejecutarConsulta("commit;");
				
				}else{
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = "La información del manejo animal en el predio ya ha sido ingresada.";
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
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
		
		$idProcedenciaAnimales = htmlspecialchars ($_POST['procedenciaAnimales'],ENT_NOQUOTES,'UTF-8');
		$procedenciaAnimales = htmlspecialchars ($_POST['nombreProcedenciaAnimales'],ENT_NOQUOTES,'UTF-8');
		$idCategoriaAnimalesAdquiere = htmlspecialchars ($_POST['categoriaAnimalesAdquiere'],ENT_NOQUOTES,'UTF-8');
		$categoriaAnimalesAdquiere = htmlspecialchars ($_POST['nombreCategoriaAnimalesAdquiere'],ENT_NOQUOTES,'UTF-8');
	
		$conexion = new Conexion();
		$cbt = new ControladorBrucelosisTuberculosis();
	
		try {
				
			if(($identificador != null) || ($identificador != '')){
				
				$adquisicionAnimal = $cbt->buscarAdquisicionAnimalesCertificacionBT($conexion, $idCertificacionBT, $idProcedenciaAnimales, $idCategoriaAnimalesAdquiere, $numInspeccion);
		
				if(pg_num_rows($adquisicionAnimal) == 0){
					$conexion->ejecutarConsulta("begin;");
					
					$idAdquisicionAnimal = pg_fetch_result($cbt->guardarAdquisicionAnimalesCertificacionBT($conexion, $idCertificacionBT,
																	$identificador, $idProcedenciaAnimales, $procedenciaAnimales, 
																	$idCategoriaAnimalesAdquiere, $categoriaAnimalesAdquiere,
																	$numInspeccion), 
																	0, 'id_certificacion_bt_adquisicion_animales');				
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = $cbt->imprimirLineaAdquisicionAnimalesCertificacionBT($idAdquisicionAnimal,  
																	$procedenciaAnimales, $categoriaAnimalesAdquiere, $ruta,
																	$numInspeccion);
					
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
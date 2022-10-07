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
		
		$superficiePredio = htmlspecialchars ($_POST['superficiePredio'],ENT_NOQUOTES,'UTF-8');
		$superficiePastos = htmlspecialchars ($_POST['superficiePastos'],ENT_NOQUOTES,'UTF-8');
		$cerramientoExterno = htmlspecialchars ($_POST['cerramientoExterno'],ENT_NOQUOTES,'UTF-8');	
		$controlIngresoPersonas = htmlspecialchars ($_POST['controlIngresoPersonas'],ENT_NOQUOTES,'UTF-8');
		$mangaEmbudoBrete = htmlspecialchars ($_POST['mangaEmbudoBrete'],ENT_NOQUOTES,'UTF-8');
		$identificacionBovinos = htmlspecialchars ($_POST['identificacionBovinos'],ENT_NOQUOTES,'UTF-8');
		$controlIngresoAnimales = htmlspecialchars ($_POST['controlIngresoAnimales'],ENT_NOQUOTES,'UTF-8');
	
		$conexion = new Conexion();
		$cbt = new ControladorBrucelosisTuberculosis();
	
		try {
				
			if(($identificador != null) || ($identificador != '')){
				
				if($superficiePredio >= $superficiePastos){
					$informacionPredio = $cbt->buscarInformacionPredioCertificacionBT($conexion, $idCertificacionBT, $numInspeccion);
			
					if(pg_num_rows($informacionPredio) == 0){
						$conexion->ejecutarConsulta("begin;");
						
						$idInformacionPredio = pg_fetch_result($cbt->guardarInformacionPredioCertificacionBT($conexion, 
																		$idCertificacionBT, $identificador,
																		$superficiePredio, $superficiePastos,
																		$cerramientoExterno, $controlIngresoPersonas,
																		$mangaEmbudoBrete, $identificacionBovinos,
																		$controlIngresoAnimales, $numInspeccion), 
																		0, 'id_certificacion_bt_informacion_predio');				
						
						$mensaje['estado'] = 'exito';
						$mensaje['mensaje'] = $cbt->imprimirLineaInformacionPredioCertificacionBT($idInformacionPredio,
								$superficiePredio, $superficiePastos, $cerramientoExterno,
								$controlIngresoPersonas, $mangaEmbudoBrete, $identificacionBovinos,
								$controlIngresoAnimales, $ruta, $numInspeccion);
						
						$conexion->ejecutarConsulta("commit;");
					
					}else{
						$mensaje['estado'] = 'error';
						$mensaje['mensaje'] = "La información del predio ya ha sido ingresada.";
					}							
				
				}else{
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = "La superficie de los pastos no debe ser mayor a la del predio.";
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
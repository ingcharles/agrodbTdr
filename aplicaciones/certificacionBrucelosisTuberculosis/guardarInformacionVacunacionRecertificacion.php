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
		
		$idMotivoVacunacion = htmlspecialchars ($_POST['motivoVacunacion'],ENT_NOQUOTES,'UTF-8');
		$motivoVacunacion = htmlspecialchars ($_POST['nombreMotivoVacunacion'],ENT_NOQUOTES,'UTF-8');
		$idVacunasAplicadas = htmlspecialchars ($_POST['vacunasAplicadas'],ENT_NOQUOTES,'UTF-8');
		$vacunasAplicadas = htmlspecialchars ($_POST['nombreVacunasAplicadas'],ENT_NOQUOTES,'UTF-8');
		$idProcedenciaVacunas = htmlspecialchars ($_POST['procedenciaVacunas'],ENT_NOQUOTES,'UTF-8');
		$procedenciaVacunas = htmlspecialchars ($_POST['nombreProcedenciaVacunas'],ENT_NOQUOTES,'UTF-8');
		$fechaVacunacion = htmlspecialchars ($_POST['fechaVacunacion'],ENT_NOQUOTES,'UTF-8');
		$loteVacuna = htmlspecialchars ($_POST['loteVacuna'],ENT_NOQUOTES,'UTF-8');
		$calendarioVacunacion = htmlspecialchars ($_POST['calendarioVacunacion'],ENT_NOQUOTES,'UTF-8');
	
		$conexion = new Conexion();
		$cbt = new ControladorBrucelosisTuberculosis();
	
		try {
				
			if(($identificador != null) || ($identificador != '')){
				
				if($calendarioVacunacion == 'No'){
					$idMotivoVacunacion = 0;
					$motivoVacunacion = 'No Aplica';
					$idVacunasAplicadas = 0;
					$vacunasAplicadas = 'No Aplica';
					$idProcedenciaVacunas = 0;
					$procedenciaVacunas = 'No Aplica';
					$fechaVacunacion = 'now()';
					$loteVacuna = 'No Aplica';
				}
				
				$infoVacunacion = $cbt->buscarInformacionVacunacionRecertificacionBT($conexion, $idRecertificacionBT, 
											$idMotivoVacunacion, $idVacunasAplicadas, $loteVacuna,
											$numInspeccion, $calendarioVacunacion);
		
				if(pg_num_rows($infoVacunacion) == 0){
					$conexion->ejecutarConsulta("begin;");
					
					$idInformacionVacunacion = pg_fetch_result($cbt->guardarInformacionVacunacionRecertificacionBT($conexion, 
																	$idRecertificacionBT, $identificador, $idMotivoVacunacion,
																	$motivoVacunacion, $idVacunasAplicadas, 
																	$vacunasAplicadas, $fechaVacunacion, $loteVacuna,
																	$numInspeccion, $calendarioVacunacion), 
																	0, 'id_recertificacion_bt_informacion_vacunacion');				
					
					if($calendarioVacunacion == 'No'){
						$fecha = getdate();
						$fechaVacunacion = $fecha['mday'].'/'.$fecha['mon'].'/'.$fecha['year'];
					}
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = $cbt->imprimirLineaInformacionVacunacionRecertificacionBT($idInformacionVacunacion,
																	$motivoVacunacion, $vacunasAplicadas, 
																	$loteVacuna, $fechaVacunacion, $ruta,
																	$numInspeccion, $calendarioVacunacion);
					
					$conexion->ejecutarConsulta("commit;");
				
				}else{
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = "La información de vacunación en el predio ya ha sido ingresada.";
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
<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorBrucelosisTuberculosis.php';
require_once 'crearCertificadoBT.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$conexion = new Conexion();
	$cbt = new ControladorBrucelosisTuberculosis();

	$identificador = $_SESSION['usuario'];

	$idRecertificacionBT = htmlspecialchars ($_POST['idRecertificacionBT'],ENT_NOQUOTES,'UTF-8');
	$numInspeccion = htmlspecialchars ($_POST['numInspeccion'],ENT_NOQUOTES,'UTF-8');
	$numeroSolicitud = htmlspecialchars ($_POST['numSolicitud'],ENT_NOQUOTES,'UTF-8');
	
	$estado = htmlspecialchars ($_POST['resultado'],ENT_NOQUOTES,'UTF-8');
	$fechaInspeccion = htmlspecialchars ($_POST['fechaInspeccion'],ENT_NOQUOTES,'UTF-8');
	$observaciones = htmlspecialchars ($_POST['observaciones'],ENT_NOQUOTES,'UTF-8');
	
	$idLaboratorio = htmlspecialchars ($_POST['laboratorioMuestras'],ENT_NOQUOTES,'UTF-8');
	$laboratorio = htmlspecialchars ($_POST['nombreLaboratorioMuestras'],ENT_NOQUOTES,'UTF-8');
	
	$certificacion = htmlspecialchars ($_POST['certificacion'],ENT_NOQUOTES,'UTF-8');
	$numeroRecertificacion = htmlspecialchars ($_POST['numRecertificacion'],ENT_NOQUOTES,'UTF-8');
	$idCertificacionBT = htmlspecialchars ($_POST['idCertificacionBT'],ENT_NOQUOTES,'UTF-8');
	
	try {
		
		if(($identificador != null) || ($identificador != '')){
		//Actualizar todo hacia abajo
			switch ($certificacion){
				case 'Brucelosis':
					$informacionPredio = $cbt->abrirInformacionPredioRecertificacionBTInspeccion($conexion, $idRecertificacionBT, $numInspeccion);
					$inventario = $cbt->abrirInventarioAnimalRecertificacionBTInspeccion($conexion, $idRecertificacionBT, $numInspeccion);
					$manejoAnimal = $cbt->abrirManejoAnimalRecertificacionBTInspeccion($conexion, $idRecertificacionBT, $numInspeccion);
					$adquisicionAnimales = $cbt->abrirAdquisicionAnimalesRecertificacionBTInspeccion($conexion, $idRecertificacionBT, $numInspeccion);
					$veterinario = $cbt->abrirVeterinarioRecertificacionBTInspeccion($conexion, $idRecertificacionBT, $numInspeccion);
					$vacunacion = $cbt->abrirVacunacionRecertificacionBTInspeccion($conexion, $idRecertificacionBT, $numInspeccion);
					$patologia = $cbt->abrirPatologiaBrucelosisRecertificacionBTInspeccion($conexion, $idRecertificacionBT, $numInspeccion);
					
					if($estado == 'tomaMuestras'){
						if( (pg_num_rows($informacionPredio) != 0) && (pg_num_rows($inventario) != 0) && 
							(pg_num_rows($manejoAnimal) != 0) && (pg_num_rows($adquisicionAnimales) != 0) && 
							(pg_num_rows($veterinario) != 0) && (pg_num_rows($vacunacion) != 0) && 
							(pg_num_rows($patologia) != 0)){
							
								$conexion->ejecutarConsulta("begin;");
								
								/*$numInspeccionC = pg_fetch_result($cbt->generarNumeroInspeccionCertificacionBT($conexion, 'Inspección ', $numeroSolicitud), 0, 'num_inspeccion');
								$tmpInspeccion= explode(" ", $numInspeccionC);
								$incrementoInspeccion = end($tmpInspeccion)+1;
								$numInspeccion = 'Inspección '.str_pad($incrementoInspeccion, 4, "0", STR_PAD_LEFT);*/
									
								$cbt->cierreRecertificacionBTTecnico($conexion, $idRecertificacionBT, $identificador,
																	$observaciones, $estado, $numInspeccion, 
																	$idLaboratorio, $laboratorio, $fechaInspeccion);
								
								$conexion->ejecutarConsulta("commit;");
								
								$mensaje['estado'] = 'exito';
								$mensaje['mensaje'] = 'Los datos se han guardado exitosamente';
						
						}else{
						 $mensaje['estado'] = 'error';
						 $mensaje['mensaje'] = "Debe ingresar por lo menos un resultado de inspección para poder continuar.";
						}					
					}else{
						$conexion->ejecutarConsulta("begin;");
													
							if($estado == 'aprobado'){
								//creación de certificado
								$pdf = new PDF_BT();
								$pdf->AliasNbPages();
								$pdf->AddPage();
								$pdf->Body('recertificacion', $idRecertificacionBT);
								$pdf->SetFont('Times','',12);
								$pdf->Output("certificados/brucelosis/".$numeroSolicitud."-".$numeroRecertificacion."-".$identificador.".pdf");
									
								$rutaCertificado = "aplicaciones/certificacionBrucelosisTuberculosis/certificados/brucelosis/".$numeroSolicitud."-".$numeroRecertificacion."-".$identificador.".pdf";

								//Actualizar registro
								$cbt->actualizarCertificadoAprobacionRecertificacion($conexion, $idRecertificacionBT, $identificador, $rutaCertificado);
							}
							
							$cbt->cierreRecertificacionBTTecnico($conexion, $idRecertificacionBT, $identificador,
									$observaciones, $estado, $numInspeccion,
									$idLaboratorio, $laboratorio, $fechaInspeccionh);
							
							//Cambiar de estado al registro original
							$cbt->actualizarEstadoCertificacionBT($conexion, $idCertificacionBT, $estado);
							
						$conexion->ejecutarConsulta("commit;");
						
						$mensaje['estado'] = 'exito';
						$mensaje['mensaje'] = 'Los datos se han guardado exitosamente';
					}
					break;
			
				case 'Tuberculosis':
					$informacionPredio = $cbt->abrirInformacionPredioRecertificacionBTInspeccion($conexion, $idRecertificacionBT, $numInspeccion);
					$inventario = $cbt->abrirInventarioAnimalRecertificacionBTInspeccion($conexion, $idRecertificacionBT, $numInspeccion);
					$manejoAnimal = $cbt->abrirManejoAnimalRecertificacionBTInspeccion($conexion, $idRecertificacionBT, $numInspeccion);
					$adquisicionAnimales = $cbt->abrirAdquisicionAnimalesRecertificacionBTInspeccion($conexion, $idRecertificacionBT, $numInspeccion);
					$veterinario = $cbt->abrirVeterinarioRecertificacionBTInspeccion($conexion, $idRecertificacionBT, $numInspeccion);
					$vacunacion = $cbt->abrirVacunacionRecertificacionBTInspeccion($conexion, $idRecertificacionBT, $numInspeccion);
					$patologia = $cbt->abrirPatologiaBrucelosisRecertificacionBTInspeccion($conexion, $idRecertificacionBT, $numInspeccion);
					
					if($estado == 'tomaMuestras'){
						if( (pg_num_rows($informacionPredio) != 0) && (pg_num_rows($inventario) != 0) && 
							(pg_num_rows($manejoAnimal) != 0) && (pg_num_rows($adquisicionAnimales) != 0) && 
							(pg_num_rows($veterinario) != 0) && (pg_num_rows($vacunacion) != 0) && 
							(pg_num_rows($patologia) != 0)){
						
									$conexion->ejecutarConsulta("begin;");
						
									/*$numInspeccionC = pg_fetch_result($cbt->generarNumeroInspeccionCertificacionBT($conexion, 'Inspección ', $numeroSolicitud), 0, 'num_inspeccion');
									$tmpInspeccion= explode(" ", $numInspeccionC);
									$incrementoInspeccion = end($tmpInspeccion)+1;
									$numInspeccion = 'Inspección '.str_pad($incrementoInspeccion, 4, "0", STR_PAD_LEFT);*/
									
									$cbt->cierreRecertificacionBTTecnico($conexion, $idRecertificacionBT, $identificador,
																		$observaciones, $estado, $numInspeccion,
																		$idLaboratorio, $laboratorio, $fechaInspeccion);
										
									$conexion->ejecutarConsulta("commit;");
										
									$mensaje['estado'] = 'exito';
									$mensaje['mensaje'] = 'Los datos se han guardado exitosamente';
										
						}else{
							$mensaje['estado'] = 'error';
							$mensaje['mensaje'] = "Debe ingresar por lo menos un resultado de inspección para poder continuar.";
						}
					}else{
						$conexion->ejecutarConsulta("begin;");
						
							if($estado == 'aprobado'){
								//creación de certificado
								$pdf = new PDF_BT();
								$pdf->AliasNbPages();
								$pdf->AddPage();
								$pdf->Body('recertificacion', $idRecertificacionBT);
								$pdf->SetFont('Times','',12);
								$pdf->Output("certificados/tuberculosis/".$numeroSolicitud."-".$numeroRecertificacion."-".$identificador.".pdf");
									
								$rutaCertificado = "aplicaciones/certificacionBrucelosisTuberculosis/certificados/tuberculosis/".$numeroSolicitud."-".$numeroRecertificacion."-".$identificador.".pdf";

								//Actualizar registro
								$cbt->actualizarCertificadoAprobacionRecertificacion($conexion, $idRecertificacionBT, $identificador, $rutaCertificado);
							}
							
							$cbt->cierreRecertificacionBTTecnico($conexion, $idRecertificacionBT, $identificador,
									$observaciones, $estado, $numInspeccion,
									$idLaboratorio, $laboratorio, $fechaInspeccion);
							
							//Cambiar de estado al registro original
							$cbt->actualizarEstadoCertificacionBT($conexion, $idCertificacionBT, $estado);
						
						$conexion->ejecutarConsulta("commit;");
						
						$mensaje['estado'] = 'exito';
						$mensaje['mensaje'] = 'Los datos se han guardado exitosamente';
					}
					break;
			
				default:
					break;
			}
				
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Su sesión ha expirado, por favor ingrese nuevamente al sistema para continuar.";
		}
		
		$conexion->desconectar();
		echo json_encode($mensaje);
	
	} catch (Exception $ex){
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] = $ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError;
		$conexion->desconectar();
	}
	
} catch (Exception $ex) {
	$mensaje['mensaje'] = $ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
	$conexion->desconectar();
}
?>
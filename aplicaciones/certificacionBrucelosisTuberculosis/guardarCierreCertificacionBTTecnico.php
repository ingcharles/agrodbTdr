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

	$idCertificacionBT = htmlspecialchars ($_POST['idCertificacionBT'],ENT_NOQUOTES,'UTF-8');
	$numInspeccion = htmlspecialchars ($_POST['numInspeccion'],ENT_NOQUOTES,'UTF-8');
	$numeroSolicitud = htmlspecialchars ($_POST['numSolicitud'],ENT_NOQUOTES,'UTF-8');
	
	$estado = htmlspecialchars ($_POST['resultado'],ENT_NOQUOTES,'UTF-8');
	$fechaInspeccion = htmlspecialchars ($_POST['fechaInspeccion'],ENT_NOQUOTES,'UTF-8');
	$observaciones = htmlspecialchars ($_POST['observaciones'],ENT_NOQUOTES,'UTF-8');
	
	$idLaboratorio = htmlspecialchars ($_POST['laboratorioMuestras'],ENT_NOQUOTES,'UTF-8');
	$laboratorio = htmlspecialchars ($_POST['nombreLaboratorioMuestras'],ENT_NOQUOTES,'UTF-8');
	
	$certificacion = htmlspecialchars ($_POST['certificacion'],ENT_NOQUOTES,'UTF-8');
	
	try {
		
		if(($identificador != null) || ($identificador != '')){
		
			switch ($certificacion){
				case 'Brucelosis':
					$informacionPredio = $cbt->abrirInformacionPredioCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					$produccion = $cbt->abrirProduccionCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					$inventario = $cbt->abrirInventarioAnimalCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					$pediluvio = $cbt->abrirPediluvioCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					$manejoAnimal = $cbt->abrirManejoAnimalCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					$adquisicionAnimales = $cbt->abrirAdquisicionAnimalesCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					$procedenciaAgua = $cbt->abrirProcedenciaAguaCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					$veterinario = $cbt->abrirVeterinarioCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					$vacunacion = $cbt->abrirVacunacionCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					$reproduccion = $cbt->abrirReproduccionCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					$patologia = $cbt->abrirPatologiaBrucelosisCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					$abortos = $cbt->abrirAbortosBrucelosisCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					$pruebasLeche = $cbt->abrirPruebasBrucelosisLecheCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					$pruebasSangre = $cbt->abrirPruebasBrucelosisSangreCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					
					if($estado == 'tomaMuestras'){
						if( (pg_num_rows($informacionPredio) != 0) && (pg_num_rows($produccion) != 0) && (pg_num_rows($inventario) != 0) && (pg_num_rows($pediluvio) != 0) &&
							(pg_num_rows($manejoAnimal) != 0) && (pg_num_rows($adquisicionAnimales) != 0) && (pg_num_rows($procedenciaAgua) != 0) && (pg_num_rows($veterinario) != 0) &&
							(pg_num_rows($vacunacion) != 0) && (pg_num_rows($reproduccion) != 0) && (pg_num_rows($patologia) != 0) && (pg_num_rows($abortos) != 0) &&
							(pg_num_rows($pruebasLeche) != 0) && (pg_num_rows($pruebasSangre) != 0)){
							
								$conexion->ejecutarConsulta("begin;");
								
								/*$numInspeccionC = pg_fetch_result($cbt->generarNumeroInspeccionCertificacionBT($conexion, 'Inspección ', $numeroSolicitud), 0, 'num_inspeccion');
								$tmpInspeccion= explode(" ", $numInspeccionC);
								$incrementoInspeccion = end($tmpInspeccion)+1;
								$numInspeccion = 'Inspección '.str_pad($incrementoInspeccion, 4, "0", STR_PAD_LEFT);*/
									
								$cbt->cierreCertificacionBTTecnico($conexion, $idCertificacionBT, $identificador,
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
								$pdf->Body('certificacion', $idCertificacionBT);
								$pdf->SetFont('Times','',12);
								$pdf->Output("certificados/brucelosis/".$numeroSolicitud."-".$identificador.".pdf");//$pdf->Output("certificados/brucelosis/".$numeroSolicitud."-".$identificador.".pdf");
									
								$rutaCertificado = "aplicaciones/certificacionBrucelosisTuberculosis/certificados/brucelosis/".$numeroSolicitud."-".$identificador.".pdf";

								//Actualizar registro
								$cbt->actualizarCertificadoAprobacion($conexion, $idCertificacionBT, $identificador, $rutaCertificado);
								
							}
							
							$cbt->cierreCertificacionBTTecnico($conexion, $idCertificacionBT, $identificador,
									$observaciones, $estado, $numInspeccion,
									$idLaboratorio, $laboratorio, $fechaInspeccion);
							
						$conexion->ejecutarConsulta("commit;");
						
						$mensaje['estado'] = 'exito';
						$mensaje['mensaje'] = 'Los datos se han guardado exitosamente';
					}
					break;
			
				case 'Tuberculosis':
					$informacionPredio = $cbt->abrirInformacionPredioCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					$produccion = $cbt->abrirProduccionCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					$inventario = $cbt->abrirInventarioAnimalCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					$pediluvio = $cbt->abrirPediluvioCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					$manejoAnimal = $cbt->abrirManejoAnimalCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					$adquisicionAnimales = $cbt->abrirAdquisicionAnimalesCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					$procedenciaAgua = $cbt->abrirProcedenciaAguaCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					$veterinario = $cbt->abrirVeterinarioCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					$vacunacion = $cbt->abrirVacunacionCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					$reproduccion = $cbt->abrirReproduccionCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					$patologiaTuberculosis = $cbt->abrirPatologiaTuberculosisCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					//$pruebasLecheTuberculosis = $cbt->abrirPruebaTuberculosisLecheCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					$pruebasTuberculina = $cbt->abrirPruebaTuberculinaCertificacionBTInspeccion($conexion, $idCertificacionBT, $numInspeccion);
					
					if($estado == 'tomaMuestras'){
						if( (pg_num_rows($informacionPredio) != 0) && (pg_num_rows($produccion) != 0) && (pg_num_rows($inventario) != 0) && (pg_num_rows($pediluvio) != 0) &&
								(pg_num_rows($manejoAnimal) != 0) && (pg_num_rows($adquisicionAnimales) != 0) && (pg_num_rows($procedenciaAgua) != 0) && (pg_num_rows($veterinario) != 0) &&
								(pg_num_rows($vacunacion) != 0) && (pg_num_rows($reproduccion) != 0) && (pg_num_rows($patologiaTuberculosis) != 0) && /*(pg_num_rows($pruebasLecheTuberculosis) != 0) &&*/
								(pg_num_rows($pruebasTuberculina) != 0)){
						
									$conexion->ejecutarConsulta("begin;");
						
									/*$numInspeccionC = pg_fetch_result($cbt->generarNumeroInspeccionCertificacionBT($conexion, 'Inspección ', $numeroSolicitud), 0, 'num_inspeccion');
									$tmpInspeccion= explode(" ", $numInspeccionC);
									$incrementoInspeccion = end($tmpInspeccion)+1;
									$numInspeccion = 'Inspección '.str_pad($incrementoInspeccion, 4, "0", STR_PAD_LEFT);*/
									
									$cbt->cierreCertificacionBTTecnico($conexion, $idCertificacionBT, $identificador,
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
								$pdf->Body('certificacion', $idCertificacionBT);
								$pdf->SetFont('Times','',12);
								$pdf->Output("certificados/tuberculosis/".$numeroSolicitud."-".$identificador.".pdf");
									
								$rutaCertificado = "aplicaciones/certificacionBrucelosisTuberculosis/certificados/tuberculosis/".$numeroSolicitud."-".$identificador.".pdf";

								//Actualizar registro
								$cbt->actualizarCertificadoAprobacion($conexion, $idCertificacionBT, $identificador, $rutaCertificado);
							}
							
							$cbt->cierreCertificacionBTTecnico($conexion, $idCertificacionBT, $identificador,
									$observaciones, $estado, $numInspeccion,
									$idLaboratorio, $laboratorio, $fechaInspeccion);
						
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
<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEventoSanitario.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$validacion = 0;

try{
	$conexion = new Conexion();
	$cpco = new ControladorEventoSanitario();

	$identificador = $_SESSION['usuario'];

	$idEventoSanitario = htmlspecialchars ($_POST['idEventoSanitario'],ENT_NOQUOTES,'UTF-8');
	$numeroVisita = htmlspecialchars ($_POST['numeroVisita'],ENT_NOQUOTES,'UTF-8');
	$numeroSolicitud = htmlspecialchars ($_POST['numeroSolicitud'],ENT_NOQUOTES,'UTF-8');
	
	$estado = htmlspecialchars ($_POST['resultado'],ENT_NOQUOTES,'UTF-8');
	$fechaInspeccion = htmlspecialchars ($_POST['fechaInspeccion'],ENT_NOQUOTES,'UTF-8');
	$observaciones = htmlspecialchars ($_POST['observaciones'],ENT_NOQUOTES,'UTF-8');
	
	$idLaboratorio = isset($_POST['laboratorioMuestras'])?$_POST['laboratorioMuestras']:null;
	$laboratorio = isset($_POST['nombreLaboratorioMuestras'])?$_POST['nombreLaboratorioMuestras']:null;
	
	$conclusionFinal = isset($_POST['conclusionFinal'])?$_POST['conclusionFinal']:null;
        
        $movimientoAnimal = (integer) $_POST['movimientoAnimalFinal'];
	
	try {
		
		if(($identificador != null) || ($identificador != '')){	

			$muestraPrimeraVisita = $cpco->listarMuestrasPorVisita($conexion, $idEventoSanitario, $numeroVisita);
			$detalleMuestra  = $cpco->listarMuestrasDetalleInspeccion($conexion, $idEventoSanitario, $numeroVisita);
			$origenAnimal  = $cpco->listarOrigenesInspeccion($conexion, $idEventoSanitario, $numeroVisita);
			$poblaciones  = $cpco->listarPoblacionesInspeccion($conexion, $idEventoSanitario, $numeroVisita);
			$poblacionesAves  = $cpco->listarPoblacionesAvesInspeccion($conexion, $idEventoSanitario, $numeroVisita);
			$ingresos  = $cpco->listarIngresosInspeccion($conexion, $idEventoSanitario, $numeroVisita);
			$egresos = $cpco->listarEgresosInspeccion($conexion, $idEventoSanitario, $numeroVisita);
			$movimientosAves  = $cpco->listarMovimientosAvesInspeccion($conexion, $idEventoSanitario, $numeroVisita);
			$medidaSanitaria  = $cpco->abrirMedidaSanitaria($conexion, $idEventoSanitario, $numeroVisita);
			$cronologia = $cpco->listarCronologiasFinales($conexion, $idEventoSanitario);
			$diagnostico = $cpco->listarDiagnosticos($conexion, $idEventoSanitario);
			$poblacionCierre = $cpco->listarPoblacionesFinales($conexion, $idEventoSanitario);
			$poblacionCierreAves = $cpco->listarPoblacionesFinalesAves($conexion, $idEventoSanitario);
			$vacunacion = $cpco->listarVacunacionFinales($conexion, $idEventoSanitario);
			
			switch ($numeroVisita){
				case 'Visita 0001':
					if( (pg_num_rows($muestraPrimeraVisita) != 0) && (pg_num_rows($detalleMuestra) != 0) &&
							(pg_num_rows($poblaciones) != 0) && 
							//(pg_num_rows($ingresos) != 0) && (pg_num_rows($egresos) != 0) && 
							(pg_num_rows($medidaSanitaria))){
						$validacion = 1;			
					}else{
						$validacion = 0;		
					}
					
					break;
					
				case 'Visita Cierre':
					if( (pg_num_rows($medidaSanitaria) != 0) && /*(pg_num_rows($poblaciones) != 0) &&*/ 
							(pg_num_rows($cronologia) != 0) && (pg_num_rows($diagnostico) != 0) && 
							(pg_num_rows($poblacionCierre) != 0) && 
							(pg_num_rows($vacunacion)) ){
						$validacion = 1;
						$estado='cerrado';
					}else{
						$validacion = 0;
					}
					
					break;
					
				default:
					if( (pg_num_rows($muestraPrimeraVisita) != 0) && (pg_num_rows($detalleMuestra) != 0) &&
							(pg_num_rows($poblaciones) != 0) && 
							(pg_num_rows($medidaSanitaria))){
						$validacion = 1;
					}else{
						$validacion = 0;
					}
					
					break;
			}
			
			if($validacion == 1){
				if($estado == 'tomaMuestras'){
					$regMuestraPrimeraVisita = pg_fetch_assoc($muestraPrimeraVisita);
					
					if($regMuestraPrimeraVisita['colecta_material'] == 'Si'){
						$conexion->ejecutarConsulta("begin;");
						
						$cpco->cierreVisitaTecnico($conexion, $idEventoSanitario, $identificador,
								$observaciones, $estado, $numeroVisita, $idLaboratorio, $laboratorio, $movimientoAnimal);
						
						$conexion->ejecutarConsulta("commit;");
							
						$mensaje['estado'] = 'exito';
						$mensaje['mensaje'] = 'Los datos se han guardado exitosamente';
					}else{
						$mensaje['estado'] = 'error';
						$mensaje['mensaje'] = 'No puede enviar para análisis de laboratorio porque no se tomaron muestras.';
					}
				}else if($estado == 'visita'){
					$numInspeccionC = pg_fetch_result($cpco->generarNumeroVisitaEventoSanitario($conexion, 'Visita ', $numeroSolicitud), 0, 'num_inspeccion');
					$tmpInspeccion= explode(" ", $numInspeccionC);
					$incrementoInspeccion = end($tmpInspeccion)+1;
					$numInspeccion = 'Visita '.str_pad($incrementoInspeccion, 4, "0", STR_PAD_LEFT);
						
					if($incrementoInspeccion <= 15){
						$conexion->ejecutarConsulta("begin;");
												
						$cpco->cierreVisitaTecnico($conexion, $idEventoSanitario, $identificador,
								$observaciones, $estado, $numInspeccion,
								$idLaboratorio, $laboratorio, $movimientoAnimal);
						
						$conexion->ejecutarConsulta("commit;");
						
						$mensaje['estado'] = 'exito';
						$mensaje['mensaje'] = 'Los datos se han guardado exitosamente';
					}else{
						$mensaje['estado'] = 'error';
						$mensaje['mensaje'] = 'Debe seleccionar la visita de cierre.';
					}
					
				}else if($estado == 'cerrado'){
					$conexion->ejecutarConsulta("begin;");
						
						$numInspeccion = 'Visita Cierre';
						
						$cpco->modificarEventoSanitarioConclusionFinal($conexion, $idEventoSanitario, 
																		$conclusionFinal, $identificador);
						
					$conexion->ejecutarConsulta("commit;");
						
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'Los datos se han guardado exitosamente';
					
				}else if($estado == 'visitaCierre'){
					$conexion->ejecutarConsulta("begin;");
						
						$numInspeccion = 'Visita Cierre';
						
						$cpco->cierreVisitaTecnico($conexion, $idEventoSanitario, $identificador,
								$observaciones, $estado, $numInspeccion,
								$idLaboratorio, $laboratorio, $movimientoAnimal);
						
					$conexion->ejecutarConsulta("commit;");
						
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'Los datos se han guardado exitosamente';						
				}
			
			}else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = "Debe completar todos los elementos del formulario para poder continuar!.";
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
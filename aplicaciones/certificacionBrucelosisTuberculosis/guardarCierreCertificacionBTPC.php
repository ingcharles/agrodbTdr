<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorBrucelosisTuberculosis.php';

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
	$observaciones = htmlspecialchars ($_POST['observaciones'],ENT_NOQUOTES,'UTF-8');
	
	$idLaboratorio = htmlspecialchars ($_POST['laboratorioMuestras'],ENT_NOQUOTES,'UTF-8');
	$laboratorio = htmlspecialchars ($_POST['nombreLaboratorioMuestras'],ENT_NOQUOTES,'UTF-8');
	
	$certificacion = htmlspecialchars ($_POST['certificacion'],ENT_NOQUOTES,'UTF-8');
        
        $fechaInspeccion = null;
	
	try {
		
		if(($identificador != null) || ($identificador != '')){
	
			$conexion->ejecutarConsulta("begin;");
					
				$numInspeccionC = pg_fetch_result($cbt->generarNumeroInspeccionCertificacionBT($conexion, 'Inspección ', $numeroSolicitud), 0, 'num_inspeccion');
				$tmpInspeccion= explode(" ", $numInspeccionC);
				$incrementoInspeccion = end($tmpInspeccion)+1;
				$numInspeccion = 'Inspección '.str_pad($incrementoInspeccion, 4, "0", STR_PAD_LEFT);
					
				$cbt->cierreCertificacionBTTecnico($conexion, $idCertificacionBT, $identificador,
													$observaciones, $estado, $numInspeccion, 
													$idLaboratorio, $laboratorio, $fechaInspeccion);
			
			$conexion->ejecutarConsulta("commit;");
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos se han guardado exitosamente';				

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
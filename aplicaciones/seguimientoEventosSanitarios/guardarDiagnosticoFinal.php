<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEventoSanitario.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$ruta = 'seguimientoEventosSanitarios';

try{
	$conexion = new Conexion();
	$cpco = new ControladorEventoSanitario();
	
	$identificador = $_SESSION['usuario'];
	
	$idEventoSanitario = htmlspecialchars ($_POST['idEventoSanitario'],ENT_NOQUOTES,'UTF-8');
	
	$idDiagnosticosFinal = htmlspecialchars ($_POST['diagnosticoFinal'],ENT_NOQUOTES,'UTF-8');
	$nombreDiagnosticoFinal = htmlspecialchars ($_POST['nombreDiagnosticoFinal'],ENT_NOQUOTES,'UTF-8');
	
	$idEnfermedadFinal = htmlspecialchars ($_POST['enfermedadFinal'],ENT_NOQUOTES,'UTF-8');
	$nombreEnfermedadFinal = htmlspecialchars ($_POST['nombreEnfermedadFinal'],ENT_NOQUOTES,'UTF-8');
	
	$descricionDiagnosticoFinal = htmlspecialchars ($_POST['descripcionDiagnosticoFinal'],ENT_NOQUOTES,'UTF-8');
	
	
	try {
		
		$especieInspeccion = $cpco->buscarDiagnosticos($conexion, $idEventoSanitario, $nombreDiagnosticoFinal, $nombreEnfermedadFinal);
		
		if(pg_num_rows($especieInspeccion) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				$idDiagnosticoFinal = pg_fetch_result($cpco->nuevaDiagnosticos(	$conexion, 
										$idEventoSanitario, $idDiagnosticosFinal,  $nombreDiagnosticoFinal, $idEnfermedadFinal, $nombreEnfermedadFinal, $descricionDiagnosticoFinal, $identificador), 
																0, 'id_diagnosticos_final');
				
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaDiagnosticoFinal(	$idDiagnosticoFinal,  $idEventoSanitario, $nombreDiagnosticoFinal,  $nombreEnfermedadFinal, $descricionDiagnosticoFinal,
													$ruta);
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "registro ingresado ya existe, por favor verificar en el listado.";
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
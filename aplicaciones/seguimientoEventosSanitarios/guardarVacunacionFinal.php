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
	$vacunacionFinal = htmlspecialchars ($_POST['vacunacionFinal'],ENT_NOQUOTES,'UTF-8');
	
	$idTipoVacunacionFinal = htmlspecialchars ($_POST['tipoVacunacionFinal'],ENT_NOQUOTES,'UTF-8');
	$nombreTipoVacunacionFinal = htmlspecialchars ($_POST['nombreTipoVacunacionFinal'],ENT_NOQUOTES,'UTF-8');
	
	$dosisAplicadaVacunacionFinal = htmlspecialchars ($_POST['dosisFinal'],ENT_NOQUOTES,'UTF-8');
	$prediosVacunacionFinal = htmlspecialchars ($_POST['prediosVacunadosFinal'],ENT_NOQUOTES,'UTF-8');	
	
	$idLaboratoriosVacunacionFinal = htmlspecialchars ($_POST['laboratorioFinal'],ENT_NOQUOTES,'UTF-8');
	$nombreLaboratoriosVacunacionFinal = htmlspecialchars ($_POST['nombreLaboratorioFinal'],ENT_NOQUOTES,'UTF-8');
	
	$loteVacunacionFinal = htmlspecialchars ($_POST['loteFinal'],ENT_NOQUOTES,'UTF-8');
	
	try {
		if($vacunacionFinal == 'No'){
			$idTipoVacunacionFinal = 0;
			$nombreTipoVacunacionFinal = 'No Aplica';
			$dosisAplicadaVacunacionFinal = 0;
			$prediosVacunacionFinal = 0;
			$idLaboratoriosVacunacionFinal = 0;
			$nombreLaboratoriosVacunacionFinal = 'No Aplica';
			$loteVacunacionFinal = 0;
		}
		
		$especieInspeccion = $cpco->buscarVacunacionFinales($conexion, $idEventoSanitario, $nombreTipoVacunacionFinal);
		
		if(pg_num_rows($especieInspeccion) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				$idVacunacionFinal = pg_fetch_result($cpco->nuevaVacunacionFinal(	$conexion, 
												$idEventoSanitario, $idTipoVacunacionFinal,  
												$nombreTipoVacunacionFinal, $dosisAplicadaVacunacionFinal, 
												$prediosVacunacionFinal,    
												$nombreLaboratoriosVacunacionFinal, $loteVacunacionFinal, $identificador), 
																0, 'id_vacunacion_final');
				
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaVacunacionFinal(	$idVacunacionFinal,  $idEventoSanitario, $nombreTipoVacunacionFinal,  $dosisAplicadaVacunacionFinal, 
													$prediosVacunacionFinal,  $nombreLaboratoriosVacunacionFinal,  $loteVacunacionFinal,
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
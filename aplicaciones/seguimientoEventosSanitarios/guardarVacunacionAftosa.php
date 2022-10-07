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
	
	$idVacunacion = htmlspecialchars ($_POST['vacunacion'],ENT_NOQUOTES,'UTF-8');
	$nombreVacunacion = htmlspecialchars ($_POST['nombreVacunacion'],ENT_NOQUOTES,'UTF-8');
	
	$IdTipoVacunacionAftosa = htmlspecialchars ($_POST['vacunacionAftosa'],ENT_NOQUOTES,'UTF-8');
	$nombreTipoVacunacionAftosa = htmlspecialchars ($_POST['nombreVacunacionAftosa'],ENT_NOQUOTES,'UTF-8');
	
	$fechaVacunacionAftosa = htmlspecialchars ($_POST['fechaVacunacionAftosa'],ENT_NOQUOTES,'UTF-8');
	$loteVacunacionAftosa = htmlspecialchars ($_POST['loteVacunacionAftosa'],ENT_NOQUOTES,'UTF-8');	
	
	$numeroCertificadoVacunacionAftosa = htmlspecialchars ($_POST['numeroCertificadoVacunacionAftosa'],ENT_NOQUOTES,'UTF-8');
	$nombreLaboratorioVacunacionAftosa = htmlspecialchars ($_POST['nombreLaboratorioAftosa'],ENT_NOQUOTES,'UTF-8');
	
	$observacionVacunacion = htmlspecialchars ($_POST['observacionVacunacion'],ENT_NOQUOTES,'UTF-8');
	
	try {
		
		if($nombreVacunacion == 'No Vacunada'){
			$IdTipoVacunacionAftosa = 0;
			$nombreTipoVacunacionAftosa = 'No Aplica';
			
			$fechaVacunacionAftosa = 'now()';
			$loteVacunacionAftosa = 'No Aplica';
			
			$numeroCertificadoVacunacionAftosa = 'No Aplica';
			$nombreLaboratorioVacunacionAftosa = 'No Aplica';
			
			$observacionVacunacion = 'No Aplica';
		}
		
		$especieInspeccion = $cpco->buscarVacunacionAftosa($conexion, $idEventoSanitario,  $nombreVacunacion, $nombreTipoVacunacionAftosa	);
		
		if(pg_num_rows($especieInspeccion) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				$idVacunacionAftosa = pg_fetch_result($cpco->nuevaVacunacionAftosa(	$conexion, $idEventoSanitario,  
												$idVacunacion, $nombreVacunacion, $fechaVacunacionAftosa, $loteVacunacionAftosa, 
												$numeroCertificadoVacunacionAftosa, $nombreLaboratorioVacunacionAftosa, 
												$identificador, $IdTipoVacunacionAftosa,  
												$nombreTipoVacunacionAftosa, $observacionVacunacion), 
																0, 'id_vacunacion_aftosa');
				
			$conexion->ejecutarConsulta("commit;");
		
			if($nombreVacunacion == 'No Vacunada'){
				$fecha = getdate();
				$fechaVacunacionAftosa = $fecha['mday'].'/'.$fecha['mon'].'/'.$fecha['year'];
			}
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaVacunacionAftosa(	$idVacunacionAftosa, $idEventoSanitario,  $nombreTipoVacunacionAftosa, 
													$fechaVacunacionAftosa, $loteVacunacionAftosa, $numeroCertificadoVacunacionAftosa,
													$nombreLaboratorioVacunacionAftosa, $ruta, $nombreVacunacion, $observacionVacunacion  );
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
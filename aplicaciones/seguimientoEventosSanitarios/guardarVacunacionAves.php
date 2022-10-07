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
	
	$vacunacionAves = htmlspecialchars ($_POST['vacunacionAves'],ENT_NOQUOTES,'UTF-8');
	
	$numeroGalponesVacunacionAves = htmlspecialchars ($_POST['numeroGalponesVacunacionAves'],ENT_NOQUOTES,'UTF-8');
	$numeroLoteVacunacionAves = htmlspecialchars ($_POST['loteVacunacionAves'],ENT_NOQUOTES,'UTF-8');
	
	$idEnfermedadVacunacionAves = htmlspecialchars ($_POST['enfermedadVacunaAve'],ENT_NOQUOTES,'UTF-8');
	$enfermedadVacunacionAves = htmlspecialchars ($_POST['enfermedadVacunacionAves'],ENT_NOQUOTES,'UTF-8');	
	
	$edadVacunacionAves = htmlspecialchars ($_POST['edadVacunacionAves'],ENT_NOQUOTES,'UTF-8');
	$diasVacunacionAves = htmlspecialchars ($_POST['diasVacunacionAves'],ENT_NOQUOTES,'UTF-8');
	
	$mesesVacunacionAves = htmlspecialchars ($_POST['mesesVacunacionAves'],ENT_NOQUOTES,'UTF-8');
	$idTipoVacunacionAves = htmlspecialchars ($_POST['tipoVacunacionAve'],ENT_NOQUOTES,'UTF-8');
	
	$tipoVacunacionAves = htmlspecialchars ($_POST['nombreTipoVacunacionAves'],ENT_NOQUOTES,'UTF-8');
	$cepaVacunacionAves = htmlspecialchars ($_POST['cepaVacunacionAves'],ENT_NOQUOTES,'UTF-8');
	
	$viaVacunacionAves = htmlspecialchars ($_POST['viaAplicacionVacunacionAves'],ENT_NOQUOTES,'UTF-8');
	$fechaVacunacionAves = htmlspecialchars ($_POST['fechaVacunacionAves'],ENT_NOQUOTES,'UTF-8');
	
	$observacionVacunacionAves = htmlspecialchars ($_POST['observacionVacunacionAves'],ENT_NOQUOTES,'UTF-8');
	
	
	try {
		
		$especieInspeccion = $cpco->buscarVacunacionesAves($conexion, $idEventoSanitario, $enfermedadVacunacionAves,$tipoVacunacionAves);
		
		if(pg_num_rows($especieInspeccion) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				if($vacunacionAves == 'No'){
					$numeroGalponesVacunacionAves = 0;
					$numeroLoteVacunacionAves = 0;
					$idEnfermedadVacunacionAves = 0;
					$enfermedadVacunacionAves = 'No Aplica';
					$edadVacunacionAves = 0;
					$diasVacunacionAves = 0;
					$mesesVacunacionAves = 0;
					$idTipoVacunacionAves = 0;
					$tipoVacunacionAves = 'No Aplica';
					$cepaVacunacionAves = 'No Aplica';
					$fechaVacunacionAves = 'now()';
					$observacionVacunacionAves = 'No Aplica';
				}
			
				$idVacunacionAves = pg_fetch_result($cpco->nuevaVacunacionAves(	$conexion, $idEventoSanitario, $numeroGalponesVacunacionAves, 
												$numeroLoteVacunacionAves, $idEnfermedadVacunacionAves, $enfermedadVacunacionAves, $edadVacunacionAves, 
												$diasVacunacionAves, $mesesVacunacionAves, $idTipoVacunacionAves,  $tipoVacunacionAves,
												$cepaVacunacionAves, $viaVacunacionAves, $fechaVacunacionAves, 
												$observacionVacunacionAves, $identificador), 
																0, 'id_vacunacion_aves');
				
				if($vacunacionAves == 'No'){
					$fecha = getdate();
					$fechaVacunacionAves = $fecha['mday'].'/'.$fecha['mon'].'/'.$fecha['year'];
				}
				
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaVacunacionAves(	$idVacunacionAves, $idEventoSanitario, $numeroGalponesVacunacionAves, 
													$numeroLoteVacunacionAves, $enfermedadVacunacionAves, $edadVacunacionAves, 
													$diasVacunacionAves, $mesesVacunacionAves, $tipoVacunacionAves, 
													$cepaVacunacionAves, $viaVacunacionAves, $fechaVacunacionAves, 
													$observacionVacunacionAves, $ruta);
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
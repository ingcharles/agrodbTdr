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
	
	$idTipoVacunacion = htmlspecialchars ($_POST['vacunacion'],ENT_NOQUOTES,'UTF-8');
	$tipoVacunacion = htmlspecialchars ($_POST['nombreVacunacion'],ENT_NOQUOTES,'UTF-8');
	
	$numeroAnimalesVacunados = htmlspecialchars ($_POST['numeroAnimalesVacunados'],ENT_NOQUOTES,'UTF-8');
	$fechaVacunacion = htmlspecialchars ($_POST['fechaVacunacion'],ENT_NOQUOTES,'UTF-8');	
	
	$observacionVacunacion = htmlspecialchars ($_POST['observacionVacunacion'],ENT_NOQUOTES,'UTF-8');
	
	
	try {
		
		$especieInspeccion = $cpco->buscarVacunaciones($conexion, $idEventoSanitario, $tipoVacunacion);
		
		if(pg_num_rows($especieInspeccion) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				$idVacunacion = pg_fetch_result($cpco->nuevaVacunacion(	$conexion,  $idEventoSanitario, $idTipoVacunacion, $tipoVacunacion,$numeroAnimalesVacunados, 
													$fechaVacunacion, $observacionVacunacion, $identificador), 
																0, 'id_vacunacion');
				
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaVacunacion(	$idVacunacion, $idEventoSanitario, $tipoVacunacion, $numeroAnimalesVacunados, 
												$fechaVacunacion, $observacionVacunacion, $ruta);
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
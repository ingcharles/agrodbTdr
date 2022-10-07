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
	
	$idEspeciePoblacionFinalAves = htmlspecialchars ($_POST['especieFinalAves'],ENT_NOQUOTES,'UTF-8');
	$nombreEspeciePoblacionFinalAves = htmlspecialchars ($_POST['nombreEspecieFinalAves'],ENT_NOQUOTES,'UTF-8');
	
	$existentesPoblacionFinalAves = htmlspecialchars ($_POST['existentesPoblacionFinalAves'],ENT_NOQUOTES,'UTF-8');
	$enfermosPoblacionFinalAves = htmlspecialchars ($_POST['enfermosPoblacionFinalAves'],ENT_NOQUOTES,'UTF-8');	
	
	$muertosPoblacionFinalAves = htmlspecialchars ($_POST['muertosPoblacionFinalAves'],ENT_NOQUOTES,'UTF-8');
	$destruidasPoblacionFinalAves = htmlspecialchars ($_POST['destruidasPoblacionFinalAves'],ENT_NOQUOTES,'UTF-8');
	
	$sacrificadosPoblacionFinalAves = htmlspecialchars ($_POST['sacrificadosPoblacionFinalAves'],ENT_NOQUOTES,'UTF-8');
		
	
	try {
		
		$especieInspeccion = $cpco->buscarPoblacionesFinalesAves($conexion, $idEventoSanitario, $nombreEspeciePoblacionFinalAves);
		
		if(pg_num_rows($especieInspeccion) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				$idPoblacionFinalAves = pg_fetch_result($cpco->nuevaPoblacionAvesFinal(	$conexion, 
													$idEventoSanitario, $idEspeciePoblacionFinalAves,  $nombreEspeciePoblacionFinalAves,
													$existentesPoblacionFinalAves, 
													$enfermosPoblacionFinalAves,  $muertosPoblacionFinalAves,  $destruidasPoblacionFinalAves, 
													$sacrificadosPoblacionFinalAves, $identificador), 
																0, 'id_poblacion_final_aves');
				
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaPoblacionFinalAves(	$idPoblacionFinalAves,  $idEventoSanitario, $nombreEspeciePoblacionFinalAves,  $existentesPoblacionFinalAves, 
														$enfermosPoblacionFinalAves,  $muertosPoblacionFinalAves,  $destruidasPoblacionFinalAves, 
														$sacrificadosPoblacionFinalAves,
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
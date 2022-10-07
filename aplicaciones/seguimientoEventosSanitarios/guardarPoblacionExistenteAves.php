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
	
	$numeroVisita = htmlspecialchars ($_POST['numeroVisita'],ENT_NOQUOTES,'UTF-8');
	
	$idEspeciePoblacionAves = htmlspecialchars ($_POST['especiePoblacionAves'],ENT_NOQUOTES,'UTF-8');
	$nombreEspeciePoblacionAves = htmlspecialchars ($_POST['nombreEspeciePoblacionAves'],ENT_NOQUOTES,'UTF-8');	
	
	$numeroLotePoblacionAves = htmlspecialchars ($_POST['lotePoblacionAves'],ENT_NOQUOTES,'UTF-8');
	$numeroGalponPoblacionAves = htmlspecialchars ($_POST['galponPoblacionAves'],ENT_NOQUOTES,'UTF-8');
	
	$edadPoblacionAves = htmlspecialchars ($_POST['edadPoblacionAves'],ENT_NOQUOTES,'UTF-8');
	$existentesPoblacionAves = htmlspecialchars ($_POST['existentesPoblacionAves'],ENT_NOQUOTES,'UTF-8');
	
	$enfermosPoblacionAves = htmlspecialchars ($_POST['enfermasPoblacionAves'],ENT_NOQUOTES,'UTF-8');
	$muertasPoblacionAves = htmlspecialchars ($_POST['muertasPoblacionAves'],ENT_NOQUOTES,'UTF-8');
	
	$destruidasPoblacionAves = htmlspecialchars ($_POST['destruidasPoblacionAves'],ENT_NOQUOTES,'UTF-8');
	$sacrificadasPoblacionAves = htmlspecialchars ($_POST['sacrificadasPoblacionAves'],ENT_NOQUOTES,'UTF-8');
	

	try {
		
		$especieInspeccion = $cpco->buscarPoblacionesAves($conexion, $idEventoSanitario, $nombreEspeciePoblacionAves, $numeroVisita);
		
		if(pg_num_rows($especieInspeccion) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				$idPoblacionAves = pg_fetch_result($cpco->nuevaPoblacionAves(	$conexion, $idEventoSanitario, $numeroVisita,
													$idEspeciePoblacionAves,  $nombreEspeciePoblacionAves, $numeroLotePoblacionAves, 
													$numeroGalponPoblacionAves, $edadPoblacionAves, $existentesPoblacionAves,														
													$enfermosPoblacionAves, $muertasPoblacionAves, $destruidasPoblacionAves, $sacrificadasPoblacionAves, $identificador), 
																0, 'id_poblacion_aves');
				
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			
			$mensaje['mensaje'] = $cpco->imprimirLineaPoblacionAves(	$idPoblacionAves,  $idEventoSanitario, $nombreEspeciePoblacionAves, $numeroVisita, $numeroLotePoblacionAves, 
												$numeroGalponPoblacionAves, $edadPoblacionAves, $existentesPoblacionAves,														
												$enfermosPoblacionAves, $muertasPoblacionAves, $destruidasPoblacionAves, $sacrificadasPoblacionAves,	
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
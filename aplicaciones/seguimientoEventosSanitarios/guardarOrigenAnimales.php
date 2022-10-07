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
	$numVisita = htmlspecialchars ($_POST['numeroVisita'],ENT_NOQUOTES,'UTF-8');
	
	$idOrigen = htmlspecialchars ($_POST['origenAnimal'],ENT_NOQUOTES,'UTF-8');
	$nombreOrigen = htmlspecialchars ($_POST['nombreOrigenAnimal'],ENT_NOQUOTES,'UTF-8');
	
	$idPaisOrigen = htmlspecialchars ($_POST['paisOrigen'],ENT_NOQUOTES,'UTF-8');
	$nombrePaisOrigen = htmlspecialchars ($_POST['nombrePaisOrigen'],ENT_NOQUOTES,'UTF-8');	
	
	$idProvinciaOrigen = htmlspecialchars ($_POST['provinciaOrigen'],ENT_NOQUOTES,'UTF-8');
	$nombreProvinciaOrigen = htmlspecialchars ($_POST['nombreProvinciaOrigen'],ENT_NOQUOTES,'UTF-8');
	
	$idCantonOrigen = htmlspecialchars ($_POST['cantonOrigen'],ENT_NOQUOTES,'UTF-8');
	$nombreCantonOrigen = htmlspecialchars ($_POST['nombreCantonOrigen'],ENT_NOQUOTES,'UTF-8');
	
	$fechaOrigen = htmlspecialchars ($_POST['fechaOrigen'],ENT_NOQUOTES,'UTF-8');
	
	try {
		
		$especieInspeccion = $cpco->listarOrigenesInspeccion($conexion, $idEventoSanitario, $nombreOrigen, $numVisita);
		
		if(pg_num_rows($especieInspeccion) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				$idOrigenAnimales = pg_fetch_result($cpco->nuevaOrigenes(	$conexion,  $idEventoSanitario, $idOrigen, $nombreOrigen, $idPaisOrigen, 
										$nombrePaisOrigen, $idProvinciaOrigen, $nombreProvinciaOrigen, $fechaOrigen, $identificador, $numVisita, 
										$idCantonOrigen, $nombreCantonOrigen), 0, 'id_origen_animales');
				
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaOrigen(	$idOrigenAnimales, $idEventoSanitario, $nombreOrigen, $nombrePaisOrigen, $nombreProvinciaOrigen,
											$nombreCantonOrigen, $fechaOrigen, $ruta, $numVisita);
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
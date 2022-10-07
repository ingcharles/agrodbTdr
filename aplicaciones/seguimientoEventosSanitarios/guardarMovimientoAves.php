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
	
	$origenMovimientoAves = htmlspecialchars ($_POST['origenMovimientoAves'],ENT_NOQUOTES,'UTF-8');
	$paisProvincia = htmlspecialchars ($_POST['paisMovimientoAves'],ENT_NOQUOTES,'UTF-8');
	$fechaLlegada = htmlspecialchars ($_POST['fechaLlegadaMovimientoAves'],ENT_NOQUOTES,'UTF-8');
	
	$huboMovimientoAves = htmlspecialchars ($_POST['movimientoAves'],ENT_NOQUOTES,'UTF-8');
	$tipoAves = htmlspecialchars ($_POST['tipoMovimientoAves'],ENT_NOQUOTES,'UTF-8');	
	
	$idProvincia = htmlspecialchars ($_POST['provinciaMovimimentoAves'],ENT_NOQUOTES,'UTF-8');
	$provincia = htmlspecialchars ($_POST['nombreProvinciaMovimientoAves'],ENT_NOQUOTES,'UTF-8');
	
	$idCanton = htmlspecialchars ($_POST['cantonMovimientoAves'],ENT_NOQUOTES,'UTF-8');
	$canton = htmlspecialchars ($_POST['nombreCantonMovimientoAves'],ENT_NOQUOTES,'UTF-8');
	
	$idParroquia = htmlspecialchars ($_POST['parroquiaMovimientoAves'],ENT_NOQUOTES,'UTF-8');
	$parroquia = htmlspecialchars ($_POST['nombreParroquiaMovimientoAves'],ENT_NOQUOTES,'UTF-8');
	
	$idEspecieAves = htmlspecialchars ($_POST['especieMovimientoAves'],ENT_NOQUOTES,'UTF-8');
	$especieAves = htmlspecialchars ($_POST['nombreEspecieMovimientoAves'],ENT_NOQUOTES,'UTF-8');
	
	$numeroAvesMovilizadas = htmlspecialchars ($_POST['numeroMovimientoAves'],ENT_NOQUOTES,'UTF-8');
	$propietario = htmlspecialchars ($_POST['propietarioMovimientoAves'],ENT_NOQUOTES,'UTF-8');
	
	$proveedor = htmlspecialchars ($_POST['proveedorMovimientoAves'],ENT_NOQUOTES,'UTF-8');
	$finalidad = htmlspecialchars ($_POST['finalidadMovimientoAves'],ENT_NOQUOTES,'UTF-8');
	
	$fecha = htmlspecialchars ($_POST['fechaMovimientoAves'],ENT_NOQUOTES,'UTF-8');

	
	try {
		
		$especieInspeccion = $cpco->buscarMovimientosAves($conexion, $idEventoSanitario, $numeroVisita, $origenMovimientoAves, $tipoAves, $especieAves);
		
		if(pg_num_rows($especieInspeccion) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				$idMovimientoAnimalesAves = pg_fetch_result($cpco->nuevaMovimientosAves(	$conexion, 
											$idEventoSanitario, $numeroVisita, $origenMovimientoAves, $paisProvincia, $fechaLlegada,  $huboMovimientoAves, 
											$tipoAves,  $idProvincia,  $provincia,  $idCanton, $canton,  $idParroquia,  
											$parroquia,  $idEspecieAves, $especieAves,  $numeroAvesMovilizadas,  $propietario, 
											$proveedor,  $finalidad,  $fecha, $identificador), 
																0, 'id_movimiento_animales_aves');
				
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaMovimientoAves(	$idMovimientoAnimalesAves,  $idEventoSanitario, $numeroVisita, $origenMovimientoAves, $paisProvincia, $fechaLlegada,  $huboMovimientoAves, 
													$tipoAves,  $provincia,  $canton,  $parroquia,  $especieAves,  $numeroAvesMovilizadas,  $propietario, 
													$proveedor,  $finalidad,  $fecha,  $ruta);
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
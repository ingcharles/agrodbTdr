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
	
	$idTipoMovimientoEgreso = htmlspecialchars ($_POST['tipoMovimientoEgreso'],ENT_NOQUOTES,'UTF-8');
	
	$nombreTipoMovimientoEgreso = htmlspecialchars ($_POST['nombreTipoMovimientoEgreso'],ENT_NOQUOTES,'UTF-8');
	$idProvincia = htmlspecialchars ($_POST['provinciaMovimimentoEgreso'],ENT_NOQUOTES,'UTF-8');
	
	$nombreProvincia = htmlspecialchars ($_POST['nombreProvinciaMovimientoEgreso'],ENT_NOQUOTES,'UTF-8');
	$idCanton = htmlspecialchars ($_POST['cantonMovimientoEgreso'],ENT_NOQUOTES,'UTF-8');
	$nombreCanton = htmlspecialchars ($_POST['nombreCantonMovimientoEgreso'],ENT_NOQUOTES,'UTF-8');	
	
	$idParroquia = htmlspecialchars ($_POST['parroquiaMovimientoEgreso'],ENT_NOQUOTES,'UTF-8');
	$nombreParroquia = htmlspecialchars ($_POST['nombreParroquiaMovimientoEgreso'],ENT_NOQUOTES,'UTF-8');
	
	$idEspecie = htmlspecialchars ($_POST['especieMovimientoEgreso'],ENT_NOQUOTES,'UTF-8');
	$nombreEspecie = htmlspecialchars ($_POST['nombreEspecieEgresos'],ENT_NOQUOTES,'UTF-8');
	
	$propietarioMovimiento = htmlspecialchars ($_POST['PropietarioMovimientoEgreso'],ENT_NOQUOTES,'UTF-8');
	$fincaMovimiento = htmlspecialchars ($_POST['fincaMovimientoEgreso'],ENT_NOQUOTES,'UTF-8');
	
	$fechaMovimiento = htmlspecialchars ($_POST['fechaMovimientoEgreso'],ENT_NOQUOTES,'UTF-8');
	$numeroAnimales = htmlspecialchars ($_POST['numMovimientoEgreso'],ENT_NOQUOTES,'UTF-8');
	
	try {
		
		//$especieInspeccion = $cpco->buscarEgresos($conexion, $idEventoSanitario, $nombreTipoMovimientoEgreso);
		
		//if(pg_num_rows($especieInspeccion) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				$idEgreso = pg_fetch_result($cpco->nuevaEgresosAnimales(	$conexion, 
													$idEventoSanitario, $numeroVisita, $idTipoMovimientoEgreso, $nombreTipoMovimientoEgreso,
													$idProvincia,  $nombreProvincia,  $idCanton, $nombreCanton, 
													$idParroquia,  $nombreParroquia,  $idEspecie, $nombreEspecie,  
													$propietarioMovimiento,  $fincaMovimiento,  $fechaMovimiento, $identificador,
													$numeroAnimales), 
																0, 'id_egreso');
				
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaEgresos(	$idEgreso,  $idEventoSanitario, $numeroVisita, $nombreProvincia,  $nombreCanton, 
											$nombreParroquia,  $nombreEspecie,  $propietarioMovimiento,  $fincaMovimiento,  $fechaMovimiento, 
											$ruta, $numeroAnimales);
		/*}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "registro ingresado ya existe, por favor verificar en el listado.";
		}*/
		
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
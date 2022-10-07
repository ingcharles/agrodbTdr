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
	$idTipoMovimientoIngreso = htmlspecialchars ($_POST['tipoMovimientoIngreso'],ENT_NOQUOTES,'UTF-8');
	
	$nombreTipoMovimientoIngreso = htmlspecialchars ($_POST['nombreTipoMovimientoIngreso'],ENT_NOQUOTES,'UTF-8');
	$idProvincia = htmlspecialchars ($_POST['provinciaMovimimentoIngreso'],ENT_NOQUOTES,'UTF-8');
	
	$nombreProvincia = htmlspecialchars ($_POST['nombreProvinciaMovimientoIngreso'],ENT_NOQUOTES,'UTF-8');
	$idCanton = htmlspecialchars ($_POST['cantonMovimientoIngreso'],ENT_NOQUOTES,'UTF-8');
	$nombreCanton = htmlspecialchars ($_POST['nombreCantonMovimientoIngreso'],ENT_NOQUOTES,'UTF-8');	
	
	$idParroquia = htmlspecialchars ($_POST['parroquiaMovimientoIngreso'],ENT_NOQUOTES,'UTF-8');
	$nombreParroquia = htmlspecialchars ($_POST['nombreParroquiaMovimientoIngreso'],ENT_NOQUOTES,'UTF-8');
	
	$idEspecie = htmlspecialchars ($_POST['especieMovimientoIngreso'],ENT_NOQUOTES,'UTF-8');
	$nombreEspecie = htmlspecialchars ($_POST['nombreEspecieIngreso'],ENT_NOQUOTES,'UTF-8');
	
	$propietarioMovimiento = htmlspecialchars ($_POST['propietarioMovimientoIngreso'],ENT_NOQUOTES,'UTF-8');
	$fincaMovimiento = htmlspecialchars ($_POST['fincaMovimientoIngreso'],ENT_NOQUOTES,'UTF-8');
	
	$fechaMovimiento = htmlspecialchars ($_POST['fechaMovimientoIngreso'],ENT_NOQUOTES,'UTF-8');
	$numeroAnimales = htmlspecialchars ($_POST['numMovimientoIngreso'],ENT_NOQUOTES,'UTF-8');
	
	try {
		
		//$especieInspeccion = $cpco->buscarIngresos($conexion, $idEventoSanitario, $nombreTipoMovimientoIngreso);
		
		//if(pg_num_rows($especieInspeccion) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				$idIngreso = pg_fetch_result($cpco->nuevaIngresosAnimales(	$conexion, 
													$idEventoSanitario, $numeroVisita, $idTipoMovimientoIngreso, $nombreTipoMovimientoIngreso, 
													$idProvincia, $nombreProvincia, $idCanton,  $nombreCanton, 
													$idParroquia, $nombreParroquia, $idEspecie,$nombreEspecie, $propietarioMovimiento, 
													$fincaMovimiento,  $fechaMovimiento, $identificador, $numeroAnimales), 
																0, 'id_ingreso');
				
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaIngresos(	$idIngreso, $idEventoSanitario, $numeroVisita, $nombreProvincia, $nombreCanton, 
											$nombreParroquia, $nombreEspecie, $propietarioMovimiento, $fincaMovimiento,  $fechaMovimiento, 
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
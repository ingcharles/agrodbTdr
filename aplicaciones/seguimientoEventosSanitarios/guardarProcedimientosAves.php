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
	
	$farmacoAves = htmlspecialchars ($_POST['farmacoAves'],ENT_NOQUOTES,'UTF-8');
	
	$principioActivoAves = htmlspecialchars ($_POST['principioActivoAves'],ENT_NOQUOTES,'UTF-8');	
	$dosisProcedimientoAves = htmlspecialchars ($_POST['dosisAves'],ENT_NOQUOTES,'UTF-8');
	$fechaInicioProcedimientoAves = htmlspecialchars ($_POST['fechaInicioAves'],ENT_NOQUOTES,'UTF-8');		
	$fechaFinProcedimientoAves = htmlspecialchars ($_POST['fechaFinAves'],ENT_NOQUOTES,'UTF-8');
	$idFinalidadProcedimientoAves = htmlspecialchars ($_POST['finalidadAves'],ENT_NOQUOTES,'UTF-8');
	$nombreFinalidadProcedimientoAves = htmlspecialchars ($_POST['nombreFinalidadAves'],ENT_NOQUOTES,'UTF-8');
		
	
	try {
		
		$especieInspeccion = $cpco->buscarProcedimientosAves($conexion, $idEventoSanitario, $principioActivoAves);
		
		if(pg_num_rows($especieInspeccion) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				if($farmacoAves == 'No'){
					$principioActivoAves = 'No Aplica';
					$dosisProcedimientoAves = 'No Aplica';
					$fechaInicioProcedimientoAves = 'now()';
					$fechaFinProcedimientoAves = 'now()';
					$idFinalidadProcedimientoAves = 0;
					$nombreFinalidadProcedimientoAves = 'No Aplica';
				}
			
				$idProcedimientosAves = pg_fetch_result($cpco->nuevaProcedimientosAves(	$conexion, $idEventoSanitario, $principioActivoAves, 
													$dosisProcedimientoAves, $fechaInicioProcedimientoAves, $fechaFinProcedimientoAves, 
													$idFinalidadProcedimientoAves,$nombreFinalidadProcedimientoAves,  $identificador), 
																0, 'id_procedimientos_aves');
				
				if($farmacoAves == 'No'){
					$fecha = getdate();
					$fechaInicioProcedimientoAves = $fecha['mday'].'/'.$fecha['mon'].'/'.$fecha['year'];
					$fechaFinProcedimientoAves = $fecha['mday'].'/'.$fecha['mon'].'/'.$fecha['year'];
				}
				
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaProcedimientoAves(	$idProcedimientosAves, $idEventoSanitario, $principioActivoAves, 
													$dosisProcedimientoAves, $fechaInicioProcedimientoAves, $fechaFinProcedimientoAves, 
													$nombreFinalidadProcedimientoAves, $ruta);
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
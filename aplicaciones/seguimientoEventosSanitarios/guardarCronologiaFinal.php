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
	
	$idTipoCronologiaFinal = htmlspecialchars ($_POST['tipoCronologiaFinal'],ENT_NOQUOTES,'UTF-8');
	$nombreTipoCronologiaFinal = htmlspecialchars ($_POST['nombreCronologiaFinal'],ENT_NOQUOTES,'UTF-8');
	
	$fechaCronologiaFinal = htmlspecialchars ($_POST['fechaCronologiaFinal'],ENT_NOQUOTES,'UTF-8');
		
	
	try {
		
		$especieInspeccion = $cpco->buscarCronologiasFinales($conexion, $idEventoSanitario, $nombreTipoCronologiaFinal);
		
		if(pg_num_rows($especieInspeccion) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				$idCronologiaFinal = pg_fetch_result($cpco->nuevaCronologiasFinales(	$conexion, 
												$idEventoSanitario, $idTipoCronologiaFinal, $nombreTipoCronologiaFinal,  $fechaCronologiaFinal, $identificador), 
																0, 'id_cronologia_final');
				
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaCronologiaFinal(	$idCronologiaFinal,  $idEventoSanitario, $nombreTipoCronologiaFinal,  $fechaCronologiaFinal,
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
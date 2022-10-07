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
	
	$idTipoCronologia = htmlspecialchars ($_POST['tipoCronologia'],ENT_NOQUOTES,'UTF-8');
	$nombreTipoCronologia = htmlspecialchars ($_POST['nombreCronologia'],ENT_NOQUOTES,'UTF-8');
	
	$fechaCronologia = htmlspecialchars ($_POST['fechaCronologia'],ENT_NOQUOTES,'UTF-8');
	$horaCronologia = htmlspecialchars ($_POST['horaCronologia'],ENT_NOQUOTES,'UTF-8');	
	
	try {
		
		$especieInspeccion = $cpco->buscarCronologias($conexion, $idEventoSanitario, $nombreTipoCronologia);
		
		if(pg_num_rows($especieInspeccion) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				$idCronologia = pg_fetch_result($cpco->nuevaCronologia(	$conexion, $idEventoSanitario, $idTipoCronologia, $nombreTipoCronologia, $fechaCronologia, 
										$horaCronologia , $identificador), 
																0, 'id_cronologia');
				
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaCronologia(	$idCronologia, $idEventoSanitario, $nombreTipoCronologia, $fechaCronologia, 
												$horaCronologia, $ruta );
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
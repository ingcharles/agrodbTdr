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
	
	$idEspecie = htmlspecialchars ($_POST['especie'],ENT_NOQUOTES,'UTF-8');
	$nombreEspecie = htmlspecialchars ($_POST['nombreEspecie'],ENT_NOQUOTES,'UTF-8');	
	$idTipoExplotacion = htmlspecialchars ($_POST['tipoExplotacion'],ENT_NOQUOTES,'UTF-8');
	$nombreTipoExplotacion = htmlspecialchars ($_POST['nombreTipoExplotacion'],ENT_NOQUOTES,'UTF-8');
		
	
	try {
		
		$especieInspeccion = $cpco->buscarTiposExplotaciones($conexion, $idEventoSanitario, $nombreEspecie, $nombreTipoExplotacion);
		
		if(pg_num_rows($especieInspeccion) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				$idExplotacionRegistrada = pg_fetch_result($cpco->nuevaExplotacion(	$conexion, $idEventoSanitario, $idEspecie, $nombreEspecie,
																$idTipoExplotacion, $nombreTipoExplotacion, $identificador), 
																0, 'id_explotacion');
				
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaTipoExplotacion(	$idExplotacionRegistrada, $idEventoSanitario, $nombreEspecie, 
													$nombreTipoExplotacion, $ruta );
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "El registro ingresado ya existe, por favor verificar en el listado.";
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
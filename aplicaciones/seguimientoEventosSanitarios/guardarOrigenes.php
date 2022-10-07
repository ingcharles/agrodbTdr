<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEventoSanitario.php';

	$identificador = $_SESSION['usuario'];
	
$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$ruta = 'seguimientoEventosSanitarios';

try{
	
	$idEventoSanitario = htmlspecialchars ($_POST['idEventoSanitario'],ENT_NOQUOTES,'UTF-8');
	$numeroVisita  = htmlspecialchars ($_POST['numeroVisita'],ENT_NOQUOTES,'UTF-8');
	$origenEnfermedad  = htmlspecialchars ($_POST['origenEnfermedad'],ENT_NOQUOTES,'UTF-8');
	$cuarentenaPredio = htmlspecialchars ($_POST['cuarentenaPredio'],ENT_NOQUOTES,'UTF-8');
	$numeroActa = htmlspecialchars ($_POST['numeroActa'],ENT_NOQUOTES,'UTF-8');
	$medidasSanitarias = htmlspecialchars ($_POST['medidasSanitarias'],ENT_NOQUOTES,'UTF-8');
	$observaciones = htmlspecialchars ($_POST['observacionesOrigenes'],ENT_NOQUOTES,'UTF-8');
	
	try{
		$conexion = new Conexion();
		$cpco = new ControladorEventoSanitario();
			
		if(($identificador != null) || ($identificador != '')){
			
			$conexion->ejecutarConsulta("begin;");
			
				$origen = $cpco->abrirMedidaSanitaria($conexion, $idEventoSanitario, $numeroVisita);
				
				if(pg_num_rows($origen) == 0){
				
					$cpco->nuevaMedidaSanitaria($conexion, $idEventoSanitario, $numeroVisita, $origenEnfermedad, $cuarentenaPredio,
												$numeroActa,  $medidasSanitarias, $observaciones, $identificador);
				}else{
					$cpco->modificarMedidaSanitaria($conexion, $idEventoSanitario, $numeroVisita, $origenEnfermedad, $cuarentenaPredio,
												$numeroActa,  $medidasSanitarias, $observaciones, $identificador);
				}
				
			$conexion->ejecutarConsulta("commit;");
			
			$conexion->desconectar();
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Se han guardado los datos.';
			
	
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'Su sesión ha expirado, por favor ingrese nuevamente al sistema para continuar.';
		
			$conexion->desconectar();
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
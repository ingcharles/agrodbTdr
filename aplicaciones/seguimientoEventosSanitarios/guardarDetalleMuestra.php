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
	
	$idMuestra = htmlspecialchars ($_POST['idMuestra'],ENT_NOQUOTES,'UTF-8');
	
	$idEspecieMuestra = htmlspecialchars ($_POST['especieMuestra'],ENT_NOQUOTES,'UTF-8');
	$nombreEspecieMuestra = htmlspecialchars ($_POST['nombreEspecieMuestra'],ENT_NOQUOTES,'UTF-8');
	
	$idPruebasMuestra = htmlspecialchars ($_POST['pruebasMuestra'],ENT_NOQUOTES,'UTF-8');
	$nombrePruebasMuestra = htmlspecialchars ($_POST['nombrePruebasMuestra'],ENT_NOQUOTES,'UTF-8');
	
	$tipoMuestra = htmlspecialchars ($_POST['tipoMuestra'],ENT_NOQUOTES,'UTF-8');
	$nombreTipoMuestra = htmlspecialchars ($_POST['nombreTipoMuestra'],ENT_NOQUOTES,'UTF-8');
	$numeroMuestras = htmlspecialchars ($_POST['numeroMuestras'],ENT_NOQUOTES,'UTF-8');	
	
	$fechaColectaMuestra = htmlspecialchars ($_POST['fechaColectaMuestra'],ENT_NOQUOTES,'UTF-8');
	$horaColectaMuestra = htmlspecialchars ($_POST['horaColectaMuestra'],ENT_NOQUOTES,'UTF-8');
	
	$fechaEnvioMuestra = htmlspecialchars ($_POST['fechaEnvioMuestra'],ENT_NOQUOTES,'UTF-8');
	$horaEnvioMuestra = htmlspecialchars ($_POST['horaEnvioMuestra'],ENT_NOQUOTES,'UTF-8');
		
	
	try {
		
		$especieInspeccion = $cpco->buscarDetalleMuestras($conexion, $idEventoSanitario, $nombreEspecieMuestra, $nombreTipoMuestra, $numVisita);
		
		if(pg_num_rows($especieInspeccion) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				$idPatologiaEspecieAfectada = pg_fetch_result($cpco->nuevaDetalleMuestras(	$conexion, $idMuestra, $idEventoSanitario, $idEspecieMuestra, $nombreEspecieMuestra,
													$tipoMuestra, $nombreTipoMuestra, $numeroMuestras, 
													$fechaColectaMuestra, $horaColectaMuestra, $fechaEnvioMuestra, $horaEnvioMuestra, $identificador), 
																0, 'id_detalle_muestra');
				
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaMuestra(	$idDetalleMuestra, $idMuestra, $idEventoSanitario, $nombreEspecieMuestra, $nombreTipoMuestra, $numeroMuestras, 
											$fechaColectaMuestra, $horaColectaMuestra, $fechaEnvioMuestra, $horaEnvioMuestra,
											$ruta );
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
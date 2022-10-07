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
	
	$idEspecieAfectada = htmlspecialchars ($_POST['especieAfectada'],ENT_NOQUOTES,'UTF-8');
	$nombreEspecieAfectada = htmlspecialchars ($_POST['nombreEspecieAfectada'],ENT_NOQUOTES,'UTF-8');
	
	$especificacionEspecieAfectada = htmlspecialchars ($_POST['especifiqueEspecieAfectada'],ENT_NOQUOTES,'UTF-8');
		
	
	try {
		
		$especieInspeccion = $cpco->buscarEspecieAnimalAfactada($conexion, $idEventoSanitario, $nombreEspecieAfectada);
		
		if(pg_num_rows($especieInspeccion) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				$idEspecieAfectadaEventoSanitario = pg_fetch_result($cpco->nuevaEspeciesAfectadasEventoSanitario(	$conexion, $idEventoSanitario, $idEspecieAfectada,  $nombreEspecieAfectada,
													$especificacionEspecieAfectada, $identificador), 
																0, 'id_especie_afectada_evento_sanitario');
				
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaEspecieAfectada(	$idEspecieAfectadaEventoSanitario, $idEventoSanitario, $nombreEspecieAfectada, 
													$especificacionEspecieAfectada, $ruta );
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
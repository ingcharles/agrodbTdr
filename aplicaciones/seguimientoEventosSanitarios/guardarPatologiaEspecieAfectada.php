<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorNotificacionEventoSanitario.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$ruta = 'seguimientoEventosSanitarios';

try{
	$conexion = new Conexion();
	$cpco = new ControladorNotificacionEventoSanitario();
	
	$identificador = $_SESSION['usuario'];
	
	$idNotificacionEventoSanitario = htmlspecialchars ($_POST['idNotificacionEventoSanitario'],ENT_NOQUOTES,'UTF-8');
	
	$idPatologia = htmlspecialchars ($_POST['patologiaDenunciada'],ENT_NOQUOTES,'UTF-8');
	$nombrePatologia = htmlspecialchars ($_POST['nombrePatologia'],ENT_NOQUOTES,'UTF-8');
	
	$idEspecie = htmlspecialchars ($_POST['especieAfectada'],ENT_NOQUOTES,'UTF-8');
	$nombreEspecie = htmlspecialchars ($_POST['nombreEspecie'],ENT_NOQUOTES,'UTF-8');	
	
	$animalesEnfermos = htmlspecialchars ($_POST['animalesEnfermos'],ENT_NOQUOTES,'UTF-8');
	$animalesMuertos = htmlspecialchars ($_POST['animalesMuertos'],ENT_NOQUOTES,'UTF-8');
		
	
	try {
		
		$especieInspeccion = $cpco->buscarPatologiaEspecieAfectada($conexion, $idNotificacionEventoSanitario, $nombrePatologia, $nombreEspecie);
		
		if(pg_num_rows($especieInspeccion) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				$idPatologiaEspecieAfectada = pg_fetch_result($cpco->nuevaPatologiaEspecieAfectada($conexion, 
														$idNotificacionEventoSanitario, $identificador, $idPatologia, 
														$nombrePatologia, $idEspecie, $nombreEspecie,
														$animalesEnfermos, $animalesMuertos), 
														0, 'id_patologia_especie_afectada');
				
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaPatologiaEspecieAfectada($idPatologiaEspecieAfectada,
																$idNotificacionEventoSanitario, $nombrePatologia, $nombreEspecie, 
																$animalesEnfermos, $animalesMuertos, $ruta);
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "La especie, raza y categoría ingresada ya existe, por favor verificar en el listado.";
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
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
	
	$idEspeciePoblacionFinal = htmlspecialchars ($_POST['especieFinal'],ENT_NOQUOTES,'UTF-8');
	$nombreEspeciePoblacionFinal = htmlspecialchars ($_POST['nombreEspecieFinal'],ENT_NOQUOTES,'UTF-8');
	
	$idCategoriaPoblacionFinal = htmlspecialchars ($_POST['categoriaFinal'],ENT_NOQUOTES,'UTF-8');
	$nombreCategoriaPoblacionFinal = htmlspecialchars ($_POST['nombreCategoriaFinal'],ENT_NOQUOTES,'UTF-8');	
	
	$existentesPoblacionFinal = htmlspecialchars ($_POST['existentesPoblacionFinal'],ENT_NOQUOTES,'UTF-8');
	$enfermosPoblacionFinal = htmlspecialchars ($_POST['enfermosPoblacionFinal'],ENT_NOQUOTES,'UTF-8');
	
	$muertosPoblacionFinal = htmlspecialchars ($_POST['muertosPoblacionFinal'],ENT_NOQUOTES,'UTF-8');
	$sacrificadosPoblacionFinal = htmlspecialchars ($_POST['sacrificadosPoblacionFinal'],ENT_NOQUOTES,'UTF-8');
	
	$matadosEliminadosPoblacionFinal = htmlspecialchars ($_POST['matadosPoblacionFinal'],ENT_NOQUOTES,'UTF-8');
	
	try {
		
		$especieInspeccion = $cpco->buscarPoblacionesFinales($conexion, $idEventoSanitario, $nombreEspeciePoblacionFinal,  $nombreCategoriaPoblacionFinal);
		
		if(pg_num_rows($especieInspeccion) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				$idPoblacionFinal = pg_fetch_result($cpco->nuevaPoblacionAnimalFinal(	$conexion, 
													$idEventoSanitario, $idEspeciePoblacionFinal,  $nombreEspeciePoblacionFinal,
													$idCategoriaPoblacionFinal,  $nombreCategoriaPoblacionFinal,
													$existentesPoblacionFinal,  $enfermosPoblacionFinal,  $muertosPoblacionFinal,  
													$sacrificadosPoblacionFinal,  $matadosEliminadosPoblacionFinal, $identificador), 
																0, 'id_poblacion_final');
				
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaPoblacionFinal(	$idPoblacionFinal,  $idEventoSanitario, $nombreEspeciePoblacionFinal,  $nombreCategoriaPoblacionFinal, 
													$existentesPoblacionFinal,  $enfermosPoblacionFinal,  $muertosPoblacionFinal,  $sacrificadosPoblacionFinal, 
													$matadosEliminadosPoblacionFinal, $ruta);
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
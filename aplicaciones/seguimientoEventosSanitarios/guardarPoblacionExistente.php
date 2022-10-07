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
	
	$idEspeciePoblacion = htmlspecialchars ($_POST['especiePoblacion'],ENT_NOQUOTES,'UTF-8');
	$nombreEspeciePoblacion = htmlspecialchars ($_POST['nombreEspeciePoblacion'],ENT_NOQUOTES,'UTF-8');	
	
	$idTipoEspeciePoblacion = htmlspecialchars ($_POST['categoriaPoblacion'],ENT_NOQUOTES,'UTF-8');
	$tipoEspeciePoblacion = htmlspecialchars ($_POST['nombreCategoriasPoblacion'],ENT_NOQUOTES,'UTF-8');
		
	$existentes = htmlspecialchars ($_POST['existentesPoblacion'],ENT_NOQUOTES,'UTF-8');
	$enfermos = htmlspecialchars ($_POST['enfermosPoblacion'],ENT_NOQUOTES,'UTF-8');
	
	$muertos = htmlspecialchars ($_POST['muertosPoblacion'],ENT_NOQUOTES,'UTF-8');
	$sacrificados = htmlspecialchars ($_POST['sacrificadosPoblacion'],ENT_NOQUOTES,'UTF-8');
	
	$matadosEliminados = htmlspecialchars ($_POST['matadosPoblacion'],ENT_NOQUOTES,'UTF-8');
	$totalSinVacunar = htmlspecialchars ($_POST['totalSinVacunaPoblacion'],ENT_NOQUOTES,'UTF-8');
	
	$enfermosSinVacunas = htmlspecialchars ($_POST['enfermosSinVacunaPoblacion'],ENT_NOQUOTES,'UTF-8');
	$ingresosConCertificadoMovilizacion = htmlspecialchars ($_POST['ingresosConCertificadoPoblacion'],ENT_NOQUOTES,'UTF-8');
	
	$egresosConCertificadoMovilizacion = htmlspecialchars ($_POST['egresosConCertificadoPoblacion'],ENT_NOQUOTES,'UTF-8');
	$ingresosConCertificadoVacunacion = htmlspecialchars ($_POST['ingresosConCertificadoVacunaPoblacion'],ENT_NOQUOTES,'UTF-8');
	
	$egresosConCertificadoVacunacion = htmlspecialchars ($_POST['egresosConCertificadoVacunaPoblacion'],ENT_NOQUOTES,'UTF-8');
	$numeroCertificado = htmlspecialchars ($_POST['numeroCertificadoPoblacion'],ENT_NOQUOTES,'UTF-8');
	
	$totalIngresados = htmlspecialchars ($_POST['totalIngresosPoblacion'],ENT_NOQUOTES,'UTF-8');
	$totalEgresados = htmlspecialchars ($_POST['totalEgresosPoblacion'],ENT_NOQUOTES,'UTF-8');
	
	try {
		
		$especieInspeccion = $cpco->buscarPoblaciones($conexion, $idEventoSanitario, $numeroVisita, $nombreEspeciePoblacion, $tipoEspeciePoblacion);
		
		if(pg_num_rows($especieInspeccion) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				$idPoblacionAnimales = pg_fetch_result($cpco->nuevaPoblacionAnimal(	$conexion, $idEventoSanitario, $numeroVisita, $idEspeciePoblacion, $nombreEspeciePoblacion, 
												$idTipoEspeciePoblacion, $tipoEspeciePoblacion, $existentes, $enfermos, $muertos,  $sacrificados,  
												$totalSinVacunar, $enfermosSinVacunas, $identificador), 
																0, 'id_poblacion_animales');
				
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaPoblacion(	$idPoblacionAnimales, $idEventoSanitario, $numeroVisita, $nombreEspeciePoblacion, 
											$tipoEspeciePoblacion, $existentes, $enfermos, $muertos,  $sacrificados,  
											$totalSinVacunar, $enfermosSinVacunas, $ruta);
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
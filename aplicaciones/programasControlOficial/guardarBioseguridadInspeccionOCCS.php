<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramasControlOficial.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$ruta = 'programasControlOficial';

try{
	$conexion = new Conexion();
	$cpco = new ControladorProgramasControlOficial();
	
	$identificador = $_SESSION['usuario'];
	
	$idInspeccionOCCS = htmlspecialchars ($_POST['idInspeccionOCCS'],ENT_NOQUOTES,'UTF-8');
	
	$calendarioVacunacion = htmlspecialchars ($_POST['calendarioVacunacion'],ENT_NOQUOTES,'UTF-8');
	$vacuna = htmlspecialchars ($_POST['vacuna'],ENT_NOQUOTES,'UTF-8');	
	$calendarioDesparacitacion = htmlspecialchars ($_POST['calendarioDesparacitacion'],ENT_NOQUOTES,'UTF-8');
	$frecuencia = htmlspecialchars ($_POST['frecuencia'],ENT_NOQUOTES,'UTF-8');
	$asesoramientoTecnico = htmlspecialchars ($_POST['asesoramientoTecnico'],ENT_NOQUOTES,'UTF-8');
	$nombreAsesor = htmlspecialchars ($_POST['nombreAsesor'],ENT_NOQUOTES,'UTF-8');
	$profesionAsesor = htmlspecialchars ($_POST['profesionAsesor'],ENT_NOQUOTES,'UTF-8');
	$identificacionIndividual = htmlspecialchars ($_POST['identificacionIndividual'],ENT_NOQUOTES,'UTF-8');
	$tipoIdentificacion = htmlspecialchars ($_POST['tipoIdentificacion'],ENT_NOQUOTES,'UTF-8');
	$idTipoAlimentacion = htmlspecialchars ($_POST['tipoAlimentacion'],ENT_NOQUOTES,'UTF-8');
	$nombreTipoAlimentacion = htmlspecialchars ($_POST['nombreTipoAlimentacion'],ENT_NOQUOTES,'UTF-8');
	$corralManejo = htmlspecialchars ($_POST['corralManejo'],ENT_NOQUOTES,'UTF-8');
	$registrosProductivos = htmlspecialchars ($_POST['registrosProductivos'],ENT_NOQUOTES,'UTF-8');
	$idTipoProduccion = htmlspecialchars ($_POST['tipoProduccion'],ENT_NOQUOTES,'UTF-8');
	$nombreTipoProduccion = htmlspecialchars ($_POST['nombreTipoProduccion'],ENT_NOQUOTES,'UTF-8');
	$idSectorPerteneciente = htmlspecialchars ($_POST['sectorPerteneciente'],ENT_NOQUOTES,'UTF-8');
	$nombreSectorPerteneciente = htmlspecialchars ($_POST['nombreSectorPerteneciente'],ENT_NOQUOTES,'UTF-8');
		
	try {
		
		$bioseguridadInspeccion = $cpco->buscarBioseguridadInspeccionOCCS($conexion, $idInspeccionOCCS, $nombreSectorPerteneciente);
		
		if(pg_num_rows($bioseguridadInspeccion) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				$idBioseguridadInspeccionOCCS = pg_fetch_result($cpco->nuevaBioseguridadInspeccionOCCS($conexion, $idInspeccionOCCS,
														$identificador,
														$calendarioVacunacion, $vacuna, $calendarioDesparacitacion,
														$frecuencia, $asesoramientoTecnico, $nombreAsesor, $profesionAsesor,
														$identificacionIndividual, $tipoIdentificacion, $idTipoAlimentacion,
														$nombreTipoAlimentacion, $corralManejo, $registrosProductivos,
														$idTipoProduccion, $nombreTipoProduccion, $idSectorPerteneciente,
														$nombreSectorPerteneciente), 
														0, 'id_inspeccion_occs_bioseguridad');
			
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaBioseguridadInspeccionOCCS($idBioseguridadInspeccionOCCS, $idInspeccionOCCS, 
														$vacuna, $frecuencia, $tipoIdentificacion, $nombreTipoAlimentacion, 
														$nombreTipoProduccion, $nombreSectorPerteneciente, $ruta);
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "La información de bioseguridad ingresada ya existe, por favor verificar en el listado.";
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
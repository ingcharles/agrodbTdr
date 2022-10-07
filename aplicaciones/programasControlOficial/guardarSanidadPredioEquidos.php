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
	
	$idCatastroPredioEquidos = htmlspecialchars ($_POST['idCatastroPredioEquidos'],ENT_NOQUOTES,'UTF-8');
	
	$profesionalTecnico = htmlspecialchars ($_POST['profesionalTecnico'],ENT_NOQUOTES,'UTF-8');
	$pesebreras = htmlspecialchars ($_POST['pesebreras'],ENT_NOQUOTES,'UTF-8');
	$areaCuarentena = htmlspecialchars ($_POST['areaCuarentena'],ENT_NOQUOTES,'UTF-8');
	$eliminacionDesechos = htmlspecialchars ($_POST['eliminacionDesechos'],ENT_NOQUOTES,'UTF-8');
	$controlVectores = htmlspecialchars ($_POST['controlVectores'],ENT_NOQUOTES,'UTF-8');
	$usoAperosIndividuales = htmlspecialchars ($_POST['usoAperosIndividuales'],ENT_NOQUOTES,'UTF-8');
	$reportePositivoAIE = htmlspecialchars ($_POST['reportePositivoAIE'],ENT_NOQUOTES,'UTF-8');
	$idMedidaSanitaria = htmlspecialchars ($_POST['medidaSanitaria'],ENT_NOQUOTES,'UTF-8');
	$nombreMedidaSanitaria = htmlspecialchars ($_POST['nombreMedidaSanitaria'],ENT_NOQUOTES,'UTF-8');
		
	try {
		
		$sanidad = $cpco->buscarSanidadPredioEquidos($conexion, $idCatastroPredioEquidos);
		
		if(pg_num_rows($sanidad) == 0){
			$conexion->ejecutarConsulta("begin;");
			
			$idSanidadPredioEquidos = pg_fetch_result($cpco->nuevaSanidadPredioEquidos($conexion, $idCatastroPredioEquidos, $identificador,
													$profesionalTecnico, $pesebreras, $areaCuarentena, $eliminacionDesechos, $controlVectores,
													$usoAperosIndividuales, $reportePositivoAIE, $idMedidaSanitaria, $nombreMedidaSanitaria), 
													0, 'id_catastro_predio_equidos_sanidad');
			
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaSanidadPredioEquidos($idSanidadPredioEquidos, $idCatastroPredioEquidos, $profesionalTecnico,
													$pesebreras, $areaCuarentena, $eliminacionDesechos, $controlVectores, $usoAperosIndividuales, 
													$reportePositivoAIE, $nombreMedidaSanitaria, $ruta);
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Solo puede ingresar un registro de Sanidad, Infraestructura y Manejo Animal.";
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
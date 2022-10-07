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
	
	$idControlVectores = htmlspecialchars ($_POST['idControlVectores'],ENT_NOQUOTES,'UTF-8');
	
	$malla = htmlspecialchars ($_POST['malla'],ENT_NOQUOTES,'UTF-8');
	$idEspecieMalla = htmlspecialchars ($_POST['especieMalla'],ENT_NOQUOTES,'UTF-8');	
	$especieMalla = htmlspecialchars ($_POST['nombreEspecieMalla'],ENT_NOQUOTES,'UTF-8');
	$numeroCapturadosMalla = htmlspecialchars ($_POST['numeroCapturadosMalla'],ENT_NOQUOTES,'UTF-8');
	$observacionesMalla = htmlspecialchars ($_POST['observacionesMalla'],ENT_NOQUOTES,'UTF-8');
		
	try {
		
		$sitioCaptura = $cpco->buscarSitioCapturaControlVectores($conexion, $idControlVectores, $malla, $idEspecieMalla);
		
		if(pg_num_rows($sitioCaptura) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				$idControlVectoresSitioCaptura = pg_fetch_result($cpco->nuevoSitioCapturaControlVectores($conexion, 
														$idControlVectores, $identificador, $malla, $idEspecieMalla, 
														$especieMalla, $numeroCapturadosMalla, $observacionesMalla),
														0, 'id_control_vectores_sitio_captura');
			
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaSitioCapturaControlVectores($idControlVectoresSitioCaptura,
																$idControlVectores, $malla, $especieMalla,
																$numeroCapturadosMalla, $observacionesMalla, $ruta);
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "La especie seleccionada en la malla ya existe, por favor verificar en el listado.";
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
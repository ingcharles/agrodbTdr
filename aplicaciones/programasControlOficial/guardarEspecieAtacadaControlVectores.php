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
	
	$idEspecie = htmlspecialchars ($_POST['especie'],ENT_NOQUOTES,'UTF-8');
	$nombreEspecie = htmlspecialchars ($_POST['nombreEspecie'],ENT_NOQUOTES,'UTF-8');	
	$especieExistente = htmlspecialchars ($_POST['especieExistente'],ENT_NOQUOTES,'UTF-8');
	$especieMordeduras = htmlspecialchars ($_POST['especieMordeduras'],ENT_NOQUOTES,'UTF-8');
		
	try {
		
		$especieAtacada = $cpco->buscarEspecieAtacadaControlVectores($conexion, $idControlVectores, $nombreEspecie);
		
		if(pg_num_rows($especieAtacada) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				$idControlVectoresEspecieAtacada = pg_fetch_result($cpco->nuevaEspecieAtacadaControlVectores($conexion, 
														$idControlVectores, $identificador, $idEspecie, 
														$nombreEspecie, $especieExistente, $especieMordeduras), 
														0, 'id_control_vectores_especie_atacada');
			
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaEspecieAtacadaControlVectores($idControlVectoresEspecieAtacada,
																$idControlVectores, $nombreEspecie, 
																$especieExistente, $especieMordeduras, $ruta);
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "La especie atacada ingresada ya existe, por favor verificar en el listado.";
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
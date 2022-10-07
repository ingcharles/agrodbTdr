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
	
	$idQuiropteros = htmlspecialchars ($_POST['quiropteros'],ENT_NOQUOTES,'UTF-8');
	$nombreQuiropteros = htmlspecialchars ($_POST['nombreQuiropteros'],ENT_NOQUOTES,'UTF-8');	
	$numeroQuiropteros = htmlspecialchars ($_POST['numeroQuiropteros'],ENT_NOQUOTES,'UTF-8');
		
	try {
		
		$especieAtacada = $cpco->buscarQuiropteroCapturadoControlVectores($conexion, $idControlVectores, $nombreQuiropteros);
		
		if(pg_num_rows($especieAtacada) == 0){
			$conexion->ejecutarConsulta("begin;");
			
				$idControlVectoresQuiropterosCapturados = pg_fetch_result($cpco->nuevoQuiropteroCapturadoControlVectores($conexion, 
														$idControlVectores, $identificador, 
														$idQuiropteros, $nombreQuiropteros, $numeroQuiropteros),
														0, 'id_control_vectores_quiropteros_capturados');
				
				$cpco->eliminarTotalQuiropterosTratadosControlVectores($conexion, $idControlVectores);
			
			$conexion->ejecutarConsulta("commit;");
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaQuiropterosCapturadosControlVectores($idControlVectoresQuiropterosCapturados,
																$idControlVectores, $nombreQuiropteros, 
																$numeroQuiropteros, $ruta);
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
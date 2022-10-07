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
	
	$numeroVampirosTratados = htmlspecialchars ($_POST['numeroVampirosTratados'],ENT_NOQUOTES,'UTF-8');
	$numeroVampirosNoTratados = htmlspecialchars ($_POST['numeroVampirosNoTratados'],ENT_NOQUOTES,'UTF-8');	
	$numeroVampirosLaboratorio = htmlspecialchars ($_POST['numeroVampirosLaboratorio'],ENT_NOQUOTES,'UTF-8');
		
	$sumaQuiropterosTratados= $numeroVampirosTratados+$numeroVampirosNoTratados+$numeroVampirosLaboratorio;
	
	try {
		$cantidadQuiropterosCapturados = pg_fetch_result($cpco->cantidadQuiropterosCapturadosControlVectores($conexion, $idControlVectores), 0, 'quiropteros_capturados');
		
		if($cantidadQuiropterosCapturados == $sumaQuiropterosTratados){
			
			$especieTratada = $cpco->buscarQuiropteroTratadoControlVectores($conexion, $idControlVectores);
			
			if(pg_num_rows($especieTratada) == 0){
				$conexion->ejecutarConsulta("begin;");
				
					$idControlVectoresQuiropterosTratados = pg_fetch_result($cpco->nuevoQuiropteroTratadoControlVectores($conexion, $idControlVectores,
															$identificador, $numeroVampirosTratados, $numeroVampirosNoTratados, 
															$numeroVampirosLaboratorio), 0, 'id_control_vectores_quiropteros_tratados');
				
				$conexion->ejecutarConsulta("commit;");
			
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = $cpco->imprimirLineaQuiropterosTratadosControlVectores($idControlVectoresQuiropterosTratados,
															$idControlVectores, $numeroVampirosTratados, $numeroVampirosNoTratados,
															$numeroVampirosLaboratorio, $ruta);
			}else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = "Solo puede ingresar un resultado de Quirópteros Tratados.";
			}
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Los valores deben coincidir con el total de los Quirópteros Capturados.";
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
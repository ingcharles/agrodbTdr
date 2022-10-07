<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
		$nombre = $_POST['nombreEvaluacion'];
		$objetivo = $_POST['objetivo'];	
		$codParametro = $_POST['parametro'];
		$estadoCatastro = $_POST['catastro'];
		
	try {
		$conexion = new Conexion();
		$ced = new ControladorEvaluacionesDesempenio();
		$ca = new ControladorAreas();
		$conexion->ejecutarConsulta("begin;");
		
		$numero = pg_fetch_result($ced->buscarCodigoEvaluacion($conexion), 0, 'numero');
		$codigo = 'EVA-'.str_pad($numero, 2, "0", STR_PAD_LEFT);

		$qEvaluacion = $ced->guardarEvaluacion($conexion, $nombre, $_SESSION['usuario'], $codigo,$objetivo,$codParametro,$estadoCatastro);
		$idEvaluacion = pg_fetch_assoc($qEvaluacion);
		$idEvaluacion = $idEvaluacion['id_evaluacion'];
		$id_tipo=pg_fetch_result($ced->devolverSubTipo($conexion),0,'id_tipo');
		$ced->guardarTipoEvaluacion($conexion,$idEvaluacion,$id_tipo);
		$banderaEvaluacion = pg_num_rows($ced->devolverEvaluacionActiva($conexion));
		
		if($banderaEvaluacion == 0){
			if($estadoCatastro == 'Si'){
				$ced->activarInactivarCatastroOpcion($conexion, 'inactivo','Contrato por funcionario');
				$ced->activarInactivarCatastroOpcion($conexion, 'inactivo','Administrar reponsables RRHH');
				$ced->activarInactivarCatastroOpcion($conexion, 'inactivo','Administrar usuarios');
				$ced->activarInactivarCatastroOpcion($conexion, 'inactivo','Administrar responsables');
				$ced->activarInactivarCatastroOpcion($conexion, 'inactivo','Manual de funciones');
			}
		}
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'La evaluación ha sido generada satisfactoriamente.';
		//$mensaje['mensaje'] = print_r($areas);
		$conexion->ejecutarConsulta("commit;");
		echo json_encode($mensaje);
	} catch (Exception $ex){
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia".$ex;
		echo json_encode($mensaje);
	} finally {
			$conexion->desconectar();
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>

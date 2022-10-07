<?php 
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';

$conexion = new Conexion();
$ced = new ControladorEvaluacionesDesempenio();

$idEvaluacion = $_POST['codEvaluacion'];

try {
		$conexion->ejecutarConsulta("begin;");
		//-----------cerrar evaluacion-----------------------------------------------------------------
		$ced->actualizarEvaluacion($conexion,$idEvaluacion,'',0);
		//-----------actualizar estado-----------------------------------------------------------------
		$ced->actualizarEvaluacion($conexion,$idEvaluacion,'cerrado',3);
		//---------------------------------------------------------------------------------------------

		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Evaluación se cerro correctamente.';
		$conexion->ejecutarConsulta("commit;");
		echo json_encode($mensaje);
}catch (Exception $exc){
	$conexion->ejecutarConsulta("rollback;");
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = "Error al ejecutar sentencia".$exc;
	echo json_encode($mensaje);
}finally {
			$conexion->desconectar();
	}


?>





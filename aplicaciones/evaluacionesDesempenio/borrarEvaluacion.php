<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
		$conexion = new Conexion();
		$ced = new ControladorEvaluacionesDesempenio();
	try {

		$conexion->ejecutarConsulta("begin;");
		//$ced->inactivarActivarParametros ($conexion,$_POST['codParametro'],'false');
		$ced->actualizarEvaluacion($conexion, $_POST['idEvaluacion'],'eliminado',0);
		$ced->inactivarActivarAplicantes($conexion,'','eliminado',$_POST['idEvaluacion']);
		$ced->inactivarActivarAplicantesIndividual($conexion,'','eliminado', $_POST['idEvaluacion']);
		
		
		if($_POST['resultado'] == 'activo' || $_POST['resultado'] == 'proceso' || $_POST['resultado'] == 'finalizado' || $_POST['resultado'] == 'excepciones'){
			
			if(strcmp($_POST['estadoCatastro'], 'Si') == 0){
				$ced->activarInactivarCatastroOpcion($conexion, 'activo','Contrato por funcionario');
				$ced->activarInactivarCatastroOpcion($conexion, 'activo','Administrar reponsables RRHH');
				$ced->activarInactivarCatastroOpcion($conexion, 'activo','Administrar usuarios');
				$ced->activarInactivarCatastroOpcion($conexion, 'activo','Administrar responsables');
				$ced->activarInactivarCatastroOpcion($conexion, 'activo','Manual de funciones');
			}
		}else {
			if($_POST['banderaEvaluacion'] == 0){
				$ced->activarInactivarCatastroOpcion($conexion, 'activo','Contrato por funcionario');
				$ced->activarInactivarCatastroOpcion($conexion, 'activo','Administrar reponsables RRHH');
				$ced->activarInactivarCatastroOpcion($conexion, 'activo','Administrar usuarios');
				$ced->activarInactivarCatastroOpcion($conexion, 'activo','Administrar responsables');
				$ced->activarInactivarCatastroOpcion($conexion, 'activo','Manual de funciones');
			}
			
		}
		
		$conexion->ejecutarConsulta("commit;");
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Parámetros guardados satisfactoriamente';
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
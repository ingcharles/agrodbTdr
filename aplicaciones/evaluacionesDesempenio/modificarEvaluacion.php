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
		$codEvaluacion=$_POST['codEvaluacion'];
	try {
		$conexion = new Conexion();
		$ced = new ControladorEvaluacionesDesempenio();
		$ca = new ControladorAreas();
		
		$numero = pg_fetch_result($ced->buscarCodigoEvaluacion($conexion), 0, 'numero');
		$codigo = 'EVA-'.str_pad($numero, 2, "0", STR_PAD_LEFT);

		$qEvaluacion = $ced->modificarEvaluacion($conexion,$codEvaluacion, $nombre,$objetivo,$codParametro,$estadoCatastro);
		$ced->registroModificaciones($conexion,$_SESSION['usuario'],$codEvaluacion,'evaluaciones');

		$banderaEvaluacion = pg_num_rows($ced->devolverEvaluacionActiva($conexion));		
		if($banderaEvaluacion == 0){
				if($estadoCatastro == 'Si'){
					$ced->activarInactivarCatastroOpcion($conexion, 'inactivo','Contrato por funcionario');
					$ced->activarInactivarCatastroOpcion($conexion, 'inactivo','Administrar reponsables RRHH');
					$ced->activarInactivarCatastroOpcion($conexion, 'inactivo','Administrar usuarios');
					$ced->activarInactivarCatastroOpcion($conexion, 'inactivo','Administrar responsables');
					$ced->activarInactivarCatastroOpcion($conexion, 'inactivo','Manual de funciones');
				}else {
					$ced->activarInactivarCatastroOpcion($conexion, 'activo','Contrato por funcionario');
					$ced->activarInactivarCatastroOpcion($conexion, 'activo','Administrar reponsables RRHH');
					$ced->activarInactivarCatastroOpcion($conexion, 'activo','Administrar usuarios');
					$ced->activarInactivarCatastroOpcion($conexion, 'activo','Administrar responsables');
					$ced->activarInactivarCatastroOpcion($conexion, 'activo','Manual de funciones');
				}
		}
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'La evaluación ha sido actualizada satisfactoriamente.';
		//$mensaje['mensaje'] = print_r($areas);
		
		$conexion->desconectar();
		echo json_encode($mensaje);
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>

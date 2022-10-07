<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
		$datos = array(
				'idEvaluacion' =>  htmlspecialchars ($_POST['codParametro'],ENT_NOQUOTES,'UTF-8'),
				'identificadorUsuario' =>  htmlspecialchars ($_POST['identificadorUsuario'],ENT_NOQUOTES,'UTF-8'),
				'fechaInicio' => htmlspecialchars ($_POST['fechaInicio'],ENT_NOQUOTES,'UTF-8'),
				'fechaFin' =>  htmlspecialchars ($_POST['fechaFin'],ENT_NOQUOTES,'UTF-8'),
				'motivo' => htmlspecialchars ($_POST['motivo'],ENT_NOQUOTES,'UTF-8'),
				'observacion' => htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8'),
				'envioNotificacion' => htmlspecialchars ($_POST['envioNotificacion'],ENT_NOQUOTES,'UTF-8'),
				'notificacion' => htmlspecialchars ($_POST['notificacion'],ENT_NOQUOTES,'UTF-8'));
	try {
		$conexion = new Conexion();
		$ced = new ControladorEvaluacionesDesempenio();

		$conexion->ejecutarConsulta("begin;");
		
		$ced->guardarExcepcion($conexion,$datos['identificadorUsuario'],$datos['fechaInicio'],$datos['fechaFin'],$_SESSION['usuario'],$datos['motivo'],$datos['observacion'],$datos['envioNotificacion'],$datos['notificacion'],$datos['idEvaluacion']);
		
		$pendientes= $ced->abrirEvaluacionDisponibleUsuario ($conexion, $datos['identificadorUsuario'], 'finalizado',$datos['idEvaluacion']);
		while($fila = pg_fetch_assoc($pendientes)){
			$ced->activarExcepcionAplicantes($conexion,$datos['identificadorUsuario'], $fila['identificador_evaluado'],$fila['tipo'],$datos['idEvaluacion']);
		}
		//---------------------------------------------------------------------------------
		$qListaAplicantes=$ced->listarAplicantesEvaluacionIndividual($conexion, $datos['identificadorUsuario'],'finalizado',$datos['idEvaluacion']);
		while($aplicantes = pg_fetch_assoc($qListaAplicantes)){
			$ced->activarExcepcionAplicantesIndividual($conexion,$datos['identificadorUsuario'], $aplicantes['identificador_evaluado'],$datos['idEvaluacion']);
		}
		
		$conexion->ejecutarConsulta("commit;");
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Parámetros guardados satisfactoriamente.';
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
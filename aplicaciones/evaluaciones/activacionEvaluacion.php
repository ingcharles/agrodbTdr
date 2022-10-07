<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMonitoreo.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorGestionAplicacionesPerfiles.php';
require_once '../../clases/ControladorEvaluaciones.php';

define ( 'IN_MSG', '<br/> >>> ' );
define ( 'OUT_MSG', '<br/> <<< ' );
define ( 'PRO_MSG', '<br/> ... ' );

if($_SERVER['REMOTE_ADDR'] == ''){

	$conexion = new Conexion();
	$ce = new ControladorEvaluaciones();
	$cm = new ControladorMonitoreo();

	$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_ACT_INC_EVA_APLI');

	if($resultadoMonitoreo){


		echo '<h1>ACTIVACION DE EVALUACIONES </h1>';

		$res = $ce->obtenerAplicantesPendientes($conexion,1,"and fecha_activacion <= now()");

		$conexion->ejecutarConsulta("begin;");
		while($fila=pg_fetch_assoc($res)){
			$ce->ingresarAplicantes($conexion, $fila['identificador'], $fila['nombre'], $fila['apellido'], $fila['id_evaluacion']);
			echo IN_MSG.$fila['identificador'].' '.$fila['nombre'].' '.$fila['apellido'].' se activó evaluación EVA-'.$fila['id_evaluacion'].'<br/>';
			$ce->actualizarEstadoActivacion($conexion,$fila['id_activacion'],2);
		}
		$conexion->ejecutarConsulta("commit;");

		echo '<br><h1>INACTIVACION DE EVALUACIONES </h1>';

		$res = $ce->obtenerAplicantesPendientes($conexion,2,"and fecha_desactivacion <= now()");

		$conexion->ejecutarConsulta("begin;");
		while($fila=pg_fetch_assoc($res)){
			$ce->inactivarAplicantes($conexion, $fila['identificador'], $fila['id_evaluacion']);
			echo IN_MSG.$fila['identificador'].' '.$fila['nombre'].' '.$fila['apellido'].' se inactivó evaluación EVA-'.$fila['id_evaluacion'].'<br/>';
			$ce->actualizarEstadoActivacion($conexion,$fila['id_activacion'],3);
		}
		$conexion->ejecutarConsulta("commit;");
	}
}else{

	$minutoS1=microtime(true);
	$minutoS2=microtime(true);
	$tiempo=$minutoS2-minutoS1;
	$xcadenota = "FECHA ".date("d/m/Y")." ".date("H:i:s");
	$xcadenota.= "; IP REMOTA ".$_SERVER['REMOTE_ADDR'];
	$xcadenota.= "; SERVIDOR HTTP ".$_SERVER['HTTP_REFERER'];
	$xcadenota.= "; SEGUNDOS ".$tiempo."\n";
	$arch = fopen("../../../aplicaciones/logs/cron/activacion_inactivacion_evaluacion".date("d-m-Y").".txt", "a+");
	fwrite($arch, $xcadenota);
	fclose($arch);

}


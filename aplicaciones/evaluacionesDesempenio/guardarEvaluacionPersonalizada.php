<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorEmpleados.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';

$conexion = new Conexion();
$ced = new ControladorEvaluacionesDesempenio();
$ca = new ControladorAreas();
$ce = new ControladorEmpleados();

$idEvaluacion = $_POST['idEvaluacion'];

$identificadorEvaluado= $_POST['idEvaluado'];
//echo $idEvaluacion;
$detalleAplicantesIndividuales = $_POST['valorIngresado'];
//print_r($detalleAplicantesIndividuales).'</br>' ;

//$ced->eliminarEvaluacionCumplimineto($conexion, $idEvaluacion);
try {
	$conexion->ejecutarConsulta("begin;");
for ($i = 0; $i < count ($detalleAplicantesIndividuales); $i++) {
	
	$valores = explode('&', $detalleAplicantesIndividuales[$i]);
																		//$aplicanteIndividual = ($valores[1]!=''?$valores[1]:0);
	$idFuncion = ($valores[1]!=''?$valores[1]:0);
	$nombreFuncion = ($valores[2]!=''?$valores[2]:0);
	$valorMeta = ($valores[3]!=''?$valores[3]:0);
	$valorCumplimiento = ($valores[4]!=''?$valores[4]:0);
	$valorTotal = ($valores[5]!=''?$valores[5]:0);	
	$ced -> guardarEvaluacionIndividual($conexion, $valores[0], $idFuncion, $nombreFuncion,$valorMeta,$valorCumplimiento,$valorTotal, $idEvaluacion, $identificadorEvaluado);
	$idTipoEvaluacion=pg_fetch_result($ced->devolverEvaluacion ($conexion,$idEvaluacion),0,'id_tipo');
	
	$ponderacionIndividual=pg_fetch_result($ced->abrirTipoEvaluacion($conexion, $idTipoEvaluacion, 'individual'),0,'ponderacion');
	$resultadoIndividual=pg_fetch_result($ced->obtenerResultadoEvaluacionIndividual($conexion, $ponderacionIndividual, $identificadorEvaluado, $idEvaluacion),0,'valor');
	
	$ced -> actualizarResultadoCumplim($conexion, $identificadorEvaluado, $idEvaluacion, $resultadoIndividual);
	}

$ced-> actualizarEstadoAplicantesIndividual($conexion, $valores[0]);
$mensaje ['estado'] = 'exito';
$mensaje ['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';
$conexion->ejecutarConsulta("commit;");
} catch (Exception $ex) {
	$conexion->ejecutarConsulta("rollback;");
	$mensaje ['estado'] = 'error';
	$mensaje ['mensaje'] = 'Error al ejecutar sentencia';
}

?>

<header>
	<h1>Resultado evaluación.</h1>
</header>
	

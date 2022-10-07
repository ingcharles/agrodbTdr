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

$resultadoCumplimiento = $_POST['valorIngresado'];

//print_r($resultadoCumplimiento);

$ced->eliminarEvaluacionCumplimineto($conexion, $idEvaluacion);

for ($i = 0; $i < count ($resultadoCumplimiento); $i++) {
	
	$valores = explode('-', $resultadoCumplimiento[$i]);
	
	$valorGPR = ($valores[1]!=''?$valores[1]:0);
	$valorPresupuesto = ($valores[2]!=''?$valores[2]:0);
	$total = ($valores[3]!=''?$valores[3]:0);
	
	$ced -> guardarEvaluacionCumplimineto($conexion, $valores[0], $valorGPR, $valorPresupuesto, $total, $idEvaluacion);
	$ced -> actualizarResultadoCumplimiento($conexion, $valores[0], $idEvaluacion, $total);
}

?>

<header>
	<h1>Resultado evaluación evaluación</h1>
</header>
	
<div>Los datos han sido actualizados correctamente</div>
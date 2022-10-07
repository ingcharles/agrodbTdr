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
  echo '<header>
		<h1>Resultado evaluación</h1>
		</header>';

try {
$qFuncionarios = $ced->listarAplicantesEvaluacion($conexion, $idEvaluacion);
$idTipoEvaluacion=pg_fetch_result($ced->devolverEvaluacion ($conexion,$idEvaluacion),0,'id_tipo');

$evaluacionSuperior = pg_fetch_assoc($ced-> abrirTipoEvaluacion($conexion, $idTipoEvaluacion, 'superior'));
$evaluacionInferior = pg_fetch_assoc($ced-> abrirTipoEvaluacion($conexion, $idTipoEvaluacion, 'inferior'));
$evaluacionPares = pg_fetch_assoc($ced-> abrirTipoEvaluacion($conexion, $idTipoEvaluacion, 'pares'));
$autoevaluacion = pg_fetch_assoc($ced-> abrirTipoEvaluacion($conexion, $idTipoEvaluacion, 'autoevaluacion'));
$ponderacionIndividual=pg_fetch_result($ced->abrirTipoEvaluacion($conexion, $idTipoEvaluacion, 'individual'),0,'ponderacion');

while ($funcionario = pg_fetch_assoc($qFuncionarios)){
		if(1){
	//	if($funcionario['identificador']=='0201798907'){
	$resultadoSuperior = pg_fetch_assoc($ced->obtenerResultadoFuncionario($conexion, $funcionario['identificador'], $evaluacionSuperior['id_tipo_evaluacion'],0,$idEvaluacion));
	$resultadoInferior = pg_fetch_assoc($ced->obtenerResultadoFuncionario($conexion, $funcionario['identificador'], $evaluacionInferior['id_tipo_evaluacion'],0,$idEvaluacion));
	$resultadoPares = pg_fetch_assoc($ced->obtenerResultadoFuncionario($conexion, $funcionario['identificador'], $evaluacionPares['id_tipo_evaluacion'],0,$idEvaluacion));
	$resultadoAutoevaluacion = pg_fetch_assoc($ced->obtenerResultadoFuncionario($conexion, $funcionario['identificador'], $autoevaluacion['id_tipo_evaluacion'],0,$idEvaluacion));
	$resultadoIndividual=pg_fetch_result($ced->obtenerResultadoEvaluacionIndividual($conexion, $ponderacionIndividual, $funcionario['identificador'], $idEvaluacion),0,'valor');
	
	$competenciasSuperior = pg_num_rows($ced->verificarCompetenciasConductuales($conexion, $funcionario['identificador'], $evaluacionSuperior['id_tipo_evaluacion'],$idEvaluacion));
	$competenciasInferior = pg_num_rows($ced->verificarCompetenciasConductuales($conexion, $funcionario['identificador'], $evaluacionInferior['id_tipo_evaluacion'],$idEvaluacion));
	$competenciasPares = pg_num_rows($ced->verificarCompetenciasConductuales($conexion, $funcionario['identificador'], $evaluacionPares['id_tipo_evaluacion'],$idEvaluacion));
	
	$bandera=1;
	$resultadoSupe=number_format($resultadoSuperior['valor'],3);
	//---------------no tiene subordinados - pares-------uno---------------
	if($competenciasInferior == 0 and $competenciasPares == 0 ){
		$bandera=0;
		// jefe inmediato 35%
		$ponderacionSuperior = pg_fetch_result($ced->obternerPonderacionCompetencias($conexion, 'uno', $idTipoEvaluacion,'superior'),0,'ponderacion');
		$resultadoSuperior = pg_fetch_assoc($ced->obtenerResultadoFuncionario($conexion, $funcionario['identificador'], $evaluacionSuperior['id_tipo_evaluacion'],$ponderacionSuperior,$idEvaluacion));	
	}
	//---------------no tiene jefe inmediato - pares------dos---------------
	if($competenciasSuperior == 0 and $competenciasPares == 0 and $bandera){
		$bandera=0;
		// subordinado 35%
		$ponderacionInferior = pg_fetch_result($ced->obternerPonderacionCompetencias($conexion, 'dos', $idTipoEvaluacion,'inferior'),0,'ponderacion');		
		$resultadoInferior = pg_fetch_assoc($ced->obtenerResultadoFuncionario($conexion, $funcionario['identificador'], $evaluacionInferior['id_tipo_evaluacion'],$ponderacionInferior,$idEvaluacion));
	}
	//---------------no tiene jefe inmediato---------------tres--------------
	if($competenciasSuperior == 0 and $bandera ){
		$bandera=0;
		// subordinario 20%
		$ponderacionInferior = pg_fetch_result($ced->obternerPonderacionCompetencias($conexion, 'tres', $idTipoEvaluacion,'inferior'),0,'ponderacion');
		$resultadoInferior = pg_fetch_assoc($ced->obtenerResultadoFuncionario($conexion, $funcionario['identificador'], $evaluacionInferior['id_tipo_evaluacion'],$ponderacionInferior,$idEvaluacion));
	}
	//---------------no tiene subordinado------------------cuatro--------------
	if($competenciasInferior == 0 and $bandera){
		$bandera=0;
		// jefe inmediato 15%
		$ponderacionSuperior = pg_fetch_result($ced->obternerPonderacionCompetencias($conexion, 'cuatro', $idTipoEvaluacion,'superior'),0,'ponderacion');
		$resultadoSuperior = pg_fetch_assoc($ced->obtenerResultadoFuncionario($conexion, $funcionario['identificador'], $evaluacionSuperior['id_tipo_evaluacion'],$ponderacionSuperior,$idEvaluacion));
		// pares 20%
		$ponderacionPares = pg_fetch_result($ced->obternerPonderacionCompetencias($conexion, 'cuatro', $idTipoEvaluacion,'pares'),0,'ponderacion');
		$resultadoPares = pg_fetch_assoc($ced->obtenerResultadoFuncionario($conexion, $funcionario['identificador'], $evaluacionPares['id_tipo_evaluacion'],$ponderacionPares,$idEvaluacion));
	}
	//---------------no tiene pares------------------------cinco--------------
	if($competenciasPares == 0 and $bandera){
		// jefe inmediato 20%
		$ponderacionSuperior = pg_fetch_result($ced->obternerPonderacionCompetencias($conexion, 'cinco', $idTipoEvaluacion,'superior'),0,'ponderacion');
		$resultadoSuperior = pg_fetch_assoc($ced->obtenerResultadoFuncionario($conexion, $funcionario['identificador'], $evaluacionSuperior['id_tipo_evaluacion'],$ponderacionSuperior,$idEvaluacion));
		// subordinado 15%
		$ponderacionInferior = pg_fetch_result($ced->obternerPonderacionCompetencias($conexion, 'cinco', $idTipoEvaluacion,'inferior'),0,'ponderacion');
		$resultadoInferior = pg_fetch_assoc($ced->obtenerResultadoFuncionario($conexion, $funcionario['identificador'], $evaluacionInferior['id_tipo_evaluacion'],$ponderacionInferior,$idEvaluacion));
	}
	
	$ced->actualizarResultadoEvaluacion($conexion, $funcionario['identificador'],$idEvaluacion, number_format($resultadoSuperior['valor'],3), number_format($resultadoInferior['valor'],3), number_format($resultadoPares['valor'],3), number_format($resultadoAutoevaluacion['valor'],3), number_format($resultadoIndividual,3));
		}
    }
    
    echo '<div>Los resultados de evaluación se han generaron correctamente.</div>';
   //-----------actualizar estado-----------------------------------------------------------------
	$ced->actualizarEvaluacion($conexion,$idEvaluacion,'excepciones',3);
}catch (Exception $exc){
	echo '<div>Error al generar resultados....!!.</div>';
	echo $exc;
}
?>

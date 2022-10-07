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

$ced->eliminarEvaluacionCumplimineto($conexion, $idEvaluacion);

for ($i = 0; $i < count ($resultadoCumplimiento); $i++) {
	
	$valores = explode('-', $resultadoCumplimiento[$i]);
	
	$valorGPR = ($valores[1]!=''?$valores[1]:0);
	$valorPAPP = ($valores[2]!=''?$valores[2]:0);
	$total = ($valores[3]!=''?$valores[3]:0);
	
	$ced -> guardarEvaluacionCumplimineto($conexion, $valores[0], $valorGPR, $valorPAPP, $total, $idEvaluacion);

}

$arrayTotal = array();


$qFuncionarios = $ca->listarFuncionariosInstitucion($conexion);

$evaluacionSuperior = pg_fetch_assoc($ced-> abrirTipoEvaluacion($conexion, $idEvaluacion, 'superior'));
$evaluacionInferior = pg_fetch_assoc($ced-> abrirTipoEvaluacion($conexion, $idEvaluacion, 'inferior'));
$evaluacionPares = pg_fetch_assoc($ced-> abrirTipoEvaluacion($conexion, $idEvaluacion, 'pares'));
$autoevaluacion = pg_fetch_assoc($ced-> abrirTipoEvaluacion($conexion, $idEvaluacion, 'autoevaluacion'));

while ($funcionario = pg_fetch_assoc($qFuncionarios)){
		
	$resultadoSuperior = pg_fetch_assoc($ced->obtenerResultadoFuncionario($conexion, $funcionario['identificador'], $evaluacionSuperior['id_tipo_evaluacion']));
	$resultadoInferior = pg_fetch_assoc($ced->obtenerResultadoFuncionario($conexion, $funcionario['identificador'], $evaluacionInferior['id_tipo_evaluacion']));
	$resultadoPares = pg_fetch_assoc($ced->obtenerResultadoFuncionario($conexion, $funcionario['identificador'], $evaluacionPares['id_tipo_evaluacion']));
	$resultadoEvalaucion = pg_fetch_assoc($ced->obtenerResultadoFuncionario($conexion, $funcionario['identificador'], $autoevaluacion['id_tipo_evaluacion']));
	
	if ($resultadoInferior['valor']!= 0){
		$evaluacionSuperiorInferior = number_format(($resultadoSuperior['valor'] + $resultadoInferior['valor'])/2,3);
	}else{
		$evaluacionSuperiorInferior = number_format($resultadoSuperior['valor'],3);
	}
	
	$nombres = pg_fetch_assoc($ce->obtenerDatosPersonales($conexion, $funcionario['identificador']));
	
	$qSubprocesos = $ca->buscarAreasSubprocesos($conexion, $funcionario['id_area']);
	
	if(pg_num_rows($qSubprocesos)!=0){				
		$valorCumplimiento= pg_fetch_assoc($ced->buscarEvalucionCumplimientoArea($conexion, $funcionario['id_area'], $idEvaluacion));
	}else{
		if(strtoupper(substr($funcionario['id_area'], 0,2)) == 'CP'){
			$valorCumplimiento = pg_fetch_assoc($ced->buscarEvalucionCumplimientoArea($conexion, $funcionario['id_area'], $idEvaluacion));
		}else{		
			$areaPadre = pg_fetch_assoc($ca->buscarPadreSubprocesos($conexion, $funcionario['id_area']));
			if($areaPadre['id_area_padre']=='AGR'){
				$valorCumplimiento= pg_fetch_assoc($ced->buscarEvalucionCumplimientoArea($conexion, $funcionario['id_area'], $idEvaluacion));
			}else{
				$valorCumplimiento= pg_fetch_assoc($ced->buscarEvalucionCumplimientoArea($conexion, $areaPadre['id_area_padre'], $idEvaluacion));
			}			
		}
	}
	
	$valorTotal = $evaluacionSuperiorInferior + number_format($resultadoPares['valor'],3) + number_format($resultadoEvalaucion['valor'],3) + $valorCumplimiento['valor_total'];
	
	$arrayTotal[] = array(area=>$funcionario['nombre'],nombre=>$nombres['nombres_completos'],funcionario=>$funcionario['identificador'], superior=>$evaluacionSuperiorInferior, pares=>number_format($resultadoPares['valor'],3), autoevaluacion=>number_format($resultadoEvalaucion['valor'],3), cumplimiento=>$valorCumplimiento['valor_total'], valorTotal=>$valorTotal);
}


?>


<header>
	<h1>Resultado evaluación evaluación</h1>
</header>

	<table>
		<thead>
			<tr>
			    <th>Área funcionario</th>
			    <th>Nombres</th>
			    <th>Cédula</th>
			    <th>Jefes y subordinados</th>
			    <th>Pares</th>
			    <th>Autoevaluación</th>
			    <th>Cumplimiento</th>
			    <th>Total</th>
			</tr>
	</thead>
	 <?php
	 
	 foreach ($arrayTotal as $key => $evaluacion){
       
	 	echo '<tr>
				<td>'.$evaluacion['area'].'</td>
				<td>'.$evaluacion['nombre'].'</td>
				<td>'.$evaluacion['funcionario'].'</td>
		        <td>'.$evaluacion['superior'].'</td>
		        <td>'.$evaluacion['pares'].'</td>
		        <td>'.$evaluacion['autoevaluacion'].'</td>
				<td>'.$evaluacion['cumplimiento'].'</td>
				<td>'.$evaluacion['valorTotal'].'</td>
    		</tr>';
	 	
	 	
	 }
	 
	 ?>
</table>

<form id="filtrar" action="aplicaciones/evaluacionesDesempenio/reporteEvaluacionGeneral.php" target="_blank" method="post">
	
	<?php 
		foreach ($arrayTotal as $key => $valores){

		echo'<input type="hidden" name="area[]" value="'.$valores['area'].'"/>
			 <input type="hidden" name="nombre[]" value="'.$valores['nombre'].'"/>
			 <input type="hidden" name="funcionario[]" value="'.$valores['funcionario'].'"/>
			 <input type="hidden" name="superior[]" value="'.$valores['superior'].'"/>
			 <input type="hidden" name="pares[]" value="'.$valores['pares'].'"/>
			 <input type="hidden" name="autoevaluacion[]" value="'.$valores['autoevaluacion'].'"/> 
			 <input type="hidden" name="cumplimiento[]" value="'.$valores['cumplimiento'].'"/>
			 <input type="hidden" name="valorTotal[]" value="'.$valores['valorTotal'].'"/>';
		
		}

	?>
	
	<button>Generar Reporte</button>
</form>

	
	
	






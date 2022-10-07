<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';

$conexion = new Conexion();
$ced = new ControladorEvaluacionesDesempenio();
$ca = new ControladorAreas();

$arrayPresupuestoGpr= array();

$idEvaluacion=explode(",",$_POST['elementos']);

if($idEvaluacion[0]!=''){
	
	$resultadoEvaluacion = pg_fetch_assoc($ced->verificarResultadosEvaluacion($conexion, $idEvaluacion[0]));
	$valoresPresupuestoGpr = $ced->listarValoresGprPresupuesto($conexion, $idEvaluacion[0]);
	
	
	if(pg_num_rows($valoresPresupuestoGpr) > 0){

		while ($fila = pg_fetch_assoc($valoresPresupuestoGpr)){
			//echo 'g';
			$arrayPresupuestoGpr[] = array(
					idArea=>$fila['id_area'],
					valorGpr=>$fila['valor_gpr'],
					valorPresupuesto=>$fila['valor_presupuesto'],
					valorTotal=>$fila['valor_total']
			);
		}

	}else{
		$arrayPresupuestoGpr = 0;
	}
	//print_r($arrayPresupuestoGpr);
}
$qAreas = $ca->obtenerAreasDireccionesTecnicas($conexion, "('Planta Central', 'Oficina Técnica','Dirección Distrital A','Dirección Distrital B')", "(1,3,4)");

?>
<header>
	<h1>Cumplimiento valores evaluación</h1>
</header>

<div id="estado"></div>

<form id="valorCumplimiento" data-rutaAplicacion="evaluacionesDesempenio" data-opcion="guardarResultadoCumplimiento" data-destino="detalleItem">

	<input type="hidden" name="idEvaluacion" value="<?php echo $idEvaluacion[0];?>"/>
	<fieldset>
		<legend>Cumplimiento (GPR, Consumo de presupuesto asignado)</legend>
		
			<table id="tValorCumplido">
		<thead>
			<tr>
			    <th>Área</th>
			    <th>Lugar </th>
			    <th>Valor GPR (%)</th>
			    <th>Valor consumo de presupuesto asignado (%)</th>
			    <th>Total (0.25)</th>
			</tr>
		</thead>
	 <?php
	 
	while ($area = pg_fetch_assoc($qAreas)){
		$nombrePadre = pg_fetch_result($ca->buscarPadreSubprocesos($conexion,$area['id_area_padre']), 0, 'nombre');
	 	echo '<tbody><tr id = '.$area['id_area'].'>
				<td>'.$area['nombre'].'</td>
				<td>'.$nombrePadre.'</td>
				<td><input type = "text" id="gpr_'.$area['id_area'].'" name="valorGPR[]"></input></td>
				<td><input type = "text" id="presupuesto_'.$area['id_area'].'" name="valorPRESUPUESTO[]"></input></td>
				<td><input type = "text" id="t_'.$area['id_area'].'" readonly="readonly"></input></td>
    		</tr></tbody>';
	 }
	 ?>
</table>

	<div id="resultadoCumplimiento"></div>
	</fieldset>
	<button type="submit" class="guardar">Generar resultados</button>
	
</form>
</body>

<script type="text/javascript">

var array_evaluacion= <?php echo json_encode($idEvaluacion); ?>;// llega array de evaluaciones [1]
var valor_resultado= <?php echo json_encode($resultadoEvaluacion['valor']); ?>;//llega cuantos registros hay en resutados_evaluacion
var array_valoresPresupuestoGpr= <?php echo json_encode($arrayPresupuestoGpr); ?>;// llega un array con losregistros de evaluacion_gpr_papp ()
var ponderacion = 0.25;

	$(document).ready(function(){

		for(var i=0;i<array_valoresPresupuestoGpr.length;i++){

			$('#valorCumplimiento').find('#gpr_'+array_valoresPresupuestoGpr[i]['idArea']+'').val(array_valoresPresupuestoGpr[i]['valorGpr']);//carga el valor del papp

			$('#valorCumplimiento').find('#presupuesto_'+array_valoresPresupuestoGpr[i]['idArea']+'').val(array_valoresPresupuestoGpr[i]['valorPresupuesto']);//consulta si hay valor en el pap

			$('#valorCumplimiento').find('#t_'+array_valoresPresupuestoGpr[i]['idArea']+'').val(array_valoresPresupuestoGpr[i]['valorTotal']);// carga el valor de total
		}
		
		if(array_evaluacion == ''){
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione una evaluación, a continuación presione el boton valores Presupuesto / GPR.</div>');
		}

		if(valor_resultado == '0'){
			$("#detalleItem").html('<div class="mensajeInicial">Por favor generar los resultados de la evaluación, a continuación presione el boton valores Presupuesto / GPR.</div>');
		}
		
	});

	$("input[type='text']").change(function(){

		var valorPRESUPUESTO = 0;
		var valorGPR = 0;
		var total = 0;
		
		var area = $(this).attr('id').split('_');

			valorGPR = Number($('#gpr_'+area[area.length-1]+'').val());
			valorPRESUPUESTO = Number($('#presupuesto_'+area[area.length-1]+'').val());
			
			total =  Math.round(((((valorGPR+valorPRESUPUESTO)/2)*ponderacion)/100)*1000)/1000;
			$('#t_'+area[area.length-1]+'').val(total);
	});

	$("#valorCumplimiento").submit(function(event){

		event.preventDefault();
		
		$('#tValorCumplido  tbody tr').each(function(){  
			var area = '';
			var valorGPR = 0;
			var valorPRESUPUESTO= 0;
			var total = 0;
			
			area = $(this).attr('id');
			valorGPR = Number($('#gpr_'+area+'').val());
			valorPRESUPUESTO = Number($('#presupuesto_'+area+'').val());
			total = Number($('#t_'+area+'').val());

			if(area){
				$("#resultadoCumplimiento").append("<input name='valorIngresado[]' value="+area+"-"+valorGPR+"-"+valorPRESUPUESTO+"-"+total+" type='hidden'>");
			}
	    });

		abrir($(this),event,false);	
	});
	
</script>

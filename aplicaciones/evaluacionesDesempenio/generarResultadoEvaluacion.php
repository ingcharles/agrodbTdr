<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';

$conexion = new Conexion();
$ced = new ControladorEvaluacionesDesempenio();

$idEvaluacion = $_POST['id'];

$qEvaluacion = $ced->abrirEvaluacion($conexion, $idEvaluacion, 'ABIERTOS');
$evaluacion = pg_fetch_assoc($qEvaluacion);

$resultadoEvaluacion = pg_fetch_assoc($ced->verificarResultadosEvaluacion($conexion, $idEvaluacion));
$resultadoCumplimiento = pg_fetch_assoc($ced->verificarCumplimientoEvaluacion($conexion, $idEvaluacion));

set_time_limit(780);


?>


<header>
	<h1>Evaluación</h1>
</header>

<div id="estado"></div>

<form id="generarResultadoEvaluacion" data-rutaAplicacion="evaluacionesDesempenio" data-opcion="guardarResultadoEvaluacion" data-destino="detalleItem">

	<input type="hidden" name="idEvaluacion" value="<?php echo $evaluacion['id_evaluacion'];?>"/>
		
	<fieldset>
	
		<legend>Datos generales</legend>
		
			<div data-linea="1">
				<label>Nombre evaluación: </label><?php echo $evaluacion['nombre'];?>	 
			</div>
			
	</fieldset>
	

	<button id="btnResultados" type="submit" class="guardar" disabled="disabled">Generar resultados evaluación</button>
	
</form>

</body>

<script type="text/javascript">

var valor_resultado= <?php echo json_encode($evaluacion['vigencia']); ?>;
var valor_cumplimiento= <?php echo json_encode($resultadoCumplimiento['valor']); ?>;

	$(document).ready(function(){
		
		if(valor_cumplimiento != '0' && valor_resultado == 'finalizado' ){
			$('#btnResultados').removeAttr("disabled","disabled");
			
		}else {
			if(valor_cumplimiento == '0'){
					$("#estado").html("Generar valores GPR y PAPP").addClass('alerta');
				}
			if(valor_resultado == 'excepciones'){
				$("#estado").html("Evaluacion esta finalizada...!!").addClass('alerta');
				}
		}
	});

	$("#generarResultadoEvaluacion").submit(function(event){
		abrir($(this),event,false);
	});

</script>




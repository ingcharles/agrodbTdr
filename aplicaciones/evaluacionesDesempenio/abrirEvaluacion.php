<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';

$conexion = new Conexion();
$ced = new ControladorEvaluacionesDesempenio();
$qEvaluacion = $ced->abrirEvaluacion($conexion, $_POST['id'], 'ABIERTOS');
$evaluacion = pg_fetch_assoc($qEvaluacion);
$res = $ced->listaParametros($conexion,'ABIERTOS');

//$res=$ced->listaParametrosSinUso ($conexion);

$banderaEvaluacion = pg_num_rows($ced->devolverEvaluacionActiva($conexion));

$consultaEva= pg_fetch_result($ced->devolverEvaluacionVigente ($conexion,'','', $_POST['id'] ),0,'vigencia');
//$resultadoEvaluacion = pg_fetch_assoc($ced->verificarResultadosEvaluacion($conexion, $evaluacion['id_evaluacion']));

?>
<header>
	<h1>Modificar/Generar evaluación</h1>
</header>
<div id="estado"></div>
<form id="modificicarEvaluacion" data-rutaAplicacion="evaluacionesDesempenio" data-opcion="modificarEvaluacion" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="codEvaluacion" id="codEvaluacion" value="<?php echo $_POST['id'];?>"> 
	<fieldset>	
		<legend>Datos generales</legend>		
			<div data-linea="1">
				<label>Nombre evaluación</label>	
					<input type="text" name="nombreEvaluacion" id="nombreEvaluacion" value="<?php echo $evaluacion['nombre'];?>" disabled="disabled"> 
			</div>			
			<div data-linea="2">
				<label>Objetivo</label> 
					<input type="text" name="objetivo" id="objetivo" value="<?php echo $evaluacion['objetivo'];?>" disabled="disabled"/> 
			</div>
			<div data-linea="3">
				<label>Parámetros</label>	
					<select style='width:100%' name="parametro" id="parametro" disabled="disabled">
					<option value="" >Seleccione...</option>
					<?php
					   while($fila = pg_fetch_assoc($res)){
							echo '<option value="'.$fila['cod_parametro'].'">'. $fila['nombre_parametro'] . '</option>';
						}		   					
					?>
				</select>
			</div>			
			<div data-linea="4">
				<label>Desactivar catastro</label> 
				<select style='width:100%' name="catastro" id="catastro" disabled="disabled">
					<option value="" >Seleccione...</option>
					<?php
						$area = array('Si','No');										
						for ($i=0; $i<sizeof($area); $i++){
							echo '<option value="'.$area[$i].'">'. $area[$i] . '</option>';
						}		   					
					?>
				</select>	
			</div>	
			<p>
		<button id="modificarEva" type="button" class="editar" <?php echo ($filaSolicitud['estado']=='Aprobado'? ' disabled=disabled':'')?>>Modificar</button>
		<button id="actualizarPer" type="submit" class="guardar" disabled="disabled">Guardar</button>
	</p>		
	</fieldset>
</form>
<form id="generarEvaluacion" 
	data-rutaAplicacion="evaluacionesDesempenio" 
	data-opcion="activarProcesoEvaluacion" 
	data-accionEnExito="ACTUALIZAR">
	<input type="hidden" name="idEvaluacion" id="idEvaluacion" value="<?php echo $_POST['id'];?>"> 
	<button type="submit" id="btnGenerar" >Generar evaluación</button>
</form>
</body>
<script type="text/javascript">
	var valor_resultado= <?php echo json_encode($consultaEva); ?>;
	var banderaEvaluacion= <?php echo json_encode($banderaEvaluacion); ?>;
	$(document).ready(function(){
			distribuirLineas();
			construirValidador();

			if(banderaEvaluacion){
				$("#btnGenerar").attr("disabled","disabled");
				$("#estado").html("Existe una evaluación en curso no puede generar otra...!!").addClass('alerta');
			 }
			
			if(valor_resultado == 'activo' || valor_resultado == 'proceso' || valor_resultado == 'cerrado' || valor_resultado == 'finalizado' || valor_resultado == 'eliminado' || valor_resultado == 'excepciones'){
				$("#btnGenerar").attr("disabled","disabled");
				$('#modificarEva').attr("disabled","disabled");
				$("#estado").html("Evaluación en curso...!!").addClass('alerta');
			 }
			
			cargarValorDefecto("parametro","<?php echo $evaluacion['cod_parametro'];?>");
			cargarValorDefecto("catastro","<?php echo $evaluacion['estado_catastro'];?>");
			
		});
	
	$("#modificicarEvaluacion").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		if($.trim($("#nombreEvaluacion").val())==""){
			error = true;
			$("#nombreEvaluacion").addClass("alertaCombo");
		}
		if($.trim($("#objetivo").val())==""){
			error = true;
			$("#objetivo").addClass("alertaCombo");
		}
		if($("#parametro").val()==""){
			error = true;
			$("#parametro").addClass("alertaCombo");
		}
		if($("#catastro").val()==""){
			error = true;
			$("#catastro").addClass("alertaCombo");
		}
		if (!error){
			ejecutarJson(this);
		}else{
				$("#estado").html('Por favor revise el formato de la información ingresada').addClass('alerta');
			}
	});

	$("#modificarEva").click(function(){
		$("#modificicarEvaluacion input").removeAttr("disabled");	
		$("#modificicarEvaluacion select").removeAttr("disabled");
		$("#actualizarPer").removeAttr("disabled");
		$("#btnGenerar").attr("disabled","disabled");
		$(this).attr("disabled","disabled");
	});

	$("#generarEvaluacion").submit(function(event){
		event.preventDefault();
		ejecutarJson(this);
	});
	
</script>




<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';

$conexion = new Conexion();
$ced = new ControladorEvaluacionesDesempenio();
$res = $ced->listaParametros($conexion,'ABIERTOS');

?>
<header>
	<h1>Nueva evaluación</h1>
</header>
<div id="estado"></div>
<form id="nuevaEvaluacion" data-rutaAplicacion="evaluacionesDesempenio" data-opcion="guardarNuevaEvaluacion" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">		
	<fieldset>	
		<legend>Datos generales</legend>		
			<div data-linea="1">
				<label>Nombre evaluación</label>	
					<input type="text" name="nombreEvaluacion" id="nombreEvaluacion" placeholder="Ej: Evaluación de desempeño" data-er="^[a-zA-Z]+[a-zA-Z]+[\w\s-ñÑÁáÉéÍíÓóÚúÜü]+$" maxlength="200"> 
			</div>			
			<div data-linea="2">
				<label>Objetivo</label> 
					<input type="text" name="objetivo" id="objetivo" placeholder="Objetivo" data-er="^[a-zA-Z]+[a-zA-Z]+[\w\s-ñÑÁáÉéÍíÓóÚúÜü]+$" maxlength="200"/> 
			</div>
			<div data-linea="3">
				<label>Parámetros</label>	
					<select style='width:100%' name="parametro" id="parametro" >
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
				<select style='width:100%' name="catastro" id="catastro" >
					<option value="" >Seleccione...</option>
					<?php
						$area = array('Si','No');										
						for ($i=0; $i<sizeof($area); $i++){
							echo '<option value="'.$area[$i].'">'. $area[$i] . '</option>';
						}		   					
					?>
				</select>	
			</div>			
	</fieldset>
	<button type="submit" class="guardar">Guardar</button>
</form>
</body>
<script type="text/javascript">
	$(document).ready(function(){
		construirValidador();
		distribuirLineas();
	});
	$("#nuevaEvaluacion").submit(function(event){
		event.preventDefault();
		var msg="Por favor revise el formato de la información ingresada";
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		
		if($.trim($("#nombreEvaluacion").val())==""){
			error = true;
			$("#nombreEvaluacion").addClass("alertaCombo");
		}
		
		if(!esCampoValido("#nombreEvaluacion")){
			error = true;
			$("#nombreEvaluacion").addClass("alertaCombo");
			msg="El nombre de la evaluación debe llevar letras y números";
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
				$("#estado").html(msg).addClass('alerta');
			}
	});
	
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");

		return patron.test($(elemento).val());
	}
</script>




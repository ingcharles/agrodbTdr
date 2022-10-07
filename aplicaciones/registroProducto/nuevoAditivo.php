<?php 
	session_start();
?>

	<header>
		<h1>Nuevo Aditivo</h1>
	</header>

	<div id="estado"></div>
		<form id="nuevoAditivo" data-rutaAplicacion="registroProducto" data-opcion="guardarNuevoAditivo" data-accionEnExito = 'ACTUALIZAR'>
					
			<fieldset>
				<legend>Aditivo importancia toxicológica</legend>	
				
    				<div data-linea="1">
    					<label for="area">Área</label>
    						<select id="area" name="area">
    								<option value="" selected="selected">Seleccione....</option>
    								<option value="IAP">Registro de insumos agrícolas</option>
    								<option value="IAV">Registro de insumos pecuarios</option>
    								<option value="IAF">Registro de insumos fertilizantes</option>
    								<option value="IAPA">Registro de insumos para plantas de autoconsumo</option>
    						</select>
    				</div>
    					
    				<label>Nombre común: </label>
    					<div data-linea="2">
    					<textarea id="nombreComun" name="nombreComun" required="required" maxlength="256" > </textarea>
    				</div>
    				
    				<label>Nombre químico: </label>
    				<div data-linea="3">    					
    					<textarea id="nombreQuimico" name="nombreQuimico" required="required" maxlength="256" > </textarea>
    				</div>
    				
    				<div data-linea="4">
    					<label>Cas: </label>
    					<input type="text" id="cas" name="cas" required="required" maxlength="256" >
    				</div>
    				
    				<div data-linea="5">
    					<label>Fórmula química: </label>
    					<input type="text" id="formulaQuimica" name="formulaQuimica" required="required" maxlength="256" >
    				</div>
				
					<div data-linea="6">
    					<label>Grupo químico: </label>
    					<input type="text" id="grupoQuimico" name="grupoQuimico" required="required" maxlength="256" >
    				</div>
			</fieldset>
			
			<div>
				<button type="submit" class="guardar">Guardar</button>
			</div>
			
		</form>
</body>

<script>
	$('document').ready(function(){
		construirValidador();
		distribuirLineas();				
	});
	
	$("#nuevoAditivo").submit(function(event){

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#area").val()==""){
			error = true;
			$("#area").addClass("alertaCombo");
		}

		if(($.trim($("#nombreComun").val())=="")  || !esCampoValido("#nombreComun")){
			error = true;
			$("#nombreComun").addClass("alertaCombo");
		}

		if(($.trim($("#nombreQuimico").val())=="")  || !esCampoValido("#nombreQuimico")){
			error = true;
			$("#nombreQuimico").addClass("alertaCombo");
		}

		if(($.trim($("#cas").val())=="")  || !esCampoValido("#cas")){
			error = true;
			$("#cas").addClass("alertaCombo");
		}

		if(($.trim($("#formulaQuimica").val())=="")  || !esCampoValido("#formulaQuimica")){
			error = true;
			$("#formulaQuimica").addClass("alertaCombo");
		}

		if(($.trim($("#grupoQuimico").val())=="")  || !esCampoValido("#grupoQuimico")){
			error = true;
			$("#grupoQuimico").addClass("alertaCombo");
		}

		if (!error){
			ejecutarJson($(this));
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}		
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
</script>
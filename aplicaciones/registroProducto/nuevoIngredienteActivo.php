<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorCatalogos.php';
	
	$conexion = new Conexion();
	$cr = new ControladorRequisitos();	
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Nuevo requisito</h1>
	</header>

	<div id="estado"></div>
		<form id="nuevoIngredienteActivo" data-rutaAplicacion="registroProducto" data-opcion="guardarNuevoIngredienteActivo" data-accionEnExito = 'ACTUALIZAR'>
					
			<fieldset>
				<legend>Ingrediente activo</legend>	
				
				<div data-linea="1">
					<label for="area">Área</label>
						<select id="area" name="area">
								<option value="" selected="selected">Seleccione una dirección...</option>
								<option value="IAP">Registro de insumos agrícolas</option>
								<!--option value="IAV">Registro de insumos pecuarios</option-->
								<option value="IAF">Registro de insumos fertilizantes</option>
								<option value="IAPA">Registro de insumos para plantas de autoconsumo</option>
						</select>
				</div>
					
				<label id="lingredienteActivo">Ingrediente Activo</label> 					
				<div data-linea="2">	
						<textarea id="ingredienteActivo" name="ingredienteActivo" placeholder="Ej: Ingrediente activo..." rows="2"></textarea>
				</div>
				
				<label id="lingredienteQuimico">Ingrediente Quimico</label>
				<div data-linea="3">
						<textarea id="ingredienteQuimico" name="ingredienteQuimico" placeholder="Ej: Ingrediente químico..." rows="10"></textarea>
				</div>				
				
				<div id="dCas" data-linea="4">
					<label>Cas</label>
					<input id="cas" name="cas" />
				</div>
				
				<div id="dFormulaQuimica" data-linea="5">
					<label>Fórmula química</label>
					<input id="formulaQuimica" name="formulaQuimica" />
				</div>
				
				<div id="dGrupoQuimico" data-linea="6">
					<label>Grupo químico</label>
					<input id="grupoQuimico" name="grupoQuimico" />
				</div>
				
			</fieldset>
			
			<div>
				<button type="submit" class="guardar">Guardar</button>
			</div>
			
		</form>
</body>
<script>
	$('document').ready(function(){
		acciones();
		distribuirLineas();

		$("#lingredienteActivo").hide();
		$("#ingredienteActivo").hide();
		$("#lingredienteQuimico").hide();
		$("#ingredienteQuimico").hide();
		$("#dCas").hide();
		$("#dFormulaQuimica").hide();
		$("#dGrupoQuimico").hide();
		
	});
	
	$("#nuevoIngredienteActivo").submit(function(event){

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#area").val()==""){
			error = true;
			$("#area").addClass("alertaCombo");
		}

		if($.trim($("#ingredienteActivo").val())==""){
			error = true;
			$("#ingredienteActivo").addClass("alertaCombo");
		}

		if ($("#area").val()== 'IAP'){

			if($.trim($("#cas").val())==""){
				error = true;
				$("#cas").addClass("alertaCombo");
			}

			if($.trim($("#formulaQuimica").val())==""){
				error = true;
				$("#formulaQuimica").addClass("alertaCombo");
			}

			if($.trim($("#grupoQuimico").val())==""){
				error = true;
				$("#grupoQuimico").addClass("alertaCombo");
			}
		}

		if (!error){
			ejecutarJson($(this));
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}		
		
	});

	$('#area').change(function(){

		if($("#area").val()== 'IAV') {
			$("#lingredienteActivo").show();
			$("#ingredienteActivo").show();
			$("#lingredienteQuimico").hide();
			$("#ingredienteQuimico").hide();
			$("#dCas").hide();
			$("#dFormulaQuimica").hide();
			$("#dGrupoQuimico").hide();
		}else if ($("#area").val()== 'IAP'){
			$("#lingredienteActivo").show();
			$("#ingredienteActivo").show();
			$("#lingredienteQuimico").show();
			$("#ingredienteQuimico").show();
			$("#dCas").show();
			$("#dFormulaQuimica").show();
			$("#dGrupoQuimico").show();
		}else{
			$("#dCas").hide();
			$("#dFormulaQuimica").hide();
			$("#dGrupoQuimico").hide();
			$("#lingredienteActivo").show();
			$("#ingredienteActivo").show();
			$("#lingredienteQuimico").show();
			$("#ingredienteQuimico").show();
		}
	});
	
</script>
</html>
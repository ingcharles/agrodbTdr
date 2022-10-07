<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRequisitos.php';

$conexion = new Conexion();
$cr = new ControladorRequisitos();
$res = $cr->abrirIngredienteActivo($conexion, $_POST['id']);
$ingredienteActivo = pg_fetch_assoc($res);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>

<body>

<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<h1>Datos ingrediente activo</h1>
</header>

	<div id="estado"></div>
	
<form id="ingredienteActivoQuimico" data-rutaAplicacion="registroProducto" data-opcion="actualizarIngredienteActivo" data-accionEnExito="ACTUALIZAR">
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>

<fieldset>
		<legend>Ingrediente activo seleccionado</legend>
			<input type="hidden" name="id_ingrediente_activo" value="<?php echo $ingredienteActivo['id_ingrediente_activo'];?>" />
			
		<label>Ingrediente activo</label> 	
		<div data-linea="1">
			<textarea id="ingrediente_activo" name="ingrediente_activo" disabled="disabled" ><?php echo $ingredienteActivo['ingrediente_activo'];?></textarea>
		</div>
		
		<label id= lingredienteQuimico>Ingrediente químico</label>
		<div data-linea="2"> 
			<textarea id="ingrediente_quimico" name="ingrediente_quimico"  disabled="disabled" ><?php echo $ingredienteActivo['ingrediente_quimico'];?></textarea>
		</div>
		
		<div id="dCas" data-linea="4">
			<label>Cas</label>
			<input id="cas" name="cas" disabled="disabled" value="<?php echo $ingredienteActivo['cas'];?>"/>
		</div>
				
		<div id="dFormulaQuimica" data-linea="5">
			<label>Fórmula química</label>
			<input id="formulaQuimica" name="formulaQuimica" disabled="disabled" value="<?php echo $ingredienteActivo['formula_quimica'];?>"/>
		</div>
				
		<div id="dGrupoQuimico" data-linea="6">
			<label>Grupo químico</label>
			<input id="grupoQuimico" name="grupoQuimico" disabled="disabled" value="<?php echo $ingredienteActivo['grupo_quimico'];?>"/>
		</div>
		
</fieldset>	

</form>

</body>

<script type="text/javascript">
var area = <?php echo json_encode($ingredienteActivo['id_area']);?>;

$("#modificar").click(function(){
	$("input").removeAttr("disabled");
	$("select").removeAttr("disabled");
	$("textarea").removeAttr("disabled");
	$("#actualizar").removeAttr("disabled");
	$(this).attr("disabled","disabled");
});

$("#ingredienteActivoQuimico").submit(function(event){
	event.preventDefault();

	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#ingrediente_activo").val()==""){
		error = true;
		$("#ingrediente_activo").addClass("alertaCombo");
	}

	if (area == 'IAP'){

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

$('document').ready(function(){
	
	if(area == 'IAV'){
		$('#lingredienteQuimico').hide();
		$('#ingrediente_quimico').hide();
		$("#dCas").hide();
		$("#dFormulaQuimica").hide();
		$("#dGrupoQuimico").hide();
	}else if(area == 'IAF' || area == 'IAPA'){
		$("#dCas").hide();
		$("#dFormulaQuimica").hide();
		$("#dGrupoQuimico").hide();
	}

	distribuirLineas();
	
});

</script>
</html>

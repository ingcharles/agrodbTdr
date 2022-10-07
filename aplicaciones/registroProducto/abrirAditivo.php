<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();

$aditivo = pg_fetch_assoc($cc->abrirAditivo($conexion, $_POST['id']));

?>

<header>
	<h1>Uso</h1>
</header>

	<div id="estado"></div>
	
<form id="formulario" data-rutaAplicacion="registroProducto" data-opcion="actualizarAditivo" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" name="idAditivo" value="<?php echo $aditivo['id_aditivo_toxicologico'];?>" />
	
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>

    <fieldset>
		<legend>Aditivo importancia toxicológica</legend>
				
			<div data-linea="1">
				<label for="area">Área</label>
					<select id="area" name="area" disabled>
							<option value="">Seleccione....</option>
							<option value="IAP">Registro de insumos agrícolas</option>
							<option value="IAV">Registro de insumos pecuarios</option>
							<option value="IAF">Registro de insumos fertilizantes</option>
							<option value="IAPA">Registro de insumos para plantas de autoconsumo</option>
					</select>
			</div>
				
			<label>Nombre común: </label>
				<div data-linea="2">
				<textarea id="nombreComun" name="nombreComun" required="required" maxlength="256" disabled="disabled"> <?php echo $aditivo['nombre_comun'];?> </textarea>
			</div>
			
			<label>Nombre químico: </label>
				<div data-linea="3">
				<textarea id="nombreQuimico" name="nombreQuimico" required="required" maxlength="256" disabled="disabled"> <?php echo $aditivo['nombre_quimico'];?> </textarea>
			</div>
			
			<div data-linea="4">
				<label>Cas: </label>
				<input type="text" id="cas" name="cas" value="<?php echo $aditivo['cas'];?>" required="required" maxlength="256" disabled="disabled">
			</div>
			
			<div data-linea="5">
				<label>Fórmula química: </label>
				<input type="text" id="formulaQuimica" name="formulaQuimica" value="<?php echo $aditivo['formula_quimica'];?>" required="required" maxlength="256" disabled="disabled">
			</div>
		
			<div data-linea="6">
				<label>Grupo químico: </label>
				<input type="text" id="grupoQuimico" name="grupoQuimico" value="<?php echo $aditivo['grupo_quimico'];?>" required="required" maxlength="256" disabled="disabled">
			</div>    
    </fieldset>	
</form>

<script type="text/javascript">

    $('document').ready(function(){
    	cargarValorDefecto("area","<?php echo $aditivo['area'];?>");
        distribuirLineas();
    });	
    
    $("#modificar").click(function(){
    	$("select").removeAttr("disabled");
    	$("input").removeAttr("disabled");
    	$("textarea").removeAttr("disabled");
    	$("#actualizar").removeAttr("disabled");
    	$(this).attr('disabled','disabled');
    });
    
    $("#formulario").submit(function(event){
    
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
    
</script>
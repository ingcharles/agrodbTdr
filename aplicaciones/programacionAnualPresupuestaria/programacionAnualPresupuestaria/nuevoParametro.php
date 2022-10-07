<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	
	$fecha = getdate();
?>

<header>
	<h1>Parámetros</h1>
</header>
<form id="nuevoParametro" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="guardarNuevoParametro" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<div id="estado"></div>

	<fieldset>
		<legend>Parámetros Anuales</legend>

		<div data-linea="1">
			<label>Ejercicio:</label>
			<input type="text" id="ejercicio" name="ejercicio" maxlength="4" value="<?php echo $fecha['year'];?>" readonly="readonly"/>
		</div>
		<div data-linea="1">
		<label>Entidad:</label>
			<input type="text" id="entidad" name="entidad" maxlength="3" data-er="^[0-9]+$" />
		</div>
		
		<div data-linea="2">
			<label>Subprograma:</label>
			<input type="text" id="subprograma" name="subprograma" maxlength="2" data-er="^[0-9]+$" />
		</div>
		<div data-linea="2">
			<label>Renglón Aux:</label>
			<input type="text" id="renglonAux" name="renglonAux" maxlength="6" data-er="^[0-9]+$" />
		</div>
		
		<div data-linea="3">
			<label>Fuente:</label>
			<input type="text" id="fuente" name="fuente" maxlength="3" data-er="^[0-9]+$" />
		</div>
		<div data-linea="3">
			<label>Organismo:</label>
			<input type="text" id="organismo" name="organismo" maxlength="4" data-er="^[0-9]+$" />
		</div>
		
		<div data-linea="4">
			<label>Correlativo:</label>
			<input type="text" id="correlativo" name="correlativo" maxlength="4" data-er="^[0-9]+$" />
		</div>
		<div data-linea="4">
			<label>Obra:</label>
			<input type="text" id="obra" name="obra" maxlength="3" data-er="^[0-9]+$" />
		</div>
		
		<div data-linea="5">
			<label>Operación BID:</label>
			<input type="text" id="operacionBid" name="operacionBid" maxlength="2" data-er="^[A-Z]+$" />
		</div>
		<div data-linea="5">
			<label>Proyecto Bid:</label>
			<input type="text" id="proyectoBid" name="proyectoBid" maxlength="2" data-er="^[A-Z]+$" />
		</div>
		
		<div data-linea="6">
			<label>IVA Ejercicio:</label>
			<input type="text" id="iva" name="iva" maxlength="2" data-er="^[0-9]+$" />
		</div>
		<div data-linea="6">
			
		</div>
	</fieldset>

	<button type="submit" class="guardar">Guardar</button>

</form>
<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();
		construirValidador();
	});	

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCampos(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#entidad").val()) || !esCampoValido("#entidad")){
			error = true;
			$("#entidad").addClass("alertaCombo");
		}

		if(!$.trim($("#subprograma").val()) || !esCampoValido("#subprograma")){
			error = true;
			$("#subprograma").addClass("alertaCombo");
		}

		if(!$.trim($("#renglonAux").val()) || !esCampoValido("#renglonAux")){
			error = true;
			$("#renglonAux").addClass("alertaCombo");
		}

		if(!$.trim($("#fuente").val()) || !esCampoValido("#fuente")){
			error = true;
			$("#fuente").addClass("alertaCombo");
		}

		if(!$.trim($("#organismo").val()) || !esCampoValido("#organismo")){
			error = true;
			$("#organismo").addClass("alertaCombo");
		}

		if(!$.trim($("#correlativo").val()) || !esCampoValido("#correlativo")){
			error = true;
			$("#correlativo").addClass("alertaCombo");
		}

		if(!$.trim($("#obra").val()) || !esCampoValido("#obra")){
			error = true;
			$("#obra").addClass("alertaCombo");
		}

		if(!$.trim($("#operacionBid").val()) || !esCampoValido("#operacionBid")){
			error = true;
			$("#operacionBid").addClass("alertaCombo");
		}

		if(!$.trim($("#proyectoBid").val()) || !esCampoValido("#proyectoBid")){
			error = true;
			$("#proyectoBid").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
			
			if($("#estado").html()=='Los parámetros se han guardado correctamente'){
				$("#_actualizar").click();
			}
		}
	}

	$("#nuevoParametro").submit(function(event){
		 event.preventDefault();
		 chequearCampos(this);
	});
</script>
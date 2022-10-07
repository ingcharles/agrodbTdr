<?php
session_start();

?>

<form id='abrirActivarPorcino' data-rutaAplicacion='catastroProducto' data-accionEnExito="ACTUALIZAR"   > <!-- data-opcion='guardarNuevaOperacion'  -->
	<input type="hidden" id="opcion" name="opcion" />
	<input type="hidden" id="identificadorProductoActivar" name="identificadorProductoActivar" />	
	<div id="estado"></div>
	
	<fieldset>
		<legend>Producto a movilizar</legend>
		
			<div data-linea="1">			
				<label>Ingrese identificador: </label> 
				<input type="text" id="identificadorProducto" name="identificadorProducto" value="" />		
			</div>

	</fieldset>
	<fieldset>
		<legend>Datos de origen</legend>
		
		<div id="dOperaciones" style="width: 100%">
		</div>
		<hr/>
		<div data-linea="2">			
			<label>Motivo: </label> 
			<select id="motivoActivacion" name="motivoActivacion">
				<option value="">Seleccione...</option>
				<option value="operacionComerciante">Operación Comerciante</option>
				<option value="salidaMatadero">Salida a Matadero</option>
            </select>	
		</div>
		<div data-linea="3">
			<label>Observación: </label> 
			<input type="text" id="observacionActivacion" name="observacionActivacion" value="" />		
		</div>
	</fieldset>
	<button type="submit" id="btnGuardar"  name="btnGuardar" class="guardar" >Activar</button>
</form>

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();	
	});

	$("#identificadorProducto").change(function(event){
		event.stopImmediatePropagation();
		$("#identificadorProductoActivar").val($("#identificadorProducto").val());
 		$("#abrirActivarPorcino").attr('data-destino','dOperaciones');
 		$("#abrirActivarPorcino").attr('data-opcion', 'accionesCatastro');
 		$("#opcion").val('buscarSitioCatastroIdentificador'); 		
		abrir($("#abrirActivarPorcino"),event,false); 
		$("#identificadorProducto").val("");
	 });

	$("#abrirActivarPorcino").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($('#tablaProductoMovilizar tr').length == 0) {	
			error = true;
			$("#identificadorProducto").addClass("alertaCombo");	
		}
		
		if ($("#motivoActivacion").val()==""){
		   error = true;
	       $("#motivoActivacion").addClass("alertaCombo");
		}
		
    	if ($("#observacionActivacion").val()==""){
		   error = true;
	       $("#observacionActivacion").addClass("alertaCombo");
		}

		if (!error){
			$('#abrirActivarPorcino').attr('data-opcion','guardarActivarPorcinoTemporal');
			ejecutarJson($(this));
		}else{
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}	
	});

</script>
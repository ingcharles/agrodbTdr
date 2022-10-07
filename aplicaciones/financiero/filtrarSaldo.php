<?php
	session_start();
?>

<header>
	
	<nav>
	
	<form id="listarSaldo" data-rutaAplicacion="financiero" data-opcion="listaSaldo" data-destino="tabla">
			
		<table class="filtro">
		
			<tr>
			<th>Cliente</th>
				<td>
					<select id="tipoCliente" name="tipoCliente" style="width: 100%;">
						<option value = ''>Seleccione....</option>
						<option value="ruc">Ruc</option>
						<option value="cedula">Cédula</option>
						<option value="pasaporte">Pasaporte</option>
						<option value="razonSocial">Razón Social</option>
					</select>	
						
				</td>
			
				<td colspan="2">
					<input id="identificador" name="identificador" type="text" style="width: 100%;"/>
				</td>		
			</tr>
			
			<tr>
				<th>Fecha inicio</th>
					<td>
						<input id="fechaInicio" name="fechaInicio" type="text" />
					</td>
					
				<th>Fecha fin</th>
				
					<td>
						<input id="fechaFin" name="fechaFin" type="text" />
					</td>	
			</tr>
								
			<tr>
				<th>Tipo saldo</th>
				<td>
					<select id="tipoSaldo" name="tipoSaldo" style="width: 100%;">
						<option value = ''>Seleccione....</option>
						<option value="saldoVue">Saldo VUE</option>
					</select>	
				</td>	
				<td colspan="3">
					<button>Filtrar lista</button>
				</td>
			</tr>

		</table>
		
	</form>
		
	</nav>

</header>

<div id="tabla"></div>

<script type="text/javascript">

$('document').ready(function(){
	
	$("#fechaInicio").datepicker({
	    changeMonth: true,
	    changeYear: true,
	    onSelect: function(dateText, inst) {
   		 $('#fechaFin').datepicker('option', 'minDate', $("#fechaInicio" ).val()); 
       } 
	});

	$("#fechaFin").datepicker({
	    changeMonth: true,
	    changeYear: true
	});
	
});

$("#tipoCliente").change(function(event){

	if($('#tipoCliente').val() == 'ruc'){
		$("#identificador").attr("maxlength","13");
		$("#identificador").attr("data-er","^[0-9]+$");
	}

	if($('#tipoCliente').val() == 'cedula'){
		$("#identificador").attr("maxlength","10");
		$("#identificador").attr("data-er","^[0-9]+$");
	}

	if($('#tipoCliente').val() == 'pasaporte'){
		$("#identificador").removeAttr("maxlength");
		$("#identificador").attr("data-er","^[0-9a-zA-Z]+$");
	}

	if($('#tipoCliente').val() == 'razonSocial'){
		$("#identificador").removeAttr("maxlength");
		$("#identificador").attr("data-er","^[A-Za-z0-9.- ]+$");		
	}
	
});

$("#listarSaldo").submit(function(event){
	event.preventDefault();
	
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if(!$.trim($("#tipoCliente").val())){
		error = true;
		$("#tipoCliente").addClass("alertaCombo");
	}

	if(!$.trim($("#identificador").val()) || !esCampoValido("#identificador")){
		error = true;
		$("#identificador").addClass("alertaCombo");
	}

	if($("#tipoCliente").val() == "ruc" || $("#tipoCliente").val() == "cedula"){
		if($("#identificador").val().length != $("#identificador").attr("maxlength")){
			error = true;
			$("#identificador").addClass("alertaCombo");
		}		
	}
	
	if(!$.trim($("#fechaInicio").val())){
		error = true;
		$("#fechaInicio").addClass("alertaCombo");
	}

	if(!$.trim($("#fechaFin").val())){
		error = true;		
		$("#fechaFin").addClass("alertaCombo");
	}

	if(!$.trim($("#tipoSaldo").val())){
		error = true;		
		$("#tipoSaldo").addClass("alertaCombo");
	}
		
	if(!error){
		abrir($(this),event,false);
	}
	
});

function esCampoValido(elemento){
	var patron = new RegExp($(elemento).attr("data-er"),"g");
	return patron.test($(elemento).val());
}


</script>	

	
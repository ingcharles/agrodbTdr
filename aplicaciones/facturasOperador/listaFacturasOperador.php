<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCertificados.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/GoogleAnalitica.php';
	
	$conexion = new Conexion();
	$ca = new ControladorAplicaciones();
	$cc = new ControladorCertificados();

?>
<header>
	<h1>Lista de facturas</h1>
	<nav>
		<form id="listaFacturasOperador" data-rutaAplicacion="facturasOperador">
		<table class="filtro" style="width:500px">
		
			<tr id='idTipoSolicitud'>
				<th>Solicitud</th>			
				<td colspan="3">
				<select id="tipoSolicitud" name="tipoSolicitud" style="width: 100%;">
					<option value="" >Seleccione....</option>
					<option value="Importación" >Importación</option>
					<option value="Fitosanitario" >Fitosanitario</option>
					<option value="Otros" >Otros</option>
				</select>	
				</td>
			</tr>
			<tr id='idFactura'>
				<th id="idFactura">Número factura</th>
				<td>
					<input id="numeroFactura" name="numeroFactura" type="text" maxlength="9" style="width: 100%"/>
				</td>
				<th id="ordenVue">Orden GUIA</th>
				<td>
					<input id="numeroOrdenGuia" name="numeroOrdenGuia" type="text" maxlength="21" style="width: 100%"/>
				</td>
			</tr>
			<tr id='idNumeroSolicitud'>
				<th id="idSolicitud">Número solicitud</th>
				<td>
					<input id="numeroSolicitud" name="numeroSolicitud" type="text" maxlength="21" style="width: 100%"/>
				</td>
				<th id="ordenVue">Orden VUE</th>
				<td>
					<input id="numeroOrdenVue" name="numeroOrdenVue" type="text" maxlength="21" style="width: 100%"/>
				</td>
			</tr>
			<tr id='idFecha'>
				<th>Fecha inicio</th>
				<td>
					<input id="fechaInicio" name="fechaInicio" type="text" readonly="readonly"/>
				</td>
				<th>Fecha fin</th>
				<td>
					<input id="fechaFin" name="fechaFin" type="text" readonly="readonly"/>
				</td>	
			</tr>	
			<tr>	
				<td colspan="5">
					<button>Filtrar lista</button>
				</td>
			</tr>
		</table>
		<input type="hidden" name="opcion" value= "<?php echo $_POST["opcion"];?>">
		</form>
	</nav>
</header>
<div id="tabla"></div>
<script>

$('document').ready(function(){

	$("#listadoItems").addClass("lista");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
	
	$("#fechaInicio").datepicker({
	    changeMonth: true,
	    changeYear: true,
	    onSelect: function(dateText, inst) {
	    	var fecha= $('#fechaInicio').datepicker('getDate');
	    	fecha.setDate(fecha.getDate()+180);	    
   			$('#fechaFin').datepicker('option', 'minDate', $("#fechaInicio" ).val());
   			$('#fechaFin').datepicker('option','maxDate', fecha); 
       }	 
	});

	$("#fechaFin").datepicker({
	    changeMonth: true,
	    changeYear: true
	});

	$("#idFactura").hide();
	$("#idNumeroSolicitud").hide();
	
});


$("#tipoSolicitud").change(function(e){
	
	if($("#tipoSolicitud option:selected").val()=="Importación" || $("#tipoSolicitud option:selected").val()=="Fitosanitario"){
		$("#idNumeroSolicitud").show();
		$("#idFactura").hide();
		$("#numeroFactura").val("");
		$("#numeroOrdenGuia").val("");		
	}else if ($("#tipoSolicitud option:selected").val()=="Otros"){
		$("#idFactura").show();
		$("#idNumeroSolicitud").hide();
		$("#numeroSolicitud").val("");
		$("#numeroOrdenVue").val("");
	}else{
		$("#idFactura").hide();
		$("#idNumeroSolicitud").hide();
		$("#numeroFactura").val("");
		$("#numeroOrdenGuia").val("");
		$("#numeroSolicitud").val("");
		$("#numeroOrdenGuia").val("");	
	}
	
});


$("#listaFacturasOperador").submit(function(e){

	e.preventDefault();

	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#tipoSolicitud").val() == ""){
		error = true;
		$("#tipoSolicitud").addClass("alertaCombo");
		$("#fechaInicio").addClass("alertaCombo");
		$("#fechaFin").addClass("alertaCombo");
	}

	
	if(!$.trim($("#numeroFactura").val()) && !$.trim($("#fechaInicio").val()) && !$.trim($("#fechaFin").val()) && !$.trim($("#numeroOrdenVue").val()) && !$.trim($("#numeroOrdenGuia").val())){
		error = true;
		$("#numeroFactura").addClass("alertaCombo");
		$("#numeroOrdenVue").addClass("alertaCombo");
		$("#numeroOrdenGuia").addClass("alertaCombo");
		$("#fechaInicio").addClass("alertaCombo");
		$("#fechaFin").addClass("alertaCombo");
	}

	if($.trim($("#fechaInicio").val())){
		if(!$.trim($("#fechaFin").val())){
			error = true;
			$("#fechaFin").addClass("alertaCombo");
		}
	}

	if($.trim($("#fechaFin").val())){
		if(!$.trim($("#fechaInicio").val())){
			error = true;
			$("#fechaInicio").addClass("alertaCombo");
		}
	}

	if(!error){
		$("#listaFacturasOperador").attr('data-opcion', 'listaFacturasOperadorFiltrado');
		$("#listaFacturasOperador").attr('data-destino', 'tabla');
		abrir($(this),e,false);
	}        	
});

</script>	
	
<?php
session_start();
?>
<header>
	<h1>Reportes</h1>
	<nav>
		<form id="reporteInspeccionesProtocolo" data-rutaAplicacion="inspeccionesDeProtocolo" action="aplicaciones/inspeccionesDeProtocolo/reporteImprimirInspeccionesProtocolo.php" target="_blank" method="post" >
			<table class="filtro" >
				<tr>
					<th colspan="4">Filtro para el reporte de inspección de protocolos</th>
				</tr>
								
				<tr>
					<td align="left">Estado:</td>
					<td colspan="3" id="resultadoCantones">
						<select id="bEstado" name="bEstado" style="width:270px" >
						<option value="">Seleccione...</option>
						<option value="aprobado">Aprobado</option>
						<option value="desaprobado">Desaprobado</option>
						<option value="implementacion">Implementación</option>
						</select>
					</td>		
				</tr>
				
				<tr>
					<td align="left">Fecha Inicio:</td>
					<td ><input id="bFechaInicio" type="text" name="bFechaInicio" readonly="readonly" style="width:75px"></td>
					<td align="left">Fecha Fin:</td>
					<td><input id="bFechaFin" type="text" name="bFechaFin" readonly="readonly"  style="width:75px"></td>					
				</tr>
				
				<tr>
					<td colspan="4" style='text-align:center'><button class="guardar">Generar reporte</button></td>
				</tr>
				
				<tr>
					<td colspan="4"  align="center" id="estadoError" ></td>
				</tr>
			</table>
		</form>
	</nav>
</header>

<script>
	$(document).ready(function(event){

		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
			
		var myDate = new Date();
		days= ('0' + myDate.getDate()).slice(-2);
		months= ('0' + (myDate.getMonth())).slice(-2);
		monthsI= ('0' + (myDate.getMonth()-3)).slice(-2);
		years=myDate.getFullYear();
		
		$("#bFechaInicio").val($.datepicker.formatDate('dd/mm/yy', new Date(years, monthsI,days)));
		$("#bFechaFin").val($.datepicker.formatDate('dd/mm/yy', new Date(years, months,days)));
		     
		$("#bFechaInicio").datepicker({ 
			changeMonth: true,
		 	changeYear: true,
		    onSelect: function(selectedDate){ 
		    	var year = selectedDate.substr(6,4);
				var month= ('0' + (parseInt(selectedDate.substr(3,2))+2)).slice(-2);
		        var days = selectedDate.substr(0,2);
		        $("#bFechaFin").datepicker("option", "minDate", selectedDate);
		        $("#bFechaFin").datepicker("option", "maxDate", new Date(year, month,days));
		        $("#bFechaFin").datepicker("setDate", new Date(year, month,days)); 
		    }
		});
		
		$("#bFechaFin").datepicker({
			changeMonth: true,
		    changeYear: true,
		    maxDate:"0"
		});	
		
	});
	
	$("#reporteInspeccionesProtocolo").submit(function(event){
			
		$(".alertaCombo").removeClass("alertaCombo");		 
		$("#estadoError").html("");
		var error = false;

		if($("#bEstado").val() == ""){
			error = true;
			$("#bEstado").addClass("alertaCombo");
		}
		
		if($("#bFechaInicio").val() == ""){
			error = true;
			$("#bFechaInicio").addClass("alertaCombo");
		}

		if($("#bFechaFin").val() == ""){
			error = true;
			$("#bFechaFin").addClass("alertaCombo");
		}

		if (error){
			$("#estadoError").html("Ingresar información en campos obligatorios.").addClass('alerta');
			event.preventDefault();
		}else{
			ejecutarJson(form, null);
		}
	});

	</script>
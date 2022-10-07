<?php 
session_start();


?>
<header>
<h1>Reporte Solicitudes Etiquetas</h1>
	<nav>
		<form id="reporteEtiquetas" data-rutaAplicacion='etiquetas' action="aplicaciones/etiquetas/reporteImprimirEtiquetas.php" target="_self" method="post">
			<input type="hidden" name="opcion" id="opcion" value="0" />
			<table class="filtro" style='width:100%;'>
				<tbody>
					<tr>
						<th colspan="4">Reporte Solicitudes Etiquetas</th>					
					</tr>
					<tr>
						<td>Identificación Operador:</td>
						<td colspan="3"><input id="identificacionOperador" type="text" name="identificacionOperador" maxlength="13" style='width:99%;' ></td>
					</tr>
					<tr>
						<td>Estado:</td>
						<td colspan="3">
							<select id="estadoH" name="estadoH" style='width:99%;'>
								<option value="0">Seleccione...</option>
								<option value="todos">Todos</option>
								<option value="activo">Activo</option>
								<option value="anulado">Anulado</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Fecha Inicio:</td>
						<td><input id="fechaInicio" type="text" name="fechaInicio" readonly="readonly" style='width:98%;'></td>
						<td>Fecha Fin:</td>
						<td><input id="fechaFin" type="text" name="fechaFin" readonly="readonly" style='width:98%;' ></td>					
					</tr>
					<tr>
						<td colspan="4" style='text-align:center'><button type="submit" class="guardar" >Generar Reporte</button></td>
					</tr>
					<tr>
						<td colspan="4"  align="center" id="estadoError" ></td>
					</tr>
				</tbody>
			</table>
		</form>	
	</nav>
</header>
<script>	
	$(document).ready(function(){
		distribuirLineas();
		$("#fechaInicio").datepicker({
		      changeMonth: true,
		      changeYear: true,
		      maxDate: "0"
		});

		$("#fechaFin").datepicker({
		      changeMonth: true,
		      changeYear: true,
		      maxDate: "0"
		});

		var myDate = new Date();
		 days= ('0' + myDate.getDate()).slice(-2);
		 months= ('0' + (myDate.getMonth()+1)).slice(-2);;
		 years=myDate.getFullYear();
		 $("#fechaInicio").val(days+"/"+months+"/"+years);
		 $("#fechaFin").val(days+"/"+months+"/"+years);
	});

	

	$("#reporteEtiquetas").submit(function(event){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;


		if($("#identificacionOperador").val()==""){
			error = true;
			$("#identificacionOperador").addClass("alertaCombo");
		}
		
		if($("#estadoH").val()==0){
			error = true;
			$("#estadoH").addClass("alertaCombo");
		}
		
		if (error){
			$("#estadoError").html("Por favor seleccione los campos marcados para obtener datos.").addClass('alerta');
			event.preventDefault();
		}else{ 
			$("#estadoError").html("");            
			ejecutarJson(form);      	
		}
	});
</script>
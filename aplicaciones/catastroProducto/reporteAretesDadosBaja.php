<?php 
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastroProducto.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';

session_start();
$cc = new ControladorCatalogos();
$conexion = new Conexion();

?>

<header>
	<h1>Reporte Aretes Dados de Baja</h1>
	<nav>
		<form id="nuevoFiltroAretesDadosBaja" data-rutaAplicacion="catastroProducto" action="aplicaciones/catastroProducto/reporteImprimirAretesDadosBaja.php" target="_self" method="post" >
			<table class="filtro">	
			<tr><th colspan="4">Filtros para reporte de aretes dados de baja</th></tr>		
				<tr>
					<td align="left">* Provincia:</td>
					<td colspan="3">
					<select id="provincia" name="provincia" >
						<option value="">Seleccione...</option>
						<?php 
							$qProvincias = $cc->listarLocalizacion($conexion, "PROVINCIAS");
							while($fila = pg_fetch_assoc($qProvincias)){
								echo '<option value="' . $fila['nombre'] . '">' . $fila['nombre'] . '</option>';
							}
						?>		
					</select>
					</td>
				</tr>
				<tr>
					<td align="left">* Fecha inicio:</td>
					<td><input id="fechaInicio" type="text" name="fechaInicio" readonly="readonly" style="width:85px"></td>
					<td align="left">* Fecha fin:</td>
					<td><input id="fechaFin" type="text" name="fechaFin" readonly="readonly"  style="width:85px"></td>
				</tr>
				<tr>
					<td colspan="4" style='text-align:center'><button class="guardar" >Generar Reporte</button></td>
				</tr>
				<tr>
					<td colspan="4" style='text-align:center' id="estadoFiltro"></td>
				</tr>
			</table>
		</form>
	</nav>
</header>

<script>
	
	$(document).ready(function(event){

		var myDate = new Date();
		days= ('0' + myDate.getDate()).slice(-2);
		months= ('0' + (myDate.getMonth())).slice(-2);
		monthsI= ('0' + (myDate.getMonth()-3)).slice(-2);
		years=myDate.getFullYear();
		
		$("#fechaInicio").val($.datepicker.formatDate('dd/mm/yy', new Date(years, monthsI,days)));
		$("#fechaFin").val($.datepicker.formatDate('dd/mm/yy', new Date(years, months,days)));
		     
		$("#fechaInicio").datepicker({ 
			changeMonth: true,
		 	changeYear: true,
		    onSelect: function(selectedDate){
		    	var year = selectedDate.substr(6,4);
				var month= ('0' + (parseInt(selectedDate.substr(3,2))+2)).slice(-2);
		        var days = selectedDate.substr(0,2);
		        $("#fechaFin").datepicker("option", "minDate", selectedDate);
		        $("#fechaFin").datepicker("option", "maxDate", new Date(year, month,days));
		        $("#fechaFin").datepicker("setDate", new Date(year, month,days)); 
		    }
		});
		
		$("#fechaFin").datepicker({
			changeMonth: true,
		    changeYear: true,
		    maxDate:"0"
		});	
		
	});
	
	$("#nuevoFiltroAretesDadosBaja").submit(function(event){

		if ($("#provincia").val()==""){
		   error = true;
	       $("#provincia").addClass("alertaCombo");
		}

		if ($("#fechaInicio").val()==""){
		   error = true;
	       $("#fechaInicio").addClass("alertaCombo");
		}
		if ($("#fechaFin").val()==""){
		   error = true;
	       $("#fechaFin").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Ingresar información en campos obligatorios.").addClass('alerta');		
			event.preventDefault();
		}else{ 
			ejecutarJson(form);    
		}
	});
</script>
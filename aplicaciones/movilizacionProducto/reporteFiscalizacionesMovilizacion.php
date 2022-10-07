<?php 
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();

?>
<header>
	<h1>Reporte de fiscalizaciones de certificados de movilización</h1>
	<nav>
		<form id="nuevoMovilizacionProductos" data-rutaAplicacion="movilizacionProducto" action="aplicaciones/movilizacionProducto/reporteImprimirFiscalizacionesMovilizacion.php" target="_blank" method="post" >
		<input type="hidden" id="opcion" name="opcion" value="">

			
			<table class="filtro">
				<tr>
					<th colspan="4">Filtros para el reporte de fiscalizaciones de certificados de movilización</th>
				</tr>
				
				<tr>
					<td align="left">Provincia:</td>
					<td colspan="3">
						<select id="provincia" name="provincia"  style="width:270px">
						<option value="0">Seleccione...</option>
						<option value="todos">Todos</option>
						<?php 
							$qProvincias = $cc->listarLocalizacion($conexion, "PROVINCIAS");
							while($fila = pg_fetch_assoc($qProvincias)){
								echo '<option value="' . $fila['id_localizacion'] . '">' . $fila['nombre'] . '</option>';
							}
						?>		
						</select>
					</td>		
				</tr>
				
				<tr>
					<td align="left">Cantón:</td>
					<td colspan="3" id="resultadoCantones">
						<select id="canton" name="canton" style="width:270px" >
						<option value="0">Seleccione...</option>
						</select>
					</td>		
				</tr>
				
				<tr>
					<td align="left">Parroquia:</td>
					<td colspan="3" id="resultadoParroquias">
						<select id="parroquia" name="parroquia" style="width:270px" >
						<option value="0">Seleccione...</option>
						</select>
					</td>		
				</tr>
				
				<tr>
					<td align="left">Resultado Fiscalización:</td>
					<td colspan="3">
						<select id="resultado" name="resultado" style="width:270px" >
						<option value="">Seleccione...</option>
						<option value="todos">Todos</option>
						<option value="positivo">Positivo</option>
						<option value="negativo">Negativo</option>

						</select>
					</td>		
				</tr>
				
				<tr>
					<td align="left">Fecha Inicio:</td>
					<td ><input id="fechaInicio" type="text" name="fechaInicio" readonly="readonly" style="width:70px"></td>
					<td align="left">Fecha Fin:</td>
					<td><input id="fechaFin" type="text" name="fechaFin" readonly="readonly"  style="width:70px"></td>					
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

	$("#nuevoMovilizacionProductos").submit(function(event){
			
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
	
		if($("#provincia").val()==0){
			error = true;
			$("#provincia").addClass("alertaCombo");
		}

		if($("#provincia").val()!='todos'){
			if($("#canton").val()==0 || $("#canton").val()==null){
				error = true;
				$("#canton").addClass("alertaCombo");
			}
			
			if($("#canton").val()!='todos'){
				if($("#parroquia").val()==0 || $("#parroquia").val()==null){
					error = true;
					$("#parroquia").addClass("alertaCombo");
				}
			}
		}
		
		if($("#resultado").val()==0){
			error = true;
			$("#resultado").addClass("alertaCombo");
		}
		
		if (error){
			$("#estadoError").html("Ingresar información en campos obligatorios.").addClass('alerta');
			event.preventDefault();
		}else{ 
			ejecutarJson(form);    
		}
	});

	$("#provincia").change(function(event){
		if($("#provincia").val()!=0){
			if($("#provincia").val()!='todos'){
				$('#nuevoMovilizacionProductos').attr('data-destino','resultadoCantones');
				$('#nuevoMovilizacionProductos').attr('data-opcion','accionesMovilizacionProducto');
			    $('#opcion').val('listaCantones');		
				abrir($("#nuevoMovilizacionProductos"),event,false); 
			}else{
				$("#canton").val(0);
				$("#canton").attr("disabled",true);
				$("#parroquia").val(0);
				$("#parroquia").attr("disabled",true);
				$("#canton").removeClass("alertaCombo");
				$("#parroquia").removeClass("alertaCombo");
			}	
		}
	 });
	 
</script>
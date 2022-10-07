<?php 
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$identificadorUsuario=$_SESSION['usuario'];

?>
<header>
	<h1>Reporte de Catastro de Sanidad Animal - Porcinos</h1>
	<nav>
		<form id="nuevoFiltroRegistroCatastro" data-rutaAplicacion="catastroProducto" action="aplicaciones/catastroProducto/reporteImprimirRegistroCatastro.php" target="_blank" method="post" >
		<input type="hidden" id="opcion" name="opcion" value="">

			<table class="filtro" >
				<tr>
					<th colspan="4">Filtros para el reporte de catastro en Sanidad Animal - Porcinos</th>
				</tr>
				<tr>
					<td align="left">Provincia:</td>
					<td colspan="3">
						<select id="provincia" name="provincia"  style="width:250px">
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
						<select id="canton" name="canton" style="width:250px" >
						<option value="0">Seleccione...</option>
						</select>
					</td>		
				</tr>
				
				<tr>
					<td align="left">Parroquia:</td>
					<td colspan="3" id="resultadoParroquias">
						<select id="parroquia" name="parroquia" style="width:250px" >
						<option value="0">Seleccione...</option>
						</select>
					</td>		
				</tr>				
				<tr>
					<td align="left">Operación:</td>
					<td colspan="3">
						<select id="operacionB" name="operacionB" style="width:250px" >
						<option value="">Seleccione...</option>		
						<option value="todos">Todos</option>							
                            <?php 
                            	$qOperaciones=$cc->listarOperaciones($conexion, 'SA');
                            	while($fila = pg_fetch_assoc($qOperaciones)){
                            		echo '<option value="' . $fila['id_tipo_operacion'] . '">' . $fila['nombre'] . '</option>';
                            	}
                            ?>
						</select>
					</td>		
				</tr>								
				<tr>
						<td align="left">Fecha Inicio:</td>
						<td ><input id="fechaInicio" type="text" name="fechaInicio" readonly="readonly" style="width:85px"></td>
						<td align="left">Fecha Fin:</td>
						<td><input id="fechaFin" type="text" name="fechaFin" readonly="readonly"  style="width:85px"></td>					
				</tr>
				
				<tr>
					<td colspan="4" style='text-align:center'><button  class="guardar" >Generar Reporte</button></td>
				</tr>
				<tr>
					<td colspan="4" style='text-align:center' id="estado"></td>
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


		$("#nuevoFiltroRegistroCatastro").submit(function(event){
			
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
		
		if($("#operacionB").val()==0){
			error = true;
			$("#operacionB").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Ingresar información en campos obligatorios.").addClass('alerta');		
			event.preventDefault();
		}else{ 
			ejecutarJson(form);    
		}
	});

	$("#provincia").change(function(event){
		if($("#provincia").val()!=0){
			if($("#provincia").val()!='todos'){
				$('#nuevoFiltroRegistroCatastro').attr('data-destino','resultadoCantones');
				$('#nuevoFiltroRegistroCatastro').attr('data-opcion','accionesCatastro');
			    $('#opcion').val('listaCantones');		
				abrir($("#nuevoFiltroRegistroCatastro"),event,false); 
			}else{
				$("#canton").val(0);
				$("#canton").attr("disabled",true);
				$("#parroquia").val(0);
				$("#parroquia").attr("disabled",true);
			}	
		}
	 });

</script>
<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$va = new ControladorVacunacionAnimal();
$cc = new ControladorCatalogos();

?>
<header>
	<nav>
		<form  id="filtrarVacunacionAnimal" data-rutaAplicacion='vacunacionAnimal' action="aplicaciones/vacunacionAnimal/reporteImprimirCatastros.php" target="_self" method="post">
			<input type="hidden" name="opcion" id='opcion' value="0" />
			<table class="filtro" >
				<tbody>
					<tr>
						<th colspan="4">Reporte registro catastros</th>					
					</tr>
					<tr>
						<th>Provincia</th>
						<th colspan="3">
							<select id="provincia" name="provincia" style='width: 425px;'>
								<option value="" >Seleccione...</option>
								<?php 
									$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
									foreach ($provincias as $provincia){
										echo '<option value="' . $provincia['nombre'] . '">' . $provincia['nombre'] . '</option>';
									}
								?>
							</select> 			
						</th>
					</tr>
					<tr>
						<th >Sitio</th>
						<th colspan="3">
						<div id="resultadoSitiosProvincia" >
							<select id="sitios" name="sitios" style='width: 425px;'>
								<option value="0">Seleccione...</option>
				   			 </select>	
				   		</div>
				  	</tr>
					<tr>
						<th>Fecha inicio</th>
						<th><input id="fechaInicio" type="text" name="fechaInicio"></th>
						<th>Fecha fin</th>
						<th><input id="fechaFin" type="text" name="fechaFin"></th>					
					</tr>
					<tr>
						<td colspan="4" style='text-align:center'> 
							<button type="submit" class="guardar">Generar reporte Excel</button>
						</td>
					</tr>
					<tr>
						<td colspan="4" id="estado" align="center"></td>
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
		      changeYear: true
		});
		
		$("#fechaFin").datepicker({
		      changeMonth: true,
		      changeYear: true
		});

		fecha = fechaActual();
		$("#fechaInicio").val(fecha);
		$("#fechaFin").val(fecha);
	
	});

	 $("#provincia").change(function(event){ 
		$('#filtrarVacunacionAnimal').attr('data-opcion','accionesCatastroAnimal');
		$('#filtrarVacunacionAnimal').attr('data-destino','resultadoSitiosProvincia');
		$('#opcion').val(5);		     	
		abrir($("#filtrarVacunacionAnimal"),event,false);
	});
		 
	function fechaActual() {
	  	var date = new Date();
	  	var year = date.getFullYear();
	 	var month = (1 + date.getMonth()).toString();
	 	month = month.length > 1 ? month : '0' + month;
	  	var day = date.getDate().toString();
	  	day = day.length > 1 ? day : '0' + day;
	 	return  day + '/' + month + '/' +  year;
	}	

	$("#filtrarVacunacionAnimal").submit(function(event){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#provincia").val()==0){
			error = true;
			$("#provincia").addClass("alertaCombo");
		}
		
		if($("#sitio").val()==0 || $("#sitio").val()==null){
			error = true;
			$("#sitio").addClass("alertaCombo");
			$("#sitios").addClass("alertaCombo");
		}
		
		if (error == true){
			$("#estado").html("Por favor llene todos los campos para obtener datos.").addClass('alerta');
			event.preventDefault();
		}else{                   
			$("#estado").html("").removeClass('alerta');	
			ejecutarJson(form); 		      	
		}
	});
</script>
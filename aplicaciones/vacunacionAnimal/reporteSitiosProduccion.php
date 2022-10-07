<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();

$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');

?>
<header>
		<nav>
			<form  id="filtrarSitioProduccion" data-rutaAplicacion='vacunacionAnimal' action="aplicaciones/vacunacionAnimal/reporteImprimirSitiosProduccion.php" target="_self" method="post">
			<input  type="hidden" id="nombreParroquia" name="nombreParroquia" value="0" >
			<input  type="hidden" id="nombreCanton" name="nombreCanton" value="0" >
			<input  type="hidden" id="nombreProvincia" name="nombreProvincia" value="0" >
			<table class="filtro" >
				<tbody>
					<tr>
						<th colspan="4">Reporte sitios</th>					
					</tr>
					<tr>
						<th>Provincia</th>
						<th colspan="3"  width="413px;">
							<select id="provincia" name="provincia" style='width: 420px;'>
								<option value="">Seleccione...</option>
								<?php 
									$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
									foreach ($provincias as $provincia){
										echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
									}
								?>
							</select>
						</th>
					</tr>
					
					<tr>
						<th>Cantón</th>		
						<th colspan="3">
							<select id="canton" name="canton" disabled="disabled" style='width: 420px;'>
								<option value="">Seleccione...</option>
							</select>
						</th>
					</tr>
					
					<tr>
						<th >Parroquia</th>
						<th colspan="3">
							<select id="parroquia" name="parroquia" disabled="disabled" style='width: 420px;'>
								<option value="">Seleccione...</option>
							</select>
						</th>
					</tr>
					
					<tr>
						<th>Fecha inicio</th>
						<th ><input id="fechaInicio" type="text" name="fechaInicio"></th>
						<th >Fecha fin</th>
						<th ><input id="fechaFin" type="text" name="fechaFin"></th>					
					</tr> 
					<tr>
						<td colspan="4" style='text-align:center'> 
							<button type="submit" class="guardar" >Generar reporte Excel</button>
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

	function fechaActual() {
	  	var date = new Date();
	  	var year = date.getFullYear();
	 	var month = (1 + date.getMonth()).toString();
	 	month = month.length > 1 ? month : '0' + month;
	  	var day = date.getDate().toString();
	  	day = day.length > 1 ? day : '0' + day;
	 	return  day + '/' + month + '/' +  year;
	}	

	$("#filtrarSitioProduccion").submit(function(event){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
	
		if($("#provincia").val()==0 || $("#provincia").val()==""){
			error = true;
			$("#provincia").addClass("alertaCombo");
		}
		
		if($("#canton").val()==0 || $("#canton").val()==""){
			error = true;
			$("#canton").addClass("alertaCombo");
		}
		
		if($("#parroquia").val()==0 || $("#parroquia").val()==""){
			error = true;
			$("#parroquia").addClass("alertaCombo");
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
<script type="text/javascript">
var array_canton= <?php echo json_encode($cantones); ?>;
var array_parroquia= <?php echo json_encode($parroquias); ?>;

	$("#provincia").change(function(event){
		scanton ='0';
		scanton = '<option value="">Seleccione...</option>';
	    for(var i=0;i<array_canton.length;i++){
		    if ($("#provincia").val()==array_canton[i]['padre']){
		    	scanton += '<option data-latitud="'+array_canton[i]['latitud']+'"data-longitud="'+array_canton[i]['longitud']+'"data-zona="'+array_canton[i]['zona']+'" value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
			}
	   	}
	    $('#canton').html(scanton);
	    $("#canton").removeAttr("disabled");
	});

	$("#canton").change(function(event){
		sparroquia ='0';
		sparroquia = '<option value="">Seleccione...</option>';
	    for(var i=0;i<array_parroquia.length;i++){
		    if ($("#canton").val()==array_parroquia[i]['padre']){
		    	sparroquia += '<option value="'+array_parroquia[i]['codigo']+'">'+array_parroquia[i]['nombre']+'</option>';
			    } 
	    	}
	    $('#parroquia').html(sparroquia);
		$("#parroquia").removeAttr("disabled");
	});

	$("#parroquia").change(function(event){
		$("#nombreParroquia").val($("#parroquia option:selected").text());
		$("#nombreCanton").val($("#canton option:selected").text());
		$("#nombreProvincia").val($("#provincia option:selected").text());
		
	});
</script>
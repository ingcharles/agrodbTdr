<header>
	<h1>Reporte empleados</h1>
	<nav>
		<form id="filtrar" data-rutaAplicacion="uath" data-opcion="consultaReporteEmpleados" data-destino="tabla">
				<table>
					<tr>
					        <td>cédula:</td>
						    <td> <input id="identificador" type="text" name="identificador" maxlength="10" value="<?php echo $_POST['identificador'];?>">	</td>
							<td>apellido:</td>
							<td> <input id="apellidoEmpleado" type="text" name="apellidoEmpleado" maxlength="128" value="<?php echo $_POST['apellidoEmpleado'];?>">	</td>
					</tr>
					
					<tr>			
						<td>nombre:</td>
						<td> <input id="nombreEmpleado" type="text" name="nombreEmpleado" maxlength="128" value="<?php echo $_POST['nombreEmpleado'];?>">	</td>
						<td>capacitación:</td>
						<td> <input id="tituloCapacitacion" type="text" name="tituloCapacitacion" maxlength="128" value="<?php echo $_POST['tituloCapacitacion'];?>">	</td>
					</tr>
					
					<tr>			
						<td>inicio:</td>
						<td> <input id="fechaInicio" type="text" name="fechaInicio" maxlength="128" value="<?php echo $_POST['fechaInicio'];?>">	</td>
						<td>fin:</td>
						<td> <input id="fechaFin" type="text" name="fechaFin" maxlength="128" value="<?php echo $_POST['fechaFin'];?>">	</td>
					</tr>
					
					<tr>					    
						<td id="mensajeError"></td>
						<td colspan="3"> <button id='buscarEmpleado'>Buscar</button>	</td>
					</tr>
					
					</table>
		</form>
</nav>
</header>
<div id="tabla"></div>

<script>	
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
		$( "#fechaInicio" ).datepicker({
		      changeMonth: true,
		      changeYear: true
		    });
		$( "#fechaFin" ).datepicker({
		      changeMonth: true,
		      changeYear: true
		    });			
	});

	$("#filtrar").submit(function(event){
		event.preventDefault();
		
		if($('#identificador').val().length<10 && $('#nombreEmpleado').val().length==0 && $('#apellidoEmpleado').val().length==0 && $('#tituloCapacitacion').val().length==0 && $('#fechaInicio').val().length==0 && $('#fechaFin').val().length==0)
		{		
			$('#mensajeError').html('<span class="alerta">La cédula ingresada no es válida!');
		}
		else if($('#identificador').val().length==10 || $('#nombreEmpleado').val().length!=0 || $('#apellidoEmpleado').val().length!=0 || $('#tituloCapacitacion').val().length!=0 || $('#fechaInicio').val().length!=0 || $('#fechaFin').val().length!=0)
		{	$('#mensajeError').html('');	
			abrir($('#filtrar'),event, false);
		}
		
	});

</script>
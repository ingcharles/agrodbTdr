<header>
	<h1>Nuevo Registro de Aplicacion </h1>
</header>
<form id='nuevoAplicacion' data-rutaAplicacion='asignarAplicacionPerfil' data-opcion="guardarNuevoAplicacion" data-destino="detalleItem"  data-accionEnExito="ACTUALIZAR"   >
	<input type="hidden" id="opcion" name="opcion" value="">
	<input type="hidden" id="identificadorResponsable" name="identificadorResponsable" value="<?php echo $identificadorUsuario;?>" />
	<div id="estado"></div>
	<fieldset>
		<legend>Datos Aplicación</legend>
			<div data-linea="1">
				<label>Nombre: </label> 
				<input type="text" id="nombreAplicacion" name="nombreAplicacion" maxlength="256" />
			</div>
			<div data-linea="1">
				<label>Version: </label> 
				<input type="text" id="versionAplicacion" name="versionAplicacion" value=1.0 maxlength="8" />
			</div>
			<div data-linea="2">
				<label>Ruta: </label> 
				<input type="text" id="rutaAplicacion" name="rutaAplicacion"  maxlength="1024" />
			</div>
			<div data-linea="2">
				<label>Color: </label> 
				<input name="colorAplicacion" id="colorAplicacion" type="color" value="#000000" />
			</div>
			<div data-linea="3">
				<label>Codificación: </label> 
				<input type="text" id="codificacionAplicacion" name="codificacionAplicacion"  maxlength="16"  />
			</div>
			<div data-linea="3">
				<label>Estado: </label> 
				<select id="estadoAplicacion" name="estadoAplicacion"  >
					<option value="">Seleccione...</option>
					<option value="activo">Activo</option>
					<option value="inactivo">Inactivo</option>
				</select>
			</div>
			<div data-linea="4">
				<label>Descripción: </label> 
				<input type="text" id="descripcionAplicacion" name="descripcionAplicacion" maxlength="1024" />
			</div>
			<div data-linea="5" style="text-align: center">
				<button type="submit" id="guardar" name="guardar" >Guardar</button>
			</div>
	</fieldset>
</form>

<script type="text/javascript">

	$('document').ready(function(){
		distribuirLineas();
	});

	$("#nuevoAplicacion").submit(function(event){
		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		
		if($.trim($("#descripcionAplicacion").val())=="" ){
			error = true;
			$("#descripcionAplicacion").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese la descripción de la aplicación.").addClass("alerta");
		}
		
		if($.trim($("#estadoAplicacion").val())=="" ){
			error = true;
			$("#estadoAplicacion").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese el estado de la aplicación.").addClass("alerta");
		}

		if($.trim($("#codificacionAplicacion").val())=="" ){
			error = true;
			$("#codificacionAplicacion").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese la codificación de la aplicación.").addClass("alerta");
		}

		if($.trim($("#rutaAplicacion").val())=="" ){
			error = true;
			$("#rutaAplicacion").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese la ruta de la aplicación.").addClass("alerta");
		}
		
		if($.trim($("#versionAplicacion").val())=="" ){
			error = true;
			$("#versionAplicacion").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese la versión de la aplicación.").addClass("alerta");
		}	
		
		if($.trim($("#nombreAplicacion").val())=="" ){
			error = true;
			$("#nombreAplicacion").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese el nombre de la aplicación.").addClass("alerta");
		}	

		if (!error){
			//$('#nuevoAplicacion').attr('data-opcion','guardarNuevoAplicacion');    			
			//$('#nuevoAplicacion').attr('data-destino','detalleItem');
			abrir($("#nuevoAplicacion"),event,false);	
		}
	});
</script>
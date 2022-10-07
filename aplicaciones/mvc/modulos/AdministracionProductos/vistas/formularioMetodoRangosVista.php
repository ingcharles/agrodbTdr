<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id="regresar" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='Parametros/editar' data-destino="detalleItem">
		<input type="hidden" name="id_parametro" value="<?php echo $this->modeloMetodos->getIdParametro(); ?>" />
		<button class="regresar">Regresar a Parámetro</button>
</form>

<form id='formularioMetodo' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='Metodos/actualizar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<fieldset>
		<legend>Método</legend>
		
		<input type="hidden" name="id_metodo" value="<?php echo $this->modeloMetodos->getIdMetodo(); ?>" />
		<input type="hidden" name="id_parametro" value="<?php echo $this->modeloMetodos->getIdParametro(); ?>" />
		<input type="hidden" name="descripcion_original" value="<?php echo $this->modeloMetodos->getDescripcion(); ?>" />
		
		<div data-linea="1">
			<label for="descripcion">Descripción</label>
			<input type="text" id="descripcion" name="descripcion" placeholder="Nombre del parametro"  value="<?php echo $this->modeloMetodos->getDescripcion(); ?>" maxlength="256" class="validacion" disabled="disabled"/>
		</div>
		
		<div data-linea="2">
			<button id="modificar" type="button" class="editar">Editar </button>
			<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
		</div>
	</fieldset>
</form>

<form id='formularioRango' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='Rangos/guardar' data-destino="detalleItem">
	<fieldset>
		<legend>Rango</legend>
		
		<input type="hidden" name="id_metodo" value="<?php echo $this->modeloMetodos->getIdMetodo(); ?>" />
		
		<div data-linea="1">
			<label for="descripcion">Descripción</label>
			<input type="text" id="descripcion" name="descripcion" placeholder="Nombre del método" maxlength="256" class="validacion"/>
		</div>
		
		<div data-linea="2">
			<button type="submit" class="mas">Añadir rango</button>
		</div>
			
	</fieldset>
</form>

<fieldset>
	<legend>Rangos</legend>
		<table id="rango">
			<?php echo $this->registroRango; ?>
		</table>
</fieldset>

<script type="text/javascript">

	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		mostrarMensaje("","EXITO");
	 });

	$("#formularioMetodo").submit(function (event) {
		event.preventDefault();
		var error = false;
		$(".alertaCombo").removeClass("alertaCombo");

		$('#formularioMetodo .validacion').each(function(i, obj) {
			if(!$.trim($(this).val())){
				error = true;
				$(this).addClass("alertaCombo");
			}
		});

		if (!error) {
				var respuesta = JSON.parse(ejecutarJson($("#formularioMetodo")).responseText);
				filtrarFormulario('noRefrescar');
				if(respuesta.estado == 'EXITO'){
					mostrarMensaje(respuesta.mensaje,respuesta.estado);
				}else{
					mostrarMensaje(respuesta.mensaje,respuesta.estado);
				}
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#formularioRango").submit(function (event) {
		event.preventDefault();
		var error = false;
		$(".alertaCombo").removeClass("alertaCombo");

		$('#formularioRango .validacion').each(function(i, obj) {
			if(!$.trim($(this).val())){
				error = true;
				$(this).addClass("alertaCombo");
			}
		});

		if (!error) {
				var respuesta = JSON.parse(ejecutarJson($("#formularioRango")).responseText);
				filtrarFormulario('noRefrescar');
				if(respuesta.estado == 'EXITO'){
	           		$("#rango").append(respuesta.linea);
	           		mostrarMensaje(respuesta.mensaje,respuesta.estado);
	                $("#formularioRango input[type=text]").each(function() { this.value = '' });
	            }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

	$("#rango").on("submit","form.abrir",function(event){
		event.stopImmediatePropagation();
		abrir($(this),event,false);
	});

	$("#regresar").submit(function(event){
		event.stopImmediatePropagation();
		abrir($(this),event,false);
	});

</script>

<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id="regresar" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='Productos/editar' data-destino="detalleItem">
		<input type="hidden" name="id_producto" value="<?php echo $this->modeloParametros->getIdProducto(); ?>" />
		<button class="regresar">Regresar a Producto</button>
</form>

<form id='formularioParametro' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='Parametros/actualizar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<fieldset>
		<legend>Parámetro</legend>
		
		<input type="hidden" name="id_parametro" value="<?php echo $this->modeloParametros->getIdParametro(); ?>" />
		<input type="hidden" name="id_producto" value="<?php echo $this->modeloParametros->getIdProducto(); ?>" />
		<input type="hidden" name="descripcion_original" value="<?php echo $this->modeloParametros->getDescripcion(); ?>" />
		
		<div data-linea="1">
			<label for="descripcion">Descripción</label>
			<input type="text" id="descripcion" name="descripcion" placeholder="Nombre del parametro"  value="<?php echo $this->modeloParametros->getDescripcion(); ?>" maxlength="256" class="validacion" disabled="disabled"/>
		</div>
		
		<div data-linea="2">
			<button id="modificar" type="button" class="editar">Editar </button>
			<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
		</div>
	</fieldset>
</form>

<form id='formularioMetodo' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='Metodos/guardar' data-destino="detalleItem">
	<fieldset>
		<legend>Método</legend>
		
		<input type="hidden" name="id_parametro" value="<?php echo $this->modeloParametros->getIdParametro(); ?>" />
		
		<div data-linea="1">
			<label for="descripcion">Descripción</label>
			<input type="text" id="descripcion" name="descripcion" placeholder="Nombre del método" maxlength="256" class="validacion"/>
		</div>
		
		<div data-linea="2">
			<button type="submit" class="mas">Añadir método</button>
		</div>
			
	</fieldset>
</form>

<fieldset>
	<legend>Métodos</legend>
		<table id="metodo">
			<?php echo $this->registroMetodo; ?>
		</table>
</fieldset>

<script type="text/javascript">

	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		mostrarMensaje("","EXITO");
	 });

	$("#formularioParametro").submit(function (event) {
		event.preventDefault();
		var error = false;
		$(".alertaCombo").removeClass("alertaCombo");

		$('#formularioParametro .validacion').each(function(i, obj) {
			if(!$.trim($(this).val())){
				error = true;
				$(this).addClass("alertaCombo");
			}
		});

		if (!error) {
				var respuesta = JSON.parse(ejecutarJson($("#formularioParametro")).responseText);
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
	           		$("#metodo").append(respuesta.linea);
	           		mostrarMensaje(respuesta.mensaje,respuesta.estado);
	                $("#formularioMetodo input[type=text]").each(function() { this.value = '' });
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

	$("#metodo").on("submit","form.abrir",function(event){
		event.stopImmediatePropagation();
		abrir($(this),event,false);
	});

	$("#regresar").submit(function(event){
		event.stopImmediatePropagation();
		abrir($(this),event,false);
	});

</script>

<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formularioTipoProducto' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='TipoProductos/actualizar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<fieldset>
		<legend>Tipo de producto</legend>
		
			<input type="hidden" name="id_tipo_producto" value="<?php echo $this->modeloTipoProductos->getIdTipoProducto(); ?>" />

		<div data-linea="2">
			<label for="id_area">Área </label> Laboratorios
			<input type="hidden" id="id_area" name="id_area" value="<?php echo $this->modeloTipoProductos->getIdArea(); ?>" />
		</div>

		<div data-linea="3">
			<label for="nombre">Nombre </label> 
			<input type="text" name="nombre" value="<?php echo $this->modeloTipoProductos->getNombre(); ?>" placeholder="Nombre del tipo de producto" maxlength="256" class="validacion" disabled="disabled"/>
		</div>
		
		<div data-linea="4">
			<button id="modificar" type="button" class="editar">Editar </button>
			<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
		</div>
	</fieldset>
	
	
	
</form>

<form id='formularioSubtipoProducto' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='SubtipoProductos/guardar' data-destino="detalleItem">
	<fieldset>
		<legend>Subtipo de producto</legend>

			<input type="hidden" name="id_tipo_producto" value="<?php echo $this->modeloTipoProductos->getIdTipoProducto(); ?>" />

		<div data-linea="1">
			<label for="familia">Nombre</label>
			<input type="text" name="nombre" placeholder="Nombre del subtipo de producto" maxlength="256" class="validacion"/>
		</div>

		<div data-linea="2">
			<button type="submit" class="mas">Añadir subtipo de producto</button>
		</div>
	</fieldset>
</form>

<fieldset>
	<legend>Subtipo de Productos</legend>
		<table id="subtipoProducto">
			<?php echo $this->registroSubtipoProducto; ?>
		</table>
</fieldset>

<script type="text/javascript">
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		mostrarMensaje("","EXITO");
	 });

	$("#formularioTipoProducto").submit(function (event) {
		event.preventDefault();
		var error = false;
		$(".alertaCombo").removeClass("alertaCombo");

		$('#formularioTipoProducto .validacion').each(function(i, obj) {
			if(!$.trim($(this).val())){
				error = true;
				$(this).addClass("alertaCombo");
			}
		});

		if (!error) {
				JSON.parse(ejecutarJson($("#formularioTipoProducto")).responseText);
				filtrarFormulario('noRefrescar');
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#formularioSubtipoProducto").submit(function (event) {
		event.preventDefault();
		var error = false;
		$(".alertaCombo").removeClass("alertaCombo");

		$('#formularioSubtipoProducto .validacion').each(function(i, obj) {
			if(!$.trim($(this).val())){
				error = true;
				$(this).addClass("alertaCombo");
			}
		});

		if (!error) {
				var respuesta = JSON.parse(ejecutarJson($("#formularioSubtipoProducto")).responseText);
				filtrarFormulario('noRefrescar');
				if(respuesta.estado == 'exito'){
	           		$("#subtipoProducto").append(respuesta.linea);
	                $("#formularioSubtipoProducto input[type=text]").each(function() { this.value = '' });
	            }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

	$("#subtipoProducto").on("submit","form.abrir",function(event){
		event.stopImmediatePropagation();
		abrir($(this),event,false);
	});

</script>

<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formularioTipoProducto' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='TipoProductos/guardar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<fieldset>
		<legend>Tipo de producto</legend>

			<div data-linea="1">
			<label for="id_area">Área </label> Laboratorios
			<input type="hidden" id="id_area" name="id_area" value="LT" />
		</div>

		<div data-linea="2">
			<label for="nombre">Nombre </label> 
			<input type="text" name="nombre" placeholder="Nombre del tipo de producto" maxlength="256" class="validacion"/>
		</div>
		
		<div data-linea="3">
			<button type="submit" class="mas">Añadir tipo de producto</button>
		</div>
	</fieldset>
</form>

<form id="formularioSubtipoProducto" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos/TipoProductos' data-opcion='editar' data-destino="detalleItem">
		<input type="hidden" id="id_tipo_producto" name="id"/>
</form>

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
				var respuesta = JSON.parse(ejecutarJson($("#formularioTipoProducto")).responseText);
				if(respuesta.estado == 'exito'){
					$("#id_tipo_producto").val(respuesta.id);
					abrir($("#formularioSubtipoProducto"),event,true);
				}
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

</script>

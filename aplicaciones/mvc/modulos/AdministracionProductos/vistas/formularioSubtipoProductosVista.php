<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id="regresar" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='TipoProductos/editar' data-destino="detalleItem">
		<input type="hidden" name="id" value="<?php echo $this->modeloSubtipoProductos->getIdTipoProducto(); ?>" />
		<button class="regresar">Regresar a Tipo de Producto</button>
</form>

<form id='formularioSubTipoProducto' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='SubtipoProductos/actualizar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<fieldset>
		<legend>Subtipo de producto</legend>
		
			<input type="hidden" name="id_subtipo_producto" value="<?php echo $this->modeloSubtipoProductos->getIdSubtipoProducto(); ?>" />

		<div data-linea="3">
			<label for="nombre">Nombre </label> 
			<input type="text" name="nombre" value="<?php echo $this->modeloSubtipoProductos->getNombre(); ?>" placeholder="Nombre del tipo de producto" maxlength="256" class="validacion" disabled="disabled"/>
		</div>
		
		<div data-linea="4">
			<button id="modificar" type="button" class="editar">Editar </button>
			<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
		</div>
	</fieldset>
	
	
	
</form>

<form id='formularioProducto' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='Productos/guardar' data-destino="detalleItem">
	<fieldset>
		<legend>Producto</legend>

			<input type="hidden" name="id_subtipo_producto" value="<?php echo $this->modeloSubtipoProductos->getIdSubtipoProducto(); ?>" />

		<div data-linea="1">
			<label for="nombre_comun">Nombre común</label>
			<input type="text" id="nombre_comun" name="nombre_comun" placeholder="Nombre del producto" maxlength="256" class="validacion"/>
		</div>
		
		<div data-linea="1">
			<label for="nombre_cientifico">Nombre científico</label>
			<input type="text" name="nombre_cientifico"  placeholder="Nombre científico del producto" maxlength="256"/>
		</div>
		
		<div data-linea="2">
			<label for="partida_arancelaria">Partida Arancelaria</label>
			<input type="text" name="partida_arancelaria" placeholder="Partida arancelaria del producto" data-er="^[0-9]{10}+$" data-inputmask="'mask': '9999999999'"/>
		</div>
		
		<div data-linea="2">
			<label for="unidad_medida">Unidad cantidad:</label>
				<select name="unidad_medida">
					<option value="">Seleccionar....</option>
					<?php echo $this->comboUnidadesMedida() ?>
				</select>
		</div>
		
		<div data-linea="3">
			<input type="file" class="archivo" name="informe" accept="application/pdf"/>
			<input type="hidden" class="rutaArchivo" name="ruta" value="0"/>
			<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
				<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/administracionProductos/producto" >Subir archivo</button>
				<input type="hidden" id="fecha" name="fecha" value="<?php echo $this->fecha;?> "/>
				<button type="submit" class="mas">Añadir producto</button>
			</div>
			
	</fieldset>
</form>

<fieldset>
	<legend>Productos</legend>
		<table id="producto">
			<?php echo $this->registroProducto; ?>
		</table>
</fieldset>

<script type="text/javascript">
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		mostrarMensaje("","EXITO");
	 });

	$("#formularioSubTipoProducto").submit(function (event) {
		event.preventDefault();
		var error = false;
		$(".alertaCombo").removeClass("alertaCombo");

		$('#formularioSubTipoProducto .validacion').each(function(i, obj) {
			if(!$.trim($(this).val())){
				error = true;
				$(this).addClass("alertaCombo");
			}
		});

		if (!error) {
				JSON.parse(ejecutarJson($("#formularioSubTipoProducto")).responseText);
				filtrarFormulario('noRefrescar');
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#formularioProducto").submit(function (event) {
		event.preventDefault();
		var error = false;
		$(".alertaCombo").removeClass("alertaCombo");

		$('#formularioProducto .validacion').each(function(i, obj) {
			if(!$.trim($(this).val())){
				error = true;
				$(this).addClass("alertaCombo");
			}
		});

		if (!error) {
				var respuesta = JSON.parse(ejecutarJson($("#formularioProducto")).responseText);
				filtrarFormulario('noRefrescar');
				if(respuesta.estado == 'EXITO'){
					$("#producto").append(respuesta.linea);
					mostrarMensaje(respuesta.mensaje,respuesta.estado);
					$("#formularioProducto select").each(function() { this.selectedIndex = 0 });
					$("#formularioProducto input[type=text]").each(function() { this.value = '' });
				}else{
					mostrarMensaje(respuesta.mensaje,respuesta.estado);
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

	$("#producto").on("submit","form.abrir",function(event){
		event.stopImmediatePropagation();
		abrir($(this),event,false);
	});

	$('button.subirArchivo').click(function (event) {
        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF') {

            subirArchivo(
                archivo
                , $("#nombre_comun").val().replace(/ /g,'')+$('#fecha').val().replace(/ /g,'')
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
            );
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
    });

	$("#regresar").submit(function(event){
		event.stopImmediatePropagation();
		abrir($(this),event,false);
	});

</script>

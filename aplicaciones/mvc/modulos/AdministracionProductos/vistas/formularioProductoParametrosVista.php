<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id="regresar" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='SubtipoProductos/editar' data-destino="detalleItem">
		<input type="hidden" name="id_subtipo_producto" value="<?php echo $this->modeloProductos->getIdSubtipoProducto(); ?>" />
		<button class="regresar">Regresar a Subtipo de Producto</button>
</form>

<form id='formularioProducto' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='Productos/actualizar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<fieldset>
		<legend>Producto</legend>
		
		<input type="hidden" name="id_producto" value="<?php echo $this->modeloProductos->getIdProducto(); ?>" />
		<input type="hidden" name="partida_arancelaria_original" value="<?php echo $this->modeloProductos->getPartidaArancelaria(); ?>" />
		<input type="hidden" name="nombre_comun_original" value="<?php echo $this->modeloProductos->getNombreComun(); ?>" />
		<input type="hidden" name="codigo_producto" value="<?php echo $this->modeloProductos->getCodigoProducto(); ?>" />
		<input type="hidden" name="id_subtipo_producto" value="<?php echo $this->modeloProductos->getIdSubtipoProducto(); ?>" />

		<div data-linea="1">
			<label for="nombre_comun">Nombre común</label>
			<input type="text" id="nombre_comun" name="nombre_comun" value="<?php echo $this->modeloProductos->getNombreComun(); ?>" placeholder="Nombre del producto" maxlength="256" class="validacion" disabled="disabled"/>
		</div>
		
		<div data-linea="1">
			<label for="nombre_cientifico">Nombre científico</label>
			<input type="text" name="nombre_cientifico"  value="<?php echo $this->modeloProductos->getNombreCientifico(); ?>" placeholder="Nombre científico del producto" maxlength="256" disabled="disabled"/>
		</div>
		
		<div data-linea="2">
			<label for="partida_arancelaria">Partida Arancelaria</label>
			<input type="text" name="partida_arancelaria" value="<?php echo $this->modeloProductos->getPartidaArancelaria(); ?>" placeholder="Partida arancelaria del producto" data-er="^[0-9]{10}+$" data-inputmask="'mask': '9999999999'" disabled="disabled"/>
		</div>
		
		<div data-linea="2">
			<label>Código producto </label>
			<?php echo $this->modeloProductos->getCodigoProducto(); ?>
		</div>
		
		<div data-linea="3">
			<label for="unidad_medida">Unidad cantidad </label>
				<select name="unidad_medida" class="validacion" disabled="disabled">
					<option value="">Seleccionar....</option>
					<?php echo $this->comboUnidadesMedida($this->modeloProductos->getUnidadMedida()); ?>
				</select>
		</div>
		
		<div data-linea="4">
			<label>Archivo adjunto</label> <?php echo $this->modeloProductos->getRuta();?>
		</div>
		
		<div data-linea="5">
			<input type="file" class="archivo" name="informe" accept="application/pdf" disabled="disabled"/>
			<input type="hidden" class="rutaArchivo" name="ruta" value="0"/>
			<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
				<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/administracionProductos/producto" disabled="disabled" >Subir archivo</button>
				<input type="hidden" id="fecha" name="fecha" value="<?php echo $this->fecha;?> "/>
			</div>
		
		<div data-linea="6">
			<button id="modificar" type="button" class="editar">Editar </button>
			<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
		</div>
	</fieldset>
	
	
	
</form>

<form id='formularioParametro' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='Parametros/guardar' data-destino="detalleItem">
	<fieldset>
		<legend>Parámetros</legend>
		
		<input type="hidden" name="id_producto" value="<?php echo $this->modeloProductos->getIdProducto(); ?>" />
		
		<div data-linea="1">
			<label for="descripcion">Descripción</label>
			<input type="text" id="descripcion" name="descripcion" placeholder="Nombre del parametro" maxlength="256" class="validacion"/>
		</div>
		
		<div data-linea="2">
			<button type="submit" class="mas">Añadir parámetro</button>
		</div>
			
	</fieldset>
</form>

<fieldset>
	<legend>Parametros</legend>
		<table id="parametro">
			<?php echo $this->registroParametro; ?>
		</table>
</fieldset>

<script type="text/javascript">
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		mostrarMensaje("","EXITO");
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
					mostrarMensaje(respuesta.mensaje,respuesta.estado);
				}else{
					mostrarMensaje(respuesta.mensaje,respuesta.estado);
				}
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
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
	           		$("#parametro").append(respuesta.linea);
	           		mostrarMensaje(respuesta.mensaje,respuesta.estado);
	                $("#formularioParametro input[type=text]").each(function() { this.value = '' });
	            }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$("button.subirArchivo").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

	$("#parametro").on("submit","form.abrir",function(event){
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

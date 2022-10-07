<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>AplicacionMovilExternos' data-opcion='Eventos/guardar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
	<input type="hidden" id="id" name="id" />
	<input type="hidden" id="id_evento" name="id_evento" value="<?php echo $this->modeloEventos->getIdEvento(); ?>" />

	<fieldset>
		<legend>Información del Evento</legend>

		<div data-linea="1">
			<label for="nombre_evento">Título: </label>
			<input type="text" id="nombre_evento" name="nombre_evento" value="<?php echo $this->modeloEventos->getNombreEvento(); ?>" placeholder="Nombre del evento o campaña preventiva" required maxlength="256" />
		</div>

		<label for="descripcion">Descripción del Evento: </label>
		<div data-linea="2">
			<textarea id="descripcion" name="descripcion" rows="10" required placeholder="Descripción del evento"><?php echo $this->modeloEventos->getDescripcion(); ?></textarea>
		</div>

		<div data-linea="3">
			<label for="ruta_imagen">Imagen: </label>

			<input type="file" id="imagen" class="archivo" accept="image/JPG" data-max-size="819000" />
			<input type="hidden" class="rutaArchivo" name="archivo" id="archivo" value="0" required />
			<input type="hidden" id="ruta_imagen" name="ruta_imagen" required value="<?php echo $this->modeloEventos->getRutaImagen(); ?>" maxlength="256" readonly="readonly" />

			<div class="estadoCarga">En espera de archivo... (Tamaño máximo 800KB)</div>
			<button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo ADM_APP_ARC_EVEN_URL; ?>">Subir archivo</button>
		</div>

		<div data-linea="4">
			<label for="ruta_recurso">Ruta Recurso: </label>
			<input type="text" id="ruta_recurso" name="ruta_recurso" value="<?php echo $this->modeloEventos->getRutaRecurso(); ?>" placeholder="Enlace a un recuro disponible para descargar" maxlength="250" />
		</div>
	</fieldset>



	<div data-linea="25">
		<button type="submit" class="guardar">Guardar</button>
	</div>
</form>

<script type="text/javascript">
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
	});

	$('button.subirArchivo').click(function(event) {
		var fileInput = $('#imagen');
		var maxSize = fileInput.data('max-size');
		var nombre_archivo = "<?php echo 'evento_' . time(); ?>";
		var boton = $(this);
		var archivo = boton.parent().find(".archivo");
		var rutaArchivo = boton.parent().find(".rutaArchivo");
		var extension = archivo.val().split('.');
		var estado = boton.parent().find(".estadoCarga");

		if (fileInput.get(0).files.length) {

			if (extension[extension.length - 1].toUpperCase() == 'JPG') {

				var fileSize = fileInput.get(0).files[0].size; // bytes
				if (fileSize > maxSize) {
					$("#estado").html("La imagen puede pesar hasta 800kb.").addClass("alerta");
					$("#imagen").val("");
					return false;
				} else {
					$("#estado").html("").removeClass("alerta");
					$("#imagen").removeClass("alertaCombo");
					subirArchivo(
						archivo, nombre_archivo, boton.attr("data-rutaCarga"), rutaArchivo, new carga(estado, archivo, boton)
					);
				}

				$('#ruta_imagen').val("<?php echo URL_GUIA_PROYECTO . '/' . ADM_APP_ARC_URL; ?>eventos/" + nombre_archivo + ".jpg");

			} else {
				estado.html('Formato incorrecto, solo se admite archivos en formato JPG');
				archivo.val("0");
			}
		}
	});

	$("#formulario").submit(function(event) {
		event.preventDefault();
		var error = false;

		if ($("#ruta_imagen").val() == '') {
			error = true;
			$("#imagen").addClass("alertaCombo");
		}

		if (!error) {
			var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
			if (respuesta.estado == 'exito') {
				$("#estado").html("Se han guardado los datos con éxito.").addClass("exito");

				$("#id").val(respuesta.contenido);
				$("#formulario").attr('data-opcion', 'Eventos/editar');
				abrir($("#formulario"), event, false);
			}
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>
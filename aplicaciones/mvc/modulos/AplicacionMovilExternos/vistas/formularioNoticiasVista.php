<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>AplicacionMovilExternos' data-opcion='Noticias/guardar' data-destino="detalleItem" method="post">
	<input type="hidden" id="id_noticia" name="id_noticia" value="<?php echo $this->modeloNoticias->getIdNoticia(); ?>" />

	<fieldset>
		<legend>Información de la Noticia</legend>

		<div data-linea="1">
			<label for="titulo">Título: </label>
			<input type="text" id="titulo" name="titulo" value="<?php echo $this->modeloNoticias->getTitulo(); ?>" placeholder="Título de la noticia" required maxlength="128" />
		</div>

		<label for="noticia">Detalle de la Noticia: </label>
		<div data-linea="2">
			<textarea id="noticia" name="noticia" rows="10" required placeholder="Contenido de la noticia"><?php echo $this->modeloNoticias->getNoticia(); ?></textarea>
		</div>

		<hr />

		<div data-linea="7">
			<label for="fuente">Fuente: </label>
			<input type="text" id="fuente" name="fuente" value="<?php echo $this->modeloNoticias->getFuente(); ?>" placeholder="Nombre de la fuente de información de la noticia" required maxlength="64" />
		</div>

		<p class="nota">Por favor ingrese la ruta completa de la fuente, http://...</p>

		<div data-linea="8">
			<label for="url_fuente">URL Fuente: </label>
			<input type="text" id="url_fuente" name="url_fuente" value="<?php echo $this->modeloNoticias->getUrlFuente(); ?>" placeholder="Campo donde se registra la url de la noticia en caso de no ser generada originalmente por Agrocalidad." required maxlength="512" />
		</div>

		<hr />

		<div data-linea="5" class="abrir">
			<label for="fecha_noticia">Fecha: </label> <?php echo date('Y-m-d H:i', strtotime($this->modeloNoticias->getFechaNoticia())); ?>
		</div>

		<div data-linea="5" class="abrir">
			<label for="visitas">Visitas: </label> <?php echo $this->modeloNoticias->getVisitas(); ?>
		</div>

		<hr class="abrir" />

		<div data-linea="4">

			<label for="ruta">Imagen: </label>
			<div class="abrir"><?php echo ($this->modeloNoticias->getRuta() == '' ? '<span class="alerta">No ha cargado ninguna imagen</span>' : '<a href="' . $this->modeloNoticias->getRuta() . '" target="_blank" class="archivo_cargado" id="archivo_cargado">Click aquí para ver la imagen</a>') ?></div>

			<input type="file" id="imagen" class="archivo" accept="image/JPG" data-max-size="104000" />
			<input type="hidden" class="rutaArchivo" name="archivo" id="archivo" value="0" required />
			<input type="hidden" id="ruta" name="ruta" required value="<?php echo $this->modeloNoticias->getRuta(); ?>" maxlength="256" readonly="readonly" />

			<div class="estadoCarga">En espera de archivo... (Tamaño máximo 100KB)</div>
			<button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo ADM_APP_ARC_NOTI_URL; ?>">Subir archivo</button>
		</div>

		<hr class="abrir" />

		<div data-linea="9" class="abrir">
			<label for="estadoNoticia">Estado: </label>
			<select id="estadoNoticia" name="estado">
				<?php
				echo $this->comboEstado($this->modeloNoticias->getEstado());
				?>
			</select>
		</div>
	</fieldset>
	<div data-linea="10">
		<button type="submit" class="guardar">Guardar</button>
	</div>
</form>

<script type="text/javascript">
	var bandera = <?php echo json_encode($this->formulario); ?>;

	$(document).ready(function() {
		construirValidador();
		distribuirLineas();

		if (bandera == 'nuevo') {
			$(".abrir").hide();
			$("#estadoNoticia").removeAttr("required");
		} else {
			$(".abrir").show();
			$("#estadoNoticia").attr("required");
		}
	});

	$('button.subirArchivo').click(function(event) {
		var fileInput = $('#imagen');
		var maxSize = fileInput.data('max-size');
		var nombre_archivo = "<?php echo 'noticia_' . time(); ?>";
		var boton = $(this);
		var archivo = boton.parent().find(".archivo");
		var rutaArchivo = boton.parent().find(".rutaArchivo");
		var extension = archivo.val().split('.');
		var estado = boton.parent().find(".estadoCarga");

		if (fileInput.get(0).files.length) {

			if (extension[extension.length - 1].toUpperCase() == 'JPG') {

				var fileSize = fileInput.get(0).files[0].size; // bytes
				if (fileSize > maxSize) {
					$("#estado").html("La imagen puede pesar hasta 100kb.").addClass("alerta");
					$("#imagen").val("");
					return false;
				} else {
					$("#estado").html("").removeClass("alerta");
					$("#imagen").removeClass("alertaCombo");
					subirArchivo(
						archivo, nombre_archivo, boton.attr("data-rutaCarga"), rutaArchivo, new carga(estado, archivo, boton)
					);
				}

				$('#ruta').val("<?php echo URL_GUIA_PROYECTO . '/' . ADM_APP_ARC_URL; ?>noticias/" + nombre_archivo +"."+ extension[extension.length - 1]);
				
			} else {
				estado.html('Formato incorrecto, solo se admite archivos en formato JPG');
				archivo.val("0");
			}
		}
	});

	$("#formulario").submit(function(event) {
		event.preventDefault();
		var error = false;

		if (bandera == 'nuevo') {

			if ($("#ruta").val() == '') {
				error = true;
				$("#imagen").addClass("alertaCombo");
			}

		}

		if (!error) {
			var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
			if (respuesta.estado == 'exito') {
				$("#estado").html("Se han guardado los datos con éxito.").addClass("exito");
				$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');
				abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), "#listadoItems", true);
			}
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>
<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<div class="pestania" id="ParteI">
	<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>AplicacionMovilExternos' data-opcion='Eventos/guardar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
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
				<label for="ruta_imagen">Imagen: </label> <?php echo ($this->modeloEventos->getRutaImagen() == '' ? '<span class="alerta">No ha cargado ninguna imagen</span>' : '<a href="' . $this->modeloEventos->getRutaImagen() . '" target="_blank" class="archivo_cargado" id="archivo_cargado">Click aquí para ver la imagen</a>') ?>

				<input type="file" id="informe" class="archivo" accept="image/JPG" data-max-size="819000" />
				<input type="hidden" class="rutaArchivo" name="archivo" id="archivo" value="0" required />
				<input type="hidden" id="ruta_imagen" name="ruta_imagen" required value="<?php echo $this->modeloEventos->getRutaImagen(); ?>" maxlength="256" readonly="readonly" />

				<div class="estadoCarga">En espera de archivo... (Tamaño máximo 800kb)</div>
				<button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo ADM_APP_ARC_EVEN_URL; ?>">Subir archivo</button>
			</div>

			<div data-linea="4">
				<label for="ruta_recurso">Ruta Recurso: </label>
				<input type="text" id="ruta_recurso" name="ruta_recurso" value="<?php echo $this->modeloEventos->getRutaRecurso(); ?>" placeholder="Enlace a un recuro disponible para descargar" maxlength="250" />
			</div>

			<div data-linea="5">
				<label for="ruta_recurso">Estados: </label>
				<select name="estado" required="required">
					<?php echo $this->comboEstado($this->modeloEventos->getEstado()); ?>
				</select>
			</div>
		</fieldset>

		<div data-linea="25">
			<button type="submit" class="guardar">Guardar</button>
		</div>
	</form>
</div>

<div class="pestania" id="ParteII">
	<form id='formularioDetalle' class="abrir" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>AplicacionMovilExternos' data-opcion='DetalleEventos/guardar' data-destino="detalleItem" method="post">
		<input type="hidden" id="id_evento_campania" name="id_evento_campania" value="<?php echo $this->modeloEventos->getIdEvento(); ?>" />

		<fieldset>
			<legend>Información de Campaña</legend>

			<div data-linea="23">
				<label for="nombre_campania">Nombre: </label>
				<input type="text" id="nombre_campania" name="nombre_campania" placeholder="Título del detalle de la campaña" maxlength="128" />
			</div>

			<label for="evento_campania">Contenido: </label>
			<div data-linea="24">
				<textarea id="evento_campania" name="evento_campania" rows="4" placeholder="Contenido del detalle de campaña"></textarea>
			</div>

			<div data-linea="25">
				<input type="file" id="informe1" class="archivoDetalle" accept="image/JPG" data-max-size="819000" />
				<input type="hidden" class="rutaArchivoDetalle" name="archivoDetalle" id="archivoDetalle" value="0" required />
				<input type="hidden" id="ruta_imagen_campania" name="ruta_imagen_campania" required readonly="readonly" />

				<div class="estadoCarga" id="estadoCarga">En espera de archivo... (Tamaño máximo 800KB)</div>
				<button type="button" class="subirArchivoDetalle adjunto" data-rutaCarga="<?php echo ADM_APP_ARC_EVEN_URL; ?>">Subir archivo</button>
			</div>

			<div data-linea="26">
				<label for="ruta_recurso_campania">Ruta recurso: </label>
				<input type="text" id="ruta_recurso_campania" name="ruta_recurso_campania" placeholder="Enlace a un recurso disponible para descargar" maxlength="250" />
			</div>

			<hr />

			<div data-linea="27">
				<button type="submit" class="mas">Agregar</button>
			</div>
		</fieldset>

	</form>

	<fieldset>
		<legend>Detalle de Campaña</legend>
		<div data-linea="28">
			<table id="tbItems" style="width:100%">
				<thead>
					<tr>
						<th style="width: 5%;">Nº</th>
						<th style="width: 25%;">Nombre</th>
						<th style="width: 35%;">Contenido</th>
						<th style="width: 10%;">Imagen</th>
						<th style="width: 10%;">Recurso</th>
						<th style="width: 5%;"></th>
						<th style="width: 5%;"></th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</fieldset>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		construirAnimacion($(".pestania"));

		fn_mostrarDetalleEventos();
	});

	//Pestaña I
	$('button.subirArchivo').click(function(event) {
		var fileInput = $('#informe');
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
					$("#informe").val("");
					return false;
				} else {
					subirArchivo(
						archivo, nombre_archivo, boton.attr("data-rutaCarga"), rutaArchivo, new carga(estado, archivo, boton)
					);
				}

				$('#ruta_imagen').val("<?php echo URL_GUIA_PROYECTO . '/' . ADM_APP_ARC_URL; ?>eventos/" + nombre_archivo + ".jpg");

			} else {
				estado.html('Formato incorrecto, solo se admite archivos en formato JPG');
				archivo.val("0");
			}
		} else {
			estado.html('No ha seleccionado ninguna imagen');
			archivo.val("0");
		}
	});

	$("#formulario").submit(function(event) {
		event.preventDefault();
		var error = false;

		if (!error) {
			var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
			if (respuesta.estado == 'exito') {
				$("#estado").html("Se han guardado los datos con éxito.").addClass("exito");
			}
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	//Pestaña II
	$('button.subirArchivoDetalle').click(function(event) {
		var fileInput = $('#informe1');
		var maxSize = fileInput.data('max-size');
		var nombre_archivo = "detalle_evento_" + Math.floor(Math.random() * 10000000000);
		var boton = $(this);
		var archivo = boton.parent().find(".archivoDetalle");
		var rutaArchivo = boton.parent().find(".rutaArchivoDetalle");
		var extension = archivo.val().split('.');
		var estado = boton.parent().find(".estadoCarga");

		if (fileInput.get(0).files.length) {

			if (extension[extension.length - 1].toUpperCase() == 'JPG') {

				var fileSize = fileInput.get(0).files[0].size; // bytes
				if (fileSize > maxSize) {
					$("#estado").html("La imagen puede pesar hasta 800kb.").addClass("alerta");
					$("#informe1").val("");
					return false;
				} else {
					$("#estado").html("").removeClass("alerta");
					$("#informe1").removeClass("alertaCombo");
					subirArchivo(
						archivo, nombre_archivo, boton.attr("data-rutaCarga"), rutaArchivo, new carga(estado, archivo, boton)
					);
				}

				$('#ruta_imagen_campania').val("<?php echo URL_GUIA_PROYECTO . '/' . ADM_APP_ARC_URL; ?>eventos/" + nombre_archivo + ".jpg");

			} else {
				estado.html('Formato incorrecto, solo se admite archivos en formato JPG');
				archivo.val("0");
			}
		} else {
			estado.html('No ha seleccionado ninguna imagen');
			archivo.val("0");
		}
	});

	//Función para agregar elementos
	$("#formularioDetalle").submit(function(event) {
		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if (!$.trim($("#nombre_campania").val())) {
			error = true;
			$("#nombre_campania").addClass("alertaCombo");
		}

		if (!$.trim($("#evento_campania").val())) {
			error = true;
			$("#evento_campania").addClass("alertaCombo");
		}

		if (!$.trim($("#archivoDetalle").val())) {
			error = true;
			$(".estadoCargaDetalle").addClass("alertaCombo");
		}

		if ($("#ruta_imagen_campania").val() == '') {
			error = true;
			$("#informe1").addClass("alertaCombo");
		}

		if (!error) {
			var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
			if (respuesta.estado == 'exito') {
				fn_mostrarDetalleEventos();
				fn_limpiarDetalle();
			}
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	//Funcion que elimina una fila de la lista 
	function fn_eliminarDetalle(idDetalleEvento) {
		$.post("<?php echo URL ?>AplicacionMovilExternos/DetalleEventos/borrar", {
				elementos: idDetalleEvento
			},
			function(data) {
				fn_mostrarDetalleEventos();
			});
	}

	//Funcion que actualiza el estado de un registro 
	function fn_actualizarEstadoDetalle(estadoDetalle, idDetalle) {
		$.post("<?php echo URL ?>AplicacionMovilExternos/DetalleEventos/actualizarEstado", {
				id_detalle_evento: idDetalle,
				estado: estadoDetalle
			},
			function(data) {
				fn_mostrarDetalleEventos();
			});
	}

	//Para cargar el detalle de eventos registrados
	function fn_mostrarDetalleEventos() {
		var idEvento = $("#id_evento").val();

		$.post("<?php echo URL ?>AplicacionMovilExternos/DetalleEventos/construirDetalleEventos/" + idEvento, function(data) {
			$("#tbItems tbody").html(data);
		});
	}

	//Vacía el formulario de ingreso de detalles de evento
	function fn_limpiarDetalle() {
		$("#nombre_campania").val("");
		$("#evento_campania").val("");
		$("#informe1").val("").removeClass("verde");
		$("#informeDetalle").val("");
		$("#archivoDetalle").val("");
		$("#ruta_imagen_campania").val("");
		$("#ruta_recurso_campania").val("");
		$(".subirArchivoDetalle").removeAttr("disabled");
		$("#estadoCarga").html("En espera de archivo... (Tamaño máximo 800KB)");
	}
</script>
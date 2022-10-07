<header>
    <h1><?php echo $this->accion;  echo $this->panelBusquedaRespuesta; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>NotificacionesFitosanitarias' data-opcion='respuestanotificacion/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
	
<!--        <fieldset>			
        <legend>Detalle Notificación</legend>
        <table width="100%" id="tbrequerimiento">
            <thead><tr>
			<th>Cód. Documento</th>
			<th>País notifica</th>
			<th>Producto</th>
			<th>F.notificación</th>
			<th>Descripción</th>
                        <th>Enlace</th>
                    </tr>
            </thead>
            <?php echo $this->itemsRequeridos; ?>
        </table>
    </fieldset>-->
    
        <fieldset>
                <legend>Detalle Notificación</legend>				

		<div data-linea="1">
			<label for="cod_documento">Cód. Documento </label>
			<input type="text" id="codigo_documento" name="codigo_documento" readonly="readonly" value="<?php echo $id; ?>" />
		</div>
                
		<legend>RespuestaNotificacion</legend>				

		<div data-linea="2">
			<label for="id_respuesta_notificacion">id_respuesta_notificacion </label>
			<input type="text" id="id_respuesta_notificacion" name="id_respuesta_notificacion" value="<?php echo $this->modeloRespuestaNotificacion->getIdRespuestaNotificacion(); ?>"
			placeholder="Identificador de la tabla de respuestas operadores-técnicos" required maxlength="8" />
		</div>				

<!--		<div data-linea="2">
			<label for="id_notificacion">id_notificacion </label>
			<input type="text" id="id_notificacion" name="id_notificacion" value="<?php echo $this->modeloRespuestaNotificacion->getIdNotificacion(); ?>"
			placeholder="Identificador de la tabla notificaciones" required maxlength="8" />
		</div>				

		<div data-linea="3">
			<label for="id_padre">id_padre </label>
			<input type="text" id="id_padre" name="id_padre" value="<?php echo $this->modeloRespuestaNotificacion->getIdPadre(); ?>"
			placeholder="Identificador de una revisión realizada por un operador a una notificación" required maxlength="8" />
		</div>				

		<div data-linea="4">
			<label for="identificador">identificador </label>
			<input type="text" id="identificador" name="identificador" value="<?php echo $this->modeloRespuestaNotificacion->getIdentificador(); ?>"
			placeholder="Número de identificación de un operador-técnico" required maxlength="13" />
		</div>				

		<div data-linea="5">
			<label for="tipo">tipo </label>
			<input type="text" id="tipo" name="tipo" value="<?php echo $this->modeloRespuestaNotificacion->getTipo(); ?>"
			placeholder="Tipo de usuario operador-técnico" required maxlength="10" />
		</div>				

		<div data-linea="6">
			<label for="respuesta">respuesta </label>
			<input type="text" id="respuesta" name="respuesta" value="<?php echo $this->modeloRespuestaNotificacion->getRespuesta(); ?>"
			placeholder="Respuesta realizada por parte de un operador-técnico" required maxlength="256" />
		</div>				

		<div data-linea="7">
			<label for="archivo">archivo </label>
			<input type="text" id="archivo" name="archivo" value="<?php echo $this->modeloRespuestaNotificacion->getArchivo(); ?>"
			placeholder="Ruta de ubicación de archivos" required maxlength="64" />
		</div>				

		<div data-linea="8">
			<label for="fecha_revision">fecha_revision </label>
			<input type="text" id="fecha_revision" name="fecha_revision" value="<?php echo $this->modeloRespuestaNotificacion->getFechaRevision(); ?>"
			placeholder="Fecha que un operador realiza una pregunta a una notificación" required maxlength="8" />
		</div>				

		<div data-linea="9">
			<label for="fecha_respuesta">fecha_respuesta </label>
			<input type="text" id="fecha_respuesta" name="fecha_respuesta" value="<?php echo $this->modeloRespuestaNotificacion->getFechaRespuesta(); ?>"
			placeholder="Fecha que un técnico responde a una pregunta realizada por un operador" required maxlength="8" />
		</div>				

		<div data-linea="10">
			<label for="estado_respuesta">estado_respuesta </label>
			<input type="text" id="estado_respuesta" name="estado_respuesta" value="<?php echo $this->modeloRespuestaNotificacion->getEstadoRespuesta(); ?>"
			placeholder="True: el operador-técnico contesto y la respuesta es visible para todos los operadores y técnicos
False: el operador-técnico contesto y la respuesta es visible solo para quien realizo la pregunta" required maxlength="8" />
		</div>				

		<div data-linea="11">
			<label for="finalizar_respuesta">finalizar_respuesta </label>
			<input type="text" id="finalizar_respuesta" name="finalizar_respuesta" value="<?php echo $this->modeloRespuestaNotificacion->getFinalizarRespuesta(); ?>"
			placeholder="True: no se puede hacer preguntas por parte de los operadores ni tampoco respuestas por parte del técnico
False: se puede realizar preguntas por parte del operador y respuestas por parte del técnico" required maxlength="8" />
		</div>

		<div data-linea="12">
			<button type="submit" class="guardar">Guardar</button>
		</div>-->
	</fieldset >
</form >
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>NotificacionesFitosanitarias' data-opcion='respuestaNotificacion/guardarDocumento' data-destino="detalleItem" method="post">
        <input type="hidden" id="nombreNotificacion" name="nombreNotificacion" value="1"  />
	<fieldset>
		<legend>Respuesta Notificación</legend>

		<div data-linea="1">
			<input type="file" id="informe" class="archivo" accept="application/msword | application/pdf" />
			<input type="hidden" class="rutaArchivo" name="archivo" id="archivo" value="0" />
			<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
			<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/mvc/modulos/NotificacionesFitosanitarias/archivos/respuestaNotificacion">Subir archivo</button>
		</div>

                <button type="submit" class="guardar"> Enviar Respuesta</button>
		
	</fieldset>
</form>
<?php
?>

<script type="text/javascript">

$(document).ready(function(){
	distribuirLineas();
});

$('button.subirArchivo').click(function (event) {
	var nombre_archivo = "<?php echo 'notificacion' . (md5(time())); ?>";
        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF' || extension[extension.length - 1].toUpperCase() == 'DOC' || extension[extension.length - 1].toUpperCase() == 'DOCX') {

            subirArchivo(
                archivo
                , nombre_archivo
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
            );
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF / WORD');
            archivo.val("0");
        }
});

$("#formulario").submit(function (event) {

	event.preventDefault();

	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#archivo").val() == 0){
		error = true;
		$("#informe").addClass("alertaCombo");
	}

	if (!error) {
		var respuesta = JSON.parse(ejecutarJson($(this)).responseText);

		if (respuesta.estado == 'exito'){
			//fn_filtrar_datos();
        }else {
            $("#estado").html(respuesta.mensaje).addClass("alerta");
        }
    }
});

</script>

<!--<script type ="text/javascript">
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
	 });

	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		if (!error) {
			abrir($(this), event, false);
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>-->

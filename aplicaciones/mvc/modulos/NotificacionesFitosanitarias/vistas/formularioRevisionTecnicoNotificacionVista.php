<header>
    <nav><?php echo $this->accion;?></nav>
    <nav><?php echo $this->panelBusquedaRespuesta;?></nav>
    
</header>
<fieldset>
    <legend>Revisiones realizadas</legend>
    <div id="paginacion" class="normal" style="width: 100%"></div>
    <table width="100%" id="tbrequerimiento">
        <thead><tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Operador</th>
                <th>Visualizar</th> 
                </tr></thead>
        <tbody>
            <?php
            if (count($this->itemsFiltrados) > 0){
            echo $this->itemsFiltrados;
            }
            ?>
        </tbody>
    </table>
</fieldset>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>NotificacionesFitosanitarias' data-opcion='respuestaNotificacion/guardar' data-destino="detalleItem" method="post">
        <input type="hidden" id="idNotificacion" name="idNotificacion" value="<?php echo $_POST['id']?>"  />
        <fieldset>
		<legend>Respuesta Notificación</legend>
                <div data-linea="1">
			<label for="respuesta">Respuesta: </label>
			<input type="text" id="respuesta" name="respuesta"  />
		</div>

		<div data-linea="2">
                        <label for="adjuntarArchivo">Adjuntar archivo: </label>
			<input type="file" id="informe" class="archivo" accept="application/msword | application/pdf" />
			<input type="hidden" class="rutaArchivo" name="archivo" id="archivo" value="0" />
			<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
			<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/mvc/modulos/NotificacionesFitosanitarias/archivos/respuestaTecnicoNotificacion">Subir archivo</button>
		</div>

                <button type="submit" class="guardar"> Enviar Respuesta</button>
		
	</fieldset>
</form>

<script type="text/javascript">
	$(document).ready(function () {
        distribuirLineas();    
        $("#btnExcel").hide(); 
        $("#formulario").hide(); 
   });
    
    $("#btnFiltrarNotificacion").click(function (event) { 
            event.preventDefault();
            var fechaCierre = $("#fechaCierre").val();
            var hoy = new Date().toJSON().slice(0,10)
         
            if(hoy <= fechaCierre){
                $("#btnFiltrarNotificacion").hide(); 
                $("#formulario").show(); 
                 //fn_filtrar();
            }else{
                $("#btnFiltrarNotificacion").hide(); 
                alert('Fuera de fecha');
            }
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

//Funcion para listar revisones realizadas
    function fn_verRespuestaNotificacion(idRespuestaNotificacion,idNotificacion,identificador,estadoRespuesta=null) {  
        var elementoDestino = "#detalleItem";
        var url = "<?php echo URL ?>NotificacionesFitosanitarias/respuestaNotificacion/verRevisionesNotificaciones";
        var data = {
            idRespuestaNotificacion: idRespuestaNotificacion,
            idNotificacion: idNotificacion,
            identificador: identificador,
            estadoRespuesta: estadoRespuesta 
            
         };
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "text",
            contentType: "application/x-www-form-urlencoded; charset=latin1",
            beforeSend: function () {
                $(elementoDestino).html("<div id='cargando'>Cargando...</div>").fadeIn();
            },
            success: function (html) {
                $("#detalleItem").html(html);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $(elementoDestino).html(
                        "<div id='error'>¡Ups!... algo no anda bien.<br />"
                        + "Se produjo un " + textStatus + " "
                        + jqXHR.status
                        + ".<br />Disculpe los inconvenientes causados.</div>");
            },
            complete: function () {
            }
        });
    }
        
</script>

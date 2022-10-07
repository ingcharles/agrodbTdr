<header>
<form id="regresar" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>NotificacionesFitosanitarias' data-opcion='respuestaNotificacion/respuestaNotificaciones' data-destino="detalleItem" method="post">
		<input type="hidden" name="id" value="<?php echo $_POST['idNotificacion'];?>"/>
		<button type="submit" class="regresar">Regresar nivel anterior</button>

</form>
<nav><?php echo $this->accion;?></nav>

</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>NotificacionesFitosanitarias' data-opcion='respuestaNotificacion/guardarTecnico' data-destino="detalleItem" method="post">
        <input type="hidden" id="idNotificacion1" name="idNotificacion1" />
        <input type="hidden" id="idRespuestaNotificacion1" name="idRespuestaNotificacion1" />
        <input type="hidden" id="idPadre1" name="idPadre1" />
        <input type="hidden" id="identificacion1" name="identificacion1" />
        <input type="hidden" id="archivo1" name="archivo1" />
            
       
</form>
 <?php echo $this->datosTecnico;?>
<script>
    $(document).ready(function () {
        distribuirLineas();    
        $("#formulario").hide(); 
         
    });
    $("#btnFiltrarNotificacion").click(function (event) { 
        event.preventDefault(); 
        var fechaCierre = $("#fechaCierre").val(); 
        var hoy = new Date().toJSON().slice(0,10);
        if(hoy <= fechaCierre){
            $("#btnFiltrarNotificacion").hide(); 
            $("#formulario").show(); 
            $("#idNotificacion1").val($("#idNotificacion").val()); 
            $("#idRespuestaNotificacion1").val($("#idResNotificacion").val()); 
            $("#idPadre1").val($("#idPadre").val()); 
            $("#identificacion1").val($("#identificacion").val());
            $("#archivo1").val($("#archivo").val());
            $("#archivo1").val($("#archivo").val());
        }else{
            $("#btnFiltrarNotificacion").hide(); 
            //alert('Fuera de fecha');
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
$("#regresar").submit(function(event){
		event.preventDefault();
				abrir($(this),event,false);
	});
    
//Funcion para listar revisones realizadas
    function fn_verRespuestaNotificacion(idRespuestaNotificacion,idNotificacion,identificador) {
        alert ('SiLLego');
        var elementoDestino = "#detalleItem";
        var url = "<?php echo URL ?>NotificacionesFitosanitarias/respuestaNotificacion/verRevisionesNotificaciones";
        var data = {
            idRespuestaNotificacion: idRespuestaNotificacion,
            idNotificacion: idNotificacion,
            identificador: identificador
           
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

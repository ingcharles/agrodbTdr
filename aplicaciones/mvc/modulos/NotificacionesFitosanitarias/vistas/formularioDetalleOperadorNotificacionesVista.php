<header>
<H1>Revisión Notificación</H1>
<script src="<?php echo URL ?>modulos/NotificacionesFitosanitarias/vistas/js/funcionCf.js"></script>
<form id="regresar" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>NotificacionesFitosanitarias' data-opcion='respuestaNotificacion/respuestaNotificaciones' data-destino="detalleItem" method="post">
		<input type="hidden" name="id" value="<?php echo $_POST['idNotificacion'];?>"/>
		<button type="submit" class="regresar">Regresar nivel anterior</button>

</form>
<nav><?php echo $this->accion;?></nav>
</header>

<input type="hidden" id="idRespuestaNotificacion" name="idRespuestaNotificacion" value="<?php echo $_POST['idRespuestaNotificacion'];?>"/>
<input type="hidden" id="idNotificacion" name="idNotificacion" value="<?php echo $_POST['idNotificacion'];?>"/>
<input type="hidden" id="identificador" name="identificador" value="<?php echo $this->modeloRespuestaNotificacion->getIdentificador()?>"/>

<fieldset>
	<legend>Revisión realizada por Operador</legend>
		
		<div data-linea="0">
			<label for="respuesta">Nombre Operador: </label><?php echo $datosOperador['razon_social']; ?>
		</div>
		<?php 
                if($this->perfilUsuario == 'PFL_TEC_RES_NOTI'){
		  echo '
        <div data-linea="1">
			<label for="respuesta">Cédula Operador: </label>'. $this->modeloRespuestaNotificacion->getIdentificador() .'</div>
        <div data-linea="2">
			<label for="respuesta">Celular: </label>'. $datosOperador['celular'] .'</div>
		<div data-linea="3">
			<label for="respuesta">Correo electrónico: </label>'. $datosOperador['correo_electronico']  .'</div>';
		}
		?>
		
		
		<div data-linea="4">
			<label for="respuesta">Fecha revisión: </label><?php echo date('Y-m-d',strtotime($this->modeloRespuestaNotificacion->getFechaRevision())); ?>
		</div>
		<div data-linea="5">
			<label for="respuesta">Revisión ingresada: </label><?php echo $this->modeloRespuestaNotificacion->getRespuesta(); ?>
		</div>
		<div data-linea="6">
                        <label for="respuesta">Archivo adjunto: </label><?php if($this->modeloRespuestaNotificacion->getArchivo() != '0'){ 
                                echo '<a href="' . $this->modeloRespuestaNotificacion->getArchivo() . '" target="_blank" >Descargar</a>'; }else { echo "No existe archivo cargado";} ?>
                </div>  
                <?php 
                if(($this->perfilUsuario == 'PFL_TEC_RES_NOTI') && ($this->modeloRespuestaNotificacion->getEstadoRespuesta() == 'false')){
		  echo '<div id="botonResponder" data-linea="7">
			<button id="btnFiltrarNotificacion">Responder</button>
		</div>';
		}
		?>
</fieldset>


<div id="seccionRespuestas" name="seccionRespuestas">
</div>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>NotificacionesFitosanitarias' data-opcion='respuestaNotificacion/guardarOperador' data-destino="detalleItem" method="post" data-accionEnExito="ACTUALIZAR">
    <input type="hidden" id="idNotificacion1" name="idNotificacion1" />
        <input type="hidden" id="idRespuestaNotificacion1" name="idRespuestaNotificacion1" />
        <input type="hidden" id="idPadre1" name="idPadre1" />
        <input type="hidden" id="identificador1" name="identificador1" value="<?php echo $_SESSION['usuario']?>"/>
 
        <fieldset>
		<legend>Respuesta Notificación....</legend>
                <div data-linea="1">
			<label for="respuesta">Respuesta: </label>
			<input type="text" id="respuesta" name="respuesta"  required="required"/>
		</div>

		<div data-linea="2">
                        <label for="adjuntarArchivo">Adjuntar archivo: </label>
			<input type="file" id="informe" class="archivo" accept="application/msword | application/pdf" />
			<input type="hidden" class="rutaArchivo" name="archivo" id="archivo" value="0" />
			<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
			<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/mvc/modulos/NotificacionesFitosanitarias/archivos/respuestaOperadorNotificacion">Subir archivo</button>
		</div>
		
		<?php 
                if(($this->perfilUsuario == 'PFL_TEC_RES_NOTI')){
		  echo '<div id="check" name="check" data-linea="3">
            <label for="respuesta">¿Finalizar intercambio de información para esta revisión del operador?: SI</label>
                <input type="checkbox" id="finalizarRespuestaOperador" name="finalizarRespuestaOperador" />
        </div>';
		}
		?>
		
                <button type="submit" class="guardar"> Enviar Respuesta</button>
		<?php echo $this->panelBusquedaOperadores;?>
	</fieldset>
</form>


<script>
    $(document).ready(function () {   
        $("#formulario").hide(); 
        fn_mostrarDetalleRespuestas();
        distribuirLineas();
    });  

    function fn_mostrarDetalleRespuestas() {
        $.post("<?php echo URL ?>NotificacionesFitosanitarias/RespuestaNotificacion/imprimirPreguntasOperador", 
    			{
                	idRespuestaNotificacion : $("#idRespuestaNotificacion").val(),
                	idNotificacion : $("#idNotificacion").val(),
                	identificador : $("#identificador").val()
    			},
                function (data) {
                    $("#seccionRespuestas").html(data);
                    distribuirLineas();
                });
    }
    
    function formularioRespuesta(idRespuesta) {       
        $("#formulario").show();
       
        distribuirLineas();
        $("#idNotificacion1").val($("#idNotificacion"+idRespuesta).val()); 
        $("#idRespuestaNotificacion1").val($("#idRespuestaNotificacion"+idRespuesta).val()); 
        $("#idPadre1").val($("#idPadre"+idRespuesta).val()); 
    }

    $("#btnFiltrarNotificacion").click(function (event) { 
        event.preventDefault(); 

        $("#btnFiltrarNotificacion").hide(); 
        $("#formulario").show(); 
        distribuirLineas();
        $("#idNotificacion1").val($("#idNotificacion").val()); 
        $("#idRespuestaNotificacion1").val($("#idRespuestaNotificacion").val()); 
        $("#idPadre1").val($("#idPadre").val());  
        $("#identificador1").val(<?php echo $_SESSION['usuario'];?>); 
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

	if (!error) {
		var respuesta = JSON.parse(ejecutarJson($(this)).responseText);

		if (respuesta.estado == 'exito'){
        }else {
            $("#estado").html(respuesta.mensaje).addClass("alerta");
        }
    }
});

$("#regresar").submit(function(event){
		event.preventDefault();
				abrir($(this),event,false);
	});

</script>

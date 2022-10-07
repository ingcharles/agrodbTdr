<header>
    <h1><?php echo $this->detalleFormulario; ?></h1>
</header>
<fieldset>
	<legend>Detalle Notificación</legend>
		<div data-linea="1">
			<label for="respuesta">Cód Documento: </label><?php echo $this->modeloNotificaciones->getCodigoDocumento(); ?>
		</div>
		<div data-linea="2">
			<label for="respuesta">País que notifica: </label><?php echo $this->modeloNotificaciones->getNombrePaisNotifica(); ?>
		</div>
		<div data-linea="3">
			<label for="respuesta">Tipo de documento: </label><?php echo $this->modeloNotificaciones->getTipoDocumento(); ?>
		</div>
		<div data-linea="4">
			<label for="respuesta">Fecha notificación: </label><?php echo date('Y-m-d',strtotime($this->modeloNotificaciones->getFechaNotificacion())); ?>
		</div>
		<div data-linea="5">
			<label for="respuesta">Producto: </label><?php echo $this->modeloNotificaciones->getProducto(); ?>
		</div>
		<div data-linea="6">
			<label for="respuesta">Palabras clave de la notificación: </label><?php echo $this->modeloNotificaciones->getPalabraClave(); ?>
		</div>
		
		<div data-linea="7">
			<label for="respuesta">Descripción Notificación: </label><?php echo $this->modeloNotificaciones->getDescripcion();?>
		</div>
		<div data-linea="8">
			<label for="respuesta">Enlace: </label>
			<?php 
			    if($this->modeloNotificaciones->getEnlace() != '' || $this->modeloNotificaciones->getEnlace() != null){
			        echo '<a href ="' .$this->modeloNotificaciones->getEnlace(). '" target="_blank" >Abrir enlace</a>';
    			}else{
    				echo "No existe un enlace";
    			}
			?>
		</div>
        <div data-linea="9">
			<label for="respuesta">Área temática: </label><?php echo $this->areaTematica;?>
		</div>
		 <div data-linea="10">
		 <?php 
		    if($this->perfilUsuario == 'PFL_TEC_RES_NOTI' ){
			  echo '<label><input type="checkbox" id="btnComentarios" value="Si" >&nbsp;Notificación no requiere comentarios</label>';
		    }
			?>
		</div>
		 <div data-linea="10">
            <?php 
             //   $fechaActual = date('Y-m-d');
            //    if(($this->perfilUsuario != 'PFL_TEC_RES_NOTI') and ($fechaActual <= $this->modeloNotificaciones->getFechaCierre())){
            //    if( ($fechaActual <= $this->modeloNotificaciones->getFechaCierre()) and $this->modeloNotificaciones->getComentarios() != 'no'){
                		echo '<div id="botonResponder" data-linea="7">
			<button id="btnFiltrarNotificacion">Responder</button>
                        </div>';
		   //}
            ?>
		</div>
</fieldset>
<div >
   <h2>Revisiones realizadas</h2>
</div>
    	<div data-linea="22" id="comentarioTec">
    		
		</div>
<fieldset>
        <legend>Revisiones Realizadas</legend>
    	
    	<div data-linea="23">
    		<div id="tablaOperador" name="tablaOperador"> </div>
    		<div id="tablaTecnico" name="tablaTecnico"> </div>
    		<div id="tablaTecnicoComentarios" name="tablaTecnicoComentarios"> </div>
		</div>
		
</fieldset>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>NotificacionesFitosanitarias' data-opcion='respuestaNotificacion/guardar' 
data-destino="detalleItem" method="post" >
	<input type="hidden" id="idNotificacion" name="idNotificacion" value="<?php echo $_POST['id']?>"  />
        <input type="hidden" id="fechaCierre" name="fechaCierre" value="<?php echo date('Y-m-d',strtotime($this->modeloNotificaciones->getFechaCierre())); ?>" >
                        
        
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
			<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/mvc/modulos/NotificacionesFitosanitarias/archivos/respuestaOperadorNotificacion">Subir archivo</button>
		</div>

                <button type="submit" class="guardar"> Enviar Respuesta</button>
		
	</fieldset>
</form>

<script type="text/javascript">
var perfil = <?php echo json_encode($this->perfilUsuario); ?>;
var comentarios = <?php echo json_encode($this->modeloNotificaciones->getComentarios());?>;
var idNotificacion = <?php echo json_encode($this->modeloNotificaciones->getIdNotificacion());?>;

	$(document).ready(function () {
        distribuirLineas();    
        $("#formulario").hide(); 

        if(perfil === 'PFL_OPE_PRE_NOTI'){
        	fn_mostrarDetalleOperador();
        }else if(perfil === 'PFL_TEC_RES_NOTI'){
        	fn_mostrarDetalleTecnico();
        }

        if(comentarios == 'no'){
            $("#btnComentarios").attr('checked',true);
        	$("#btnFiltrarNotificacion").attr('disabled', true);
        }else{
        	$("#btnFiltrarNotificacion").attr('disabled', false);
        }
   });

	//Para cargar el detalle de fiscalizaciones registradas
    function fn_mostrarDetalleOperador() {
        var idMovilizacion = $("#idNotificacion").val();
        
    	$.post("<?php echo URL ?>NotificacionesFitosanitarias/RespuestaNotificacion/tablaHtmlRevisionesRealizadasOperador/" + idMovilizacion, function (data) {
            $("#tablaOperador").html(data);
        });
    }

    function fn_mostrarDetalleTecnico() {
        var idMovilizacion = $("#idNotificacion").val();

    	$.post("<?php echo URL ?>NotificacionesFitosanitarias/RespuestaNotificacion/tablaHtmlRevisionesRealizadasTecnico/" + idMovilizacion, function (data) {
            $("#tablaTecnico").html(data);
        });

    	$.post("<?php echo URL ?>NotificacionesFitosanitarias/RespuestaNotificacion/tablaHtmlRevisionesRealizadasTecnicoComentarios/" + idMovilizacion, function (data) {
            $("#tablaTecnicoComentarios").html(data);
        });
    }
    
    $("#btnFiltrarNotificacion").click(function (event) { 
            event.preventDefault();
            var fechaCierre = $("#fechaCierre").val(); 
            var hoy = new Date().toJSON().slice(0,10)
         
            if(hoy <= fechaCierre){   
                $("#btnFiltrarNotificacion").hide(); 
                $("#formulario").show(); 
            }else{
                $("#btnFiltrarNotificacion").hide(); 
                alert('Fecha fuera de notificación');
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

	/*if($("#archivo").val() == 0){
		error = true;
		$("#informe").addClass("alertaCombo");
	}*/

	if (!error) {
		var respuesta = JSON.parse(ejecutarJson($(this)).responseText);

		if (respuesta.estado == 'exito'){

			   if(perfil === 'PFL_TEC_RES_NOTI'){
				    distribuirLineas();
				    $("#comentarioTec").html(respuesta.contenido);
		        }
        }else {
            $("#estado").html(respuesta.mensaje).addClass("alerta");
        }
    }
});


//Funcion para listar revisones realizadas
    function fn_verRespuestaNotificacion(idRespuestaNotificacion,idNotificacion,identificador,estadoRespuesta= null){      
         
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
            error: function (jqXHR, textStatus, errorThrown){
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
  //Funcion para listar revisones realizadas
    function fn_verRespuestaNotificacionTecnico(idRespuestaNotificacion,idNotificacion,identificador,estadoRespuesta= null){      
         
        var elementoDestino = "#detalleItem";
        var url = "<?php echo URL ?>NotificacionesFitosanitarias/respuestaNotificacion/verRevisionesNotificacionesTecnico";
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
            error: function (jqXHR, textStatus, errorThrown){
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
    $("#btnComentarios").click(function (event) { 
    	var comentarios = 'no';
    	if($(this).is(':checked')){
    	  	$("#btnFiltrarNotificacion").attr('disabled', true);
    	  	$("#btnFiltrarNotificacion").hide();
    	  	$("#formulario").hide(); 
    	  	$.post("<?php echo URL ?>NotificacionesFitosanitarias/Notificaciones/actualizarComentarios",
    	  			{   
    	  		        comentarios: 'no',
					    id_notificacion: idNotificacion,
					},function (data) {
            });
    	  }else{
    		  $("#btnFiltrarNotificacion").show();
    		 
    		  $("#btnFiltrarNotificacion").attr('disabled', false);
	      	  	$.post("<?php echo URL ?>NotificacionesFitosanitarias/Notificaciones/actualizarComentarios",
	      	  			{
	      	  		        comentarios: 'si',
	  					    id_notificacion: idNotificacion,
	  					},function (data) {
	              });
    	  }
}); 
</script>

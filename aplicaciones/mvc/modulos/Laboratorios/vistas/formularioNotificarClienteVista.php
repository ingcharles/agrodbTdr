<header>
    <h1><?php echo $this->accion; ?></h1>
</header>		
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Laboratorios'			 
      data-opcion = 'BandejaRecepcion/enviarNotificacionClienteManual' data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post" lang="" >			
    <fieldset>			
        <legend>Enviar notificaci&oacute;n al cliente</legend>			
        <div data-linea="1">
            <label for="">Cliente:</label> 
            <input type="text" readonly style="background: transparent; border: 0" value="<?php echo $this->datosUsuario['nombre']; ?>"/>
        </div>

        <div data-linea="2">
            <label for="">Solicitud:</label> 
            <input type="text" readonly style="background: transparent; border: 0" value="<?php echo $this->modeloSolicitudes->getCodigo(); ?>"/>
        </div>

        <div data-linea="3">
            <label for="asunto">Asunto:</label> 
            <input type="text" id="asunto" name="asunto" placeholder="Asunto" required/>
        </div>

        <label for="cuerpo">Mensaje:</label> 
        <div data-linea="4">
            <textarea id="cuerpo" name="cuerpo" 
                      placeholder="" required></textarea>
        </div>

        <div data-linea ="5">			
            <input type ="hidden" name="id_solicitud" id="id_solicitud" value ="<?php echo $this->modeloSolicitudes->getIdSolicitud(); ?>">			
            <input type ="hidden" name="mail" id="mail" value ="<?php echo $this->datosUsuario['email']; ?>">	
            <input type ="hidden" name="identificador_cliente" id="identificador_cliente" value ="<?php echo $this->datosUsuario['identificador']; ?>">	
            <button type ="submit" class="fas fa-share-square"> Enviar</button>
        </div >
    </fieldset>
</form>
<script type ="text/javascript">
    $(document).ready(function () {
        $('.checklist').fSelect();
<?php echo $this->codigoJS ?>
        distribuirLineas();
    });

    $("#formulario").submit(function (event) {
        event.preventDefault();
        var error = false;
        if (!error) {
            var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
            //Traemos la lista solo si guardo correctamenre
            if(respuesta.estado == 'exito')
            {
                $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí una solicitud para revisar las órdenes de trabajo.</div>');
            }
        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });
</script>

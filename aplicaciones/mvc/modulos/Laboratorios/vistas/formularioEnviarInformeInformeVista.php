<header>
    <h1><?php echo $this->accion; ?></h1>
</header>		
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Laboratorios'			 
      data-opcion = 'ArchivoInformeAnalisis/enviarNotificacionInforme' data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post" lang="" >			
    <fieldset>			
        <legend>Enviar informe</legend>			
        <?php if ($this->requierePago == 'NO') : ?>
            <div data-linea="1">
                <label><input type="checkbox" id="checkNotificarCreaSol" name="checkNotificarCreaSol" value="1">Enviar al usuario que registr&oacute; la solicitud </label>
            </div>
            <hr>
            <div data-linea="2">
                <label>Enviar una copia a:</label> 
                <select id="destinatario_correo" name="destinatario_correo[]" multiple="multiple" class="checklist">
                    <option value="">Ninguno</option>
                    <?php
                    echo $this->comboCatalogoLab(\Agrodb\Core\Constantes::catalogos_lab()->COD_ENVIO_INFORME);
                    ?>
                </select>
            </div>

            <div data-linea ="3">			
                <input type ="hidden" name="id_archivo_informe_analisis" id="id_archivo_informe_analisis" value ="<?php echo $this->modeloArchivoInformeAnalisis->getIdArchivoInformeAnalisis() ?>">			
                <button type ="submit" class="fas fa-share-square"> Enviar informe</button>
            </div>
        <?php else : ?>
            <div data-linea="1">
                <label><?php echo Agrodb\Core\Constantes::REQUIERE_PAGO; ?></label>
            </div>
        <?php endif; ?>
    </fieldset>
</form>
<script type ="text/javascript">
    $(document).ready(function () {
        $('.checklist').fSelect();
<?php echo $this->codigoJS ?>
        construirValidador();
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
            fn_filtrar();
            }
        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });
</script>

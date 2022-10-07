<header>
    <h1>Reingreso de muestras</h1>
</header>
<fieldset>
    <legend>&Oacute;rdenes de trabajo generadas</legend>

    <table width="100%" id="tablaOrdenTrabajo">
        <thead>
            <tr>
                <th>#</th>
                <th>C&oacute;digo</th>
                <th>Laboratorio</th>
                <th>Fecha de inicio</th>
                <th>Estado</th>
                <th>Costo en Orden</th>
                <th>Descargar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($this->itemsOrdenesTrabajo as $fila)
            {
                echo $fila[0];
            }
            ?>
        </tbody>
    </table>
</fieldset>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Laboratorios'
      data-opcion='BandejaRecepcion/guardarMuestrasReingreso' data-destino="detalleItem"
      data-accionEnExito="NADA" method="post">
    <fieldset>
        <legend>Recepción de la muestra</legend>

        <table width="100%" id="muestras">
            <thead>
                <tr>
                    <th>#</th>
                    <th>C&oacute;digo de campo</th>
                    <th>Análisis</th>
                    <th>Conservación de la muestra</th>
                    <th>Observación</th>
                    <th>Fecha toma</th>
                    <th>Responsable</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($this->itemsMuestras as $muestra)
                {
                    echo $muestra[0];
                }
                ?>
            </tbody>
        </table>
        <div data-linea="1">
            <input type ="hidden" id="idSolicitud" name ="idSolicitud" value="<?php echo $this->idSolicitud; ?>"/>
            <input type ="hidden" id="idLaboratorio" name ="idLaboratorio" value="<?php echo $this->idLaboratorio; ?>"/>
            <input type ="hidden" id="idOrdenTrabajo" name ="idOrdenTrabajo" value="<?php echo $this->idOrdenTrabajo; ?>"/>

            <button type="submit" class="guardar"> Guardar</button> 
        </div>
    </fieldset>
    <input
        type="hidden" name="identificador" id="identificador"
        value="<?php echo $this->usuarioActivo() ?>">
</form>
<script type="text/javascript">
<?php echo $this->codigoJS ?>

    function fn_activarOrden() {
        var url = "BandejaRecepcion/activarOrden";
        $("#formulario").attr('data-opcion', url);
        $("#formulario").submit();
    }

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

    function  fn_descargar_ot(idOrdenTrabajo) {
        var url = "<?php echo URL ?>Laboratorios/BandejaInformes/descargarOt/" + idOrdenTrabajo;
        $.ajax({
            type: "POST",
            url: url,
            dataType: "text",
            contentType: "application/x-www-form-urlencoded; charset=latin1",
            beforeSend: function () {

            },
            success: function (html) {
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $(elementoDestino).html('Error al cargar los campos de resultado')
            },
            complete: function () {

            }
        });
    }

</script>
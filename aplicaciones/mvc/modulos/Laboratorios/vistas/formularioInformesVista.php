<header>
    <h1>Verificaci&oacute;n muestras</h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Laboratorios'
      data-opcion='BandejaResponsableTecnico/almacenarMuestra' data-destino="detalleItem"
      data-accionEnExito="NADA" method="post">
    <fieldset>
        <legend>Informes de la orden seleccionada</legend>
        <table id="muestras">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Tipo</th>
                    <th>Fecha fin An&aacute;lisis</th>
                    <th>Observación</th>
                    <th>Estado</th>
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

        </div>
    </fieldset>
    <input type="hidden" name="identificador" id="identificador"
           value="<?php echo $this->usuarioActivo() ?>">
</form>

<!-- Código javascript -->
<script type="text/javascript">
<?php echo $this->codigoJS ?>
    distribuirLineas();
    $("#formulario").submit(function (event) {
        event.preventDefault();
        var error = false;
       if (!error) {
            var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
            //Traemos la lista solo si guardo correctamenre
            if(respuesta.estado == 'exito')
            {
           // fn_filtrar();
            }
        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });

    //Cuando seleccionamos una dirección, llenamos el combo de laboratorios
    $("#direccion").change(function () {
        $.post("<?php echo URL ?>Laboratorios/BandejaResponsableTecnico/comboLaboratorios/" + $(this).val(), function (data) {
            $("#laboratorio").html(data);
            $("#laboratorio").removeAttr("disabled");
        });
    });
    //Cuando seleccionamos un laboratorio, llenamos el combo de servicios
    $("#laboratorio").change(function () {
        $.post("<?php echo URL ?>Laboratorios/Solicitudes/comboServicios/" + $(this).val(), function (data) {
            $("#servicio").html(data);
            $("#servicio").removeAttr("disabled");
        });
    });
</script>

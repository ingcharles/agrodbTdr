<link rel='stylesheet'
      href='<?php echo URL_RESOURCE ?>estilos/bootstrap.min.css'>
<script
src="<?php echo URL_MVC_MODULO ?>Laboratorios/vistas/js/laboratoriosjs.js" type="text/javascript"></script>
<header>
    <h1>Confirmaci&oacute;n de an&aacute;lisis</h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Laboratorios'
      data-opcion='Solicitudes/nuevoConfirmacionAnalisis' data-destino="detalleItem"
      data-accionEnExito="ACTUALIZAR" method="post">
    <fieldset>
        <legend>Confirmaci&oacute;n de an&aacute;lisis</legend>
        <table id="muestras">
            <thead>
                <tr>
                    <th>#</th>
                    <th title="Clic para seleccionar la muestra a confirmar">Confirmar</th>
                    <th title="C&oacute;digo de la muestra">C&oacute;digo</th>
                    <th title="C&oacute;digo de campo de la muestra">C&oacute;digo de campo</th>
                    <th title="An&&aacute;lisis de la muestra">An&aacute;lisis</th>
                    <th title="Muestra id&oacute;nea">Muestra Id&oacute;nea</th>
                    <th title="Fecha de inicio del an&aacute;lisis">Fecha inicio An&aacute;lisis</th>
                    <th title="Ver otros datos">+ Datos</th>
                    <th>Estado Muestra</th>
                    <th>Estado Aprobaci&oacute;n</th>
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
            <input type ="hidden" id="id_solicitud" name ="id_solicitud" value="<?php echo $this->modeloSolicitudes->getIdSolicitud(); ?>"/>
            <input type ="hidden" id="usuario_guia" name ="usuario_guia" value="<?php echo $this->modeloSolicitudes->getUsuarioGuia(); ?>"/>
            <input type ="hidden" id="idLaboratorio" name ="idLaboratorio" value="<?php echo $this->idLaboratorio; ?>"/>
            <input type ="hidden" id="idOrdenTrabajo" name ="idOrdenTrabajo" value="<?php echo $this->idOrdenTrabajo; ?>"/>

        </div>
    </fieldset>

    <fieldset>
        <legend class='legendMuestras'>Datos para la Confirmaci&oacute;n</legend>

        <div data-linea="1">
            <label for="chkNuevaMuestra">Requiere nueva muestra?</label>
            <input type="checkbox" id="chkNuevaMuestra" name="chkNuevaMuestra" value="SI"/> 
        </div>

        <div data-linea="2">
            <label
                for="chkacepta">Requiere pago?</label>
            <input type="checkbox" id="chkNuevoPago" name="chkNuevoPago" value="SI"/> 
        </div>

        <div data-linea="3">
            <label
                for="chkacepta">Notificar al cliente?</label>
            <input type="checkbox" id="chkNotificarCliente" name="chkNotificarCliente" value="SI"/> 
        </div>

        <div data-linea="4">
            <label
                for="chkInforme">Informe principal depende del resultado de esa solicitud?</label>
            <input type="checkbox" id="chkInforme" value=""/> 
        </div>
        <button type="submit" class="">Continuar &gt;&gt;</button>
    </fieldset>
</form>

<!-- Código javascript -->
<script type="text/javascript">
<?php echo $this->codigoJS ?>
    distribuirLineas();
    distribuirLineas();
    $("#formulario").submit(function (event) {
        event.preventDefault();
        if ($('[name="muestras[]"]:checked').length > 0) {
            var error = false;
        } else {
            mostrarMensaje("Seleccione las muestras a confirmar", "FALLO");
        }

        if (!error) {
            abrir($(this), event, false);   //requerido esta fn para abrir nuevo formulario
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

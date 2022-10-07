<link rel='stylesheet'
      href='<?php echo URL_RESOURCE ?>estilos/bootstrap.min.css'>
<script
src="<?php echo URL_MVC_MODULO ?>Laboratorios/vistas/js/laboratoriosjs.js" type="text/javascript"></script>
<header>
    <h1>Emisi&oacute;n de marbetes</h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Laboratorios'
      data-opcion='BandejaResponsableTecnico/guardarVerificacionMarbetes' data-destino="detalleItem"
      data-accionEnExito="NADA" method="post">
    <fieldset>
        <legend>Emisi&oacute;n de marbetes</legend>
        <table id="muestras">
            <thead>
                <tr>
                    <th>#</th>
                    <th title="N&uacute;mero de lote">No Lote</th>
                    <th title="Cantidad de marbetes">Cantidad de marbetes</th>
                    <th title="Fecha impresi&oacute;n">Fecha impresi&oacute;n</th>
                    <th title="No. inicio de serie">No. inicio de serie</th>
                    <th title="No. fin de serie">No. fin de serie</th>
                    <th title="Estado">Estado</th>
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
            <input type ="hidden" id="notificar" name ="notificar" value="0"/>
        </div>

        <?php if ($this->modeloOrdenTrabajo->getEstado() == 'ACTIVA'): ?>
            <button type="submit" class="guardar"> Guardar</button>
        <?php elseif ($this->modeloOrdenTrabajo->getEstado() == 'EN PROCESO' & $this->idoneaEnProceso) : ?>
            <button type="submit" class="guardar"> Guardar</button>
        <?php endif; ?>
    </fieldset>
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
            fn_filtrar();
            }
        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });
</script>
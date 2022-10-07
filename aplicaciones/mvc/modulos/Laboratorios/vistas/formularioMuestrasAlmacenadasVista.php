<link rel='stylesheet'
      href='<?php echo URL_RESOURCE ?>estilos/bootstrap.min.css'>
<header>
    <h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Laboratorios'
      data-opcion='' data-destino="detalleItem"
      data-accionEnExito="NADA" method="post">
    <fieldset>
        <legend>Muestras almacenadas</legend>
        <div id="paginacionMuestrasAlmacendadas" class="normal"></div>
        <table width="100%" class="table" id="tblMuestrasAlmacenadas">
            <thead>
                <tr>
                    <th>#</th>
                    <th width="140px">C&oacute;digo</th>
                    <th>C&oacute;digo de campo</th>
                    <th>An&aacute;lisis</th>
                    <th>Fecha fin An&aacute;lisis</th>
                    <th>Estado</th>
                    <th title="Seleccionar la fecha de fin de almacenamiento">Fecha fin Almacenamiento</th>
                    <th title="Seleccione si desea cambiar de estado">Desechada</th>
                </tr>
            </thead>
            <tbody>
                <?php
                echo $this->itemsMuestrasAlmacenadas;
                ?>
            </tbody>
        </table>
        <div data-linea="1">
            <input type="hidden" id="id_orden_trabajo" name ="id_orden_trabajo" value="<?php echo $this->idOrdenTrabajo; ?>"/>
        </div>
    </fieldset>
    <input
        type="hidden" name="identificador" id="identificador"
        value="<?php echo $this->usuarioActivo() ?>">
</form>

<!-- Código javascript -->
<script type="text/javascript">
<?php echo $this->codigoJS ?>
    distribuirLineas();

    //Actualiza los datos por cada registro
    function fn_actualizarDatos(idRecepcionMuestras) {
        $("#estado").removeClass();
        var url = "<?php echo URL ?>Laboratorios/BandejaResponsableTecnico/actualizar";
        $.ajax({
            type: "POST",
            url: url,
            data: {
                id_orden_trabajo: $("#id_orden_trabajo").val(),
                id_recepcion_muestras: idRecepcionMuestras,
                fecha_fin_almacenamiento: $("#fecha_fin_almacenamiento_" + idRecepcionMuestras).val(),
                estado_actual: $("#estado_actual_" + idRecepcionMuestras).val(),
                id_laboratorios_provincia: $("#id_laboratorios_provincia").val()
            },
            dataType: "json",
            contentType: "application/x-www-form-urlencoded; charset=latin1",
            beforeSend: function () {
                $("#paginacionMuestrasAlmacendadas").html("<div id='cargando'>Cargando...</div>");
            },
            success: function (data) {
                $("#paginacionMuestrasAlmacendadas").html("");
                $("#tblMuestrasAlmacenadas tbody").html(data.datos);
                mostrarMensaje(data.mensaje, "EXITO");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $("#paginacionMuestrasAlmacendadas").html("");
            },
            complete: function () {

            }
        });
    }
</script>

<script src="<?php echo URL_RESOURCE ?>js/js_comunes.js" type="text/javascript"></script>
<script src="<?php echo URL_MVC_MODULO ?>Laboratorios/vistas/js/laboratoriosjs.js" type="text/javascript"></script>
<link rel='stylesheet' href='<?php echo URL_RESOURCE ?>estilos/bootstrap.min.css'>
<script src="<?php echo URL_RESOURCE ?>js/bootstrap.min.js" type="text/javascript"></script>
<link rel='stylesheet' href='<?php echo URL_RESOURCE ?>estilos/fSelect.css'>
<header>
    <h1>Analizar muestras</h1>
    <nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>
<fieldset>
    <legend class='legendBusqueda'>Filtros de b&uacute;squeda</legend>

    <?php echo $this->laboratoriosProvinciaUsuario(); ?>

    <div data-linea="1">
        <label for="fCodigo">C&oacute;digo</label> 
        <input type="text" id="fCodigo" name="fCodigo"/>
    </div>

    <div data-linea="2">
        <button id="btnFiltrar" class="fas fa-search"> Filtrar lista</button>
        <button id="btnLimpiar" class="fas fa-times"> Limpiar filtros</button>
    </div>
</fieldset>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
    <thead>
        <tr>
            <th>#</th>
            <th title="C&oacute;digo de la muestra">C&oacute;digo</th>
            <th title="Cliente">Cliente</th>
            <th title="Visualizar datos y anexos de la solicitud">Datos</br>solicitud</th>
            <th title="Descargar la orden de trabajo">Descargar O.T.</th>
            <th title="Fecha de activaci&oacute;n de la orden de trabajo">Fecha activaci&oacute;n</th>
            <th title="Estado de la orden de trabajo">Estado</th>
        </tr>
    </thead>
    <tbody>
        <tr><td colspan="5">Dar clic en Filtrar lista para mostrar los datos</td></tr>
    </tbody>
</table>

<script>
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
        distribuirLineas();
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para editarla.</div>');

        //Cuando se presiona en Filtrar lista, debe cargar los datos
        $("#btnFiltrar").click(function () {
            if ($("#id_laboratorios_provincia").val() === "") {
                $('#id_laboratorios_provincia').addClass("alertaCombo");
                mostrarMensaje("Seleccione el Laboratorio.", "FALLO");
            } else {
                fn_filtrar();
            }
        });

        //Para Limpiar los filtros
        $("#btnLimpiar").click(function () {
            $("#fCodigo").val('');
        });
    });

    // Función para filtrar
    function fn_filtrar() {
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para editar.</div>');
        $("#paginacion").html("<div id='cargando'>Cargando...</div>");
        $.post("<?php echo URL ?>Laboratorios/BandejaResponsableTecnico/listarDatosAnalizar",
                {
                    id_laboratorios_provincia: $("#id_laboratorios_provincia").val(),
                    codigo_ot: $("#fCodigo").val()
                },
        function (data) {
            construirPaginacion($("#paginacion"), JSON.parse(data));
            $("#listadoItems").removeClass("comunes");
        });
    }

    // Función para visualizar la orden de trabajo
    function fn_verPdf(idOrden) {
        var elementoDestino = "#detalleItem";
        var url = "<?php echo URL ?>Laboratorios/BandejaInformes/descargarOt/" + idOrden;
        var data = {
            id: "_nuevo",
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
                $(elementoDestino).html(html);
                redimensionarVentanaTrabajo();
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

<header>
    <h1>&Oacute;rdenes de trabajo</h1>
    <nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>
<div id="cantidadItemsSeleccionados" style="display: none">0</div>
<fieldset>
    <legend class='legendBusqueda'>Filtros de b&uacute;squeda</legend>

    <?php echo $this->laboratoriosProvinciaUsuario(); ?>

    <div data-linea="1">
        <label for="fEstado">Estado Orden de T.</label> 
        <select id="fEstado" name="fEstado">
            <option value="">TODOS...</option>
            <?php
            foreach ($this->estados as $item)
            {
                echo '<option value="' . $item . '">' . $item . '</option>';
            }
            ?>
        </select>
    </div>

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
            <th title="Informes de la Orden de Trabajo">Informes</th>
            <th title="Fecha de activaci&oacute;n de la orden de trabajo">Fecha activaci&oacute;n</th>
            <th title="Estado de la orden de trabajo">Estado</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<script>
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
        distribuirLineas();
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para editar.</div>');
        $("#listadoItems").removeClass("comunes");

        //Cuando se presiona en Filtrar lista, debe cargar los datos
        $("#btnFiltrar").click(function () {
            if ($("#id_laboratorios_provincia").val() === "") {
                $("#id_laboratorios_provincia").addClass("alertaCombo");
                mostrarMensaje("Seleccione el Laboratorio.", "FALLO");
            } else {
                fn_filtrar();
            }
        });

        //Para Limpiar los filtros
        $("#btnLimpiar").click(function () {
            $("#fEstado").val('');
            $("#fCodigo").val('')
        });
    });

    // Función para filtrar
    function fn_filtrar() {
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí la configuración para editarla.</div>');
        $("#paginacion").html("<div id='cargando'>Cargando...</div>");
        $.post("<?php echo URL ?>Laboratorios/BandejaResponsableTecnico/listarDatos",
                {
                    id_laboratorios_provincia: $("#id_laboratorios_provincia").val(),
                    estado_orden: $("#fEstado").val(),
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

    $("#_labetiquetas").click(function () {
        if ($(".seleccionado").attr('id') === undefined) {
            mostrarMensaje("Por favor seleccione un registro a la vez.", "FALLO");
            return false;
        }
    });

    //Controles para la accion Muestras almacenadas
    $("#_labmuestras").click(function () {
        if ($("#id_laboratorios_provincia").val() === "") {
            $('#id_laboratorios_provincia').addClass("alertaCombo");
            mostrarMensaje("Seleccione el Laboratorio.", "FALLO");
            return false;
        } else if ($("#cantidadItemsSeleccionados").text() > 1) {
            //si es 0 entonces se muestra todas las muestras del laboratorio seleccionado
            //si es >1 entonces controlar que sea solo uno
            mostrarMensaje("Por favor seleccione un registro a la vez.", "FALLO");
            return false;
        } else {
            $("#_labmuestras").attr('data-opcion', "bandejaResponsableTecnico/muestras/" + $("#id_laboratorios_provincia").val());
        }
    });
</script>



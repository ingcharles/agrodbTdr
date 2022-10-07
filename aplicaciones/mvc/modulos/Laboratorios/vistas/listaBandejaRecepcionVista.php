<script
src="<?php echo URL_MVC_MODULO ?>Laboratorios/vistas/js/laboratoriosjs.js" type="text/javascript"></script>
<header>
    <h1>Bases de datos</h1>
    <nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>
<div id="cantidadItemsSeleccionados" style="display: none">0</div>
<fieldset>
    <legend class='legendBusqueda'>Filtros de b&uacute;squeda</legend>
    <div data-linea="1">
        <label for="fEstado">Estado Solicitud</label> 
        <select id="fEstado" name="fEstado" title="Estado de la Solicitud">
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
        <label for="fCodigo">C&oacute;digo Solicitud</label> 
        <input type="text" id="fCodigo" name="fCodigo" placeholder="C&oacute;digo de la Solicitud" title="C&oacute;digo de la Solicitud"/>
    </div>

    <div data-linea="1">
        <label for="fCliente">Nombre Cliente</label> 
        <input type="text" id="fCliente" name="fCliente" placeholder="Nombre del cliente" title="Nombre del cliente"/>
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
            <th title="C&oacute;digo de la Solicitud creada por el sistema">C&oacute;digo Solicitud</th>
            <th title="Nombre del cliente">Cliente</th>
            <th title="Tipo de solicitud">Tipo</th>
            <th title="Visualizar memo si tiene exoneraci&oacute;n de pago">Memo</th>
            <th title="Visualizar datos y anexos de la solicitud">Datos</br>solicitud</th>
            <th title="Fecha en que envi&oacute; la solicitud del cliente">Fecha env&iacute;o</th>
            <th title="Costo total de la solicitud">Costo</th>
            <th title="Estado actual de la solicitud; si una orden de trabajo no finaliza, la solicitud debe seguir EN PROCESO.">Estado</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<!-- Código javascript -->
<script type="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
        distribuirLineas();
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí una solicitud para revisar las órdenes de trabajo.</div>');
        $("#listadoItems").removeClass("comunes");

        //Cuando se presiona en Filtrar lista, debe cargar los datos
        $("#btnFiltrar").click(function () {
            fn_filtrar();
        });

        //Para Limpiar los filtros
        $("#btnLimpiar").click(function () {
            $("#fEstado").val('');
            $("#fCodigo").val('');
            $("#fCliente").val('');
        });

        //Parar abrir el formulario de reingreso de muestras
        $("#_labrmuestras").click(function () {
            if ($(".seleccionado").attr('id') === undefined) {
                mostrarMensaje("Por favor seleccione un registro a la vez.", "FALLO");
                return false;
            }
        });
    });

    $("#_labnotificar").click(function () {
        if ($("#cantidadItemsSeleccionados").text() === 0 | $("#cantidadItemsSeleccionados").text() > 1) {
            mostrarMensaje("Por favor seleccione un registro a la vez.", "FALLO");
            return false;
        }
    });

    $("#_labordentrabajo").click(function () {
        if ($("#cantidadItemsSeleccionados").text() === 0 | $("#cantidadItemsSeleccionados").text() > 1) {
            mostrarMensaje("Por favor seleccione un registro a la vez.", "FALLO");
            return false;
        }
    });

    // Función para filtrar
    function fn_filtrar() {
        $("#paginacion").html("<div id='cargando'>Cargando...</div>");
        $.post("<?php echo URL ?>Laboratorios/BandejaRecepcion/listarDatos",
                {
                    estado: $("#fEstado").val(),
                    codigo: $("#fCodigo").val(),
                    cliente: $("#fCliente").val()
                },
        function (data) {
            construirPaginacion($("#paginacion"), JSON.parse(data));
            $("#listadoItems").removeClass("comunes");
        });
    }

    ///Funcion para abrir la vista con el documento adjunto
    function fn_abrirVistaMemo(idSolicitud) {
        var elementoDestino = "#detalleItem";
        var data = {
            idSolicitud: idSolicitud
        };
        $.ajax({
            type: "POST",
            url: "<?php echo URL ?>Laboratorios/BandejaRecepcion/verMemo",
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



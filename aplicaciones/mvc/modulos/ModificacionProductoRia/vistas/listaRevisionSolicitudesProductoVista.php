<header>
    <nav><?php echo $this->panelBusqueda; ?></nav>
    <nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
    <thead>
    <tr>
        <th>#</th>
        <th>Código</th>
        <th>Tipo</th>
        <th>Producto</th>
        <th>Titular</th>
        <th>Provincia</th>
        <th>Técnico</th>
    </tr>
    </thead>
    <tbody></tbody>
</table>

<script>
    $(document).ready(function () {
        $("#listadoItems").removeClass("comunes");
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
    });

    $("#id_provincia").change(function (event) {
        mostrarMensaje("", "EXITO");
        if ($("#id_provincia").val() !== '') {
            $("#nombre_provincia").val($("#id_provincia option:selected").text());
        } else {
            $("#nombre_provincia").val('');
        }
    });

    //Cuando se presiona en Filtrar lista, debe cargar los datos
    $("#btnFiltrar").click(function () {

        var error = false;
        $(".alertaCombo").removeClass("alertaCombo");
        mostrarMensaje("", "EXITO");

        $('#fBusqueda .validacion').each(function (i, obj) {
            if (!$.trim($(this).val())) {
                error = true;
                $(this).addClass("alertaCombo");
            }
        });

        if (!$("input[name='tipo']").is(':checked')) {
            $("input[name='tipo']").addClass("alertaCombo");
            $("#l_razon").addClass("alertaCombo");
            $("#l_id").addClass("alertaCombo");
            error = true;
        }

        if (!error) {
            fn_filtrar();
        }

    });

    function fn_filtrar() {

        $("#paginacion").html("<div id='cargando'>Cargando...</div>");
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');

        $.post("<?php echo URL ?>ModificacionProductoRia/RevisionSolicitudesProducto/listarSolicitudesProducto",
            {
                tipo: $('[name="tipo"]:checked').val(),
                id_provincia: $('#id_provincia').val(),
                nombre_provincia: $('#nombre_provincia').val(),
                tipo_solicitud: $('#tipo_solicitud').val(),
                busqueda: $('#busqueda').val()
            },

            function (data) {
                if (data.estado === 'FALLO') {
                    construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
                    mostrarMensaje(data.mensaje, "FALLO");
                } else {
                    construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
                }
            }, 'json');
    }
</script>

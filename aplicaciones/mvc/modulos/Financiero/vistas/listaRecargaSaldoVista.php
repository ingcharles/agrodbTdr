<header>
    <h1>Recarga saldos</h1>

    <nav><?php echo $this->comboRecargaSaldo; ?></nav>
    <nav><?php echo $this->crearAccionBotones(); ?></nav>

</header>

<div id="paginacion" class="normal"></div>
<table id="tablaItems" style="text-align: center;">
    <thead>
        <tr>
            <th>#</th>
            <th>Identificador</th>
            <th>No Orden Pago</th>
            <th>Monto</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<?php

?>


<script>
    $(document).ready(function() {

        construirPaginacion($("#paginacion"), <?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
        $("#listadoItems").removeClass("comunes");

        $("#fechaInicio").datepicker({
            changeMonth: true,
            changeYear: true,
            maxDate: "0",
            onSelect: function(dateText, inst) {
                var fecha = new Date($('#fechaInicio').datepicker('getDate'));
                fecha.setDate(fecha.getDate() + 90);
                $('#fechaFin').datepicker('option', 'minDate', $("#fechaInicio").val());
                $('#fechaFin').datepicker('option', 'maxDate', fecha);
            }
        });

        $("#fechaFin").datepicker({
            changeMonth: true,
            changeYear: true,
            maxDate: "0",
            onSelect: function(dateText, inst) {
                var fecha = new Date($('#fechaInicio').datepicker('getDate'));
            }
        });

    });

    //
    $("#btnFiltrar").click(function(event) {
        event.preventDefault();
        fn_filtrar();
    });



    // Función para filtrar
    function fn_filtrar() {

        $(".alertaCombo").removeClass("alertaCombo");
        mostrarMensaje("", "EXITO");
        var error = false;

        if (!$.trim($("#fechaInicio").val()) || !esCampoValido("#fechaInicio")) {
            error = true;
            $("#fechaInicio").addClass("alertaCombo");
        }

        if (!$.trim($("#fechaFin").val()) || !esCampoValido("#fechaFin")) {
            error = true;
            $("#fechaFin").addClass("alertaCombo");
        }

        if (!error) {

            $("#paginacion").html("<div id='cargando'>Cargando...</div>");

            $.post("<?php echo URL ?>Financiero/saldos/buscarOrdenPagoSaldoDisponible", {
                    identificadorOperador: $("#identificadorFiltro").val(),
                    fechaInicio: $("#fechaInicio").val(),
                    fechaFin: $("#fechaFin").val()
                },
                function(data) {
                    if (data.estado === 'FALLO') {
                        mostrarMensaje(data.mensaje, "FALLO");
                    } else {
                        construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
                    }
                }, 'json');

        } else {
            mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
        }
    }
</script>
<header>
    <h1>Bodegas</h1>
    <nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
    <thead><tr>
            <th>#</th>
            <th>Provincia</th>
            <th>Bodega</th>
            <th>Estado</th>
        </tr></thead>
    <tbody></tbody>
</table>

<script>
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí una bodega para editarla.</div>');
        construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
        $("#listadoItems").removeClass("comunes");
    });
    $("#_eliminar").click(function () {
        if ($("#cantidadItemsSeleccionados").text() > 1) {
            mostrarMensaje("Por favor seleccione un registro a la vez.", "FALLO");
            return false;
        }
    });
    $("#tablaItems").click(function () {
    });
</script>



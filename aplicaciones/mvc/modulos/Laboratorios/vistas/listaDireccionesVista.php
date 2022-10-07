<header>
    <h1>Direcciones de diagn&oacute;stico</h1>
    <nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>

<div id="paginacion" class="normal"></div>
<table id="tablaItems">
    <thead>
        <tr>
            <th>#</th>
            <th>Dirección de diagnóstico</th>
            <th>Código</th>
            <th>Estado</th>
            <th>Orden</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<script>
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí una Dirección para editarla.</div>');
        construirPaginacion($("#paginacion"),<?php echo json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE); ?>);
        $("#listadoItems").removeClass("comunes");
    });
    $("#_eliminar").click(function () {
        if ($("#cantidadItemsSeleccionados").text() > 1) {
            mostrarMensaje("Por favor seleccione un registro a la vez.", "FALLO");
            return false;
        }
    });
</script>



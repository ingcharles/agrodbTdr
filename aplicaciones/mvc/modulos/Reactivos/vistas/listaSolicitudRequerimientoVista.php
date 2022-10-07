<div id="paginacion" class="normal"></div>
<table id="tablaItems">
    <thead><tr>
            <th>#</th>
            <th>Reactivos</th>
            <th>Saldo Bodega</th>
            <th>Saldo Laboratorio</th>
            <th>Unidad</th>
        </tr></thead>
    <tbody></tbody>
</table>

<script>
    $(document).ready(function () {
        <?php echo $this->codigoJS; ?>
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí una solicitud para editarla.</div>');
        construirPaginacion($("#paginacion"),<?php echo(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
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



<header>
    <h1>Firmas electr&oacute;nicas</h1>
    <nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
    <thead>
        <tr>
            <th>#</th>
            <th>Cédula</th>
            <th>Nombre</th>
            <th>Estado</th>
            <th>Reenviar</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<script>
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        construirPaginacion($("#paginacion"),<?php echo(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
        $("#listadoItems").removeClass("comunes");
    });
    $("#_eliminar").click(function () {
        if ($("#cantidadItemsSeleccionados").text() > 1) {
            mostrarMensaje("Por favor seleccione un registro a la vez.", "FALLO");
            return false;
        }
    });

    /**
     * Cambia el estado del firmante
     * @param {type} campo
     * @param {type} idInforme
     * @returns {undefined}
     */
    function  cambiarEstado(campo, idFirmaElectronica) {

        var valor = $(campo).prop("checked") ? 'true' : 'false';
        var estado = 'ACTIVO';
        if (valor == 'false')
        {
            estado = 'INACTIVO';
        }

        $.post("<?php echo URL ?>Laboratorios/FirmasElectronicas/cambiarEstado",
                {
                    id_firma_electronica: idFirmaElectronica,
                    estado: estado
                },
        function (data) {
            mostrarMensaje("Estado de la firma cambio a: " + estado, "FALLO");
        });

    }

    //Reenviar la firma
    function  fn_reenviar(idFirmaElectronica) {
        $.post("<?php echo URL ?>Laboratorios/FirmasElectronicas/reenviarFirma",
                {
                    id_firma_electronica: idFirmaElectronica
                },
        function (data) {
            mostrarMensaje("Se reenvió el correo para la activación de la firma.", "FALLO");
        });
    }
</script>



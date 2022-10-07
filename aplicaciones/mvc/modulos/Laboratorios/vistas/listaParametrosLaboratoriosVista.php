<header>
    <h1>Par&aacute;metros de laboratorios</h1>
    <nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>
<fieldset>
    <legend class='legendBusqueda'>Filtros de b&uacute;squeda</legend>
    <div data-linea="1">
        <label for="direccion">Direcci&oacute;n</label> 
        <select id="fDireccion" name="fDireccion">
            <option value="">Seleccionar....</option>
            <?php echo $this->comboDirecciones();
            ?>
        </select>
    </div>

    <div data-linea="1">
        <label for="fLaboratorio">Laboratorio</label> 
        <select id="fLaboratorio" name="fLaboratorio">
        </select>
    </div>
    <div data-linea="2">
        <button id="btnFiltrar" class="fas fa-search"> Filtrar lista</button>
    </div>
</fieldset>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
    <thead>
        <tr>
            <th>#</th>
            <th>Direcci&oacute;n</th>
            <th>Laboratorio</th>
            <th>Nombre par&aacute;metro</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>


<script>
    $(document).ready(function () {
        <?php echo $this->codigoJS; ?>
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí la configuración para editarla.</div>');
        distribuirLineas();

        $("#_eliminar").click(function () {
            if ($("#cantidadItemsSeleccionados").text() > 1) {
                mostrarMensaje("Por favor seleccione un registro a la vez.", "FALLO");
                return false;
            }
        });

        $("#tablaItems").click(function () {
        });

        //CARGAMOS LOS COMBOS DE LOS FITROS DE BUSQUEDA
        //Cuando seleccionamos una dirección, llenamos el combo de laboratorios
        $("#fDireccion").change(function () {
            $.post("<?php echo URL ?>Laboratorios/ParametrosLaboratorios/comboLaboratorios/" + $(this).val(), function (data) {
                $("#fLaboratorio").html(data);
                $("#fLaboratorio").removeAttr("disabled");
            });
        });

        //Cuando se presiona en Filtrar lista, debe cargar los datos
        $("#btnFiltrar").click(function () {
            fn_filtrar();
        });
    });

    // Función para filtrar
    function fn_filtrar() {
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí registro para editar.</div>');
        $("#paginacion").html("<div id='cargando'>Cargando...</div>");
        $.post("<?php echo URL ?>Laboratorios/ParametrosLaboratorios/listarDatos",
                {
                    fDireccion: $("#fDireccion").val(),
                    fLaboratorio: $("#fLaboratorio").val()
                },
        function (data) {
            construirPaginacion($("#paginacion"), JSON.parse(data));
            $("#listadoItems").removeClass("comunes");
        });
    }
</script>



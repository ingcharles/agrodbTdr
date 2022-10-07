<header>
    <h1>Laboratorios en provincia</h1>
    <nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>

<fieldset>
    <legend class='legendBusqueda'>Filtros de b&uacute;squeda</legend>
    <div data-linea="1">
        <label for="direccion">Direcci&oacute;n</label> 
        <select id="fDireccion" name="fDireccion">
            <option value="">Seleccionar....</option>
            <?php echo $this->comboDirecciones(); ?>
        </select>
    </div>

    <div data-linea="1">
        <label for="fLaboratorio">Laboratorio</label> 
        <select id="fLaboratorio" name="fLaboratorio">
        </select>
    </div>

    <div data-linea="1">
        <button id="btnFiltrar" class="fas fa-search"> Filtrar lista</button>
        <button id="btnLimpiar" class="fas fa-times"> Limpiar filtros</button>
    </div>
</fieldset>
<div id="paginacion" class="normal"></div>

<table id="tablaItems">
    <thead><tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Provincia</th>
            <th>Tipo</th>
            <th>Estado</th>
        </tr></thead>
    <tbody></tbody>
</table>

<script>
<?php echo $this->codigoJS ?>
    $(document).ready(function () {
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para editarla.</div>');
        distribuirLineas();

        //CARGAMOS LOS COMBOS DE LOS FITROS DE BUSQUEDA
        //Cuando seleccionamos una dirección, llenamos el combo de laboratorios
        $("#fDireccion").change(function () {
            fn_llenarCmbLaboratorioFiltro();
        });

        //funcion para mostrar los laboratorios en el combo de filtro
        function fn_llenarCmbLaboratorioFiltro() {
            $("#fLaboratorio").html("");
            if ($("#fDireccion").val() !== "") {
                //Cargamos los laboratorios
                $.post("<?php echo URL ?>Laboratorios/LaboratoriosProvincia/comboLaboratorios/" + $("#fDireccion").val(), function (data) {
                    $("#fLaboratorio").html(data);
                });
            }
        }
        //Cuando se presiona en Filtrar lista, debe cargar los datos
        $("#btnFiltrar").click(function () {
            fn_filtrar();
        });
    });

    // Función para filtrar
    function fn_filtrar() {
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para editar.</div>');
        $("#paginacion").html("<div id='cargando'>Cargando...</div>");
        $.post("<?php echo URL ?>Laboratorios/LaboratoriosProvincia/listarDatos",
                {
                    fDireccion: $("#fDireccion").val(),
                    fLaboratorio: $("#fLaboratorio").val()
                },
        function (data) {
            construirPaginacion($("#paginacion"), JSON.parse(data));
            $("#listadoItems").removeClass("comunes");
        });
    }

    $("#_eliminar").click(function () {
        if ($("#cantidadItemsSeleccionados").text() > 1) {
            mostrarMensaje("Por favor seleccione un registro a la vez.", "FALLO");
            return false;
        }
    });
</script>



<script src="<?php echo URL_RESOURCE ?>js/jquery.easyui.min.js"></script>
<link rel="stylesheet" href="<?php echo URL_RESOURCE ?>estilos/easyui.css">

<header>
    <h1>Par&aacute;metros de servicios</h1>
    <nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>
<fieldset>
    <legend class='legendBusqueda'>Filtros de b&uacute;squeda</legend>
    <div data-linea="1">
        <label for="fDireccion">Direcci&oacute;n</label> 
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
        <label for="fServicio"> Servicio </label> 
        <select id="fServicio" name="fServicio">
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
            <th>Servicio</th>
            <th>Nombre par&aacute;metro</th>
            <th>Estado</th>
            <th>Obligatorio</th>
            <th>Orden</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<script>
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un parámetro para editarla.</div>');
        distribuirLineas();

        $("#_eliminar").click(function () {
            if ($("#cantidadItemsSeleccionados").text() > 1) {
                mostrarMensaje("Por favor seleccione un registro a la vez.", "FALLO");
                return false;
            }
        });

        //CARGAMOS LOS COMBOS DE LOS FITROS DE BUSQUEDA
        //Cuando seleccionamos una dirección, llenamos el combo de laboratorios
        $("#fDireccion").change(function () {
            $("#fLaboratorio").html("");
            $("#fServicio").html("");
            if ($(this).val() !== "") {
                $.post("<?php echo URL ?>Laboratorios/ParametrosServicios/comboLaboratorios/" + $(this).val(), function (data) {
                    $("#fLaboratorio").html(data);
                    $("#fLaboratorio").removeAttr("disabled");
                });
            }
        });

        //Cuando seleccionamos un laboratorio, llenamos el combo de servicios
        $("#fLaboratorio").change(function () {
            $("#fServicio").html("");
            if ($(this).val() !== "") {
                $.post("<?php echo URL ?>Laboratorios/ParametrosServicios/comboServiciosSinJoinGuia/" + $(this).val(), function (data) {
                    $("#fServicio").html(data);
                    $("#fServicio").removeAttr("disabled");
                });
            }
        });

        //Cuando se presiona en Filtrar lista, debe cargar los datos
        $("#btnFiltrar").click(function () {
            if ($("#fLaboratorio").val() === null) {
                $("#fDireccion").addClass("alertaCombo");
                $("#fLaboratorio").addClass("alertaCombo");
                mostrarMensaje("Seleccione la Dirección y el Laboratorio", "FALLO");
            } else {
                fn_filtrar();
            }
        });
    });
    // Función para filtrar
    function fn_filtrar() {
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí registro para editar.</div>');
        $("#paginacion").html("<div id='cargando'>Cargando...</div>");
        $.post("<?php echo URL ?>Laboratorios/ParametrosServicios/listarDatos",
                {
                    fDireccion: $("#fDireccion").val(),
                    fLaboratorio: $("#fLaboratorio").val(),
                    fServicio: $("#fServicio").val()
                },
        function (data) {
            construirPaginacion($("#paginacion"), JSON.parse(data));
            $("#listadoItems").removeClass("comunes");
        });
    }
</script>



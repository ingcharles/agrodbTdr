<script src="<?php echo URL_RESOURCE ?>js/jquery.easyui.min.js"></script>
<link rel="stylesheet" href="<?php echo URL_RESOURCE ?>estilos/easyui.css">
<script accesskey=""src="<?php echo URL_RESOURCE ?>js/jquery.treetable.js"></script>
<link rel="stylesheet" href="<?php echo URL_RESOURCE ?>estilos/jquery.treetable.css">
<link rel="stylesheet" href="<?php echo URL_RESOURCE ?>estilos/jquery.treetable.theme.default.css">
<link rel='stylesheet' href='<?php echo URL_RESOURCE ?>estilos/fSelect.css'>
<header>
    <h1>Auditor&iacute;a BD</h1>
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
        <label for="fFechaInicial">Fecha Inicio</label> 
        <input type="date" id="fFechaInicio" name="fFechaInicial" />
    </div>
    <div data-linea="2">
        <label for="fFechaFinal">Fecha fin</label> 
        <input type="date" id="fFechaFin" name="fFechaFinal" />
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
            <th>Operación</th>
            <th>Campos del informe</th>
            <th>Valor anterior</th>
            <th>Valor nuevo</th>
            <th>Fecha cambio</th>
            <th>Usuario</th>
        </tr>
    </thead>
    <tbody id="tb"></tbody>
</table>

<script>
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        distribuirLineas();

        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí una solicitud para editarla.</div>');
        $("#listadoItems").removeClass("comunes");

        fn_llenarCmbLaboratorioFiltro();

        $("#btnFiltrar").click(function () {
            if ($("#fLaboratorio").val() === null) {
                mostrarMensaje("Seleccioner la Dirección y el Laboratorio.", "FALLO");
            } else {
                fn_filtrar();
            }
        });

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
                $.post("<?php echo URL ?>Laboratorios/AuditoriaLabLog/comboLaboratorios/" + $("#fDireccion").val(), function (data) {
                    $("#fLaboratorio").html(data);
                });
            }
        }
    });


    // Función para filtrar
    function fn_filtrar() {
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro de Auditoría para editarla.</div>');
        $("#paginacion").html("<div id='cargando'>Cargando...</div>");
        $.post("<?php echo URL ?>Laboratorios/AuditoriaLabLog/verAuditoria/",
                {
                    idDireccion: $("#fDireccion").val(),
                    idLaboratorio: $("#fLaboratorio").val(),
                    fechaInicio: $("#fFechaInicio").val(),
                    fechaFin: $("#fFechaFin").val()
                },
        function (data) {
            construirPaginacion($("#paginacion"), JSON.parse(data));
            $("#listadoItems").removeClass("comunes");
        });
    }
</script>
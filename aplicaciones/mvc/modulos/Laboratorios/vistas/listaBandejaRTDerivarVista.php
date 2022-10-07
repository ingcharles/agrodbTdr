<header>
    <h1>Derivar orden de trabajo</h1>
    <nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>
<fieldset>
    <legend class='legendBusqueda'>Filtros de b&uacute;squeda</legend>

    <?php echo $this->laboratoriosProvinciaUsuario(); ?>

    <div data-linea="1">
        <label for="fCodigo">C&oacute;digo</label> 
        <input type="text" id="fCodigo" name="fCodigo"/>
    </div>

    <div data-linea="2">
        <button id="btnFiltrar" class="fas fa-search"> Filtrar lista</button>
        <button id="btnLimpiar" class="fas fa-times"> Limpiar filtros</button>
    </div>
</fieldset>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
    <thead>
        <tr>
            <th>#</th>
            <th>C&oacute;digo</th>
            <th>Cliente</th>
            <th>Descargar</th>
            <th>Memo</th>
            <th>Fecha</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<script>
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
        distribuirLineas();
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para editarla.</div>');

        //Cuando se presiona en Filtrar lista, debe cargar los datos
        $("#btnFiltrar").click(function () {
            if ($("#id_laboratorios_provincia").val() === "") {
                $('#id_laboratorios_provincia').addClass("alertaCombo");
                mostrarMensaje("Seleccione el Laboratorio.", "FALLO");
            } else {
                fn_filtrar();
            }
        });

        //Para Limpiar los filtros
        $("#btnLimpiar").click(function () {
            $("#fCodigo").val('');
        });
    });

    // Función para filtrar
    function fn_filtrar() {
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para editar.</div>');
        $("#paginacion").html("<div id='cargando'>Cargando...</div>");
        $.post("<?php echo URL ?>Laboratorios/BandejaResponsableTecnico/listarDatosDerivar",
                {
                    id_laboratorios_provincia: $("#id_laboratorios_provincia").val(),
                    codigo_ot: $("#fCodigo").val()
                },
        function (data) {
            construirPaginacion($("#paginacion"), JSON.parse(data));
            $("#listadoItems").removeClass("comunes");
        });
    }
</script>

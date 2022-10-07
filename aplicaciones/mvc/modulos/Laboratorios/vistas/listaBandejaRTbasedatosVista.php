<script
src="<?php echo URL_RESOURCE ?>js/js_comunes.js" type="text/javascript"></script>
<header>
    <h1>Bases de datos</h1>
    <nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>
<fieldset>
    <legend class='legendBusqueda'>Filtros de b&uacute;squeda</legend>

    <?php echo $this->laboratoriosProvinciaUsuario(); ?>

    <div data-linea="1">
        <label for="fechai">Fecha inicio</label> 
        <input type="date" id="fechai" name="fechai"/>
    </div>
    <div data-linea="1">
        <label for="fechaf">Fecha fin</label> 
        <input type="date" id="fechaf" name="fechaf"/>
    </div>

    <div data-linea="2">
        <button id="btnFiltrar" class="fas fa-search">Generar Excel</button>
        
    </div>
</fieldset>


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
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí la configuración para editarla.</div>');
        $("#paginacion").html("<div id='cargando'>Cargando...</div>");
        $.post("<?php echo URL ?>Laboratorios/BandejaInformes/baseDatos",
                {
                    id_laboratorios_provincia: $("#id_laboratorios_provincia").val(),
                    fechai: $("#fechai").val(),
                    fechaf: $("#fechaf").val()
                },
        function (data) {
            construirPaginacion($("#paginacion"), JSON.parse(data));
            $("#listadoItems").removeClass("comunes");
        });
    }
</script>

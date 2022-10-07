<header>
    <h1>Cronograma</h1>
    <nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>
<fieldset>
    <legend class='legendBusqueda'>Filtros de b&uacute;squeda</legend>
    <div data-linea="1">
        <label for="anio"> Año</label> 
        <input type="number" id="anio"  name="anio" min="<?php echo date("Y"); ?>" max="<?php echo date("Y") + 1; ?>">
    </div>
    <div data-linea="1">
        <button id="btnFiltrar" class="fas fa-search"> Filtrar lista</button>
    </div>    
</fieldset>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
    <thead><tr>
            <th>#</th>
            <th>Año</th>
            <th>Fecha inicio</th>
            <th>Fecha final</th>
            <th>Estado</th>
        </tr></thead>
    <tbody></tbody>
</table>

<script>
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        distribuirLineas();
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí el registro de cronograma para editarla.</div>');

        /**
         * Se requiere enviar mediante POST el id del la opción y el id nuevo cronograma
         * @returns lista filtrada
         */
        $("#btnFiltrar").click(function () {
            if ($("#anio").val() === '') {
                $("#anio").addClass("alertaCombo");
                mostrarMensaje("Ingresar el año.", "FALLO");
            } else {
                fn_filtrar();
            }
        });
    });

    function fn_filtrar() {
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para editar.</div>');
        $.post("<?php echo URL ?>Laboratorios/CronogramaPostregistro/filtrar/" + $("#anio").val(),
                {
                    opcion:<?php echo $_POST['opcion']; ?>,
                    id: "_cronogramaPostregistro"
                },
        function (data) {
            construirPaginacion($("#paginacion"), JSON.parse(data));
            $("#listadoItems").removeClass("comunes");
        });
    }
</script>



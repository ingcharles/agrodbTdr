<script src="<?php echo URL_RESOURCE ?>js/jquery.easyui.min.js"></script>
<link rel="stylesheet" href="<?php echo URL_RESOURCE ?>estilos/easyui.css">
<header>
    <h1>Ingresar Soluciones</h1>
</header>
<fieldset>
    <legend class='legendBusqueda'>Filtros de b&uacute;squeda - Soluciones</legend>

    <?php echo $this->laboratoriosProvinciaUsuario(); ?>

    <div data-linea="1">
        <label for="fNombre">Nombre</label> 
        <input type="text" id="fNombre" name="fNombre"/>
    </div>

    <div data-linea="2">
        <button id="btnFiltrar" class="fas fa-search"> Filtrar lista</button>
        <button id="btnLimpiar" class="fas fa-times"> Limpiar filtros</button>
    </div>
</fieldset>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
    <thead><tr>
            <th>#</th>
            <th title="Nombre de la Soluci&oacute;n">Soluci&oacute;n</th>
            <th title="Unidad de medida">Unidad</th>
            <th title="Volumen final de la solci&oacute;n">Volumen final</th>
            <th title="Estado del registro, si es INACTIVO no se usa para el descuento en el an&aacute;lisis">Estado del registro</th>
            <th title="N&uacute;mero de reactivos activos de la soluci&oacute;n">Total reactivos</br>activos</th>
        </tr></thead>
    <tbody>
        <tr><td colspan="4">Presione <b>Filtrar lista</b> para mostrar la lista de soluciones creadas</td></tr>
    </tbody>
</table>

<script>
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        distribuirLineas();
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí la solución para editarla.</div>');

        //Cuando se presiona en Filtrar lista, debe cargar los datos
        $("#btnFiltrar").click(function () {
            if ($("#id_laboratorios_provincia").val() === "") {
                mostrarMensaje("Seleccione el Laboratorio.", "FALLO");
            } else {
                fn_filtrar();
            }
        });

        //Para Limpiar los filtros
        $("#btnLimpiar").click(function () {
            $("#fNombre").val('');
        });
    });

    // Función para filtrar
    function fn_filtrar() {
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para editar.</div>');
        $("#paginacion").html("<div id='cargando'>Cargando...</div>");
        $.post("<?php echo URL ?>Reactivos/ReactivosSolucion/listarSoluciones",
                {
                    id_laboratorios_provincia: $("#id_laboratorios_provincia").val(),
                    nombre: $("#fNombre").val()
                },
        function (data) {
            construirPaginacion($("#paginacion"), JSON.parse(data));
            $("#listadoItems").removeClass("comunes");
        });
    }

    $("#_informacionAdicional").click(function () {
        if ($(".seleccionado").attr('id') === undefined) {
            mostrarMensaje("Por favor seleccione un registro a la vez.", "FALLO");
            return false;
        }
    });
</script>

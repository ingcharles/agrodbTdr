<link rel='stylesheet'
      href='<?php echo URL_RESOURCE ?>estilos/bootstrap.min.css'>
<script src="<?php echo URL_RESOURCE ?>js/jquery.easyui.min.js"></script>
<link rel="stylesheet" href="<?php echo URL_RESOURCE ?>estilos/easyui.css">
<header>
    <h1>Reactivos</h1>
    <nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>
<fieldset>
    <legend class='legendBusqueda'>Filtros de b&uacute;squeda</legend>

    <?php echo $this->laboratoriosProvinciaPrincipal(); ?>

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
<table id="tablaItems" class="table">
    <thead><tr>
            <th>#</th>
            <th>Reactivo</th>
            <th>Tipo</th>
            <th>Origen</th>
            <th>Unidad</th>
            <th>Ingresos</th>
            <th>Egresos</th>
            <th>Saldo</th>
            <th>Cantidad m&iacute;nima</th>
            <th>Estado</th>
        </tr></thead>
    <tbody>
        <tr><td colspan="4">Presione <b>Filtrar lista</b> para mostrar la lista de reactivos</td></tr>
    </tbody>
</table>
<script>
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        distribuirLineas();
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí el reactivo para editarla.</div>');

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
        $.post("<?php echo URL ?>Reactivos/ReactivosLaboratorios/listarDatos",
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

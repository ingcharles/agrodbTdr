<header>
    <h1>Subir saldos</h1>
    <nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>
<fieldset>
    <legend class='legendBusqueda'>Filtros de b&uacute;squeda</legend>
    <div data-linea="1">
        <label for="fCodigo">C&oacute;digo</label> 
        <input type="text" id="fCodigo" placeholder="Buscar por c&oacute;digo"/>
    </div>

    <div data-linea="1">
        <label for="fCodigo">Nombre</label> 
        <input type="text" id="fNombre" placeholder="Buscar por nombre"/>
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
            <th title="Provincia de la Bodega">Provincia</th>
            <th title="Nombre de la Bodega">Bodega</th>
            <th>Código</th>
            <th>Nombre</th>
            <th>Saldo anterior</th>
            <th>Saldo actual</th>
            <th>Unidad</th>
            <th>Fecha de Registro</th>
        </tr></thead>
    <tbody id="tb"></tbody>
</table>

<script>
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        distribuirLineas();
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí el registro para subir el certificado del reactivo.</div>');

        //Cuando se presiona en Filtrar lista, debe cargar los datos
        $("#btnFiltrar").click(function () {
            fn_filtrar();
        });

        //Para Limpiar los filtros
        $("#btnLimpiar").click(function () {
            $("#fCodigo").val('');
            $("#fNombre").val('');
        });
    });
    fn_filtrar();
    // Función para filtrar
    function fn_filtrar() {
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para editar.</div>');
        $("#paginacion").html("<div id='cargando'>Cargando...</div>");
        $.post("<?php echo URL ?>Reactivos/ReactivosBodega/listarDatos",
                {
                    codigo: $("#fCodigo").val(),
                    nombre: $("#fNombre").val()
                },
        function (data) {
            construirPaginacion($("#paginacion"), JSON.parse(data));
            $("#listadoItems").removeClass("comunes");
        });
    }
</script>


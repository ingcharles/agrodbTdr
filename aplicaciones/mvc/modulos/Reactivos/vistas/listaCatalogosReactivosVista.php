<header>
    <h1>Cat&aacute;logos generales</h1>
    <nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>
<script accesskey=""src="<?php echo URL_RESOURCE ?>js/jquery.treetable.js"></script>
<link rel="stylesheet" href="<?php echo URL_RESOURCE ?>estilos/jquery.treetable.css">
<link rel="stylesheet" href="<?php echo URL_RESOURCE ?>estilos/jquery.treetable.theme.default.css">
<script src="<?php echo URL_RESOURCE ?>js/jquery.easyui.min.js"></script>
<link rel="stylesheet" href="<?php echo URL_RESOURCE ?>estilos/easyui.css">

<div id="paginacion" class="normal"></div>
<fieldset>
    <legend class='legendBusqueda'>Filtros de b&uacute;squeda</legend>

    <div data-linea="1">
        <label for="fNombre">Nombre</label> 
        <input type="text" id="fNombre" name="fNombre" placeholder="Solo de primer nivel"/>
    </div>
    <div data-linea="1">
        <button id="btnFiltrar" class="fas fa-search"> Filtrar lista</button>
        <button id="btnLimpiar" class="fas fa-times"> Limpiar filtros</button>
    </div>
</fieldset>
<div id="paginacion" class="normal"></div>
<table id="tablaArbol">
    <caption>
        <a href="#"
           onclick="jQuery('#tablaArbol').treetable('expandAll');
                   return false;">Expandir</a>
        <a href="#"
           onclick="jQuery('#tablaArbol').treetable('collapseAll');
                   return false;">Contraer</a>
    </caption>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>C&oacute;digo</th>
            <th>Descripción</th>
            <th>Orden</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody id="tb"></tbody>
</table>
<script type="text/javascript">
    var idExpandir;

    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        distribuirLineas();
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí la configuración para editarla.</div>');

        fn_filtrar();
        $("#btnFiltrar").click(function () {
            fn_filtrar();
        });

        //Para Limpiar los filtros
        $("#btnLimpiar").click(function () {
            $("#nombre").val('');
        });
    });

    // Función para filtrar
    function fn_filtrar() {
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para editar.</div>');
        $("#paginacion").html("<div id='cargando'>Cargando...</div>");
        $.post("<?php echo URL ?>Reactivos/CatalogosReactivos/listarCatalogos",
                {
                    fNombre: $("#fNombre").val()
                },
        function (data) {
            $("#paginacion").html("");
            $('#tablaArbol').treetable('destroy');
            $("#tb").empty();
            $("#tablaArbol tbody").append(data);
            $("#tablaArbol").treetable({
                expandable: true
            });
            if (typeof idExpandir !== "undefined") {
                if (idExpandir !== "") {
                    $("#tablaArbol").treetable("reveal", idExpandir);
                }
            }
            idExpandir = "";
        });
    }
</script>
<script src="<?php echo URL_RESOURCE ?>js/jquery.easyui.min.js"></script>
<link rel="stylesheet" href="<?php echo URL_RESOURCE ?>estilos/easyui.css">
<script accesskey=""src="<?php echo URL_RESOURCE ?>js/jquery.treetable.js"></script>
<link rel="stylesheet" href="<?php echo URL_RESOURCE ?>estilos/jquery.treetable.css">
<link rel="stylesheet" href="<?php echo URL_RESOURCE ?>estilos/jquery.treetable.theme.default.css">
<header>
    <h1>Procedimiento</h1>
    <nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>
<div id="divFiltros">
    <fieldset>
        <legend class='legendBusqueda'>Filtros de b&uacute;squeda</legend>

        <?php echo $this->laboratoriosProvinciaUsuario(); ?>

        <div data-linea="2">
            <button id="btnFiltrar" class="fas fa-search"> Filtrar lista</button>
        </div>
    </fieldset>
</div>
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
            <th title="nombre del Servicio">Servicio</th>
            <th title="Tipo: INDIVIDUAL, PAQUETE, ELEMENTO (del paquete)">Tipo</th>
            <th title="Estado del Servicio">Estado Servicio</th>
            <th title="Agregar procedimiento al Servicio">Agregar</th>
        </tr>
    </thead>
    <tbody id="tb"></tbody>
</table>

<script>
    var idExpandir;

    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un procedimiento para editarla.</div>');

        $("#btnFiltrar").click(function () {
            if ($("#divFiltros #id_laboratorios_provincia").val() === "") {
                mostrarMensaje("Seleccione el Laboratorio.", "FALLO");
            } else {
                fn_filtrar();
            }
        });
    });

    // Función para filtrar
    function fn_filtrar() {
        var idLaboratorio = 0;
        if ($("#divFiltros #id_laboratorios_provincia").is("select")) {
            idLaboratorio = $("#divFiltros #id_laboratorios_provincia").find(':selected').attr('data-id');
        } else {
            idLaboratorio = $("#divFiltros #id_laboratorios_provincia").attr('data-id');
        }
        $("#paginacion").html("<div id='cargando'>Cargando...</div>");
        $.post("<?php echo URL ?>Reactivos/ActividadUso/listarDatos",
                {
                    idLaboratoriosProvincia: $("#divFiltros #id_laboratorios_provincia").val(),
                    idLaboratorio: idLaboratorio
                },
        function (data) {
            $("#paginacion").html("");
            $('#tablaArbol').treetable('destroy');
            $("#tb").empty();
            $("#tablaArbol tbody").append(data);
            if (typeof idExpandir !== "undefined") {
                if (idExpandir !== "") {
                    $("#tablaArbol").treetable("reveal", idExpandir);
                }
            }
            idExpandir = "";
            $("#tablaArbol").treetable({expandable: true});
        });
    }

    //Funcion para abrir la vista para agregar un nuevo registro
    //a partir del item seleccionado
    function fn_abrirVistaAgregar(id) {
        var elementoDestino = "#detalleItem";
        var url = "<?php echo URL ?>Reactivos/ActividadUso/listarProcedimientoAnalisis";
        var data = {
            id: id
        };
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "text",
            contentType: "application/x-www-form-urlencoded; charset=latin1",
            beforeSend: function () {
                $(elementoDestino).html("<div id='cargando'>Cargando...</div>").fadeIn();
            },
            success: function (html) {
                $(elementoDestino).html(html);
                redimensionarVentanaTrabajo();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $(elementoDestino).html(
                        "<div id='error'>¡Ups!... algo no anda bien.<br />"
                        + "Se produjo un " + textStatus + " "
                        + jqXHR.status
                        + ".<br />Disculpe los inconvenientes causados.</div>");
            },
            complete: function () {

            }
        });
    }
</script>



<script src="<?php echo URL_RESOURCE ?>js/jquery.easyui.min.js"></script>
<link rel="stylesheet" href="<?php echo URL_RESOURCE ?>estilos/easyui.css">
<script accesskey=""src="<?php echo URL_RESOURCE ?>js/jquery.treetable.js"></script>
<link rel="stylesheet" href="<?php echo URL_RESOURCE ?>estilos/jquery.treetable.css">
<link rel="stylesheet" href="<?php echo URL_RESOURCE ?>estilos/jquery.treetable.theme.default.css">
<header>
    <h1>Informes de resultados</h1>
    <nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>
<fieldset>
    <legend class='legendBusqueda'>Filtros de b&uacute;squeda</legend>
    <div data-linea="1">
        <label for="direccion">Direcci&oacute;n</label> 
        <select id="fDireccion" name="fDireccion">
            <option value="">Seleccionar....</option>
            <?php echo $this->comboDirecciones(); ?>
        </select>
    </div>

    <div data-linea="1">
        <label for="fLaboratorio">Laboratorio</label> 
        <select id="fLaboratorio" name="fLaboratorio">
        </select>
    </div>
    <div data-linea="2">
        <button id="btnFiltrar" class="fas fa-search"> Filtrar lista</button>
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
            <th>Laboratorio/Item</th>
            <th>Estado</th>
            <th>Nivel</th>
            <th>Orden</th>
            <th>Agregar</th>
            <th>Vista Previa/</br>Copiar</th>
        </tr>
    </thead>
    <tbody id="tb"></tbody>
</table>

<script type="text/javascript">
    var idExpandir;
    var auxIdDireccion;
    var auxIdLaboratorio;
    var auxIdPadre;
    var crearHijo = 0;

    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        distribuirLineas();
      //  distribuirLineas(); //importante! nuevamente
        //$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí la configuración para editarla.</div>');
        fn_llenarCmbLaboratorioFiltro();
 });
 
        $("#btnFiltrar").click(function () {
            fn_filtrar();
        });

        //CARGAMOS LOS COMBOS DE LOS FITROS DE BUSQUEDA
        //Cuando seleccionamos una dirección, llenamos el combo de laboratorios
        $("#fDireccion").change(function () {
            $('#tablaArbol').treetable('destroy');
            $("#tb").empty();
            fn_llenarCmbLaboratorioFiltro();
        });

        //funcion para mostrar los laboratorios en el combo de filtro
        function fn_llenarCmbLaboratorioFiltro() {
            $("#fLaboratorio").html("");
            if ($("#fDireccion").val() !== "") {
                //Cargamos los laboratorios
                $.post("<?php echo URL ?>Laboratorios/Informes/comboLaboratorios/" + $("#fDireccion").val(), function (data) {
                    $("#fLaboratorio").html(data);
                });
            }
        }
   

    // Función para filtrar
    function fn_filtrar() {
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para editar.</div>');
        $("#paginacion").html("<div id='cargando'>Cargando...</div>");
        $.post("<?php echo URL ?>Laboratorios/Informes/listarDatos",
                {
                    fDireccion: $("#fDireccion").val(),
                    fLaboratorio: $("#fLaboratorio").val()
                },
        function (data) {
            $("#paginacion").html("");
            $('#tablaArbol').treetable('destroy');
            $("#tb").empty();
            $("#tablaArbol tbody").append(data);
            fn_setearArbol();
            if (typeof idExpandir !== "undefined") {
                if (idExpandir !== "") {
                    $("#tablaArbol").treetable("reveal", idExpandir);
                }
            }
            idExpandir = "";
        });
    }

    // Setear tabla tipo árbol
    function fn_setearArbol() {
        $("#tablaArbol").treetable({expandable: true});

        // Highlight selected row
        $("#tablaArbol tbody").on("mousedown", "tr", function () {
            $(".selected").not(this).removeClass("selected");
            $(this).toggleClass("selected");
        });

        // Drag & Drop Example Code
        $("#tablaArbol .file, #tablaArbol .folder").draggable({
            helper: "clone",
            opacity: .75,
            refreshPositions: true, // Performance?
            revert: "invalid",
            revertDuration: 300,
            scroll: true
        });

        $("#tablaArbol .folder").each(function () {
            $(this).parents("#tablaArbol tr").droppable({
                accept: ".file, .folder",
                drop: function (e, ui) {
                    var droppedEl = ui.draggable.parents("tr");
                    if (droppedEl.data("ttId") !== $(this).data("ttId")) {
                        fn_actualizarDnD(droppedEl.data("ttId"), $(this).data("ttId"));
                        $("#tablaArbol").treetable("move", droppedEl.data("ttId"), $(this).data("ttId"));
                    }
                },
                hoverClass: "accept",
                over: function (e, ui) {
                    var droppedEl = ui.draggable.parents("tr");
                    if (this != droppedEl[0] && !$(this).is(".expanded")) {
                        $("#tablaArbol").treetable("expandNode", $(this).data("ttId"));
                    }
                }
            });
        });
    }

    ///Funcion para abrir la vista para agregar un nuevo registro
    //a partir del item seleccionado
    function fn_abrirVistaAgregar(id) {
        crearHijo = 1;
        var ids = id.split('-');
        auxIdDireccion = ids[0];
        auxIdLaboratorio = ids[1];
        auxIdPadre = ids[2];

        var elementoDestino = "#detalleItem";
        var url = "<?php echo URL ?>Laboratorios/Informes/nuevo";
        var data = {
            id: "_nuevo",
            idPadre: id
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

    //Funcion para abrir la vista previa
    function fn_vistaPrevia(idLaboratorio) {
        $("#auxIdPadre").val(idLaboratorio);
        var elementoDestino = "#detalleItem";
        var url = "<?php echo URL ?>Laboratorios/Laboratorios/vistaPrevia";
        var data = {
            id: "_nuevo",
            idLaboratorio: idLaboratorio
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

    $("#_nuevo").click(function () {
        crearHijo = 0;
    });

    //Funcion para actualizar el padre
    function fn_actualizarDnD(id, idPadreNuevo) {
        $.post("<?php echo URL ?>Laboratorios/Informes/editarDnD/",
                {
                    idInforme: id,
                    fkIdInforme: idPadreNuevo
                },
        function (data) {
            idExpandir = id;
        });
    }

    // Función para filtrar
    function fn_cambiar_estado(idInforme, estado) {
        $.post("<?php echo URL ?>Laboratorios/Informes/guardar",
                {
                    id_informe: idInforme,
                    estado_registro: estado
                },
        function (data) {

        });
        setTimeout(
                function () {
                    idExpandir = idInforme;
                    fn_filtrar();
                }, 1000);
    }

    //Función para copiar los campos de todo un informe o seccion
    function fn_copiar(idInforme, idDireccion, idLaboratorio) {
        var elementoDestino = "#detalleItem";
        var url = "<?php echo URL ?>Laboratorios/Informes/guardarCopia";
        var data = {
            fk_id_informe: idInforme,
            id_direccion: idDireccion,
            id_laboratorio: idLaboratorio
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
                setTimeout(
                        function () {
                            idExpandir = idInforme;
                            fn_filtrar();
                        }, 1000);
            }
        });
    }
    /**
     * Cambia el orden de los campos
     * @param {type} campo
     * @param {type} idInforme
     * @returns {undefined}
     */
    function  cambiarOrden(campo, idInforme) {

        $.post("<?php echo URL ?>Laboratorios/Informes/cambiarOrden",
                {
                    id_informe: idInforme,
                    orden: $(campo).val()
                },
        function (data) {

        });

    }
    /**
     * Cambiar de estado en registro
     * @param {type} campo
     * @param {type} idInforme
     * @returns {undefined}
     */
    function  cambiarEstado(campo, idInforme) {

        var valor = $(campo).prop("checked") ? 'true' : 'false';
        var estado = 'ACTIVO';
        if (valor == 'false')
        {
            estado = 'INACTIVO';
        }

        $.post("<?php echo URL ?>Laboratorios/Informes/cambiarEstado",
                {
                    id_informe: idInforme,
                    estado_registro: estado
                },
        function (data) {

        });

    }
</script>

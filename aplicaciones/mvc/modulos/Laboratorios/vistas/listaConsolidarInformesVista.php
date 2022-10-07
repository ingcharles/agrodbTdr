<link rel='stylesheet'
      href='<?php echo URL_RESOURCE ?>estilos/bootstrap.min.css'>
<script src="<?php echo URL_RESOURCE ?>js/jquery.easyui.min.js"></script>
<link rel="stylesheet" href="<?php echo URL_RESOURCE ?>estilos/easyui.css">
<script accesskey=""src="<?php echo URL_RESOURCE ?>js/jquery.treetable.js"></script>
<link rel="stylesheet" href="<?php echo URL_RESOURCE ?>estilos/jquery.treetable.css">
<link rel="stylesheet" href="<?php echo URL_RESOURCE ?>estilos/jquery.treetable.theme.default.css">
<header>
    <h1>Consolidar informes</h1>
    <nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>
<fieldset>
    <legend class='legendBusqueda'>Filtros de b&uacute;squeda</legend>

    <?php echo $this->laboratoriosProvinciaUsuario(); ?>

    <div data-linea="1">
        <label for="fCliente"> Cliente </label> 
        <select id="fCliente" name="fCliente" class="easyui-combotree" style="width: 250px">
        </select>
    </div>

    <div data-linea="2">
        <input type="checkbox" id="festado" value="ANULADO"> <label
                        for="festado">Ver informes anulados</label>
        
        
    </div>
    <div data-linea="3">
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
            <th>Informe/Muestras</th>
            <th>Fecha</br>Aprobado</th>
            <th>Estado</th>
            <th>Observación</th>
            <th>Alcance</th>
            <th>Adjunto</th>
            <th>Anular</th>
            <th>Agregar</th>
            <th>Vista Previa</th>
            <th>Configurar</th>
        </tr>
    </thead>
    <tbody id="tb"></tbody>
</table>

<script>
    var idExpandir;
    var auxIdPadre;

    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        distribuirLineas();
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí la configuración para editarla.</div>');

        //Cuando se presiona en Filtrar lista, debe cargar los datos
        $("#btnFiltrar").click(function () {
            if ($("#id_laboratorios_provincia").val() === "") {
                $('#id_laboratorios_provincia').addClass("alertaCombo");
                mostrarMensaje("Seleccione el Laboratorio.", "FALLO");
            } else if ($('#fCliente').combobox('getValue') === "") {
                $("#fCliente").addClass("alertaCombo");
                mostrarMensaje("Seleccione un cliente.", "FALLO");
            } else {
                fn_filtrar();
            }
        });
    });

    buscarClientes();

    function buscarClientes() {
        if ($("#id_laboratorios_provincia").val() !== "") {
            $("#paginacion").html("<div id='cargando'>Cargando...</div>");
            $.post("<?php echo URL ?>Laboratorios/BandejaResponsableTecnico/buscarClientes",
                    {
                        id_laboratorios_provincia: $("#id_laboratorios_provincia").val()
                    },
            function (data) {
                $("#paginacion").html("");
                $('#tablaArbol').treetable('destroy');
                $("#tb").empty();
                $('#fCliente').combobox({
                    data: data,
                    valueField: 'id',
                    textField: 'text'
                });
            }, 'json');
        }
    }

    $('#id_laboratorios_provincia').change(function () {
        buscarClientes($(this).val());
    });

    //Funcion para actualizar el padre
    function fn_actualizarDnD(id, idPadreNuevo) {
        $.post("<?php echo URL ?>Laboratorios/ArchivoInformeAnalisis/editarDnD/",
                {
                    idArchivoInformeAnalisis: id,
                    fkIdArchivoInformeAnalisis: idPadreNuevo
                },
        function (data) {
            idExpandir = id;
        });
    }

    // Función para filtrar
    function fn_filtrar() {
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para editar.</div>');
        $("#paginacion").html("<div id='cargando'>Cargando...</div>");
        var anulado = $('#festado').prop("checked") ? 'ANULADO' : '';
        $.post("<?php echo URL ?>Laboratorios/ArchivoInformeAnalisis/consolidarInformes/",
                {
                    id_laboratorios_provincia: $("#id_laboratorios_provincia").val(),
                    idCliente: $('#fCliente').combobox('getValue'),
                    festado: anulado
                    
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
    function fn_agregar_informe(idNodoRaiz, idInformeAnalisis) {
        if (!confirm("Está seguro de crear un nuevo informe?")) {
            return false;
        }
        var elementoDestino = "#detalleItem";
        var url = "<?php echo URL ?>Laboratorios/ArchivoInformeAnalisis/agregarInforme";
        var data = {
            fk_id_archivo_informe_analisis: idNodoRaiz,
            id_informe_analisis: idInformeAnalisis
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
                fn_filtrar();
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

    function fn_alcance(idPrincipal, idNodoRaiz, idInformeAnalisis) {
        if (!confirm("Está seguro de crear un nuevo informe de alcance?")) {
            return false;
        }
        var elementoDestino = "#detalleItem";
        var url = "<?php echo URL ?>Laboratorios/ArchivoInformeAnalisis/agregarAlcance";
        var data = {
            fk_id_archivo_informe_analisis: idNodoRaiz,
            id_informe_analisis: idInformeAnalisis,
            id_archivo_informe_analisis: idPrincipal
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
                fn_filtrar();
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
    function fn_vistaPrevia(idAInforme) {
        var elementoDestino = "#detalleItem";
        var url = "<?php echo URL ?>Laboratorios/BandejaInformes/descargarInformes";
        var data = {
            idAInforme: idAInforme
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
    function fn_verArchivo(idAInforme) {
        var elementoDestino = "#detalleItem";
        var url = "<?php echo URL ?>Laboratorios/ArchivoInformeAnalisis/descargarArchivo";
        var data = {
            idAInforme: idAInforme
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
    //Funcion para registrar una observación
    function fn_observacion(idAInforme) {
        var elementoDestino = "#detalleItem";
        var url = "<?php echo URL ?>Laboratorios/ArchivoInformeAnalisis/observacion";
        var data = {
            idAInforme: idAInforme
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

    //Funcion para registrar una observación
    function fn_adjuntar(idAInforme) {
        var elementoDestino = "#detalleItem";
        var url = "<?php echo URL ?>Laboratorios/ArchivoInformeAnalisis/adjuntar";
        var data = {
            idAInforme: idAInforme
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

    //Funcion para anular un informe y agregar un informe sustitutivo
    function fn_anular(idAInforme) {

        var elementoDestino = "#detalleItem";
        var url = "aplicaciones/" + "mvc/Laboratorios"
                + "/" + "ArchivoInformeAnalisis/anular";
        var data = {
            idAInforme: idAInforme
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
//Funcion para eliminar un archivo adjunto
    function fn_eliminar(idAInforme) {

        var r = confirm("Desea borrar el archivo adjunto?");
        if (r == true) {
            var elementoDestino = "#detalleItem";
            var url = "aplicaciones/" + "mvc/Laboratorios"
                    + "/" + "ArchivoInformeAnalisis/eliminarAdjunto";
            var data = {
                idAInforme: idAInforme
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
                    fn_filtrar();
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
    }

    //Funcion para anular un informe y agregar un informe sustitutivo
    function fn_configurar(idAInforme) {

        var elementoDestino = "#detalleItem";
        var url = "aplicaciones/" + "mvc/Laboratorios"
                + "/" + "ArchivoInformeAnalisis/configurar";
        var data = {
            idAInforme: idAInforme
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
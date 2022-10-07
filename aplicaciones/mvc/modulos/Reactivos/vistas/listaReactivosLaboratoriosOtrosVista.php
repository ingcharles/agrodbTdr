<header>
    <h1>Reactivo desde otro Laboratorio</h1>
</header>

<link rel='stylesheet' href='<?php echo URL_RESOURCE ?>estilos/bootstrap.min.css'>
<script src="<?php echo URL_RESOURCE ?>js/bootstrap.min.js" type="text/javascript"></script>

<fieldset>
    <legend class='legendBusqueda'>Origen</legend>

    <div data-linea="1">
        <label for="fDireccion"> Direcci&oacute;n </label> <select
            id="fDireccion" name="fDireccion">
            <option value="">Seleccionar....</option>
            <?php
            echo $this->comboDirecciones($this->modeloLaboratoriosProvincia->getIdDireccion());
            ?>
        </select>
    </div>

    <div data-linea='1'>
        <label for='fLaboratorio'>Laboratorio</label>
        <select id='fLaboratorio' name='fLaboratorio'>
        </select>
    </div>
</fieldset>

<fieldset>
    <legend class='legendBusqueda'>Filtros de b&uacute;squeda</legend>

    <div data-linea="1">
        <label for="fNombre">Nombre</label> 
        <input type="text" id="fNombre" name="fNombre"/>
    </div>

    <div data-linea="1">
        <button id="btnFiltrar" class="fas fa-search"> Filtrar lista</button>
        <button id="btnLimpiar" class="fas fa-times"> Limpiar filtros</button>
        <input type="hidden" id="idSolicitudCabecera" value="<?php echo $this->modeloSolicitudCabecera->getIdSolicitudCabecera(); ?>" />
    </div>
</fieldset>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
    <thead><tr>
            <th>#</th>
            <th>Reactivo</th>
            <th>Tipo</th>
            <th>Unidad</th>
            <th>Ingresos</th>
            <th>Egresos</th>
            <th>Saldo</th>
            <th>Estado del registro</th>
            <th>Agregar</th>
        </tr></thead>
    <tbody>
        <tr><td colspan="4">Presione <b>Filtrar lista</b> para mostrar la lista de reactivos</td></tr>
    </tbody>
</table>
<?php require APP . 'Reactivos/vistas/modalSolicitudLaboratorio.php'; ?>
<script>
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        distribuirLineas();
        $("#detalleItem").html('<div class="mensajeInicial">Presione en agregar para crear la solicitud</div>');

        fn_llenarCmbLaboratorioFiltro();

        if ('<?php echo $this->modeloSolicitudCabecera->getIdSolicitudCabecera(); ?>' !== '') {
            $("#fDireccion").attr('disabled', true);
            $("#fLaboratorio").attr('disabled', true);
            fn_ver();
        }

        //Cuando se presiona en Filtrar lista, debe cargar los datos
        $("#btnFiltrar").click(function () {
            if ($("#fLaboratorio").val() === "") {
                mostrarMensaje("Seleccione el Laboratorio.", "FALLO");
            } else {
                fn_filtrar();
            }
        });

        //Para Limpiar los filtros
        $("#btnLimpiar").click(function () {
            $("#fNombre").val('');
        });



        //CARGAMOS LOS COMBOS DE LOS FITROS DE BUSQUEDA
        //Cuando seleccionamos una dirección, llenamos el combo de laboratorios
        $("#fDireccion").change(function () {
            fn_llenarCmbLaboratorioFiltro();
        });
        
        $("#fLaboratorio").change(function () {
            fn_filtrar();
        });
        
        //funcion para mostrar los laboratorios en el combo de filtro
        function fn_llenarCmbLaboratorioFiltro() {
            $("#fLaboratorio").html("");
            if ($("#fDireccion").val() !== "") {
                //Cargamos los laboratorios
                $.post("<?php echo URL ?>Reactivos/ReactivosLaboratorios/laboratoriosProvinciaPorDireccion/" + $("#fDireccion").val(), function (data) {
                    $("#fLaboratorio").html(data);
                    $('#fLaboratorio option[value="<?php echo $this->modeloSolicitudCabecera->getIdLaboratoriosProvinciaOrigen(); ?>"]').prop('selected', true);
                    fn_filtrar();
                });
            }
        }
    });

    //Funcion para agregar el reactivo-bodega a la solicitud 
    function fn_ver() {
        var elementoDestino = "#detalleItem";
        var url = "<?php echo URL ?>Reactivos/SolicitudLaboratorio/verFormularioDetalle";
        var data = {
            id: '<?php echo $this->modeloSolicitudCabecera->getIdSolicitudCabecera(); ?>'
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
                $("#detalleItem").html(html);
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

    // Función para filtrar
    function fn_filtrar() {
         $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para editar.</div>');
        if ($("#fLaboratorio").val() !== '') {
            $("#paginacion").html("<div id='cargando'>Cargando...</div>");
            $.post("<?php echo URL ?>Reactivos/SolicitudLaboratorio/listarReactivosLaboratorio",
                    {
                        id_laboratorios_provincia: $("#fLaboratorio").val(),
                        nombre: $("#fNombre").val()
                    },
            function (data) {
                construirPaginacion($("#paginacion"), JSON.parse(data));
                $("#listadoItems").removeClass("comunes");
            });
        }
    }
    var idReactivoLaboratorio;
    //Funcion para agregar el reactivo-bodega a la solicitud 
    function fn_agregarReaLabASolicitud(idReactivoLab) {
        idReactivoLaboratorio = idReactivoLab;
        if ($("#idSolicitudCabecera").val() === '') {
            $('#modalSolicitudLaboratorio').modal('show');
        } else {
            fn_agregar();
        }

    }

    //Funcion para agregar el item en el detalle de la solicitud
    function fn_agregar() {
        console.log("LAB PROV: " + $("#id_laboratorios_provincia").val() + ", ID REA LAB: " + idReactivoLaboratorio);
        var elementoDestino = "#detalleItem";
        var url = "<?php echo URL ?>Reactivos/SolicitudLaboratorio/agregar";
        var data = {
            idReactivoLaboratorio: idReactivoLaboratorio,
            idSolicitudCabecera: $("#idSolicitudCabecera").val(),
            idLaboratoriosProvincia: $("#id_laboratorios_provincia").val(),
            idLaboratoriosProvinciaOrigen: $("#fLaboratorio").val()
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
                $("#detalleItem").html(html);
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
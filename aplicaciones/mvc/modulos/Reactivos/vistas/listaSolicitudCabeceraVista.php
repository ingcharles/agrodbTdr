<header>
    <h1><?php echo $this->opcion; ?></h1>
    <nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>
<div id="div_filtros" style="display: none">
    <fieldset>
        <legend class='legendBusqueda'>Filtros de b&uacute;squeda - Reactivos Bodega</legend>

        <div data-linea="1">
            <label for="fNombre">Nombre</label> 
            <input type="text" id="fNombre" name="fNombre"/>
        </div>

        <div data-linea="2">
            <button id="btnFiltrar" class="fas fa-search"> Filtrar lista</button>
            <button id="btnLimpiar" class="fas fa-times"> Limpiar filtros</button>
        </div>
    </fieldset>
</div>
<div id="paginacion" class="normal"></div>
<!-- Esta tabla se muestra al inicio, luego se cambia al presionar en Nuevo -->
<table id="tablaItems">
    <thead>
        <tr>
            <th>#</th>
            <th title="Nombre del laboratorio">Laboratorio solicitante</th>
            <th title="">Tipo</th>
            <th title="">Solicitado a</th>
            <th title="Fecha de solicitud">Fecha</th>
            <th title="Observaci&oacute;n de la solicitud">Observación</th>
            <th title="Estado de la solicitud">Estado</th>
            <th title="Descargar">Descargar</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<?php require APP . 'Reactivos/vistas/modalLaboratorio.php'; ?>

<script>
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí una solicitud para editarla.</div>');
        distribuirLineas();
        construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
        $("#listadoItems").removeClass("comunes");

        //Cuando se presiona en Filtrar lista, debe cargar los datos
        $("#btnFiltrar").click(function () {
            if ($("#id_laboratorios_provincia").val() === "") {
                mostrarMensaje("Seleccione el Laboratorio.", "FALLO");
            } else {
                fn_filtrarReactivo();
            }
        });

        //Para Limpiar los filtros
        $("#btnLimpiar").click(function () {
            $("#fNombre").val('');
        });
        
        $("#_ingresarSoluciones").click(function () {
            $("#_ingresarSoluciones").attr("data-destino","listadoItems");
            return true;
        });
        
        $("#_ingresoReactivo").click(function () {
            $("#_ingresoReactivo").attr("data-destino","listadoItems");
            return true;
        });
        
        $("#_ingresoReaOtroLab").click(function () {
            $("#_ingresoReaOtroLab").attr("data-destino","listadoItems");
            return true;
        });
        
        $("#_solicitudLaboratorio").click(function () {
            $("#_solicitudLaboratorio").attr("data-destino","listadoItems");
            return true;
        });
    });

    // Obtener los reactivos según la bodega seleccionada
    function fn_filtrarReactivo() {
        $.post("<?php echo URL ?>Reactivos/SolicitudRequerimiento/listarReactivosBodega",
                {
                    id_laboratorios_provincia: $("#id_laboratorios_provincia").val(),
                    id_bodega: $("#id_bodega").val(),
                    id_laboratorio: $("#id_laboratorios_provincia").find(':selected').attr('data-id'),
                    nombre: $("#fNombre").val()
                },
        function (data) {
            $("#tablaItems").html('<thead><tr><th>#</th><th>Provincia*</th><th>Bodega</th><th>Reactivo</th><th>Cantidad</th><th>Egresos</th><th>Saldo</th><th>Unidad</th><th>Estado</th><th>Agregar</th></tr></thead><tbody></tbody>');
            construirPaginacion($("#paginacion"), data.itemsReactivos); //reactivos de la bodega
            $("#tbrequerimiento tbody").html(data.itemsRequeridos);   //reactivos solicitado de la solicitud ACTIVA
        }, 'json');
    }

    $("#_nuevo").click(function () {
        $("#div_filtros").css('display', 'block');
        distribuirLineas();
        $('#modalLaboratorio').modal('show');
    });

    //Funcion para agregar el reactivo-bodega a la solicitud 
    function fn_agregarReaASolicitud(idReactivoBodega, idBodega, idLaboratoriosProvincia, idLaboratorio) {
        var elementoDestino = "#detalleItem";
        var url = "<?php echo URL ?>Reactivos/SolicitudRequerimiento/agregar";
        var data = {
            id: idReactivoBodega,
            id_laboratorios_provincia: idLaboratoriosProvincia,
            id_bodega: idBodega,
            id_laboratorio: idLaboratorio,
            nombre: $("#fNombre").val()
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



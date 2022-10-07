<script src="<?php echo URL_RESOURCE ?>js/jquery.easyui.min.js"></script>
<link rel="stylesheet" href="<?php echo URL_RESOURCE ?>estilos/easyui.css">
<header>
    <h1>Tiempos de respuestas</h1>
    <nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>
<fieldset>
    <legend class='legendBusqueda'>Filtros de b&uacute;squeda</legend>
    <div data-linea="1">
        <label for="fDireccion"> Direcci&oacute;n </label> <select
            id="fDireccion" name="fDireccion" required>
            <option value="">Seleccionar....</option>
            <?php
            echo $this->comboDirecciones($this->modeloTiemposRespuesta->getIdLaboratorio());
            ?>
        </select>
    </div>

    <div data-linea="1">
        <label for="fLaboratorio">Laboratorio</label>
        <select id="fLaboratorio" name="fLaboratorio">
        </select>
    </div>
    <div data-linea="2" id="div_provOrigenMuestra" class="cDatosGenerales">
        <label>Provincia del laboratorio </label> 

        <select id="fid_laboratorios_provincia" name="fid_laboratorios_provincia" disabled="disabled">
        </select>

    </div>

    <div data-linea="2">
        <label for="fServicio">Servicio</label> 
        <select id="fServicio" name="fServicio">
        </select>
    </div>
    <div data-linea="2">
        <button id="btnFiltrar" class="fas fa-search"> Filtrar lista</button>
    </div>

</fieldset>

<div id="paginacion" class="normal"></div>
<table id="tablaItems">
    <thead><tr>
            <th>#</th>
            <th>Laboratorio</th>
            <th>Servicio</th>
            <th>Condici&oacute;n</th>
            <th>Tiempo de respuesta</th>
            <th>Tipo usuario</th>
            <th>Tipo laboratorio</th>
        </tr></thead>
    <tbody></tbody>
</table>

<script>
    var idFiltro;
    var idLaboratorio;
    var idDireccion;
    var idServicio;
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un tiempo de respuesta para editarla.</div>');

        distribuirLineas();
        distribuirLineas(); //importante! nuevamente
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí la configuración para editarla.</div>');
        fn_llenarCmbLaboratorioFiltro();

        $("#btnFiltrar").click(function () {
            if ($("#fLaboratorio").val() !== null && $("#fLaboratorio").val() !== "") {
                fn_filtrar();
            } else {
                mostrarMensaje("Seleccione: Dirección de Diagnóstico y Laboratorio para realizar la búsqueda", "FALLO");
            }
        });
        //CARGAMOS LOS COMBOS DE LOS FITROS DE BUSQUEDA
        //Cuando seleccionamos una dirección, llenamos el combo de laboratorios
        $("#fDireccion").change(function () {
            $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí la configuración para editarla.</div>');
            fn_llenarCmbLaboratorioFiltro();
        });
        //funcion para mostrar los laboratorios en el combo de filtro
        function fn_llenarCmbLaboratorioFiltro() {
            $("#fLaboratorio").html("");
            console.log("Paso 1: Llenar los laboratorios si ha seleccionado la direccion");
            if ($("#fDireccion").val() !== "") {
                //Cargamos los laboratorios
                $.post("<?php echo URL ?>Laboratorios/TiemposRespuesta/comboLaboratorios/" + $("#fDireccion").val(), function (data) {
                    $("#fLaboratorio").html(data);
                });
            }
        }
        /*  $("#fLaboratorio").change(function () {
         $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí la configuración para editarla.</div>');
         fn_llenarCmbServiciosFiltro();
         });*/
    });

//Cuando seleccionamos un laboratorio, llenamos el combo de servicios
    $("#fLaboratorio").change(function () {
        idLaboratorio = $("#fLaboratorio").val();
        $("#fServicio").html("");
        if ($(this).val() !== "") {
            $.post("<?php echo URL ?>Laboratorios/DistribucionMuestras/comboServiciosSinJoinGuia/" + $(this).val(), function (data) {
                $("#fServicio").html(data);
                $("#fServicio").removeAttr("disabled");

                //Cargar las provincia donde estar el laboratorio
                $.post("<?php echo URL ?>Laboratorios/DistribucionMuestras/comboLaboratoriosProvincia/" + idLaboratorio, function (data) {
                    $("#fid_laboratorios_provincia").removeAttr("disabled");
                    $("#fid_laboratorios_provincia").html(data);

                });
            });
        }
    });

    // Función para filtrar
    function fn_filtrar() {
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí registro para editar.</div>');
        $("#paginacion").html("<div id='cargando'>Cargando...</div>");
        $.post("<?php echo URL ?>Laboratorios/TiemposRespuesta/listarDatos",
                {
                    fDireccion: $("#fDireccion").val(),
                    fLaboratorio: $("#fLaboratorio").val(),
                    fLaboratorios_provincia: $("#fid_laboratorios_provincia").val(),
                    fServicio: $("#fServicio").val()
                },
        function (data) {
            construirPaginacion($("#paginacion"), JSON.parse(data));
            $("#listadoItems").removeClass("comunes");
        });
    }
</script>



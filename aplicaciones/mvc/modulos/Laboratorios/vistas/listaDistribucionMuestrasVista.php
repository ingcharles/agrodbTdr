<script src="<?php echo URL_RESOURCE ?>js/jquery.easyui.min.js"></script>
<link rel="stylesheet" href="<?php echo URL_RESOURCE ?>estilos/easyui.css">
<header>
    <h1>Distribuci&oacute;n de muestras</h1>
    <nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>

<fieldset>
    <legend class='legendBusqueda'>Filtros de b&uacute;squeda</legend>
    <div data-linea="1">
        <label for="direccion">Direcci&oacute;n</label> 
        <select id="fDireccion" name="fDireccion">
            <option value="">Seleccionar....</option>
            <?php echo $this->comboDirecciones();
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
            <th>Servicio</th>
            <th>Provincia del laboratorio</th>
            <th>Provincia toma muestra</th>
            <th>Estado</th>
        </tr></thead>
    <tbody></tbody>
</table>

<script>
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para editarla.</div>');
        distribuirLineas();
        //CARGAMOS LOS COMBOS DE LOS FITROS DE BUSQUEDA
        //Cuando seleccionamos una dirección, llenamos el combo de laboratorios
        $("#fDireccion").change(function () {
            var idDireccion = $(this).val();
            $.post("<?php echo URL ?>Laboratorios/DistribucionMuestras/comboLaboratorios/" + idDireccion, function (data) {
                $("#fLaboratorio").html(data);
                $("#fLaboratorio").removeAttr("disabled");
            });
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

        //Cuando se presiona en Filtrar lista, debe cargar los datos
        $("#btnFiltrar").click(function () {
            if ($("#fLaboratorio").val() === null) {
                $("#fLaboratorio").addClass("alertaCombo");
                mostrarMensaje("Seleccione el Laboratorio.", "FALLO");
            } else {
                fn_filtrar();
            }
        });

    });

    // Función para filtrar
    function fn_filtrar() {
    $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para editar.</div>');
        $("#paginacion").html("<div id='cargando'>Cargando...</div>");
        $.post("<?php echo URL ?>Laboratorios/DistribucionMuestras/listarDatos",
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



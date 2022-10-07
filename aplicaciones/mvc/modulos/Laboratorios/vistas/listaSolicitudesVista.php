<link rel='stylesheet'
      href='<?php echo URL_RESOURCE ?>estilos/bootstrap.min.css'>
<script
src="<?php echo URL_RESOURCE ?>js/bootstrap.min.js" type="text/javascript"></script>
<script
src="<?php echo URL_MVC_MODULO ?>Laboratorios/vistas/js/laboratoriosjs.js" type="text/javascript"></script>
<link rel='stylesheet'
      href='<?php echo URL_RESOURCE ?>estilos/fSelect.css'>
<header>
    <h1>Solicitudes</h1>
    <nav><?php echo $this->crearAccionBotones(); ?>
</header>
<div id="cantidadItemsSeleccionados" style="display: none">0</div>
<fieldset>
    <legend class='legendBusqueda'>Filtros de b&uacute;squeda</legend>
    <div data-linea="1">
        <label for="fEstado">Estado Solicitud</label> 
        <select id="fEstado" name="fEstado" title="Estado de la Solicitud">
            <option value="">TODOS...</option>
            <?php
            foreach ($this->estados as $item)
            {
                echo '<option value="' . $item . '">' . $item . '</option>';
            }
            ?>
        </select>
    </div>

    <div data-linea="1">
        <label for="fCodigo">C&oacute;digo Solicitud</label> 
        <input type="text" id="fCodigo" name="fCodigo" placeholder="C&oacute;digo de la Solicitud" title="C&oacute;digo de la Solicitud"/>
    </div>

    <div data-linea="2">
        <button id="btnFiltrar" class="fas fa-search"> Filtrar lista</button>
        <button id="btnLimpiar" class="fas fa-times"> Limpiar filtros</button>
    </div>
</fieldset>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
    <thead>
        <tr>
            <th>#</th>
            <th title="C&oacute;digo de la Solicitud creada por el sistema">C&oacute;digo Solicitud</th>
            <th title="Tipo de solicitud">Tipo</th>
            <th title="Fecha de creaci&oacute;n de la Solicitud">Fecha Registro</th>
            <th title="Fecha de finalizaci&o&oacute;n de todas las &oacute;rdenes de trabajo">Fecha Finalizaci&oacute;n</th>
            <th title="Muestra la lista de informes y adjuntos">Informe</th>
            <th title="Estado actual de la solicitud; si una orden de trabajo no finaliza, la solicitud debe seguir EN PROCESO.">Estado</th>
        </tr>
    </thead>
    <tbody>
        <tr><td colspan="5">Presionar Filtrar lista para ver las Solicitudes</td></tr>
    </tbody>
</table>

<script>
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí una solicitud para editarla.</div>');

        //Cuando se presiona en Filtrar lista, debe cargar los datos
        $("#btnFiltrar").click(function () {
            fn_filtrarSolicitudes();
        });

        //Para Limpiar los filtros
        $("#btnLimpiar").click(function () {
            $("#fEstado").val('');
            $("#fCodigo").val('');
        });
    });

    $("#_labfinalenvio").click(function () {
        if ($("#cantidadItemsSeleccionados").text() == 0 | $("#cantidadItemsSeleccionados").text() > 1) {
            mostrarMensaje("Por favor seleccione un registro a la vez.", "FALLO");
            return false;
        }
    });

    $("#_solmultiusuario").click(function () {
        respuesta = confirm("Desea crear la solicitud en Blanco?");
        if (respuesta) {
            setTimeout(
                    function () {
                        fn_filtrarSolicitudes();
                    }, 1000);
            return true;

        }
        return false;
    });

    $("#_solusuario").click(function () {
        if ($(".seleccionado").attr('id') === undefined) {
            mostrarMensaje("Por favor seleccione un registro a la vez.", "FALLO");
            return false;
        }
    });

    // Función para filtrar
    function fn_filtrarSolicitudes() {
       $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí registro para editar.</div>');
        $("#paginacion").html("<div id='cargando'>Cargando...</div>");
        $.post("<?php echo URL ?>Laboratorios/Solicitudes/listarDatos",
                {
                    estado: $("#fEstado").val(),
                    codigo: $("#fCodigo").val()
                },
        function (data) {
            construirPaginacion($("#paginacion"), JSON.parse(data));
            $("#listadoItems").removeClass("comunes");
        });
    }
</script>



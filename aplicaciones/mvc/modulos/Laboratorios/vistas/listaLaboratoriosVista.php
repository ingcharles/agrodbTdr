<header>
    <h1>Laboratorios</h1>
    <nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>

<fieldset>
    <legend class='legendBusqueda'>Filtros de b&uacute;squeda</legend>
    <div data-linea="10">
        <label for="direccion"> Dirección </label> <select
            id="direccion" name="direccion" required>
            <option value="">TODOS...</option>
            <?php
            echo $this->comboDirecciones($this->modeloLaboratorios->getFkIdLaboratorio());
            ?>
        </select>
    </div>

    <div data-linea="10">
        <label for="codigo">C&oacute;digo</label> 
        <input type="text" id="codigo" name="codigo"/>
    </div>

    <div data-linea="10">
        <label for="nombre">Nombre</label> 
        <input type="text" id="nombref" name="nombref"/>
    </div>

    <button id="btnFiltrar" class="fas fa-search"> Filtrar lista</button>
    <button id="btnLimpiar" class="fas fa-times"> Limpiar filtros</button>
</fieldset>

<div id="paginacion" class="normal"></div>
<table id="tablaItems">
    <thead>
        <tr>
            <th>#</th>
            <th>Laboratorio</th>
            <th>Código</th>
            <th>Estado</th>
            <th>Orden</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<script>
    $(document).ready(function () {
        <?php echo $this->codigoJS; ?>
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un laboratorio para editarla.</div>');

        distribuirLineas();

        $("#_eliminar").click(function () {
            if ($("#cantidadItemsSeleccionados").text() > 1) {
                mostrarMensaje("Por favor seleccione un registro a la vez.", "FALLO");
                return false;
            }
        });

        //CARGAMOS LOS COMBOS DE LOS FITROS DE BUSQUEDA
        //Cuando seleccionamos una dirección, llenamos el combo de laboratorios
        $("#direccion").change(function () {
            $("#direccion option:selected").each(function () {
                idDireccion = $(this).val();
                //Cargamos los laboratorios
                $.post("<?php echo URL ?>Laboratorios/Laboratorios/comboLaboratorios/" + idDireccion, function (data) {
                    $("#laboratorio").html(data);
                });
            });
        });

        //Cuando se presiona en Filtrar lista, debe cargar los datos
        $("#btnFiltrar").click(function () {
            fn_filtrar();
        });

        //Para Limpiar los filtros
        $("#btnLimpiar").click(function () {
            $('#direccion option[value=""]').prop('selected', true);
            $("#codigo").val('');
            $("#nombref").val('');
        });
    });

    // Función para filtrar
    function fn_filtrar() {
       $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para editar.</div>');
        $("#paginacion").html("<div id='cargando'>Cargando...</div>");
        $.post("<?php echo URL ?>Laboratorios/Laboratorios/listarDatos/laboratorios",
                {
                    direccion: $("#direccion").val(),
                    codigo: $("#codigo").val(),
                    nombre: $("#nombref").val()
                },
        function (data) {
            construirPaginacion($("#paginacion"), JSON.parse(data));
        });
    }
</script>



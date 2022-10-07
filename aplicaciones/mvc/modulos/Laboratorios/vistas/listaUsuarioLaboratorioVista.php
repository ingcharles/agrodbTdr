<header>
    <h1>Asignar laboratorios</h1>
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
    
     <div data-linea="2" id="div_provOrigenMuestra" class="cDatosGenerales">
        <label>Provincia del laboratorio </label> 

        <select id="fid_laboratorios_provincia" name="fid_laboratorios_provincia" disabled="disabled">
        </select>

    </div>

    <div data-linea="2">
        <label for="codigo">Usuario (nombre/c&eacute;dula)</label> 
        <input type="text" id="fUsuario" name="fUsuario"/>
    </div>

    <div data-linea="3">
        <button id="btnFiltrar" class="fas fa-search"> Filtrar lista</button>
        <button id="btnLimpiar" class="fas fa-times"> Limpiar filtros</button>
    </div>
</fieldset>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
    <thead><tr>
            <th>#</th>
            <th>Dirección</th>
            <th>Laboratorio</th>
            <th>Usuario</th>
            <th>Perfil</th>
            <th>Provincia del Laboratorio</th>
            <th>Estado</th>
        </tr></thead>
    <tbody></tbody>
</table>

<script>
    $(document).ready(function () {
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para editarla.</div>');
        distribuirLineas();

        $("#btnFiltrar").click(function () {
            fn_filtrar();
        });
        
        //Para Limpiar los filtros
        $("#btnLimpiar").click(function () {
            $('#fDireccion option[value=""]').prop('selected', true);
            $('#fLaboratorio').html('');
            $("#fUsuario").val('');
        });

        //CARGAMOS LOS COMBOS DE LOS FITROS DE BUSQUEDA
        //Cuando seleccionamos una dirección, llenamos el combo de laboratorios
        $("#fDireccion").change(function () {
            fn_llenarCmbLaboratorioFiltro();
        });

        //funcion para mostrar los laboratorios en el combo de filtro
        function fn_llenarCmbLaboratorioFiltro() {
            $("#fLaboratorio").html("");
            if ($("#fDireccion").val() !== "") {
                //Cargamos los laboratorios
                $.post("<?php echo URL ?>Laboratorios/UsuarioLaboratorio/comboLaboratorios/" + $("#fDireccion").val(), function (data) {
                    $("#fLaboratorio").html(data);
                });
            }
        }

    });


    // Función para filtrar
    function fn_filtrar() {
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para editar.</div>');
        $("#paginacion").html("<div id='cargando'>Cargando...</div>");
        $.post("<?php echo URL ?>Laboratorios/UsuarioLaboratorio/buscarDatos",
                {
                    fDireccion: $("#fDireccion").val(),
                    fLaboratorio: $("#fLaboratorio").val(),
                    fUsuario: $("#fUsuario").val(),
                    fidLaboratoriosProvincia: $("#fid_laboratorios_provincia").val()
                },
        function (data) {
            construirPaginacion($("#paginacion"), JSON.parse(data));
        });
    }
    
    
    //Cuando seleccionamos un laboratorio, llenamos el combo de servicios
        $("#fLaboratorio").change(function () {
            idLaboratorio = $("#fLaboratorio").val();
           
            if (idLaboratorio !== "") {
               
                    //Cargar las provincia donde estar el laboratorio
                    $.post("<?php echo URL ?>Laboratorios/UsuarioLaboratorio/comboLaboratoriosProvincia/" + idLaboratorio, function (data) {
                        $("#fid_laboratorios_provincia").removeAttr("disabled");
                        $("#fid_laboratorios_provincia").html(data);

                    });
                }
            
        });

</script>



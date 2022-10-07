<?php
    session_start();
?>

    <header>
        <h1>Nuevo de tipo de producto</h1>
    </header>
    <div id="estado"></div>
    <form id="nuevoTipoProducto" data-rutaAplicacion="registroInsumosAgropecuarios" data-opcion="guardarTipo" data-destino="detalleItem">
        <fieldset id="fs_detalle">
            <legend>Detalle</legend>

            <div data-linea="1">
                <label for="area">Área</label>
                <select id="area" name="area">
                    <option value="IAP">Registro de Insumos Agrícolas</option>
                    <option value="IAV">Registro de Insumos Pecuarios</option>
                </select>
            </div>
            <div data-linea="2">
                <label for="nombreTipo">Nombre</label>
                <input id="nombreTipo" name="nombreTipo" type="text"  />
            </div>
            <div>
                <button type="submit" class="guardar">Guardar</button>
            </div>
        </fieldset>
    </form>
<script>
    $('document').ready(function() {
        distribuirLineas();
    });

    $("#nuevoTipoProducto").submit(function(e) {
        e.preventDefault();

        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;

        if ($("#area").val() == "") {
            error = true;
            $("#area").addClass("alertaCombo");
        }

        if ($.trim($("#nombreTipo").val()) == "" ) {
            error = true;
            $("#nombreTipo").addClass("alertaCombo");
        }

        if (!error){
            abrir($(this), e, false);
            //ejecutarJson($(this));
        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }

    });

</script>
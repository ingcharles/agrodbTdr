<?php
    session_start();
?>

    <header>
        <h1>Nuevo de ingrediente activo</h1>
    </header>
    <div id="estado"></div>
    <form id="nuevoIngredienteActivo" data-rutaAplicacion="registroInsumosAgropecuarios" data-opcion="guardarIngredienteActivo" data-destino="detalleItem">
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
                <label for="nombreIngredienteActivo">Nombre</label>
                <input id="nombreIngredienteActivo" name="nombreIngredienteActivo" type="text"  />
            </div>
            <div data-linea="3">
                <label for="casIngredienteActivo">CAS</label>
                <input id="casIngredienteActivo" name="casIngredienteActivo" type="text"  />
            </div>
            <hr />
            <p>Restricción aplicada a todos los productos</p>
            <div data-linea="5">
                <label for="restriccionIngredienteActivo">Restricción comercial</label>
                <select id="restriccionIngredienteActivo" name="restriccionIngredienteActivo"">
                    <option value="Ninguna - Venta libre">Ninguna - Venta libre</option>
                    <option value="Venta bajo receta">Venta bajo receta</option>
                    <option value="Venta bajo receta retenida">Venta bajo receta retenida</option>
                    <option value="Venta aplicada">Venta aplicada</option>
                </select>
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

    $("#nuevoIngredienteActivo").submit(function(e) {
        e.preventDefault();

        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;

        if ($("#area").val() == "") {
            error = true;
            $("#area").addClass("alertaCombo");
        }

        if ($.trim($("#nombreIngredienteActivo").val()) == "" ) {
            error = true;
            $("#nombreIngredienteActivo").addClass("alertaCombo");
        }

        if ($.trim($("#casIngredienteActivo").val()) == "" ) {
            error = true;
            $("#casIngredienteActivo").addClass("alertaCombo");
        }

        if (!error){
            abrir($(this), e, false);
            //ejecutarJson($(this));
        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }

    });

</script>
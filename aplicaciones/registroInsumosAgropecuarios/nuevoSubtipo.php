<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorRIA.php';

    $conexion = new Conexion();
    $cr = new ControladorRIA();
    $tipos = $cr->listarTipos($conexion, array('IAV', 'IAP'), 1);
?>

    <header>
        <h1>Nuevo de tipo de producto</h1>
    </header>
    <div id="estado"></div>
    <form id="nuevoSubtipo" data-rutaAplicacion="registroInsumosAgropecuarios" data-opcion="guardarSubtipo" data-destino="detalleItem">
        <fieldset id="fs_detalle">
            <legend>Detalle</legend>

            <div data-linea="1">
                <label for="idTipo">Tipo de producto</label>
                <select id="idTipo" name="idTipo">
                    <?php
                        while($tipo = pg_fetch_assoc($tipos)) {
                            echo '<option value="' . $tipo['id_tipo_producto'] . '">' . $tipo['nombre'] . '</option>';
                        }
                    ?>
                </select>
            </div>
            <div data-linea="2">
                <label for="nombreSubtipo">Nombre</label>
                <input id="nombreSubtipo" name="nombreSubtipo" type="text"  />
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

    $("#nuevoSubtipo").submit(function(e) {
        e.preventDefault();

        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;

        if ($("#idTipo").val() == "") {
            error = true;
            $("#idTipo").addClass("alertaCombo");
        }

        if ($.trim($("#nombreSubtipo").val()) == "" ) {
            error = true;
            $("#nombreSubtipo").addClass("alertaCombo");
        }

        if (!error){
            abrir($(this), e, false);
            //ejecutarJson($(this));
        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }

    });

</script>
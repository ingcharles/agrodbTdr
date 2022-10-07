<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorRIA.php';

    $idIngredienteActivo = htmlspecialchars($_POST['id'], ENT_NOQUOTES, 'UTF-8');

    $conexion = new Conexion();
    $cr = new ControladorRIA();

    $ingrediente = pg_fetch_assoc($cr->abrirIngredienteActivo($conexion, $idIngredienteActivo));
?>

    <header>
        <h1>Detalle de ingrediente activo</h1>
    </header>
    <div id="estado"></div>
    <form id="actualizarRegistro" data-rutaAplicacion="registroInsumosAgropecuarios" data-opcion="modificarIngredienteActivo" data-accionEnExito="ACTUALIZAR">
        <fieldset id="fs_detalle">
            <legend>Detalle</legend>

            <div data-linea="1">
                <label for="idIngredienteActivo">Código del sistema</label>
                <input id="idIngredienteActivo" name="idIngredienteActivo" type="text" readonly="readonly" value="<?php echo $ingrediente['id_ingrediente_activo']; ?>" />
            </div>
            <div data-linea="1">
                <label for="area">Área</label>
                <input id="area" name="area" type="text" readonly="readonly" disabled="disabled" value="<?php echo $ingrediente['nombre_area']; ?>" />
            </div>
            <div data-linea="2">
                <label for="nombreIngredienteActivo">Nombre</label>
                <input id="nombreIngredienteActivo" name="nombreIngredienteActivo" type="text" value="<?php echo $ingrediente['ingrediente_activo']; ?>" />
            </div>
            <div data-linea="3">
                <label for="casIngredienteActivo">CAS</label>
                <input id="casIngredienteActivo" name="casIngredienteActivo" type="text" value="<?php echo $ingrediente['cas']; ?>" />
            </div>
            <div data-linea="4">
                <label for="estadoIngredienteActivo">Prohibido</label>
                <select id="estadoIngredienteActivo" name="estadoIngredienteActivo">
                    <option value="1">No</option>
                    <option value="0">Sí</option>
                </select>
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
                <button type="submit" class="guardar">Actualizar</button>
            </div>
        </fieldset>
    </form>
<script>
    $('document').ready(function(){
        distribuirLineas();
        cargarValorDefecto("estadoIngredienteActivo", "<?php echo $ingrediente['estado']; ?>");
        cargarValorDefecto("restriccionIngredienteActivo", "<?php echo $ingrediente['restriccion']; ?>")
    });

    $("#actualizarRegistro").submit(function(e){
        e.preventDefault();

        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;

        if ($("#estadoIngredienteActivo").val() == "") {
            error = true;
            $("#estadoIngredienteActivo").addClass("alertaCombo");
        }

        if ($.trim($("#nombreIngredienteActivo").val()) == "" ) {
            error = true;
            $("#nombreIngredienteActivo").addClass("alertaCombo");
        }

        if ($.trim($("#casIngredienteActivo").val()) == "" ) {
            error = true;
            $("#casIngredienteActivo").addClass("alertaCombo");
        }

        if (!error) {
            ejecutarJson($(this));
        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }

    });

</script>
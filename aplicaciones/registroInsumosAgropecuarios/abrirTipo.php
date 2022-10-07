<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorRIA.php';

    $idTipo = htmlspecialchars($_POST['id'], ENT_NOQUOTES, 'UTF-8');

    $conexion = new Conexion();
    $cr = new ControladorRIA();

    $tipo = pg_fetch_assoc($cr->abrirTipo($conexion, $idTipo));
?>

    <header>
        <h1>Detalle de tipo de producto</h1>
    </header>
    <div id="estado"></div>
    <form id="actualizarRegistro" data-rutaAplicacion="registroInsumosAgropecuarios" data-opcion="modificarTipo" data-accionEnExito="ACTUALIZAR">
        <fieldset id="fs_detalle">
            <legend>Detalle</legend>

            <div data-linea="1">
                <label for="idTipo">Código del sistema</label>
                <input id="idTipo" name="idTipo" type="text" readonly="readonly" value="<?php echo $tipo['id_tipo_producto']; ?>" />
            </div>
            <div data-linea="1">
                <label for="area">Área</label>
                <input id="area" name="area" type="text" readonly="readonly" disabled="disabled" value="<?php echo $tipo['nombre_area']; ?>" />
            </div>
            <div data-linea="2">
                <label for="nombreTipo">Nombre</label>
                <input id="nombreTipo" name="nombreTipo" type="text" value="<?php echo $tipo['nombre']; ?>" />
            </div>
            <div data-linea="3">
                <label for="estadoTipo">Estado</label>
                <select id="estadoTipo" name="estadoTipo">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
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
        cargarValorDefecto("estadoTipo", "<?php echo $tipo['estado']; ?>")
    });

    $("#actualizarRegistro").submit(function(e){
        e.preventDefault();

        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;

        if ($("#estadoTipo").val() == "") {
            error = true;
            $("#estadoTipo").addClass("alertaCombo");
        }

        if ($.trim($("#nombreTipo").val()) == "" ) {
            error = true;
            $("#nombreTipo").addClass("alertaCombo");
        }

        if (!error) {
            ejecutarJson($(this));
        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }

    });

</script>
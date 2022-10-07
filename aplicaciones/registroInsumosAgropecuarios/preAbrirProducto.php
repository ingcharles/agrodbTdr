<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRIA.php';

$idProducto = htmlspecialchars($_POST['id'], ENT_NOQUOTES, 'UTF-8');

$conexion = new Conexion();
$cr = new ControladorRIA();

$producto = pg_fetch_assoc($cr->abrirProducto($conexion, $idProducto));
$tipos = $cr->listarTiposSubtipos($conexion, array('IAV', 'IAP'), 1);

//$tipo = pg_fetch_assoc($cr->abrirTipo($conexion, $idTipo));
?>

<header>
    <h1><?php echo $producto['nombre_comun']; ?></h1>
</header>
<div id="estado"></div>
<fieldset id="fs_opciones">
    <legend>Opciones</legend>
    <div>
        <button id="<?php echo $idProducto; ?>" type="button" class="abrir"
                data-rutaAplicacion="registroInsumosAgropecuarios" data-opcion="abrirProducto" data-destino="EXT">
            Mostrar
        </button>
        <button id="duplicar" type="button" class="duplicar">Duplicar</button>
    </div>
</fieldset>
<form id="duplicarProducto"
      data-rutaAplicacion="registroInsumosAgropecuarios"
      data-opcion="duplicarProducto"
      data-accionEnExito="">
    <input type="hidden" name="idProducto" value="<?php echo $idProducto; ?>">
    <fieldset id="fs_duplicar" style="display: none">
        <legend>Duplicar</legend>
        <div data-linea="1">
            <label for="idSubtipo">Subtipo</label>
            <select id="idSubtipo" name="idSubtipo">
                <?php
                foreach ($tipos['array_to_json'] as $tipo) {
                    echo "<optgroup label='" . $tipo['nombre'] . "'>";
                    foreach ($tipo['array_to_json'] as $subtipo) {
                        echo "<option value='" . $subtipo['id_subtipo_producto'] . "'>" . $subtipo['nombre'] . "</option>";
                    }
                    echo "</optgroup>";
                }
                ?>
            </select>
        </div>
        <div data-linea="2">
            <label for="partidaArancelaria">Partida arancelaria</label>
            <input id="partidaArancelaria" name="partidaArancelaria" type="text"/>
        </div>
        <div data-linea="3">
            <button type="submit" class="duplicar">Duplicar</button>
        </div>
    </fieldset>
</form>
<script>
    $('document').ready(function () {
        distribuirLineas();
    });

    $('#fs_opciones button.abrir').click(function () {
        abrir($(this));
    });

    $('#duplicar').click(function () {
        $("#fs_opciones").hide();
        $("#fs_duplicar").show();
    })

    $("form#duplicarProducto").submit(function (e) {
        e.preventDefault();

        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;

        if ($("#partidaArancelaria").val().trim() == "") {
            $("#partidaArancelaria").addClass("alertaCombo");
            error = true;
        }
        if (!error) {
            //abrir($(this), e, false);
            ejecutarJson($(this));
        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }

    });
</script>
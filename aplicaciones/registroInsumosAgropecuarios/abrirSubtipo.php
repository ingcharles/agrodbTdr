<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorRIA.php';

    $idSubtipo = htmlspecialchars($_POST['id'], ENT_NOQUOTES, 'UTF-8');
    if (strpos($idSubtipo, 'st_') !== false) {
        $idSubtipo = explode('_', htmlspecialchars($_POST['id'], ENT_NOQUOTES, 'UTF-8'))[1];
    }

    $conexion = new Conexion();
    $cr = new ControladorRIA();

    $tipos = $cr->listarTipos($conexion, array('IAV', 'IAP'), 1);
    $productos = $cr->listarProductos($conexion, $idSubtipo);
    $subtipo = pg_fetch_assoc($cr->abrirSubtipo($conexion, $idSubtipo));
?>

<header>
    <h1>Detalle de subtipo de producto</h1>
</header>
<div id="estado"></div>
<form id="actualizarRegistro" data-rutaAplicacion="registroInsumosAgropecuarios" data-opcion="modificarSubtipo" data-accionEnExito="ACTUALIZAR">
    <fieldset id="fs_detalle">
        <legend>Detalle</legend>

        <div data-linea="1">
            <label for="idSubtipo">Código del sistema</label>
            <input id="idSubtipo" name="idSubtipo" type="text" readonly="readonly" value="<?php echo $subtipo['id_subtipo_producto']; ?>" />
        </div>
        <div data-linea="2">
            <label for="idTipo">Tipo</label>
            <select id="idTipo" name="idTipo">
                <?php
                    while($tipo = pg_fetch_assoc($tipos)) {
                        echo '<option value="' . $tipo['id_tipo_producto'] . '">' . $tipo['nombre'] . '</option>';
                    }
                ?>
            </select>
        </div>
        <div data-linea="3">
            <label for="nombreSubtipo">Nombre</label>
            <input id="nombreSubtipo" name="nombreSubtipo" type="text" value="<?php echo $subtipo['nombre']; ?>" />
        </div>
        <div data-linea="4">
            <label for="estadoSubtipo">Estado</label>
            <select id="estadoSubtipo" name="estadoSubtipo">
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
        <div>
            <button type="submit" class="guardar">Actualizar</button>
        </div>
    </fieldset>

    <fieldset id="fs_productos">
        <legend>Productos</legend>

        <table>
            <tr><th>#</th><th>Nombre común</th><th>Nombre científico</th><th>Código sistema</th></tr>
            <?php
            $contador = 0;
            while($producto = pg_fetch_assoc($productos)) {
                $estado = $producto['estado'] == 1 ? "Activo" : "Inactivo";
                echo '<tr class="' . $estado . '">';
                echo '<td>' . ++$contador . '</td>';
                echo '<td><a href="#" target="_blank">' . $producto['nombre_comun'] . '</a></td>';
                echo '<td>' . $producto['nombre_cientifico'] . '</td>';
                echo '<td>' . $producto['id_producto'] . '</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </fieldset>
</form>
<script>
    $('document').ready(function(){
        distribuirLineas();
        cargarValorDefecto("idTipo", "<?php echo $subtipo['id_tipo_producto']; ?>")
        cargarValorDefecto("estadoSubtipo", "<?php echo $subtipo['estado']; ?>")
    });

    $("#actualizarRegistro").submit(function(e){
        e.preventDefault();

        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;

        if ($("#idTipo").val() == "") {
            error = true;
            $("#idTipo").addClass("alertaCombo");
        }

        if ($("#estadoSubtipo").val() == "") {
            error = true;
            $("#estadoSubtipo").addClass("alertaCombo");
        }

        if ($.trim($("#nombreSubtipo").val()) == "" ) {
            error = true;
            $("#nombreSubtipo").addClass("alertaCombo");
        }

        if (!error) {
            ejecutarJson($(this));
        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }

    });

</script>
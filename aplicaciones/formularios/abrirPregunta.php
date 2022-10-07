<?php

    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorFormularios.php';

    $idPregunta = $_POST['pregunta'];

    $conexion = new Conexion();
    $cf = new ControladorFormularios();
    //$ca = new ControladorAuditoria();
    /*$ca = new ControladorAplicaciones('formulario','abrirCategoria');*/

    $pregunta = pg_fetch_assoc($cf->abrirPregunta($conexion, $idPregunta));
    $opciones = $cf->cargarOpciones($conexion, $idPregunta);

    $mostrarPanelOpciones = $pregunta['tipo_pregunta'] == 2 || $pregunta['tipo_pregunta'] == 3; //selección multiple u opción multiple

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

</head>
<body>
<header>
    <h1>Detalle de pregunta</h1>
</header>
<div id="estado"></div>
<form id="regresar" data-rutaAplicacion="formularios" data-opcion="abrirCategoria" data-destino="detalleItem">
    <input type="hidden" name="categoria" value="<?php echo $pregunta['id_categoria']; ?>"/>
    <button class="regresar">Regresar a categoría</button>
</form>

<form id="actualizarRegistro" data-rutaAplicacion="formularios" data-opcion="modificarPregunta">
    <input id="idPregunta" name="idPregunta" type="hidden" value="<?php echo $idPregunta ?>"/>
    <fieldset>
        <legend>Pregunta</legend>
        <div data-linea="1">
            <label for="nombre">Nombre</label>
            <input id="nombre" name="nombre" type="text" value="<?php echo $pregunta['nombre'] ?>"/>
        </div>
        <div data-linea="2">
            <label for="ayuda">Ayuda</label>
            <input id="ayuda" name="ayuda" type="text" value="<?php echo $pregunta['ayuda'] ?>"/>
        </div>
        <div>
            <button type="submit" class="guardar">Actualizar</button>
        </div>
    </fieldset>
</form>

<?php
    if ($mostrarPanelOpciones) {
        ?>
        <form id="nuevoRegistro" data-rutaAplicacion="formularios" data-opcion="nuevaOpcion">
            <input id="pregunta" name="pregunta" type="hidden" value="<?php echo $pregunta['id_pregunta'] ?>"/>
            <input id="categoria" name="categoria" type="hidden" value="<?php echo $pregunta['id_categoria'] ?>"/>
            <input id="formulario" name="formulario" type="hidden" value="<?php echo $pregunta['id_formulario'] ?>"/>
            <fieldset>
                <legend>Opciones</legend>
                <div data-linea="1">
                    <label for="opcion">Opción</label>
                    <input name="opcion" id="opcion" type="text"/>
                </div>
                <div data-linea="2">
                    <label for="ponderacion">Ponderacion</label>
                    <span id="valorPonderacion">0</span>
                </div>
                <div>
                    <input id="ponderacion" name="ponderacion" type="range" min="0" max="5" value="0"
                           onchange="actualizarPonderacion(this.value)"/>
                </div>
                <div>
                    <button type="submit" class="mas">Añadir opcion</button>
                </div>
            </fieldset>
        </form>
        <fieldset>
            <table id="registros">
                <?php
                    while ($opcion = pg_fetch_assoc($opciones)) {
                        echo $cf->imprimirLineaOpcion($opcion['id_opcion'], $opcion['opcion'], $opcion['ponderacion']);
                    }
                ?>
            </table>
        </fieldset>
    <?php
    }
?>

</body>
<script>
    $('document').ready(function () {
        distribuirLineas();
        <?php
            if ($mostrarPanelOpciones) {
        ?>
        actualizarBotonesOrdenamiento();
        <?php
            }
        ?>
        acciones();
    });

    function actualizarPonderacion(valor) {
        $("#valorPonderacion").html(valor);
    }

</script>
</html>
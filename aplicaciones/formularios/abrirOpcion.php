<?php

    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorFormularios.php';

    $idOpcion = $_POST['opcion'];

    $conexion = new Conexion();
    $cf = new ControladorFormularios();
    //$ca = new ControladorAuditoria();
    /*$ca = new ControladorAplicaciones('formulario','abrirCategoria');*/

    $opcion = pg_fetch_assoc($cf->abrirOpcion($conexion, $idOpcion));

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

</head>
<body>
<header>
    <h1>Detalle de opció n</h1>
</header>
<div id="estado"></div>
<form id="regresar" data-rutaAplicacion="formularios" data-opcion="abrirPregunta" data-destino="detalleItem">
    <input type="hidden" name="pregunta" value="<?php echo $opcion['id_pregunta']; ?>"/>
    <button class="regresar">Regresar a pregunta</button>
</form>

<form id="actualizarRegistro" data-rutaAplicacion="formularios" data-opcion="modificarOpcion">
    <input id="idOpcion" name="idOpcion" type="hidden" value="<?php echo $idOpcion ?>"/>
    <fieldset>
        <legend>Opción</legend>
        <div data-linea="1">
            <label for="opcion">Nombre</label>
            <input id="opcion" name="opcion" type="text" value="<?php echo $opcion['opcion'] ?>"/>
        </div>
        <div data-linea="2">
            <label for="ponderacion">Ponderacion</label>
            <span id="valorPonderacion"><?php echo $opcion['ponderacion']; ?></span>
        </div>
        <div>
            <input id="ponderacion" name="ponderacion" type="range" min="0" max="5"
                   value="<?php echo $opcion['ponderacion'] ?>" onchange="actualizarPonderacion(this.value)"/>
        </div>
        <div>
            <button type="submit" class="guardar">Actualizar</button>
        </div>
    </fieldset>
</form>


</body>
<script>
    $('document').ready(function () {
        distribuirLineas();
        acciones();
    });

</script>
</html>
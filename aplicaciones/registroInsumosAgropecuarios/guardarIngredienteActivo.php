<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorRIA.php';

    $nombreIngredienteActivo = htmlspecialchars($_POST['nombreIngredienteActivo'], ENT_NOQUOTES, 'UTF-8');
    $casIngredienteActivo = htmlspecialchars($_POST['casIngredienteActivo'], ENT_NOQUOTES, 'UTF-8');
    $restriccionIngredienteActivo = htmlspecialchars($_POST['restriccionIngredienteActivo'], ENT_NOQUOTES, 'UTF-8');
    $area = htmlspecialchars($_POST['area'], ENT_NOQUOTES, 'UTF-8');

    $conexion = new Conexion();
    $cr = new ControladorRIA();

    $id = pg_fetch_row($cr->guardarIngredienteActivo($conexion, $nombreIngredienteActivo, $casIngredienteActivo, $restriccionIngredienteActivo, $area));
    echo '<input type="hidden" id="' . $id[0] . '" data-rutaAplicacion="registroInsumosAgropecuarios" data-opcion="abrirIngredienteActivo" data-destino="detalleItem"/>';
?>

<script type="text/javascript">
    $('document').ready(function() {
        abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
        abrir($("#detalleItem input"), null, true);
    });
</script>
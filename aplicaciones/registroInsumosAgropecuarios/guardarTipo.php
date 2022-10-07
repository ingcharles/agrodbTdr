<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorRIA.php';

    $nombre = htmlspecialchars($_POST['nombreTipo'], ENT_NOQUOTES, 'UTF-8');
    $area = htmlspecialchars($_POST['area'], ENT_NOQUOTES, 'UTF-8');

    $conexion = new Conexion();
    $cr = new ControladorRIA();

    $id = pg_fetch_row($cr->guardarTipo($conexion, $nombre, $area));
    echo '<input type="hidden" id="' . $id[0] . '" data-rutaAplicacion="registroInsumosAgropecuarios" data-opcion="abrirTipo" data-destino="detalleItem"/>';
?>

<script type="text/javascript">
    $('document').ready(function() {
        abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
        abrir($("#detalleItem input"), null, true);
    });
</script>
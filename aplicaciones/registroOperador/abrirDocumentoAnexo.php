<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorRegistroOperador.php';

    $conexion = new Conexion();
    $cr = new ControladorRegistroOperador();

    $documento = $_POST['id'];

    $documento = pg_fetch_assoc($cr->abrirDocumento($conexion, $documento));

?>

<header>
    <h1>Documento</h1>
</header>

<div id="estado"></div>

<fieldset>
    <legend>Documento anexo</legend>
    <div data-linea="1">
        <a href="<?php echo $documento['ruta_documento'];?>" target="_blank">
            <?php echo $documento['descr'];?> (<?php echo $documento['nombre_documento'];?>)
        </a>
    </div>
</fieldset>
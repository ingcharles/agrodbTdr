<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorRegistroOperador.php';

    $tipo = htmlspecialchars($_POST['tipo'], ENT_NOQUOTES, 'UTF-8');
    $descripcion = htmlspecialchars($_POST['descripcion'], ENT_NOQUOTES, 'UTF-8');
    $archivo = htmlspecialchars($_POST['rutaArchivo'], ENT_NOQUOTES, 'UTF-8');
    $usuario = $_SESSION['usuario'];

    $conexion = new Conexion();
    $cro = new ControladorRegistroOperador();

    $cro->guardarDocumento($conexion, $usuario, $tipo, $descripcion, $archivo);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

   El archivo se ha guardado

</body>
<script type="text/javascript">

    $("document").ready(function () {
        abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
        abrir($("input:hidden"), null, false);
    });

</script>
</html>


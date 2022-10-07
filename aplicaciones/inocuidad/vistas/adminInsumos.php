<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 31/01/18
 * Time: 21:16
 */
session_start();
require_once '../controladores/ControladorInsumo.php';
require_once '../../../clases/ControladorAplicaciones.php';
require_once '../Modelo/Insumo.php';
$conexion = new Conexion();
?>
<!DOCTYPE html>
<html>
<head>
    <script src="aplicaciones/inocuidad/js/inocuidad_root.js" type="text/javascript"/>
    <meta charset="utf-8">

</head>
<body>
<header>
    <h1>Insumos</h1>
    <nav>

        <?php
        $ca = new ControladorAplicaciones();
        $res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);

        while($fila = pg_fetch_assoc($res)){
            echo '<a href="#"
                            id="' . $fila['estilo'] . '"
                            data-destino="detalleItem"
                            data-opcion="' . $fila['pagina'] . '"
                            data-rutaAplicacion="' . $fila['ruta'] . '"
                            >'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';

        }
        ?>
    </nav>
</header>
    <?php
        $controladorInsumo = new ControladorInsumo();
        echo $controladorInsumo->listArticles();
    ?>
</body>
<script>
    $(document).ready(function(){
        $("#listadoItems").removeClass("programas");
        $("#listadoItems").addClass("comunes");
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí una ficha para editarla.</div>');
    });
</script>
</html>

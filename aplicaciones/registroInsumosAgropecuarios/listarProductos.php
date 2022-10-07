<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorRIA.php';

$conexion = new Conexion();
$ca = new ControladorAplicaciones('registroInsumosAgropecuarios', 'preAbrirProducto', null, 0);
$cr = new ControladorRIA();
$opcion = htmlspecialchars($_POST['opcion'], ENT_NOQUOTES, 'UTF-8');
$usuario = htmlspecialchars($_SESSION['usuario'], ENT_NOQUOTES, 'UTF-8');

$contador = 0;
?>

<header>
    <h1>Tipos</h1>
    <?php echo $ca->imprimirMenuDeAcciones($conexion, $opcion, $usuario); ?>
</header>
<div id="iap">
    <h2>Dirección de Registro de Insumos Agrícolas</h2>
    <table>

        <?php
        $productos = $cr->listarProductosPorArea($conexion, array('IAP'));
        while($producto = pg_fetch_assoc($productos)) {
            //$estado = $tipo['estado'] == 1 ? "Activo" : "Inactivo";
            echo '<tr>
            <td style="width: 30px">'.(++$contador).'</td><td>' .
                $ca->imprimirArticulo($producto['id_producto'], '', $producto['nombre_comun'] . ' (' . $producto['nombre_subtipo'] . ')', '') .
                '</td></tr>';
        }
        ?>

    </table>
</div>
<div id="iav">
    <h2>Dirección de Registro de Insumos Pecuarios</h2>
    <table>
        <?php
        $productos = $cr->listarProductosPorArea($conexion, array('IAV'));
        while($producto = pg_fetch_assoc($productos)) {
            //$estado = $tipo['estado'] == 1 ? "Activo" : "Inactivo";
            echo '<tr class="">
            <td class="ordinal" style="width: 30px">'.(++$contador).'</td><td>' .
                $ca->imprimirArticulo($producto['id_producto'], '', $producto['nombre_comun'] . ' (' . $producto['nombre_subtipo'] . ')', '') .
                '</td></tr>';
        }
        ?>
    </table>
</div>


<script>
    $(document).ready(function(){
        $("#listadoItems").removeClass("comunes");
        $("#listadoItems").addClass("lista");
        $("#iap table article").length == 0 ? $("#iap").remove():"";
        $("#iav table article").length == 0 ? $("#iav").remove():"";
        $("#_nuevo").attr("data-destino","EXT");
    });
</script>

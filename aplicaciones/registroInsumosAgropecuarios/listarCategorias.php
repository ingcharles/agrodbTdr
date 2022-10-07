<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorAplicaciones.php';
    require_once '../../clases/ControladorRIA.php';

    $conexion = new Conexion();
    $ca = new ControladorAplicaciones('registroInsumosAgropecuarios', 'abrirTipo');
    $cr = new ControladorRIA();
    $opcion = htmlspecialchars($_POST['opcion'], ENT_NOQUOTES, 'UTF-8');
    $usuario = htmlspecialchars($_SESSION['usuario'], ENT_NOQUOTES, 'UTF-8');

    $areas = array('IAV', 'IAP');
?>

    <header>
        <h1>Tipos</h1>
        <?php echo $ca->imprimirMenuDeAcciones($conexion, $opcion, $usuario); ?>
    </header>
    <div id="iap">
        <h2>Dirección de Registro de Insumos Agrícolas</h2>
        <div class="elementos"></div>
    </div>
    <div id="iav">
        <h2>Dirección de Registro de Insumos Pecuarios</h2>
        <div class="elementos"></div>
    </div>
<?php
    $tipos = $cr->listarTipos($conexion, $areas);
    $subtipos = $cr->listarSubtipos($conexion, $areas);

    while($tipo = pg_fetch_assoc($tipos)) {
        $categoria = strtolower($tipo['id_area']);
        $estado = $tipo['estado'] == 1 ? "Activo" : "Inactivo";
        $contenido = '<table id="t_' . $tipo['id_tipo_producto'] . '"><tr class="' . $estado . '"><th colspan="2">' . $ca->imprimirArticulo($tipo['id_tipo_producto'], '', $tipo['nombre'], '') . '</th></tr></table>';
?>
        <script type="text/javascript">
            var contenido = <?php echo json_encode($contenido);?>;
            var categoria = <?php echo json_encode($categoria);?>;
            $("#"+categoria+" div.elementos").append(contenido);
        </script>
<?php
    }

    while($subtipo = pg_fetch_assoc($subtipos)) {
        $categoria = strtolower($subtipo['id_tipo_producto']);
        $estado = $subtipo['estado'] == 1 ? "Activo" : "Inactivo";
        $contenido = '<tr class="' . $estado . '"><td class="ordinal_subtipo" style="width: 30px"></td><td>' . $ca->imprimirArticulo('st_' . $subtipo['id_subtipo_producto'], '', $subtipo['nombre'], '', '', '', null, 'abrirSubtipo') . '</td></tr>';
        ?>
        <script type="text/javascript">
            var contenido = <?php echo json_encode($contenido);?>;
            var categoria = <?php echo json_encode($categoria);?>;
            $("#t_"+categoria).append(contenido);
        </script>
        <?php
    }
?>

<script>
    $(document).ready(function(){
        $("#listadoItems").removeClass("comunes");
        $("#listadoItems").addClass("lista");
        $("#iap div article").length == 0 ? $("#iap").remove():"";
        $("#iav div article").length == 0 ? $("#iav").remove():"";
        $(".ordinal_tipo").each(function(i){
            $(this).html(i + 1);
        });
        $(".ordinal_subtipo").each(function(i){
            $(this).html(i + 1 + '.');
        })
    });
</script>

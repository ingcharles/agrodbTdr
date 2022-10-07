<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorRIA.php';

$conexion = new Conexion();
$ca = new ControladorAplicaciones('registroInsumosAgropecuarios', 'abrirComposicion', 'detalleItem', 0);
$cr = new ControladorRIA();
$opcion = htmlspecialchars($_POST['opcion'], ENT_NOQUOTES, 'UTF-8');
$usuario = htmlspecialchars($_SESSION['usuario'], ENT_NOQUOTES, 'UTF-8');

$areas = array('IAV', 'IAP');
?>

<header>
    <h1>Composiciones</h1>
    <?php echo $ca->imprimirMenuDeAcciones($conexion, $opcion, $usuario); ?>
</header>

<?php
$composiciones = $cr->listarComposiciones($conexion);

while($composicion = pg_fetch_assoc($composiciones)) {
    $nombre = $composicion['nombre'] == '' ? '(SIN NOMBRE DEFINIDO)' : $composicion['nombre'];
    $estado = $composicion['estado'] == 1 ? "Activo" : "Inactivo";
    echo '<table id="c_' . $composicion['id_composicion'] . '"><tr class="' . $estado . '"><td class="ordinal_composicion" style="width: 30px"></td><td>' . $ca->imprimirArticulo($composicion['id_composicion'], '', $nombre , '') . '</td></tr></table>';
}
?>

<script>
    $(document).ready(function(){
        $("#listadoItems").removeClass("comunes");
        $("#listadoItems").addClass("lista");
        $(".ordinal_composicion").each(function(i){
            $(this).html(i + 1 + '.');
        });
    });
</script>

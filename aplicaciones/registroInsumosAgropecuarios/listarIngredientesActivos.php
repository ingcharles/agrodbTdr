<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorRIA.php';

$conexion = new Conexion();
$ca = new ControladorAplicaciones('registroInsumosAgropecuarios', 'abrirIngredienteActivo', 'detalleItem', 0);
$cr = new ControladorRIA();
$opcion = htmlspecialchars($_POST['opcion'], ENT_NOQUOTES, 'UTF-8');
$usuario = htmlspecialchars($_SESSION['usuario'], ENT_NOQUOTES, 'UTF-8');

$areas = array('IAV', 'IAP');
?>

<header>
    <h1>Ingredientes activos</h1>
    <?php echo $ca->imprimirMenuDeAcciones($conexion, $opcion, $usuario); ?>
</header>

<?php
$ingredientes = $cr->listarIngredientesActivos($conexion, $areas);

while($ingrediente = pg_fetch_assoc($ingredientes)) {
    $estado = $ingrediente['estado'] == 1 ? "Activo" : "Inactivo";
    echo '<table id="c_' . $ingrediente['id_ingrediente_activo'] . '"><tr class="' . $estado . '"><td class="ordinal_ingrediente" style="width: 30px"></td><td>' . $ca->imprimirArticulo($ingrediente['id_ingrediente_activo'], '', $ingrediente['ingrediente_activo'] . ' (' . $ingrediente['cas'] . ')' , '') . '</td></tr></table>';
}
?>

<script>
    $(document).ready(function(){
        $("#listadoItems").removeClass("comunes");
        $("#listadoItems").addClass("lista");
        $(".ordinal_ingrediente").each(function(i){
            $(this).html(i + 1 + '.');
        });
    });
</script>

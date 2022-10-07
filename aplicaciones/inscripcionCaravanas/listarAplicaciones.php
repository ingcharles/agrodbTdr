<?php

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorInscripcionCaravana.php';
require_once '../../clases/ControladorAplicaciones.php';



$conexion = new Conexion();
$ci = new ControladorInscripcionCaravana();
$ca = new ControladorAplicaciones('inscripcionCaravanas', 'revisarAplicacion');

$inscripciones = $ci->listarAplicaciones($conexion);

?>

<header>
    <h1>Inscripciones</h1>
</header>

<?php
    $contador = 0;
    while($inscripcion = pg_fetch_assoc($inscripciones))
        echo $ca->imprimirArticulo($inscripcion['identificador'], ++$contador, '<strong>' . $inscripcion['estado'] . '</strong><br/>' . $inscripcion['identificador'], $inscripcion['fecha_inscripcion']);
?>

<script type="text/javascript">
    $(document).ready(function(){
        $("#listadoItems").addClass("comunes");
    });
</script>



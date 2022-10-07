<?php

session_start();

require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/GoogleAnalitica.php'; 

$ca = new ControladorAplicaciones('inscripcionCaravanas', 'abrirAplicacion');
?>

<header>
    <h1>Inscripción caravana</h1>
</header>

<?php
    echo $ca->imprimirArticulo('c', '', 'Inscripción de proveedor', '');
?>

<script>
    $(document).ready(function(){
        $("#listadoItems").addClass("comunes");
    });
</script>
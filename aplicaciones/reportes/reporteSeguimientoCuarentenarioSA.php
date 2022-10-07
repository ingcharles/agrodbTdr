<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicacionesPerfiles.php';

$conexion = new Conexion();
$ca = new ControladorAplicacionesPerfiles();

?>

<article style="height:140px;" id="1" class="item" data-rutaAplicacion="reportes" data-opcion="reporteSeguimientoCuarentenarioPorNivelNacional" draggable="true" data-destino="listadoItems">
    <div></div>
    <span>Reporte de Seguimientos Cuarentenarios a Nivel Nacional</span>
    <span class="ordinal">1</span>
    <aside></aside>
</article>


<script>

    $(document).ready(function() {  
        $("#listadoItems").removeClass("programas");
        $("#listadoItems").addClass("comunes");
        $("#detalleItem").html('<div class="mensajeInicial">Seleccione un reporte para visualizar.</div>');
    });

</script>
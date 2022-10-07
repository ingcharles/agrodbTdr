<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicacionesPerfiles.php';

$conexion = new Conexion();
$ca = new ControladorAplicacionesPerfiles();
$perfil = pg_numrows($ca->verificarPerfilXusuarioYcodigo($conexion, $_SESSION['usuario'], 'PFL_REP_POR_OPER'));

?>

<article style="height:140px;" id="1" class="item" data-rutaAplicacion="reportes" data-opcion="reporteGeneralOperadoresPorProvincia" draggable="true" data-destino="listadoItems">
    <div></div>
    <span>Reporte por provincia</span>
    <span class="ordinal">1</span>
    <aside></aside>
</article>

<article style="height:140px;" id="3" class="item" data-rutaAplicacion="reportes" data-opcion="reporteGeneralOperadoresPorProducto" draggable="true" data-destino="listadoItems">
    <span>Reporte por producto</span>
    <span class="ordinal">2</span>
    <aside></aside>
</article>

<?php
if($perfil>0){
?>
<article style="height:140px;" id="2" class="item" data-rutaAplicacion="reportes" data-opcion="reporteGeneralOperadoresPorOperacion" draggable="true" data-destino="listadoItems">
    <span>Reporte por tipo de Operación</span>
    <span class="ordinal">3</span>
    <aside></aside>
</article>

<?php
}
?>

<script>

    $(document).ready(function() {  
        $("#listadoItems").removeClass("programas");
        $("#listadoItems").addClass("comunes");
        $("#detalleItem").html('<div class="mensajeInicial">Seleccione un reporte para visualizar.</div>');
    });

</script>
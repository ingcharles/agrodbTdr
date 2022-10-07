<?php session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCertificadoCalidad.php';

$conexion = new Conexion();
$ca = new ControladorAplicaciones('certificadoCalidad', 'abrirSolicitudCertificadoCalidad');
$cc = new ControladorCertificadoCalidad();
?>
<header>
    <h1>Inscripciones disponibles</h1>
    <?php echo $ca->imprimirMenuDeAcciones($conexion, $_POST["opcion"], $_SESSION['usuario']); ?>
</header>
<?php
$solicitudes = $cc->listarSolicitudesDisponibles($conexion, $_SESSION['usuario']);
$contador = 0;
while ($solicitud = pg_fetch_assoc($solicitudes)) {
    echo $ca->imprimirArticulo($solicitud['id_certificado_calidad'], ++$contador, $solicitud['razon_social_exportador'], $solicitud['fecha_solicitud'], '', '', null,null);
}
?>
<script>
    $(document).ready(function () {
        $("#listadoItems").addClass("comunes");
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un evento para revisarlo.</div>');
    });

</script>
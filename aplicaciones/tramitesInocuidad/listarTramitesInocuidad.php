<?php session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorTramitesInocuidad.php';

$conexion = new Conexion();
$ca = new ControladorAplicaciones('tramitesInocuidad', 'abrirTramiteInocuidad');
$cti = new ControladorTramitesInocuidad();

$nombreOpcion  = $_POST['nombreOpcion'];

switch ($nombreOpcion){
	case 'Tramites':
		$ca = new ControladorAplicaciones('tramitesInocuidad', 'abrirTramiteInocuidad');
		$estado = "'enviado','porEntregar'";
	break;
	
	case 'Emisión tramite':
		$ca = new ControladorAplicaciones('tramitesInocuidad', 'abrirTramiteDocumental');
		$estado = "'emisionRespuesta'";
	break;
	
	default:
		echo 'Opción desconocida';
}

?>
<header>
    <h1>Tramites disponibles</h1>
    <?php echo $ca->imprimirMenuDeAcciones($conexion, $_POST["opcion"], $_SESSION['usuario']); ?>
</header>

<div id="enviado">
	<h2>Tramites creados</h2>
	<div class="elementos"></div>
</div>

<div id="porEntregar">
	<h2>Tramites por entregar</h2>
	<div class="elementos"></div>
</div>

<div id="emisionRespuesta">
	<h2>Tramites por atender</h2>
	<div class="elementos"></div>
</div>


<?php
	$solicitudes = $cti->listarTramitesDisponibles($conexion, $estado);
	$contador = 0;
	while ($solicitud = pg_fetch_assoc($solicitudes)) {
		
		$categoria = $solicitud['estado'];		
    	$contenido = $ca->imprimirArticulo($solicitud['id_solicitud'], ++$contador, $solicitud['nombre_tipo_tramite'].' - '.$solicitud['nombre_producto'], date('j/n/Y (h:i)',strtotime($solicitud['fecha_solicitud'])) .' '.$solicitud['identificador_operador']);
?>
		<script type="text/javascript">
			var contenido = <?php echo json_encode($contenido);?>;
			var categoria = <?php echo json_encode($categoria);?>;
			$("#"+categoria+" div.elementos").append(contenido);
		</script>
<?php					
		}
?>
<script>
    $(document).ready(function () {
        $("#listadoItems").addClass("comunes");
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un tramite para revisarlo.</div>');

        $("#enviado div> article").length == 0 ? $("#enviado").remove():"";
        $("#porEntregar div> article").length == 0 ? $("#porEntregar").remove():"";
        $("#emisionRespuesta div> article").length == 0 ? $("#emisionRespuesta").remove():"";
    });

</script>
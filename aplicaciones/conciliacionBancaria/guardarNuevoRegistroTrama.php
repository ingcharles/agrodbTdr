<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorConciliacionBancaria.php';


	$conexion = new Conexion ();
	$cc = new ControladorCatalogos();
	$cb = new ControladorConciliacionBancaria();

	$nombreTrama = $_POST['nombreTrama'];
	$separadorTrama = $_POST['separadorTrama'];
	$formatoEntradaTrama = $_POST['formatoEntradaTrama'];
	$formatoSalidaTrama = $_POST['formatoSalidaTrama'];
	
	$codigoSegmentoCabeceraTrama = $_POST['codigoSegmentoCabeceraTrama'];
	$tamanioSegmentoCabeceraTrama = $_POST['tamanioSegmentoCabeceraTrama'];
	$codigoSegmentoDetalleTrama = $_POST['codigoSegmentoDetalleTrama'];
	$tamanioSegmentoDetalleTrama = $_POST['tamanioSegmentoDetalleTrama'];
	

	$qTrama = $cb -> guardarNuevoRegistroTrama($conexion, $nombreTrama, $separadorTrama, $formatoEntradaTrama, $formatoSalidaTrama);
	$id = pg_fetch_result($qTrama, 0, 'id_trama');
	
	$qCabeceraTrama = $cb -> guardarNuevoCabeceraTrama($conexion, /*$idTrama*/$id, $codigoSegmentoCabeceraTrama, $tamanioSegmentoCabeceraTrama);
	
	$cb -> guardarNuevoDetalleTrama($conexion, $id, $codigoSegmentoDetalleTrama, $tamanioSegmentoDetalleTrama);

	echo '<input type="hidden" id="' . $id . '" data-rutaAplicacion="conciliacionBancaria" data-opcion="abrirRegistroTrama" data-destino="detalleItem"/>';
		
?>
	
<script type="text/javascript">
    $('document').ready(function() {
        abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
        abrir($("#detalleItem input"), null, true);
    });
</script>
<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorConciliacionBancaria.php';


	$conexion = new Conexion ();
	$cc = new ControladorCatalogos();
	$cb = new ControladorConciliacionBancaria();
	
	$nombreRegistroProcesoConciliacion = $_POST['nombreRegistroProcesoConciliacion'];
	$facturaRegistroProcesoConciliacion = $_POST['facturaRegistroProcesoConciliacion'];
	$tipoRevisionRegistroProcesoConciliacion = $_POST['tipoRevisionRegistroProcesoConciliacion'];
	
	$qRegistroProcesoConciliacion = $cb -> guardarNuevoRegistroProcesoConciliacion($conexion, $nombreRegistroProcesoConciliacion, $facturaRegistroProcesoConciliacion, $tipoRevisionRegistroProcesoConciliacion);
	$id = pg_fetch_result($qRegistroProcesoConciliacion, 0, 'id_registro_proceso_conciliacion');
	
	echo '<input type="hidden" id="' . $id . '" data-rutaAplicacion="conciliacionBancaria" data-opcion="abrirRegistroProcesoConciliacion" data-destino="detalleItem"/>';
		
?>

<script type="text/javascript">
    $('document').ready(function() {
        abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
        abrir($("#detalleItem input"), null, true);
    });
</script>
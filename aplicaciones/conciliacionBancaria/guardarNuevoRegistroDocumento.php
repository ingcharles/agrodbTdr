<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorConciliacionBancaria.php';

	$conexion = new Conexion ();
	$cc = new ControladorCatalogos();
	$cb = new ControladorConciliacionBancaria();

	$nombreDocumento = $_POST['nombreDocumento'];
	$tipoDocumento = $_POST['tipoDocumento'];
	
	$formatoEntradaDocumento = $_POST['formatoEntradaDocumento'];
	$numeroColumnasDocumento = $_POST['numeroColumnasDocumento'];
	$filaInicioLecturaDocumento = $_POST['filaInicioLecturaDocumento'];
	$columnaInicioLecturaDocumento = $_POST['columnaInicioLecturaDocumento'];
	
	$qDocumento = $cb -> guardarNuevoRegistroDocumento($conexion, $nombreDocumento, $tipoDocumento, $formatoEntradaDocumento, $numeroColumnasDocumento, $filaInicioLecturaDocumento, $columnaInicioLecturaDocumento);
	$id = pg_fetch_result($qDocumento, 0, 'id_documento');
	
	echo '<input type="hidden" id="' . $id . '" data-rutaAplicacion="conciliacionBancaria" data-opcion="abrirRegistroDocumento" data-destino="detalleItem"/>';
?>

<script type="text/javascript">
    $('document').ready(function() {
        abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
        abrir($("#detalleItem input"), null, true);
    });
</script>
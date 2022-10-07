<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorFitosanitarioExportacion.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cfe = new ControladorFitosanitarioExportacion();

$opcion = $_POST['id'];

$qSanciones = $cfe->buscarSancionesXId($conexion, $opcion);
$sanciones = pg_fetch_assoc($qSanciones);

?>
<header>
	<h1 style="font-size: 24px;">Sanciones CFE</h1>
</header>

<form id='abrirSancionCFE' data-rutaAplicacion='notificacionesCFE' data-destino="detalleItem" >

<div id="visualizar">	
	<fieldset>
		<legend>Datos de Notificación</legend>
			<div data-linea="1">
				<label>Número de cédula/RUC:</label> <?php echo $sanciones['identificador_exportador'];?> <br/>
			</div>
			<div data-linea="2">
				<label>Razón social:</label> <?php echo $sanciones['razon_social'];?> <br/>
			</div>
	</fieldset>

	<fieldset>
		<legend>Datos de Producto</legend>
			<div data-linea="3">
				<label>Producto:</label> <?php echo $sanciones['nombre_producto'];?>	
			</div>
			<div data-linea="4">
				<label>País:</label> <?php echo $sanciones['nombre_pais'];?>	
			</div>
	</fieldset>
	
	<fieldset>
		<legend>Datos de Sanción</legend>
			<div data-linea="5">
				<label>Fecha inicio:</label> <?php echo substr($sanciones['fecha_inicio_sancion'],0,10);?>	
			</div>
			<div data-linea="6">
				<label>Fecha fin:</label> <?php echo substr($sanciones['fecha_fin_sancion'],0,10);?>	
			</div>
			<div data-linea="7">
				<label>Motivo:</label> <?php echo $sanciones['motivo_sancion'];?>	
			</div>
			<div data-linea="8">
				<label>Observación:</label> <?php echo $sanciones['observacion_sancion'];?>	
			</div>
	</fieldset>

</div>

</form>

<script type="text/javascript">


$(document).ready(function(){
		distribuirLineas();

});

</script>
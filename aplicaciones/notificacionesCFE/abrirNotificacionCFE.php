<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorFitosanitarioExportacion.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cfe = new ControladorFitosanitarioExportacion();

$opcion = $_POST['id'];

$qNotificaciones = $cfe->buscarNotificacionesXId($conexion, $opcion);
$notificaciones = pg_fetch_assoc($qNotificaciones);

?>
<header>
	<h1 style="font-size: 24px;">Notificaciones CFE</h1>
</header>

<form id='abrirNotificacionCFE' data-rutaAplicacion='notificacionesCFE' data-destino="detalleItem" >

<div id="visualizar">	
	<fieldset>
		<legend>Datos de Notificación</legend>
			<div data-linea="1">
				<label>Numero notificación:</label> <?php echo $notificaciones['numero_notificacion'];?> <br/>
			</div>
			<div data-linea="2">
				<label>Fecha notificación:</label> <?php echo substr($notificaciones['fecha_notificacion'],0,10);?> <br/>
			</div>
			<div data-linea="3">
				<label>Motivo notificacion:</label> <?php echo $notificaciones['motivo_notificacion'];?> <br/>
			</div>
			<div data-linea="4">
				<label>Observación notificación:</label> <?php echo $notificaciones['observacion_notificacion'];?>	
			</div>
			
	</fieldset>

	<fieldset>
		<legend>Datos de Exportación</legend>
			<div data-linea="5">
				<label>Número CFE:</label> <?php echo $notificaciones['numero_cfe'];?>	
			</div>
			<div data-linea="6">
				<label>Nombre producto:</label> <?php echo $notificaciones['nombre_producto'];?>	
			</div>
			<div data-linea="7">
				<label>País:</label> <?php echo $notificaciones['pais'];?>	
			</div>
	</fieldset>

</div>

</form>

<script type="text/javascript">


$(document).ready(function(){
		distribuirLineas();

});

</script>
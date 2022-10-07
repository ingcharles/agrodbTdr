<header>
	<h1>Servicios</h1>
</header>
<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorUsuarios.php';
$conexion = new Conexion();
$ca = new ControladorAplicaciones();
$cu = new ControladorUsuarios();
?>
<h2>Dirección Administrativa Financiera - Gestión Financiera</h2>
<article id="0" class="item" data-rutaAplicacion="serviciosLinea"
	data-opcion="listaConfirmacionPagos" draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Confirmación de Pagos</span>
</article>

<h2>Dirección Administrativa Financiera - Transportes</h2>
<article id="1" class="item" data-rutaAplicacion="serviciosLinea"
	data-opcion="listaRecorridosInstitucionales" draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Rutas de Transporte Institucional</span>
</article>

<script>
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
	});
</script>

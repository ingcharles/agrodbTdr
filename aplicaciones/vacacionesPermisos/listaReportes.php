<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorUsuarios.php';

$conexion = new Conexion();
$cu = new ControladorUsuarios();

?>
	
	<header>
		<h1>Reportes Permiso/Vacaciones</h1>
	</header>
	<article id="0" class="item" data-rutaAplicacion="vacacionesPermisos"	data-opcion="filtroSaldoVacaciones" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Saldo vacaciones</span>
		<span class="ordinal">1</span>
		<aside></aside>
	</article>
	
	<article id="1" class="item" data-rutaAplicacion="vacacionesPermisos"	data-opcion="filtroHistoricoVacaciones" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Historico vacaciones</span>
		<span class="ordinal">2</span>
		<aside></aside>
	</article>
	
<script type="text/javascript">

	$(document).ready(function(){
		$("#listadoItems").removeClass("programas");
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un reporte para visualizar.</div>');
	});
		
</script>
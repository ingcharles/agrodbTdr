<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastroProducto.php';


session_start();
$conexion = new Conexion();
$cp = new ControladorCatastroProducto();
$identificadorUsuario=$_SESSION['usuario'];
?>
<header>
	<h1>Reportes catastro</h1>
</header>
<article style="height:140px;" id="0" class="item" data-rutaAplicacion="catastroProducto"	data-opcion="reporteCatastroArea" draggable="true" data-destino="listadoItems">
	<div></div>
	<span>Reporte resumen catastro por sitio</span>
	<span class="ordinal">1</span>
	<aside></aside>
</article>	
<?php 

$filaTipoUsuario=pg_fetch_assoc($cp->obtenerTipoUsuario($conexion, $identificadorUsuario));
switch ($filaTipoUsuario['codificacion_perfil']){
	case 'PFL_USUAR_INT':
?>
	<article style="height:140px;" id="1" class="item" data-rutaAplicacion="catastroProducto"	data-opcion="reporteRegistroCatastro" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte de Catastro de Sanidad Animal - Porcinos</span>
		<span class="ordinal">2</span>
		<aside></aside>
	</article>
	<article style="height:140px;" id="2" class="item" data-rutaAplicacion="catastroProducto"	data-opcion="reporteTransaccionesCatastro" draggable="true" data-destino="listadoItems">
		<span >Reporte de transacciones de catastro por identificación del operador</span>
		<span class="ordinal">3</span>
		<aside></aside>
	</article>
	<article style="height:140px;" id="3" class="item" data-rutaAplicacion="catastroProducto"	data-opcion="reporteAretesDadosBaja" draggable="true" data-destino="listadoItems">
		<span >Reporte de aretes dados de baja</span>
		<span class="ordinal">4</span>
		<aside></aside>
	</article>
	<article style="height:140px;" id="5" class="item" data-rutaAplicacion="catastroProducto"	data-opcion="reporteCatastroCero" draggable="true" data-destino="listadoItems">
		<span >Reporte de catastro cero</span>
		<span class="ordinal">5</span>
		<aside></aside>
	</article>
<?php
	break;
}
?>	

<script>
$(document).ready(function(event){
	$("#listadoItems").removeClass("programas");
	$("#listadoItems").addClass("comunes");
	$("#detalleItem").html('<div class="mensajeInicial">Seleccione un reporte para visualizar.</div>');
});
</script>
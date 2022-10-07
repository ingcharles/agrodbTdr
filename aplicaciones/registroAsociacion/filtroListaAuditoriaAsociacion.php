<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAuditoria.php';

$conexion = new Conexion();
$ca = new ControladorAuditoria();

$contador = 0;
$itemsFiltrados[] = array();

$qAuditoria = $ca->buscarAuditoriaXMiembrAsociacion($conexion, $_POST['identificadorMiembro'], $_POST['nombreMiembroAsociacion'], $_POST['fechaInicio'], $_POST['fechaFin']);

while($fila = pg_fetch_assoc($qAuditoria)){
	$itemsFiltrados[] = array('<tr
		id="'.$fila['id_miembro_asociacion'].'">
		<td style="white-space:nowrap;">'.++$contador.'</td>
		<td>'.$fila['identificador_miembro_asociacion'].'</td>
		<td>'.$fila['nombre_miembro_asociacion'].'</td>
		<td>'.$fila['detalle_auditoria'].'</td>
		<td>'.$fila['fecha_registro'].'</td>
		<td>'.$fila['estado_auditoria'].'</td>
		</tr>');
}

?>

<div id="paginacion" class="normal"></div>
<table id="tablaItems">
<thead>
<tr>
<th>#</th>
<th>Identificación</th>
<th>Nombre completo</th>
<th>Descripción del cambio</th>
<th>Fecha de cambio</th>
<th>Estado</th>
</tr>
</thead>
<tbody>
</tbody>
</table>

<form id="generarReporte" action="aplicaciones/registroAsociacion/reporteAuditoriaMiembroAsociacion.php" target="_blank" method="post">
				
	<input id="identificadorMiembro" type="hidden" name="identificadorMiembro" value="<?php echo $_POST['identificadorMiembro']?>"> 
	<input id="nombreMiembroAsociacion" type="hidden" name="nombreMiembroAsociacion" value="<?php echo $_POST['nombreMiembroAsociacion']?>"> 
	<input id="fechaInicio" type="hidden" name="fechaInicio" value="<?php echo $_POST['fechaInicio']?>">
	<input id="fechaFin" type="hidden" name="fechaFin" value="<?php echo $_POST['fechaFin']?>"> 
	
	<button id="btnReporte" type="submit" class="guardar">Generar reporte excel</button>

</form>

<script>

	$(document).ready(function(){
		distribuirLineas();
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");								
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
	
	});


</script>

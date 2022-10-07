<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastroProducto.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cp = new ControladorCatastroProducto();
$cc = new ControladorCatalogos();

$res = $cp->filtroCatastroArea($conexion, $_POST['identificadorSolicitanteH'],'%'.$_POST['nombreOperador'].'%', '%'.$_POST['nombreSitio'].'%', $_POST['provincia']);

$contador = 0;
$itemsFiltrados[] = array();

while($fila = pg_fetch_assoc($res)){
	$itemsFiltrados[] = array('<tr
		id="'.$fila['id_sitio'].'"
		class="item"
		data-rutaAplicacion="catastroProducto"
		data-opcion="abrirReporteCatastroArea"
		ondragstart="drag(event)"
		draggable="true"
		data-destino="detalleItem">
			<td style="white-space:nowrap;"><b>'.++$contador.'</b></td>
			<td>'.$fila['nombre_sitio'].'</td>
			<td>'.$fila['identificador_operador'].' - '.$fila['nombre_operador'].'</td>
			<td>'.$fila['provincia'].'</td>
		</tr>');
}
?>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Sitio</th>	
			<th>Operador</th>
			
			<th width="30%">Provincia</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<script type="text/javascript"> 
	$(document).ready(function(event){
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
	});
</script>		
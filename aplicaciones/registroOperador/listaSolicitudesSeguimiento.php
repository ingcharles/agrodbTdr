<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$idSolicitud=null;
$tipoOperacion=null;
$res = $cr -> filtrarSolicitudes($conexion, $idSolicitud, $_POST['ruc'], $tipoOperacion, $_POST['estado']);

$contador = 0;
$itemsFiltrados[] = array();
?>

<form id='reporteSolicitudes' data-rutaAplicacion='registroOperador' data-opcion='abrirSolicitudSeguimiento' data-destino="detalleItem">

<div id="paginacion" class="normal">

</div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>RUC</th>
			<th>Solicitud</th>
			<th>Tipo de Operación</th>
			<th>Producto</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody>
	</tbody>


<?php 

while($fila = pg_fetch_assoc($res)){

	$itemsFiltrados[] = array('<tr
		id="'.$fila['id_solicitud'].'"
		class="item">
			<td>'.++$contador.'</td>
			<td style="white-space:nowrap;"><b>'.$fila['identificador_operador'].'</b></td>
			<td>'.$fila['id_solicitud'].'</td>
			<td>'.$fila['nombre'].'</td>
			<td>'.$fila['nombre_producto']	.'</td>
			<td>'.$fila['estado'].'</td>
		</tr>');

}
?>

</table>
	
	<div id="valores"></div>
	
	<button type="submit" class="guardar">Generar reporte</button>

</form>

<script type="text/javascript"> 
	var itemInicial = 0;

	$("#reporteSolicitudes").submit(function(event){
		abrir($(this),event,false);
	});
	
	$(document).ready(function(){
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
	});

</script>
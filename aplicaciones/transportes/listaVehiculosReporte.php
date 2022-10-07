<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$conexion = new Conexion();
$cv = new ControladorVehiculos();


$res = $cv -> filtrarVehiculos($conexion, $_SESSION['nombreLocalizacion'], $_POST['placa'], $_POST['anio'], $_POST['marca'], $_POST['modelo'], $_POST['fi'], $_POST['ff'], $_POST['estado']);

$contador = 0;
$itemsFiltrados[] = array();

while($fila = pg_fetch_assoc($res)){

	$itemsFiltrados[] = array('<tr
		id="'.$fila['placa'].'"
		class="item"
		data-rutaAplicacion="transportes"
		data-opcion="abrirHistorialVehiculo"
		ondragstart="drag(event)"
		draggable="true"
		data-destino="detalleItem">
			<td>'.++$contador.'</td>
			<td style="white-space:nowrap;"><b>'.$fila['placa'].'</b></td>
			<td>'.$fila['marca'].'</td>
			<td>'.$fila['modelo']	.'</td>
			<td>'.$fila['nombre'].'</td>
			<td>'.$fila['nombres_completos'].'</td>
			<td><span class="n'.($fila['estado']==1?'Aprobado':($fila['estado']==2?'Sin_notificar':'Pendiente')).'"></span></td>
		</tr>');

}
?>
<div id="paginacion" class="normal">

</div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Placa</th>
			<th>Marca</th>
			<th>Modelo</th>
			<th>Provincia</th>
			<th>Responsable</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<script type="text/javascript"> 
	var itemInicial = 0;
	
	$(document).ready(function(){
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
	});

</script>


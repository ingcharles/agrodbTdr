<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$conexion = new Conexion();
$cv = new ControladorVehiculos();


$res = $cv -> filtrarCombustibles($conexion, $_SESSION['nombreLocalizacion'], $_POST['placa'], $_POST['gasolinera'], $_POST['tipo'], $_POST['fi'], $_POST['ff'], $_POST['estado']);

$contador = 0;
$itemsFiltrados[] = array();

?>

<form id='reporteHistorialCombustible' data-rutaAplicacion='transportes' data-opcion='abrirReporteCombustible' data-destino="detalleItem">

<div id="paginacion" class="normal">

</div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Placa</th>
			<th>Gasolinera</th>
			<th>Combustible</th>
			<th>Valor</th>
			<th>Conductor</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody>
	</tbody>

<?php 

while($fila = pg_fetch_assoc($res)){

	$itemsFiltrados[] = array('<tr
		id="'.$fila['id_combustible'].'"
		class="item">
			<td>'.++$contador.'</td>
			<td style="white-space:nowrap;"><b>'.$fila['placa'].'</b></td>
			<td>'.$fila['nombre_gasolinera'].'</td>
			<td>'.$fila['tipo_combustible']	.'</td>
			<td>'.$fila['valor_liquidacion'].'</td>
			<td>'.$fila['nombres_completos'].'</td>
			<td><span class="n'.($fila['estado']==1?'Aprobado':($fila['estado']==2?'Sin_notificar':'Pendiente')).'"></span></td>
		</tr>');

}
?>
</table>

	<div id="valores"></div>
	
	<button type="submit" class="guardar">Generar reporte</button>
	
</form>


<script type="text/javascript"> 

	$("#reporteHistorialCombustible").submit(function(event){
		//alert(this);
		abrir($(this),event,false);
	});
	
	$(document).ready(function(){
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
	});

</script>


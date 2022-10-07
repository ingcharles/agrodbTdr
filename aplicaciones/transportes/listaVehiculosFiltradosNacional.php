<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$conexion = new Conexion();
$cv = new ControladorVehiculos();


$res = $cv -> filtrarVehiculos($conexion, $_POST['sitio'], $_POST['placa'], $_POST['anio'], $_POST['marca'], $_POST['modelo'], $_POST['fi'], $_POST['ff'], $_POST['estado']);

$contador = 0;
$itemsFiltrados[] = array();

?>

<form id='reporteHistorialVehiculos' data-rutaAplicacion='transportes' data-opcion='abrirReporteVehiculoNacional' data-destino="detalleItem">

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
	
	
<?php 
while($fila = pg_fetch_assoc($res)){

	$itemsFiltrados[] = array('<tr
		id="'.$fila['placa'].'"
		class="item">
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

</table>
	<div id="valores"></div>
	
	<button type="submit" class="guardar">Generar reporte</button>
	
</form>

<script type="text/javascript"> 

	$("#reporteHistorialVehiculos").submit(function(event){
		//alert(this);
		abrir($(this),event,false);
	});
		
	$(document).ready(function(){
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
	});

</script>


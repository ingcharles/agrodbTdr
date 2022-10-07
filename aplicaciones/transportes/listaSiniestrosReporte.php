<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$conexion = new Conexion();
$cv = new ControladorVehiculos();


$res = $cv -> filtrarSiniestro($conexion, $_SESSION['nombreLocalizacion'], $_POST['placa'], $_POST['tipo_siniestro'], $_POST['fi'], $_POST['ff'], $_POST['estado']);

$contador = 0;
$itemsFiltrados[] = array();

?>

<form id='reporteHistorialSiniestros' data-rutaAplicacion='transportes' data-opcion='abrirReporteSiniestros' data-destino="detalleItem">

<div id="paginacion" class="normal">

</div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Placa</th>
			<th>Motivo</th>
			<th>Fecha</th>
			<th>Conductor</th>
			<th>Localización</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody>
	</tbody>


<?php 



while($fila = pg_fetch_assoc($res)){
	
	$itemsFiltrados[] = array('<tr
		id="'.$fila['id_siniestro'].'"
		class="item">
			<td>'.++$contador.'</td>
			<td style="white-space:nowrap;"><b>'.$fila['placa'].'</b></td>
			<td>'.$fila['tipo_siniestro'].'</td>
			<td>'.date('j/n/Y',strtotime($fila['fecha_siniestro'])).'</td>
			<td>'.$fila['nombres_completos'].'</td>
			<td>'.$fila['nombre_localizacion'].'</td>
			<td><span class="n'.($fila['estado']==1?'Aprobado':($fila['estado']==2?'Sin_notificar':'Pendiente')).'"></span></td>
		</tr>');

}
?>

</table>

	<div id="valores"></div>
	
	<button type="submit" class="guardar">Generar reporte</button>

</form>

<script type="text/javascript"> 
	var itemInicial = 0;

	$("#reporteHistorialSiniestros").submit(function(event){
		abrir($(this),event,false);
	});
	
	$(document).ready(function(){
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
	});

</script>


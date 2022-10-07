<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$conexion = new Conexion();
$cv = new ControladorVehiculos();


$res = $cv -> filtrarTalleres($conexion, $_SESSION['nombreLocalizacion'], $_POST['talleres'], $_POST['direccion'], $_POST['estado']);

$contador = 0;
$itemsFiltrados[] = array();
?>

<form id='reporteHistorialTalleres' data-rutaAplicacion='transportes' data-opcion='abrirReporteTaller' data-destino="detalleItem">

<div id="paginacion" class="normal">

</div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Gasolinera</th>
			<th>Contacto</th>
			<th>Telefono</th>
			<th>Localización</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody>
	</tbody>

<?php

while($fila = pg_fetch_assoc($res)){

	$itemsFiltrados[] = array('<tr
		id="'.$fila['id_taller'].'"
		class="item">
			<td>'.++$contador.'</td>
			<td style="white-space:nowrap;"><b>'.$fila['nombre_taller'].'</b></td>
			<td>'.$fila['contacto']	.'</td>
			<td>'.$fila['telefono'].'</td>
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

	$("#reporteHistorialTalleres").submit(function(event){
		//alert(this);
		abrir($(this),event,false);
	});
	
	$(document).ready(function(){
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
	});

</script>


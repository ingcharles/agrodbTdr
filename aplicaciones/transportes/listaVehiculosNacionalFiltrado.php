<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorVehiculos.php';

$conexion = new Conexion();
$ca = new ControladorAplicaciones();
$cv = new ControladorVehiculos();

$tipoSolicitud = $_POST['solicitudes'];
$estado = 'Documental';

$contador = 0;
$itemsFiltrados[] = array();

	if($_POST['placa']!=''){
		$res = $cv->abrirVehiculo($conexion, $_POST['placa']);
	}
	
	while($vehiculos = pg_fetch_assoc($res)){
		$itemsFiltrados[] = array('<tr
									id="'.$vehiculos['placa'].'"
									class="item"
									data-rutaAplicacion="transportes"
									data-opcion="abrirLiberarVehiculo" 
									ondragstart="drag(event)" 
									draggable="true" 
									data-destino="detalleItem">
									<td>'.++$contador.'</td>
									<td style="white-space:nowrap;"><b>'.$vehiculos['marca'].'</b></td>
									<td>'.$vehiculos['modelo'].'</td>
									<td>'.$vehiculos['placa'].'</td>
									<td>'.$vehiculos['localizacion'].'</td>
									<td><span class="n'.($vehiculos['estado']==1?'Vehiculo':($vehiculos['estado']==2?'Mantenimiento':($vehiculos['estado']==3?'Movilizacion':($vehiculos['estado']==4?'Siniestro':'Eliminado')))).'">'.($vehiculos['estado']==1?'Vehiculo':($vehiculos['estado']==2?'Mantenimiento':($vehiculos['estado']==3?'Movilizacion':($vehiculos['estado']==4?'Siniestro':'Rechazado')))).'</span></td>			
								</tr>');
	}
?>	

<div id="paginacion" class="normal"></div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Marca</th>
			<th>Modelo</th>
			<th>Placa</th>
			<th>Localización</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<script type="text/javascript"> 
	$(document).ready(function(){
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
	});

	$('#_asignar').addClass('_asignar');
	//$('#_asignar').attr('id', < ?php echo json_encode($tipoSolicitud);?>+'-'+< ?php echo json_encode($estado);?>);
	
</script>
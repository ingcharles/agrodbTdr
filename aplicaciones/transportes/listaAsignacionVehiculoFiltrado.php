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

	echo'<header> <nav>';
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);
			while($fila = pg_fetch_assoc($res)){
				echo '<a href="#"
						id="' . $fila['estilo'] . '"
						data-destino="detalleItem"
						data-opcion="' . $fila['pagina'] . '"
						data-rutaAplicacion="' . $fila['ruta'] . '"
						>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
			}
	echo'</nav></header>';
	
	if($_POST['placa']!=''){
		$res = $cv->filtrarVehiculosPlaca($conexion, $_POST['placa']);
	}else{
		$res = $cv->filtrarVehiculosProvincia($conexion, $_POST['localizacion']);
	}
	
	while($vehiculos = pg_fetch_assoc($res)){
		$itemsFiltrados[] = array('<tr
									id="'.$vehiculos['placa'].'"
									class="item"
									data-rutaAplicacion="transportes"
									data-opcion="abrirReasignarVehiculo" 
									ondragstart="drag(event)" 
									draggable="true" 
									data-destino="detalleItem">
									<td>'.++$contador.'</td>
									<td style="white-space:nowrap;"><b>'.$vehiculos['marca'].'</b></td>
									<td>'.$vehiculos['modelo'].'</td>
									<td>'.$vehiculos['placa'].'</td>
									<td>'.$vehiculos['localizacion'].'</td>
									<td><span class="n'.($vehiculos['estado']==1?'Vehiculo':($vehiculos['estado']==2?'Mantenimiento':($vehiculos['estado']==3?'Movilizacion':($vehiculos['estado']==4?'Siniestro':'Rechazado')))).'"></span></td>			
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

		alert("El vehículo debe tener finalizadas las órdenes de movilización, mantenimiento, combustible, lavadas y siniestros para ser trasladado.");
	});

	$('#_asignar').addClass('_asignar');
	//$('#_asignar').attr('id', < ?php echo json_encode($tipoSolicitud);?>+'-'+< ?php echo json_encode($estado);?>);
	
</script>
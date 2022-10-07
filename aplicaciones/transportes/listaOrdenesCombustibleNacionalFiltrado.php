<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$conexion = new Conexion();
$cv = new ControladorVehiculos();

$contador = 0;
$itemsFiltrados[] = array();
	
	$res = $cv->abrirCombustible($conexion, $_POST['ordenCombustible']);
	
	while($combustible = pg_fetch_assoc($res)){
		$itemsFiltrados[] = array('<tr
									id="'.$combustible['id_combustible'].'"
									class="item"
									data-rutaAplicacion="transportes"
									data-opcion="abrirEliminarCombustible" 
									ondragstart="drag(event)" 
									draggable="true" 
									data-destino="detalleItem">
									<td><b>'.$combustible['id_combustible'].'</b></td>
									<td>'.$combustible['fecha_solicitud'].'</td>
									<td>'.$combustible['nombregasolinera'].'</td>
									<td>'.$combustible['tipo_combustible'].'</td>
									<td>'.$combustible['placa'].'</td>
									<td>'.$combustible['localizacion'].'</td>	
									<td class="formatoTexto">'.(($combustible['estado']=="1")? 'Creado':(($combustible['estado']=="2")? 'Por Finalizar':(($combustible['estado']=="3")? 'Finalizado':'Eliminado'))).'</td>		
								</tr>');
	}
?>	

<div id="paginacion" class="normal"></div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Fecha Solicitud</th>
			<th>Gasolinera</th>
			<th>Combustible</th>
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
	
</script>
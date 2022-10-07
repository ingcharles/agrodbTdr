<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$conexion = new Conexion();
$cv = new ControladorVehiculos();

$contador = 0;
$itemsFiltrados[] = array();

$gasolinera = $_POST['gasolinera'];

		$res = $cv->abrirGasolinera($conexion, $gasolinera);
		
		while($gasolinera = pg_fetch_assoc($res)){
			$itemsFiltrados[] = array('<tr
					id="'.$gasolinera['id_gasolinera'].'"
					class="item"
					data-rutaAplicacion="transportes"
					data-opcion="abrirReseteoCupoGasolinera"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
					<td><b>'.$gasolinera['id_gasolinera'].'</b></td>
					<td>'.$gasolinera['nombre'].'</td>
					<td>'.$gasolinera['cupo'].'</td>
					<td>'.$gasolinera['saldo_disponible'].'</td>
					<td>'.$gasolinera['localizacion'].'</td>
					<td class="formatoTexto">'.(($gasolinera['estado']=="1")? 'Creado':'Eliminado').'</td>
					</tr>');
		}
	
?>	

<div id="paginacion" class="normal"></div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Nombre</th>
			<th>Cupo Mensual</th>
			<th>Saldo Disponible</th>
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
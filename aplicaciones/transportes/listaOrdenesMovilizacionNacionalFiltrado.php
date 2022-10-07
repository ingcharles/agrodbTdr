<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$conexion = new Conexion();
$cv = new ControladorVehiculos();

$contador = 0;
$itemsFiltrados[] = array();
	
	$res = $cv->abrirMovilizacion($conexion, $_POST['ordenMovilizacion']);
	
	while($movilizacion = pg_fetch_assoc($res)){
		$itemsFiltrados[] = array('<tr
									id="'.$movilizacion['id_movilizacion'].'"
									class="item"
									data-rutaAplicacion="transportes"
									data-opcion="abrirRutasMovilizacion" 
									ondragstart="drag(event)" 
									draggable="true" 
									data-destino="detalleItem">
									<td><b>'.$movilizacion['id_movilizacion'].'</b></td>
									<td>'.$movilizacion['fecha_solicitud'].'</td>
									<td>'.$movilizacion['tipo_movilizacion'].'</td>
									<td>'.$movilizacion['placa'].'</td>
									<td>'.$movilizacion['localizacion'].'</td>	
									<td class="formatoTexto">'.(($movilizacion['estado']=="1")? 'Creado':(($movilizacion['estado']=="2")? 'Por Imprimir':(($movilizacion['estado']=="3")? 'Por Finalizar':(($movilizacion['estado']=="4")? 'Finalizado':'Eliminado')))).'</td>		
								</tr>');
	}
?>	

<div id="paginacion" class="normal"></div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Fecha Solicitud</th>
			<th>Tipo</th>
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
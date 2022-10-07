<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$conexion = new Conexion();
$cv = new ControladorVehiculos();

$contador = 0;
$itemsFiltrados[] = array();

$numeroOrden = $_POST['numeroOrden'];
$tipoOrden = $_POST['tipoOrden'];

switch($tipoOrden){
	case 'Combustible':{
		$res = $cv->abrirCombustible($conexion, $numeroOrden);
		
		while($combustible = pg_fetch_assoc($res)){
			$itemsFiltrados[] = array('<tr
					id="'.$combustible['id_combustible'].'"
					class="item"
					data-rutaAplicacion="transportes"
					data-opcion="abrirOrdenesAperturaNacional"
					data-tipoOrden="'.$tipoOrden.'"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
					<td><b>'.$combustible['id_combustible'].'</b></td>
					<td>'.$tipoOrden.'</td>
					<td>'.$combustible['fecha_solicitud'].'</td>
					<td>'.$combustible['placa'].'</td>
					<td>'.$combustible['localizacion'].'</td>
					<td class="formatoTexto">'.(($combustible['estado']=="1")? 'Creado':(($combustible['estado']=="2")? 'Por Finalizar':(($combustible['estado']=="3")? 'Finalizado':'Eliminado'))).'</td>
					</tr>');
		}
		
		break;
	}
	
	case 'Mantenimiento':{
		$res = $cv->abrirMantenimiento($conexion, $numeroOrden);
		
		while($mantenimiento = pg_fetch_assoc($res)){
			$itemsFiltrados[] = array('<tr
					id="'.$mantenimiento['id_mantenimiento'].'"
					class="item"
					data-rutaAplicacion="transportes"
					data-opcion="abrirOrdenesAperturaNacional"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
					<td><b>'.$mantenimiento['id_mantenimiento'].'</b></td>
					<td>'.$tipoOrden.'</td>
					<td>'.$mantenimiento['fecha_solicitud'].'</td>
					<td>'.$mantenimiento['placa'].'</td>
					<td>'.$mantenimiento['localizacion'].'</td>
					<td class="formatoTexto">'.(($mantenimiento['estado']=="1")? 'Creado':(($mantenimiento['estado']=="2")? 'Por Finalizar':(($mantenimiento['estado']=="3")? 'Finalizado':'Eliminado'))).'</td>
					</tr>');
		}
		
		break;
	}
	
	case 'Movilizacion':{
		$res = $cv->abrirMovilizacion($conexion, $numeroOrden);
		
		while($movilizacion = pg_fetch_assoc($res)){
			$itemsFiltrados[] = array('<tr
					id="'.$movilizacion['id_movilizacion'].'"
					class="item"
					data-rutaAplicacion="transportes"
					data-opcion="abrirOrdenesAperturaNacional"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
					<td><b>'.$movilizacion['id_movilizacion'].'</b></td>
					<td>'.$tipoOrden.'</td>
					<td>'.$movilizacion['fecha_solicitud'].'</td>
					<td>'.$movilizacion['placa'].'</td>
					<td>'.$movilizacion['localizacion'].'</td>
					<td class="formatoTexto">'.(($movilizacion['estado']=="1")? 'Creado':(($movilizacion['estado']=="2")? 'Por Imprimir':(($movilizacion['estado']=="3")? 'Por Finalizar':($movilizacion['estado']=="4")? 'Finalizado':'Eliminado'))).'</td>
					</tr>');
		}
		
		break;
	}
	
	default:{
		echo 'La opción seleccionada no está disponible';
		
		break;
	}
}
	
?>	

<div id="paginacion" class="normal"></div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Tipo</th>
			<th>Fecha Solicitud</th>
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
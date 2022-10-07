<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEventoSanitario.php';

$conexion = new Conexion();
$cv = new ControladorEventoSanitario();

$contador = 0;
$itemsFiltrados[] = array();

$numeroSolicitud = $_POST['bNumSolicitud'];

		$res = $cv->abrirEventoSanitarioCodigo($conexion, $numeroSolicitud);
		
		while($registro = pg_fetch_assoc($res)){
			$itemsFiltrados[] = array('<tr
					id="'.$registro['id_evento_sanitario'].'"
					class="item"
					data-rutaAplicacion="seguimientoEventosSanitarios"
					data-opcion="expedienteEventoSanitarioExpediente"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
					<td>'.$registro['id_evento_sanitario'].'</td>
					<td><b>'.$registro['numero_formulario'].'</b></td>
					<td>'.$registro['provincia'].'</td>
					<td>'.$registro['nombre_predio'].'</td>
					<td>'.$registro['sintomatologia'].'</td>
					</tr>');
}
	
?>	

<div id="paginacion" class="normal"></div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Número Solicitud</th>
			<th>Provincia</th>
			<th>Predio</th>
			<th>Sintomatología</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<script type="text/javascript"> 
	$(document).ready(function(){
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
	});
	
</script>
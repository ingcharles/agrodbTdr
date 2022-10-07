<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$cr = new controladorRegistroOperador();

$contador = 0;
$itemsFiltrados[] = array();

	echo'<header> <nav>';
		 
			$ca = new ControladorAplicaciones();
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
	
	if($_POST['inspectores'] == ''){
		$res = $cr -> listarOperacionesRevisionProvincia($conexion, $_SESSION['nombreProvincia']);
	}else{
		$res = $cr -> listarOperacionesAsignadasInspector($conexion, $_SESSION['nombreProvincia'], $_POST['inspectores']);
	}
	
	while($operaciones = pg_fetch_assoc($res)){
	
		$itemsFiltrados[] = array('<tr
							id="'.$operaciones['id_operacion'].'"
							class="item"
							data-rutaAplicacion="registroOperador"
							data-opcion="abrirOperacionEnviada"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem">
						<td>'.++$contador.'</td>
						<td style="white-space:nowrap;"><b>'.$operaciones['razon_social'].'</b></td>
						<td>'.$operaciones['id_operacion'].'</td>
						<td>'.$operaciones['nombre'].'</td>
						<td>'.$operaciones['producto'].'</td>
						<td><span class="n'.($operaciones['estado']=='enviado'?'Recibido':($vehiculo['estado']=='asignado'?'Asignado':'Finalizado')).'"></span></td>
					</tr>');
	}
?>	

<div id="paginacion" class="normal"></div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>RUC</th>
			<th>#Operación</th>
			<th>Tipo de Operación</th>
			<th>Producto</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<script type="text/javascript"> 
	$("#reporteHistorialSitios").submit(function(event){
		abrir($(this),event,false);
	});
	
	$(document).ready(function(){
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
	});
</script>


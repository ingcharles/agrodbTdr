<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorNotificacionEnfermedades.php';
require_once '../../clases/ControladorAplicaciones.php';
	
$conexion = new Conexion();	
$cm = new ControladorNotificacionEnfermedades();

$identificadorOPerador = $_SESSION['usuario'];

?>
<header>
	<h1>Lista Reporte de Enfermedades</h1>
	<nav>
		<?php			
			$contador = 0;
			$itemsFiltrados[] = array();
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
		?>
</nav>
</header>
	
<?php 
	$res = $cm->listarReporteEnfermedades($conexion, $identificadorOPerador);
	while($fila = pg_fetch_assoc($res)){
       	$itemsFiltrados[] = array('<tr
				id="'.$fila['id_enfermedad_zoonosica'].'"
				class="item"
				data-rutaAplicacion="notificacionEnfermedades"
				data-opcion="abrirNotificacionEnfermedades"
				ondragstart="drag(event)"
				draggable="true"
				data-destino="detalleItem">
				<td align="center">'.++$contador.'</td>
				<td align="center">'.$fila['identificador_animal'].'</td>
				<td align="center">'.$fila['nombre_producto'].'</td>
       			<td align="center">'.$fila['identificador_duenio'].'</td>
				<td align="center">'.date('d/m/Y',strtotime($fila['fecha_reporte'])).'</td>
			</tr>');
   }
 ?>
 
 
 <div id="paginacion" class="normal"></div>

 
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Id. Animal</th>
			<th>Animal</th>
			<th>Id. Dueño</th>
			<th>Fecha diagnóstico</th>						
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<script>	
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
		
	});

</script>
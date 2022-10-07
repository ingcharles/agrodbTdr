<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';
$conexion = new Conexion();
?>
<header>
	<h1>Lista de eventos</h1>
	<nav>
		<?php			
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

	$cm = new ControladorMovilizacionAnimal();
    $res = $cm->listaInicioEventoMovilizacion($conexion);
    $contador = 0;
    $itemsFiltrados[] = array();
    
	while($fila = pg_fetch_assoc($res)){

       	$itemsFiltrados[] = array('<tr
				id="'.$fila['id_sitio'].'"
				class="item"
				data-rutaAplicacion="movilizacionAnimal"
				data-opcion="abrirEventoMovilizacion"
				ondragstart="drag(event)"
				draggable="true"
				data-destino="detalleItem">
				<td>'.++$contador.'</td>
				<td style="white-space:nowrap;"><b>'.$fila['identificador_evento'].'</b></td>
       			<td>'.$fila['nombre_representante'].'</td>
				<td>'.$fila['nombre_lugar'].'</td>
				<td>'.$fila['nombre_area'].'</td>
			</tr>');
       	}
 ?>
 
 
 <div id="paginacion" class="normal">
 </div>

 
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Identificación</th>
			<th>Representante</th>
			<th>Sitio</th>
			<th>Area</th>			
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
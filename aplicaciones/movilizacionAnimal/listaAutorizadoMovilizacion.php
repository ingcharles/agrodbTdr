<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';
$conexion = new Conexion();
?>
<header>
	<h1>Lista responsable de movilización</h1>
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
    $res = $cm->listaAutorizadoMovilizacionAnimal($conexion);
    $contador = 0;
    $itemsFiltrados[] = array();
    
	while($fila = pg_fetch_assoc($res)){

       	$itemsFiltrados[] = array('<tr
				id="'.$fila['id_autorizar_movilizacion'].'"
				class="item"
				data-rutaAplicacion="movilizacionAnimal"
				data-opcion="abrirAutorizadoMovilizacion"
				ondragstart="drag(event)"
				draggable="true"
				data-destino="detalleItem">
				<td>'.++$contador.'</td>
				<td style="white-space:nowrap;"><b>'.$fila['sitio'].'</b></td>
       			<td>'.$fila['nombre_propietario'].'</td>
				<td>'.$fila['nombre_autorizado'].'</td>
				<td>'.$fila['fecha_autorizacion'].'</td>
			</tr>');
       	}
 ?>
 
 
 <div id="paginacion" class="normal">
 </div>

 
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Sitio</th>
			<th>Propietario</th>
			<th>Autorizado</th>	
			<th>Fecha autorización</th>		
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
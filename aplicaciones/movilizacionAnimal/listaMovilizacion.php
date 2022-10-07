<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';
require_once '../../clases/ControladorAplicaciones.php';
	
$conexion = new Conexion();	
$cm = new ControladorMovilizacionAnimal();
?>
<header>
	<h1>Lista movilización</h1>
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
	$res = $cm->listaMovilizacion($conexion, $_SESSION['usuario']);
	while($fila = pg_fetch_assoc($res)){
       	$itemsFiltrados[] = array('<tr
				id="'.$fila['id_movilizacion_animal'].'"
				class="item"
				data-rutaAplicacion="movilizacionAnimal"
				data-opcion="abrirMovilizacionAnimal"
				ondragstart="drag(event)"
				draggable="true"
				data-destino="detalleItem">
				<td>'.++$contador.'</td>
				<td><b>No.'.$fila['numero_certificado'].'</b></td>
       			<td>'.$fila['nombre_sitio_origen'].'</td>
				<td>'.$fila['nombre_sitio_destino'].'</td>
				<td>'.$fila['nombre_autorizado'].'</td>							
			</tr>');
       	}
 ?>
 
 
 <div id="paginacion" class="normal">
 </div>

 
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>No.Certificado</th>
			<th>Lugar origen</th>
			<th>Lugar destino</th>
			<th>Autorizado</th>							
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
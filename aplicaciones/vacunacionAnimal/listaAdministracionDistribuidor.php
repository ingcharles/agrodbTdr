<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorVacunacionAnimal.php';
	
$conexion = new Conexion();
	
?>
<header>
	<h1>Lista de puntos de distribución</h1>
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

	$cc = new ControladorVacunacionAnimal();
    $res = $cc->seleccionarAdministradorPtoDistribucion($conexion);
    $contador = 0;
    $itemsFiltrados[] = array();
    
	while($fila = pg_fetch_assoc($res)){

       	$itemsFiltrados[] = array('<tr
				id="'.$fila['id_administrador_distribuidor'].'"
				class="item"
				data-rutaAplicacion="vacunacionAnimal"
				data-opcion="abrirAdministracionDistribuidor"
				ondragstart="drag(event)"
				draggable="true"
				data-destino="detalleItem">
				<td>'.++$contador.'</td>
				<td style="white-space:nowrap;"><b>'.$fila['identificador_distribuidor'].'</b></td>
       			<td>'.$fila['nombre_distribuidor'].'</td>
				<td>'.$fila['nombre_especie'].'</td>
				<td>'.$fila['estado'].'</td>
			</tr>');
       	}
 ?>
 
 
 <div id="paginacion" class="normal">
 </div>

 
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Identificacion</th>
			<th>Administrador Op.</th>
			<th>Especie</th>	
			<th>Estado</th>		
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
		//ejecutarJson(form);
		//if($('#estado').val()=='Los datos han sido ingresados satisfactoriamente')
		//  $('#_actualizar').click();
		//$('#estado2')val('');					
	});

</script>
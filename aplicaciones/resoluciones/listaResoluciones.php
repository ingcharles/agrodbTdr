<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorResoluciones.php';
?>

<header>
		<h1>Listado</h1>
		<nav>
		<?php 

			$conexion = new Conexion();
			$ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);
			//data-rutaAplicacion="' . $fila['ruta'] .'"
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
	
	
	<table>
	<thead>
		<tr>
			<th>#</th>
			<th>Nombre</th>
			<th>Número</th>
			<th>fecha</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
	

	<?php 
	$cr = new ControladorResoluciones();
	$res = $cr->listarResoluciones($conexion);
	$contador = 0;
	while($fila = pg_fetch_assoc($res)){
		echo '<tr
						id="'.$fila['id_resolucion'].'"
						class="item"
						data-rutaAplicacion="resoluciones"
						data-opcion="abrirResolucion"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td>'.(strlen($fila['nombre'])>40?(substr($fila['nombre'],0,40).'...'):(strlen($fila['nombre'])>0?$fila['nombre']:'Sin nombre')).'</td>
					<td><b>'.$fila['numero_resolucion'].'</b></td>
					<td>'.date('j/n/Y',strtotime($fila['fecha'])).'</td>
					<td>'.$fila['estado'].'</td>
				</tr>';
	}
	?>
	
	</table>
	

<script>
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una resolucion para revisarla.</div>');
	});
</script>
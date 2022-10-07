<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorVacunacionAnimal.php';
?>

<header>
		<h1>Control del areteo animal</h1>
		<nav>
		<?php 
			$conexion = new Conexion();
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
	// listado de vacunadores :  #7D97EB; #EA8F7D; #C3546E
	$ca = new ControladorVacunacionAnimal();
	$res = $ca->listaControlAreteo($conexion);
	$contador = 0;
	while($fila = pg_fetch_assoc($res)){
		echo '<article
						id="'.$fila['id_control_areteo'].'"
						class="item"
						data-rutaAplicacion="vacunacionAnimal"
						data-opcion="abrirControlAreteo"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem" 
						style="'.($fila['estado']== 'activo'?'background-color: #7D97EB;':'background-color: #EA8F7D;').'">
							<span class="ordinal">'.++$contador.'</span>
							<span><br/></span>
							<span>'.$fila['provincia'].' - '.$fila['canton'].'<br/></span>
							<span>'.$fila['estado'].'</span>
							<aside>'.$fila['fecha_registro'].'</aside>
				 </article>';	
	}
	?>

<script>
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un mantenimiento para revisarlo.</div>');
	});
</script>
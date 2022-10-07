<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRegistroOperador.php';
	require_once '../../clases/ControladorAplicaciones.php';

?>

<header>
		<h1>Sitios Aprobados</h1>
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

	<?php 
		$cr = new ControladorRegistroOperador();
		
		$res = $cr->listarSitiosAprobados($conexion, $_SESSION['usuario']);
		$contador = 0;
		while($fila = pg_fetch_assoc($res)){
			echo '<article 
						id="'.$fila['id_sitio'].'"
						class="item"
						data-rutaAplicacion="registroOperador"
						data-opcion="abrirSitioAprobado" 
						ondragstart="drag(event)"  
						draggable="true" 
						data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
					<span>'.$fila['nombre_lugar'].'<br/></span>
					<span>'.$fila['ubicacion'].'<br/></span>
					<span>'.$fila['direccion'].'<br/></span>
					<aside>Superficie: '.$fila['superficie_total'].'</aside>
				</article>';
		
		}
	?>
	

<script>
$(document).ready(function(){
	$("#listadoItems").addClass("comunes");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un sitio para revisarlo.</div>');
});
</script>
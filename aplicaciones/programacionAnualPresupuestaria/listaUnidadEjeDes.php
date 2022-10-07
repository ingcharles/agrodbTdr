<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorProgramacionPresupuestaria.php';
?>

<header>
		<h1>Catálogos PAP - Unidad Ejecutora y Desconcentrada</h1>
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
	
	<div id="ejecutora">
		<h2>Unidades Ejecutoras</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="desconcentrada">
		<h2>Unidades Desconcentradas</h2>
		<div class="elementos"></div>
	</div>
	
	<?php 
		$cpp = new ControladorProgramacionPresupuestaria();
		$res = $cpp->listarUnidadEjeDes($conexion);
		
		while($fila = pg_fetch_assoc($res)){
			if($fila['tipo']=='ejecutora'){
				$categoria ="ejecutora";
			}else if($fila['tipo']=='desconcentrada'){
				$categoria ="desconcentrada";
			}
			
			$contenido ='<article 
								id="'.$fila['id_unidad_ejedes'].'"
								class="item"
								data-rutaAplicacion="programacionAnualPresupuestaria"
								data-opcion="abrirUnidadEjeDes" 
								ondragstart="drag(event)" 
								draggable="true" 
								data-destino="detalleItem">
							<span class="ordinal">'.++$contador.'</span>
							<span>'.$fila['nombre'].'</span><br />
							<span> Código '.$fila['codigo'].'</span>
							<aside>'.date('j/n/Y',strtotime($fila['fecha_creacion'])).'</aside>
						</article>';			
			
	?>
			
			<script type="text/javascript">
							var contenido = <?php echo json_encode($contenido);?>;
							var categoria = <?php echo json_encode($categoria);?>;
							$("#"+categoria+" div.elementos").append(contenido);
			</script>
		<?php	
			}				
		?>

<script>
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');	

		$("#ejecutora div> article").length == 0 ? $("#ejecutora").remove():"";
		$("#desconcentrada div> article").length == 0 ? $("#desconcentrada").remove():"";	
	});
</script>
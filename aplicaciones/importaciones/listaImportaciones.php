<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorImportaciones.php';
	require_once '../../clases/ControladorAplicaciones.php';

?>

<header>
		<h1>Importaciones</h1>
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
	
	<div id="enviado">
		<h2>Solicitudes enviadas</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="pago">
		<h2>Solicitudes en asignación de pago</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="verificacion">
		<h2>Solicitudes en proceso de pago</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="subsanacion">
		<h2>Solicitudes en proceso de subsanación</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="aprobado">
		<h2>Solicitudes aprobadas</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="rechazado">
		<h2>Solicitudes rechazadas</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="cancelado">
		<h2>Solicitudes canceladas</h2>
		<div class="elementos"></div>
	</div>
	
	<?php 
		$ci = new ControladorImportaciones();
		
		$res = $ci->listarImportacionesOperador($conexion, $_SESSION['usuario']);
		$contador = 0;
		while($fila = pg_fetch_assoc($res)){
			$categoria = $fila['estado'];
			$contenido = '<article 
						id="'.$fila['id_importacion'].'"
						class="item"
						data-rutaAplicacion="importaciones"
						data-opcion="abrirImportacion" 
						ondragstart="drag(event)"  
						draggable="true" 
						data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
					<span><small><b>'.$fila['codigo_certificado'].'<br/></b></small></span>
					<span><small>'.$fila['pais_embarque'].' - '.$fila['nombre_embarcador'].'</small><br /></span>
					<span><small><b>Productos registrados: </b>'.$fila['num_productos'].'</small><br /></span>
					<aside><small>Estado: '.$fila['estado'].'</small></aside>
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
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una importación para revisarla.</div>');
	$("#enviado div> article").length == 0 ? $("#enviado").remove():"";
	$("#pago div> article").length == 0 ? $("#pago").remove():"";
	$("#verificacion div> article").length == 0 ? $("#verificacion").remove():"";
	$("#subsanacion div> article").length == 0 ? $("#subsanacion").remove():"";
	$("#aprobado div> article").length == 0 ? $("#aprobado").remove():"";
	$("#rechazado div> article").length == 0 ? $("#rechazado").remove():"";
	$("#cancelado div> article").length == 0 ? $("#cancelado").remove():"";
});
</script>
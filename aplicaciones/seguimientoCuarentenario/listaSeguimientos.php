<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorSeguimientoCuarentenario.php';
	require_once '../../clases/ControladorAplicaciones.php';
	
?>

<header>
		<h1>Seguimiento cuarentenario</h1>
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
	
	<div id="notificado" >
		<h2>Seguimientos Cuarentenarios Notificados</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="abierto">
		<h2>Seguimientos Cuarentenarios Abiertos</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="cerrado">
		<h2>Seguimientos Cuarentenarios Cerrados</h2>
		<div class="elementos"></div>
	</div>
	
	<?php 
		$csc = new ControladorSeguimientoCuarentenario();
		
		$res = $csc->listarSeguimientosDDAOperador($conexion, $_SESSION['nombreProvincia']);
		$contador = 0;
		while($fila = pg_fetch_assoc($res)){
			$producto=$fila['productos'];
			$producto = (strlen($producto)>=60?(substr($producto,0,60).'...'):$producto);
			$categoria = $fila['estado_seguimiento'];
			$contenido = '<article 
						id="'.$fila['id_destinacion_aduanera'].'"
						class="item"
						data-rutaAplicacion="seguimientoCuarentenario"
						data-opcion="abrirSeguimiento" 
						ondragstart="drag(event)"  
						draggable="true" 
						data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
					<span><small><b>'.$fila['codigo_certificado'].'<br/></b></small></span>
					<span><small>'.$fila['fecha_inicio'].'</small><br /></span>
					<span><small>'.$producto.'</small><br /></span>
					<aside>Estado: '.$fila['estado_seguimiento'].'</aside>
				</article>';
			?>
			<script type="text/javascript">
				var contenido = <?php echo json_encode($contenido);?>;
				var categoria = <?php echo json_encode($categoria);?>;
				$("#"+categoria+" div.elementos").append(contenido);
			</script>
			<?php					
		}
		
		
		$ress = $csc->listarSeguimientosAbiertoCerradosDDAOperador($conexion, $_SESSION['nombreProvincia']);
		$contador = 0;
		while($fila = pg_fetch_assoc($ress)){
			$producto=$fila['productos'];
			$producto = (strlen($producto)>=60?(substr($producto,0,60).'...'):$producto);
			$categoria = $fila['estado_seguimiento'];
			$contenido = '<article
						id="'.$fila['id_destinacion_aduanera'].'"
						class="item"
						data-rutaAplicacion="seguimientoCuarentenario"
						data-opcion="abrirSeguimiento"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
					<span><small><b>'.$fila['codigo_certificado'].'<br/></b></small></span>
					<span><small>'.$fila['fecha_inicio'].'</small><br /></span>
					<span><small>'.$producto.'</small><br /></span>
					<aside>Estado: '.$fila['estado_seguimiento'].'</aside>
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
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
	$("#notificado div> article").length == 0 ? $("#notificado").remove():"";
	$("#abierto div> article").length == 0 ? $("#abierto").remove():"";
	$("#cerrado div> article").length == 0 ? $("#cerrado").remove():"";
});
</script>
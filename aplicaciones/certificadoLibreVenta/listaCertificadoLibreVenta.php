<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorClv.php';
	
	$conexion = new Conexion();
	$ca = new ControladorAplicaciones();
	$clv  = new ControladorClv();
	$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);
	$listaCertificado = $clv->listarCertificadoClv($conexion, $_SESSION['usuario']);
?>

<header>
		<h1>Certificado de libre venta</h1>
		<nav>
			<?php 
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
		<h2>Solicitudes en pago</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="verificacion">
		<h2>Solicitudes en proceso de pago</h2>
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
	
	<?php 
		
		$contador = 0;
		while($fila = pg_fetch_assoc($listaCertificado)){
			$categoria = $fila['estado'];
			$contenido = '<article 
								id="'.$fila['id_clv'].'"
								class="item"
								data-rutaAplicacion="certificadoLibreVenta"
								data-opcion="abrirClv" 
								ondragstart="drag(event)"  
								draggable="true" 
								data-destino="detalleItem">
								<span class="ordinal">'.++$contador.'</span>
								<span><small>'.$fila['codigo_certificado'].'</small><br /></span>
								<span><small>'.$fila['tipo_producto'].' - '.$fila['tipo_datos_certificado'].'</small><br /></span>
								<span><small>'.$fila['nombre_producto'].'</small></span>
								<aside>Estado: '.$fila['estado'].'</aside>
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
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una exportación para revisarla.</div>');
	$("#enviado div> article").length == 0 ? $("#enviado").remove():"";
	$("#pago div> article").length == 0 ? $("#pago").remove():"";
	$("#verificacion div> article").length == 0 ? $("#verificacion").remove():"";
	$("#aprobado div> article").length == 0 ? $("#aprobado").remove():"";
	$("#rechazado div> article").length == 0 ? $("#rechazado").remove():"";
});
</script>
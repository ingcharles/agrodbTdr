<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRegistroOperador.php';
	require_once '../../clases/ControladorAplicaciones.php';

?>

<pre>
<?php //print_r($_SESSION);?>
</pre>
<header>
		<h1>Solicitudes</h1>
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
		<h2>Solicitudes para inspección</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="proceso">
		<h2>Solicitudes en proceso</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="finalizado">
		<h2>Informe y Resultado de Inspección</h2>
		<div class="elementos"></div>
	</div>
	

	<?php 
		$cr = new ControladorRegistroOperador();
		
		$res = $cr->listarOperacionesRevision($conexion);
		$contador = 0;
		while($fila = pg_fetch_assoc($res)){
			$categoria = $fila['estado'];

			$contenido = '<article 
						id="'.$fila['id_operacion'].'"
						class="item"
						data-rutaAplicacion="registroOperador"
						data-opcion="abrirOperacionEnviada" 
						ondragstart="drag(event)"  
						draggable="true" 
						data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
					<span><small>Solicitud: '.$fila['id_operacion'].'<br/></span>
					<span>'.(strlen($fila['razon_social'])>30?(substr($fila['razon_social'],0,30).'...'):(strlen($fila['razon_social'])>0?$fila['razon_social']:'')).'</span>
					<span>'.$fila['nombre'].' - '.(strlen($fila['producto'])>13?(substr($fila['producto'],0,13).'...'):(strlen($fila['producto'])>0?$fila['producto']:'')).'</small></span>
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
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una solicitud para revisarla.</div>');
	$("#proceso div> article").length == 0 ? $("#proceso").remove():"";
	$("#enviado div> article").length == 0 ? $("#enviado").remove():"";
	$("#finalizado div> article").length == 0 ? $("#finalizado").remove():"";
});
</script>
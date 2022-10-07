<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAreas.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorRequisitos.php';
	
	$conexion = new Conexion();
	$car = new ControladorAreas();
	$res = $car->areaUsuario($conexion, $_SESSION['usuario']);
	$area = pg_fetch_assoc($res);
	$_SESSION['id_area']=$area['id_area'];
	
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

<header>
		<h1>Tipo Producto</h1>
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
	
	<!-- >div id="iap">
		<h2>Inocuidad de Alimentos plaguicidas</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="iav">
		<h2>Inocuidad de Alimentos veterinarios</h2>
		<div class="elementos"></div>
	</div-->
	
	<div id="SA">
		<h2>Sanidad Animal</h2>
		<div class="elementos"></div>
	</div>
	
	
	<div id="SV">
		<h2>Sanidad Vegetal</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="LT">
		<h2>Laboratorios</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="AI">
		<h2>Inocuidad de los alimentos</h2>
		<div class="elementos"></div>
	</div>
	
	<?php  
		$cr = new ControladorRequisitos();
		$res = $cr->listarTipoProducto($conexion);
		$contador = 0;
		while($fila = pg_fetch_assoc($res)){
			$categoria = $fila['id_area'];

			$contenido = '<article 
						id="'.$fila['id_tipo_producto'].'"
						class="item"
						data-rutaAplicacion="administracionProductos"
						data-opcion="abrirTipoProducto" 
						ondragstart="drag(event)" 
						draggable="true" 
						data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
					<span>'.(strlen($fila['nombre'])>45?(substr($fila['nombre'],0,45).'...'):(strlen($fila['nombre'])>0?$fila['nombre']:'Sin asunto')).'</span>
					<aside><small>'.$fila['nombre_area'].'</small></aside>			
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
	
	
	
</body>
<script>
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		//$("#listadoItems").addClass("lista");
		$("#LT div> article").length == 0 ? $("#LT").remove():"";
		$("#SA div> article").length == 0 ? $("#SA").remove():"";
		$("#SV div> article").length == 0 ? $("#SV").remove():"";
		$("#AI div> article").length == 0 ? $("#AI").remove():"";
	});
</script>
</html>
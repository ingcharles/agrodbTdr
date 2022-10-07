<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorCatalogos.php';

	$conexion = new Conexion();
	$cr = new ControladorRequisitos();
	$cc = new ControladorCatalogos();
	
	$tipoProducto = htmlspecialchars ($_POST['fTipoProducto'],ENT_NOQUOTES,'UTF-8');
	$nombreTipoProducto= htmlspecialchars ($_POST['fNombreTipoProducto'],ENT_NOQUOTES,'UTF-8');
	$subTipoProducto = htmlspecialchars ($_POST['fSubtipoProducto'],ENT_NOQUOTES,'UTF-8');
	$nombreSubTipoProducto= htmlspecialchars ($_POST['fNombreSubTipoProducto'],ENT_NOQUOTES,'UTF-8');
	$producto = htmlspecialchars ($_POST['fProducto'],ENT_NOQUOTES,'UTF-8');
	$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8'); 
?>

</head>
<body>
	
	<div id="IAP">
		<h2>Registro de insumos agricolas</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="IAV">
		<h2>Registro de insumos pecuarios</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="IAF">
		<h2>Registro de insumos fertilizantes</h2>
		<div class="elementos"></div>
	</div>
		
	<div id="IAPA">
		<h2>Registro de insumos para plantas de autoconsumo</h2>
		<div class="elementos"></div>
	</div>	
	
	<div id="SA">
		<h2>Sanidad Animal</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="SV">
		<h2>Sanidad Vegetal</h2>
		<div class="elementos"></div></div>
	<?php  
		
	switch ($opcion){
		case 'subTipoProducto':
		    $res = $cr->listarRequisitosMovilizacionProducto($conexion,	$tipoProducto,'tipoProducto');
		break;
		
		case 'producto':
		    $res = $cr->listarRequisitosMovilizacionProducto($conexion,$subTipoProducto, 'subTipoProducto');
		break;
		
		default:
		    $res = $cr->listarRequisitosMovilizacionProducto($conexion,$producto, 'producto');
	}
	
		
		$contador = 0;
		while($fila = pg_fetch_assoc($res)){
			
			$datosTipoSubtipo = pg_fetch_assoc($cc->obtenerTipoSubtipoXProductos($conexion, $fila['id_producto']));
			$nombreTipoProducto = $datosTipoSubtipo['nombre_tipo'];
			$nombreSubtipoProducto = $datosTipoSubtipo['nombre_subtipo'];
			
			$categoria = $fila['id_area'];

			$contenido = '<article 
						id="'.$fila['id_requisito_comercio'].'"
						class="item"
						data-rutaAplicacion="administracionRequisitos"
						data-opcion="abrirProductoMovilizacion" 
						ondragstart="drag(event)" 
						draggable="true" 
						data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
					<span><small>'.(strlen($nombreTipoProducto)>20?(substr($nombreTipoProducto,0,19).'...'):(strlen($nombreTipoProducto)>0?$nombreTipoProducto:'')).'</span><br>
					<span>'.(strlen($nombreSubtipoProducto)>20?(substr($nombreSubtipoProducto,0,19).'...'):(strlen($nombreSubtipoProducto)>0?$nombreSubtipoProducto:'')).'</span><br>
					<span><b>'.(strlen($fila['nombre_producto'])>30?(substr($fila['nombre_producto'],0,30).'...'):(strlen($fila['nombre_producto'])>0?$fila['nombre_producto']:'')).'</b></span>
					<aside>'.$fila['id_area'].'</aside>	
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
		$("#IAP div> article").length == 0 ? $("#IAP").remove():"";
		$("#IAV div> article").length == 0 ? $("#IAV").remove():"";
		$("#IAF div> article").length == 0 ? $("#IAF").remove():"";
		$("#IAPA div> article").length == 0 ? $("#IAPA").remove():"";
		$("#SA div> article").length == 0 ? $("#SA").remove():"";
		$("#SV div> article").length == 0 ? $("#SV").remove():"";
	});
</script>
</html>
<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAdministrarCatalogos.php';
	require_once '../../clases/ControladorAplicaciones.php';
	
	$conexion = new Conexion();
	$cac = new ControladorAdministrarCatalogos();
	$usuario=$_SESSION['usuario'];
	
	?>
	
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

<?php 
	
	$itemsFiltrados[] = array();
	
	$registros = $cac->listarCatalogos($conexion,$_POST['txtCatalogo']);
?>



<header>
		<h1>Administración Catálogos de Negocio</h1>
		<nav>
		
		<form id="filtrar" data-rutaAplicacion="administracionCatalogos" data-opcion="listarCatalogos" data-destino="areaTrabajo #listadoItems">
		<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
		<table class="filtro" style='width: 400px;'>
			<tr>
				<th colspan="3">Buscar Catálogo:</th>
			</tr>
			<tr>
				<td>Nombre Catálogo: </td>
				<td><input type="text" id="txtCatalogo" name="txtCatalogo">	</td>
			</tr>
			
			<tr>						
				<td colspan="3"> <button  type="submit" id='buscar'>Buscar</button></td>
			</tr>
			<tr>
				<td colspan="4" style='text-align:center' id="mensajeError"></td>
			</tr>
		</table>
		</form>
		</nav>
</header>
<header>		
		<nav>

		<?php 
			$ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $usuario);
			
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
	
	<div id="catalogos">
		<h2>Lista de Catálogos</h2>
		<div class="elementos"></div>
	</div>
	
	<?php  		
	/*
		$res = $cac->listarCatalogos($conexion);
		$contador = 0;
		while($fila = pg_fetch_assoc($res)){
			//$categoria = $fila['id_area'];

			$contenido = '<article 
						id="'.$fila['id_catalogo_negocios'].'"
						class="item"
						data-rutaAplicacion="administracionCatalogos"
						data-opcion="abrirCatalogo" 
						ondragstart="drag(event)" 
						draggable="true" 
						data-destino="detalleItem"
						style="'.($fila['estado']== '1'?'background-color: #7D97EB;':'background-color: #EA8F7D;').'">
					<span class="ordinal">'.++$contador.'</span>
					<span>'.(strlen($fila['nombre'])>45?(substr($fila['nombre'],0,45).'...'):(strlen($fila['nombre'])>0?$fila['nombre']:'Sin asunto')).'</span>
					<aside><small>'.$fila['descripcion'].'</small></aside>			
				</article>';
			?>
				<script type="text/javascript">
					var contenido = <?php echo json_encode($contenido);?>;
					var categoria = <?php echo json_encode($categoria);?>;
					$("#catalogos div.elementos").append(contenido);
				</script>
				<?php					
		}
		*/
	
//	$res = $cac->listarCatalogos($conexion,$_POST['txtNombreCatalogo']);
	$contador = 0;
	while($fila = pg_fetch_assoc($registros)){
		//$categoria = $fila['id_area'];
		
		$contenido = '<article
						id="'.$fila['id_catalogo_negocios'].'"
						class="item"
						data-rutaAplicacion="administracionCatalogos"
						data-opcion="abrirCatalogo"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem"
						style="'.($fila['estado']== '1'?'background-color: #7D97EB;':'background-color: #EA8F7D;').'">
					<span class="ordinal">'.++$contador.'</span>
					<span>'.(strlen($fila['nombre'])>45?(substr($fila['nombre'],0,45).'...'):(strlen($fila['nombre'])>0?$fila['nombre']:'Sin asunto')).'</span>
					<aside><small>'.$fila['descripcion'].'</small></aside>
				</article>';
		?>
				<script type="text/javascript">
					var contenido = <?php echo json_encode($contenido);?>;
					var categoria = <?php echo json_encode($categoria);?>;
					$("#catalogos div.elementos").append(contenido);
				</script>
				<?php					
	}
	?>
	
	
	
</body>
<script>
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
		//construirPaginacion($("#paginacion"),<?php //echo json_encode($itemsFiltrados);?>);	
		$("#catalogos div> article").length == 0 ? $("#catalogos").remove():"";		
	});

	$("#_inactivar").click(function(e){
		//e.preventDefault();
		if($("#cantidadItemsSeleccionados").text()<1){
			$("#mensajeError").html("Por favor seleccione un registro para inactivar.").addClass('alerta');			
			return false;
		}
	});

	$("#filtrar").submit(function(event){
		event.preventDefault();		
		var error = false;		

		if ($.trim($("#txtCatalogo").val()).length <= 2 ) {			
			error = true;
	    	$("#mensajeError").html("Por favor ingrese al menos 3 letras para buscar las coincidencias.").addClass('alerta');
	    }	

		if(!error){	
			abrir($('#filtrar'),event, false);
		}
	});
</script>
</html>
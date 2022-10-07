<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAdministrarCatalogos.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorLotes.php';
	
	$conexion = new Conexion();
	$cac = new ControladorAdministrarCatalogos();
	$cl = new ControladorLotes();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

<header>
		<h1>Parametrización Conformación de Lotes</h1>
		<nav>
		
		<form id="filtrarParametros" data-rutaAplicacion="administracionProductos" data-opcion="listarParametrizacion" data-destino="areaTrabajo #listadoItems">
		<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
		<input type="hidden" id="opcionCombo" name="opcionCombo"/>
		<table class="filtro" style='width: 400px;'>
			<tr>
				<th colspan="3">Buscar Producto:</th>
			</tr>
			
			<tr>
				<td>Área: </td>
				<td><select id="cbAreaListar" name="cbAreaListar" style="width: 200px;">
						<option value="">Seleccione....</option>
						<option value="SA">Sanidad Animal</option>
						<option value="SV">Sanidad Vegetal</option>
						<option value="LT">Laboratorios</option>
						<option value="AI">Inocuidad de los alimentos</option>
					</select>	
				</td>
			</tr>
			
			<tr id="resultadoTipoProductoListar">
				
			</tr>
			
			<tr id="resultadoSubTipoProductoListar">
			</tr>
			
			<tr id="resultadoProductoListar">
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
	
	<div id="catalogos">
		<h2>Lista de Catálogos</h2>
		<div class="elementos"></div>
	</div>
	
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
		//$res = $cat->listarProductoParametrizacion($conexion);
		$res = $cl->listarProductoParametrizacion($conexion,$_POST['cbProductoListar']);	
		$contador = 0;
		while($fila = pg_fetch_assoc($res)){
			$categoria = $fila['id_area'];

			$contenido = '<article 
						id="'.$fila['id_producto'].'"
						class="item"
						data-rutaAplicacion="administracionProductos"
						data-opcion="abrirParametro" 
						ondragstart="drag(event)" 
						draggable="true" 
						data-destino="detalleItem"
						style="'.($fila['estado']== '1'?'background-color: #7D97EB;':'background-color: #EA8F7D;').'">
					<span class="ordinal">'.++$contador.'</span>
					<span>'.(strlen($fila['nombre_comun'])>45?(substr($fila['nombre_comun'],0,45).'...'):(strlen($fila['nombre_comun'])>0?$fila['nombre_comun']:'Sin asunto')).'</span>	
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
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
		//construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);	
		$("#catalogos div> article").length == 0 ? $("#catalogos").remove():"";		

		$("#LT div> article").length == 0 ? $("#LT").remove():"";
		$("#SA div> article").length == 0 ? $("#SA").remove():"";
		$("#SV div> article").length == 0 ? $("#SV").remove():"";
		$("#AI div> article").length == 0 ? $("#AI").remove():"";
	});


	$("#cbAreaListar").change(function(event){
		event.preventDefault();	
		if($.trim($("#cbAreaListar").val())!=""){
    		$('#filtrarParametros').attr('data-opcion','comboParametro');
    		$('#filtrarParametros').attr('data-destino','resultadoTipoProductoListar');
    		$('#opcionCombo').val('tipoProductoListar');
    		abrir($("#filtrarParametros"),event,false);
    		$("#resultadoSubTipoProductoListar").html("");
			$("#resultadoProductoListar").html("");
		} else{
			$("#resultadoTipoProductoListar").html("");
			$("#resultadoSubTipoProductoListar").html("");
			$("#resultadoProductoListar").html("");
		}
	});

	$("#filtrarParametros").submit(function(event){	
		event.preventDefault();		
		var error = false;		

		if($("#cbProductoListar").val() == ""){
			error = true;
		}

		if(!error){
			$('#filtrarParametros').attr('data-destino','areaTrabajo #listadoItems');
			$('#filtrarParametros').attr('data-opcion','listarParametrizacion');		
			abrir($('#filtrarParametros'),event, false);
		} else{
			$("#mensajeError").html("Por favor seleccione un producto para realizar la busqueda.").addClass("alerta");
		}
		
	});
	
</script>
</html>
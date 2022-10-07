<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAreas.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorCatalogos.php';
	
	$conexion = new Conexion();
	$car = new ControladorAreas();
	$cc = new ControladorCatalogos();
	$cr = new ControladorRequisitos();
	
?>


<header>
	<h1>Requistos para Movilización de Productos</h1>
	
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
	
	<?php 
	
	   $tipoProducto = $cr->obtenerTipoProductoMovilizacion($conexion);
		
	?>
	
	<nav style="width: 78%;">
	
	<form id="filtrarListaProductoMovilizacion" data-rutaAplicacion="administracionRequisitos" data-opcion="listaMovilizacion" data-destino="contenedor">

		<input type="hidden" id="opcion" name="opcion" />
	
		<table class="filtro">
			<tr>
				<td>
					<label>Tipo producto: </label>
					<select id="fTipoProducto" name="fTipoProducto" style="width: 74%;">
						<option value="0">Seleccione una opción</option>
						<?php 
						  while ($fila = pg_fetch_assoc($tipoProducto)){
								echo '<option value="'.$fila['id_tipo_producto'].'">'.$fila['nombre'].'</option>';
							}
						?>
					</select>
					<input id="fNombreTipoProducto" name="fNombreTipoProducto" type="hidden"/>
				</td>	
			</tr>
			<tr id="tSubTipoProducto"></tr>		
			<tr id="tProducto"></tr>
			<tr>
				<td colspan="5"><button>Filtrar lista</button></td>
			</tr>
		</table>
		</form>
		
	</nav>
</header>

<div id="contenedor"></div>

<script>
						
	$("#fTipoProducto").change(function (event) {
		$("#filtrarListaProductoMovilizacion").attr('data-opcion', 'accionesMovilizacion');
	    $("#filtrarListaProductoMovilizacion").attr('data-destino', 'tSubTipoProducto');
	    $("#opcion").val('subTipoProducto');
	    $("#fNombreTipoProducto").val($("#fTipoProducto  option:selected").text());
	    abrir($("#filtrarListaProductoMovilizacion"), event, false); //Se ejecuta ajax, busqueda de sub tipo producto
	});

	$("#filtrarListaProductoMovilizacion").submit(function(event){

		var error = false;
		
		if($("#fTipoProducto option:selected").val()=="0"){	
			error = true;		
			$("#fTipoProducto").addClass("alertaCombo");
		}
		if (error == true){
			
		}else{		
		$("#filtrarListaProductoMovilizacion").attr('data-opcion', 'listaMovilizacion');
	    $("#filtrarListaProductoMovilizacion").attr('data-destino', 'contenedor');
		}
		
		abrir($(this),event,false);
	});
					
</script>

<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAreas.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorProtocolos.php';
	require_once '../../clases/ControladorCatalogos.php';
	
	$conexion = new Conexion();
	$car = new ControladorAreas();
	$cc = new ControladorCatalogos();
	$cp = new ControladorProtocolos();
	
	/*$res = $car->areaUsuario($conexion, $_SESSION['usuario']);
	$area = pg_fetch_assoc($res);
	
	$_SESSION['id_area']=$area['id_area'];	*/
	
?>


<header>
	<h1>Requistos producto país protocolos</h1>
	
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
	
		$tipoProdcuto = $cp->obtenerTipoProductoXProtocolo($conexion);
		
	?>
	
	<nav style="width: 78%;">
	
	<form id="filtrarListaProtocoloComercio" data-rutaAplicacion="administracionRequisitos" >
	<?php 
	
	//echo $_POST['fSubtipoProducto'];
	?>
		<input type="hidden" id="opcion" name="opcion" />
	
		<table class="filtro">
			<tr>
				<td>
					<label>Tipo producto: </label>
					<select id="fTipoProducto" name="fTipoProducto" style="width: 74%;">
						<option value="0">Seleccione una opción</option>
						<?php 
							while ($fila = pg_fetch_assoc($tipoProdcuto)){
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
						
/*$(document).ready(function(){
	$("#listadoItems").addClass("comunes");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un item para revisarlo.</div>');
});*/

	$("#fTipoProducto").change(function (event) {
		$("#filtrarListaProtocoloComercio").attr('data-opcion', 'accionesProtocolo');
	    $("#filtrarListaProtocoloComercio").attr('data-destino', 'tSubTipoProducto');
	    $("#opcion").val('subTipoProducto');
	    $("#fNombreTipoProducto").val($("#fTipoProducto  option:selected").text());
	    abrir($("#filtrarListaProtocoloComercio"), event, false); //Se ejecuta ajax, busqueda de sub tipo producto
	});

	$("#filtrarListaProtocoloComercio").submit(function(event){

		var error = false;
		
		if($("#fTipoProducto option:selected").val()=="0"){	
			error = true;		
			$("#fTipoProducto").addClass("alertaCombo");
		}
		if (error == true){
			
		}else{		
		$("#filtrarListaProtocoloComercio").attr('data-opcion', 'listaProtocolo');
	    $("#filtrarListaProtocoloComercio").attr('data-destino', 'contenedor');
		}
		
		abrir($(this),event,false);
	});
						
</script>

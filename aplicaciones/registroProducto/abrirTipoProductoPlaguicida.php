<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorRequisitos.php';
	
	$idTipoProducto = $_POST['id'];
	
	$conexion = new Conexion();
	$cr = new ControladorRequisitos();
	$cc = new ControladorCatalogos();
	
	$tipoProducto = pg_fetch_assoc($cr->abrirTipoProducto($conexion, $idTipoProducto));
	$subtipoProductos = $cr->listarSubtipoProducto($conexion, $idTipoProducto);
?>

	<header>
		<h1>Detalle de Tipo Producto Plaguicida</h1>
	</header>

	<div id="estado"></div>
	<table class="soloImpresion">
		<tr>
			<td>
				<form id="actualizarTipoProducto" data-rutaAplicacion="registroProducto" data-opcion="modificarTipoProducto" data-accionEnExito="ACTUALIZAR">
					<input type="hidden" id="idTipoProducto" name="idTipoProducto" value="<?php echo $idTipoProducto;?>">
					<fieldset id="fs_detalle">
						<legend>Detalle</legend>
						
						<div data-linea="1">
							<label for="areaTipoProducto">Área</label>
							<select id="areaTipoProducto" name="areaTipoProducto" disabled="disabled">
									<option value="">Seleccione....</option>
									<option value="IAP">Registro de insumo agrícolas</option>
							</select>
						</div>
						
						<div data-linea="2">
							<label for="nombreTipoProducto">Nombre</label>
							<input id="nombreTipoProducto" name="nombreTipoProducto" type="text" value="<?php echo $tipoProducto['nombre']?>" disabled="disabled"/>
						</div>
						
						<div>
							<button id="modificar" type="button" class="editar">Editar</button>
							<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
						</div>
					</fieldset>
				</form>
			</td>
			
			<td>
				<form id="nuevoSubTipoProducto" data-rutaAplicacion="registroProducto" data-opcion="guardarNuevoSubtipoProductoPlaguicida" >
					<input type="hidden" id="idTipoProducto" name="idTipoProducto" value="<?php echo $idTipoProducto;?>">
					<input type="hidden" id="area" name="area" value="<?php echo $tipoProducto['id_area'];?>">
					
					<fieldset>
						<legend>Subtipo de Producto</legend>	
						<div data-linea="1">
							<label for="nombreSubtipo">Nombre</label>
							<input id="nombreSubtipo" name="nombreSubtipo" type="text"  required="required"/>
							<button type="submit" class="mas">Añadir subtipo de producto</button>		
						</div>

					</fieldset>
				</form>
				<fieldset>
					<legend>Subtipo de Productos</legend>
					<table id="subTipoProducto">
						<?php 
							while ($subtipoProducto = pg_fetch_assoc($subtipoProductos)){
								echo $cr->imprimirLineaSubtipoProducto($subtipoProducto['id_subtipo_producto'], $subtipoProducto['nombre'], $idTipoProducto, $tipoProducto['id_area'], 'registroProducto', 'Plaguicida');
							}
						?>
					</table>
				</fieldset>
			</td>
		</tr>
	</table>

<script type="text/javascript">

						
	$('document').ready(function(){
		
		cargarValorDefecto("areaTipoProducto","<?php echo $tipoProducto['id_area'];?>");
		acciones("#nuevoSubTipoProducto","#subTipoProducto");
		distribuirLineas();
	});


	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});
	
	$("#actualizarTipoProducto").submit(function(event){

		event.preventDefault();

		
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#areaTipoProducto").val()==""){
			error = true;
			$("#areaTipoProducto").addClass("alertaCombo");
		}

		if($.trim($("#nombreTipoProducto").val())=="" ){
			error = true;
			$("#nombreTipoProducto").addClass("alertaCombo");
		}
		
		if (!error){
			ejecutarJson($(this));
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
		
	});

	
</script>

<?php 

	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorFormularios.php';
	
	$idFormulario = $_POST['id'];
	
	$conexion = new Conexion();
	$cf = new ControladorFormularios();
	//$ca = new ControladorAuditoria();
	
	$formulario = pg_fetch_assoc($cf->abrirFormulario($conexion, $idFormulario));
	$categorias = $cf->cargarCategorias($conexion, $idFormulario);

?>

	<header>
		<h1>Detalle de formulario</h1>
	</header>
	<form action="aplicaciones/formularios/previsualizarFormulario.php" method="POST" target="_blank">
		<input type="hidden" name="idFormulario" value="<?php echo $idFormulario;?>">
		<button>Visualizar formulario</button>
	</form>
	<div id="estado"></div>
	<table class="soloImpresion">
		<tr>
			<td>
				<form id="actualizarRegistro" data-rutaAplicacion="formularios" data-opcion="modificarFormulario" data-accionEnExito="ACTUALIZAR">
					<input type="hidden" name="idFormulario" value="<?php echo $idFormulario;?>">
					<fieldset id="fs_detalle">
						<legend>Detalle</legend>
						
						<div data-linea="1">
							<label for="codigo">Código</label>
							<input id="codigo" name="codigo" type="text" value="<?php echo $formulario['codigo']?>" />
						</div>
						<div data-linea="1">
							<label for="nombre">Nombre</label>
							<input id="nombre" name="nombre" type="text" value="<?php echo $formulario['nombre']?>" />
						</div>
						<div data-linea="2">
							<label for="descripcion">Descripción</label>
							<input name="descripcion" id="descripcion" type="text" value="<?php echo $formulario['descripcion']?>" />
						</div>
						<div>
							<button type="submit" class="guardar">Actualizar</button>
						</div>
					</fieldset>
				</form>
			</td>
			<td>
				<form id="nuevoRegistro" data-rutaAplicacion="formularios" data-opcion="nuevaCategoria" >
					<input id="formulario" name="formulario" type="hidden" value="<?php echo $idFormulario;?>" />
					<fieldset>
						<legend>Categorias</legend>	
						<div data-linea="1">
							<label for="categoria">Categoria</label>
							<input id="categoria" name="categoria" type="text" />
							<button type="submit" class="mas">Añadir categoría</button>		
						</div>

					</fieldset>
				</form>
				<fieldset>
					<table id="registros">
						<?php 
							while ($categoria = pg_fetch_assoc($categorias)){
								echo $cf->imprimirLineaCategoria($categoria['id_categoria'], $categoria['nombre'], $idFormulario);
							}
						?>
					</table>
				</fieldset>
			</td>
		</tr>
	</table>

<script>
	$('document').ready(function(){
		$('#listadoItems #<?php echo $idFormulario?>').addClass("abierto");
		distribuirLineas();
		actualizarBotonesOrdenamiento();
	});

	acciones();

	
</script>

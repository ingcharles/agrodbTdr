<?php 

	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorFormularios.php';
	
	$idCategoria = $_POST['categoria'];
	
	$conexion = new Conexion();
	$cf = new ControladorFormularios();
	//$ca = new ControladorAuditoria();
	/*$ca = new ControladorAplicaciones('formulario','abrirCategoria');*/
	
	
	$categoria = pg_fetch_assoc($cf->abrirCategoria($conexion, $idCategoria));
	$preguntas = $cf->cargarPreguntas($conexion, $idCategoria);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Detalle de categoria</h1>
	</header>
	<div id="estado"></div>
	<form id="regresar" data-rutaAplicacion="formularios" data-opcion="abrirFormulario" data-destino="detalleItem">
		<input type="hidden" name="id" value="<?php echo $categoria['id_formulario'];?>"/>
		<button class="regresar">Regresar a formulario</button>
	</form>
	<table class="soloImpresion">
		<tr>
			<td>
				<form id="actualizarRegistro" data-rutaAplicacion="formularios" data-opcion="modificarCategoria" >
					<input id="idCategoria" name="idCategoria" type="hidden" value="<?php echo $idCategoria?>" />
					<fieldset>
						<legend>Categoria</legend>		
						<div data-linea="1">
							<label for="nombre">Nombre</label>
							<input id="nombre" name="nombre" type="text" value="<?php echo $categoria['nombre']?>" />
						</div>
						<div>
							<button type="submit" class="guardar">Actualizar</button>
						</div>
					</fieldset>
				</form>	
				<form id="nuevoRegistro" data-rutaAplicacion="formularios" data-opcion="nuevaPregunta" >
					<input id="categoria" name="categoria" type="hidden" value="<?php echo $idCategoria?>" />
					<input id="formulario" name="formulario" type="hidden" value="<?php echo $categoria['id_formulario']?>" />
							
					<fieldset>
						<legend>Preguntas</legend>	
						<div data-linea="1">
							<label for="pregunta">Pregunta</label>
							<input name="pregunta" id="pregunta" type="text"  />
						</div>
						<div data-linea="2">
							<label for="tipoPregunta">Tipo de Pregunta</label>
							<select name="tipoPregunta">
								<option value="1">Informativa</option>
								<option value="2">Seleccion múltiple, informativa</option>
								<option value="3">Opción múltiple</option>
								<option value="4">Rango x &lt; n &lt; y</option>
								
							</select>
						</div>
                        <div data-linea="3">
                            <label for="ayuda">Ayuda</label>
                            <input id="ayuda" name="ayuda" type="text" value="<?php echo $pregunta['ayuda'] ?>"/>
                        </div>
						<div>
							<button type="submit" class="mas">Añadir pregunta</button>
						</div>
					</fieldset>
				</form>
				<fieldset>
					<table id="registros">
						<?php 
							while ($pregunta = pg_fetch_assoc($preguntas)){
								echo $cf->imprimirLineaPregunta($pregunta['id_pregunta'], $pregunta['nombre'], $idCategoria);
							}
						?>
					</table>
				</fieldset>
			</td>
		</tr>
	</table>
</body>
<script>
	$('document').ready(function(){
		//$('#listadoItems #<?php echo $idPregunta?>').addClass("abierto");
		actualizarBotonesOrdenamiento();
		acciones();
		distribuirLineas();
		
	});
	
</script>
</html>

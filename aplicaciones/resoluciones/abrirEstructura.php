<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorResoluciones.php';

$conexion = new Conexion();
$cr = new ControladorResoluciones();

$estructuraPadre = pg_fetch_assoc($cr->abrirEstructura($conexion, $_POST['estructura']));
$estructuras = $cr->cargarEstructuras($conexion, $estructuraPadre['id_resolucion'], $estructuraPadre['id_estructura']);

$dataOpcion = '';
$mensaje = '';

if($estructuraPadre['id_estructura_padre'] == null) {
	$dataOpcion = 'abrirResolucion';
	$mensaje = 'Regresar a resolución';
} else {
	$dataOpcion = 'abrirEstructura';
	$mensaje = 'Regresar a nivel superior';
}

?>

<header>
	<h1>Datos de la resolución</h1>
</header>

<div id="estado"></div>
	<form id="regresar" data-rutaAplicacion="resoluciones" data-opcion="<?php echo $dataOpcion;?>" data-destino="detalleItem">
		<input type="hidden" name="id" value="<?php echo $estructuraPadre['id_resolucion'];?>"/>
		<input type="hidden" name="estructura" value="<?php echo $estructuraPadre['id_estructura_padre'];?>"/>
		<button class="regresar"><?php echo $mensaje;?></button>
	</form>
	<form id="datosEstructura" data-rutaAplicacion="resoluciones" data-opcion="modificarEstructura" data-accionEnExito="NADA" >
			<fieldset>
				<input id="estructura" name="estructura" type="hidden" value="<?php echo $estructuraPadre['id_estructura']; ?>" />
				<legend>Información de la estructura</legend>		
				<div data-linea="1">
					<label for="nivelP">Tipo de nivel</label>
					<select id="nivelP" name="nivel" disabled="disabled">
						<option value="Sección">Sección</option>
						<option value="Capítulo">Capítulo</option>
						<option value="Artículo">Artículo</option>
						<option value="Literal/Numeral">Literal/Numeral</option>
						<option value="Párrafo">Párrafo</option>
					</select>
				</div>
				<div data-linea="1">
					<label for="numeroP">Número de nivel</label>
					<input id="numeroP" name="numero" type="text" value="<?php echo $estructuraPadre['numero']?>"  disabled="disabled" />
				</div>
				<div>
					<label for="contenidoP">Contenido</label>
				</div>
				<div>
					<textarea id="contenidoP" name="contenido"  disabled="disabled"><?php echo $estructuraPadre['contenido']?></textarea>
				</div>
				<div>
				<p>
					<button id="modificar" type="button" class="editar">Editar</button>
					<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
				</p>
				</div>
			</fieldset>	
		</form>
	<!-- SECCION DE ESTRUCTURA DEL DOCUMENTO -->
			<form id="nuevaEstructura" data-rutaAplicacion="resoluciones" data-opcion="nuevaEstructura" >
				<input id="resolucion" name="resolucion" type="hidden" value="<?php echo $estructuraPadre['id_resolucion']?>" />
				<input id="idEstructuraPadre" name="idEstructuraPadre" type="hidden" value="<?php echo $estructuraPadre['id_estructura']?>" />
				<fieldset>
					<legend>Estructura del documento</legend>	
					<div data-linea="1">
						<label for="nivel">Tipo de nivel</label>
						<select id="nivel" name="nivel">
							<option value="Sección">Sección</option>
							<option value="Capítulo">Capítulo</option>
							<option value="Artículo">Artículo</option>
							<option value="Literal/Numeral">Literal/Numeral</option>
							<option value="Párrafo">Párrafo</option>
						</select>
					</div>
					<div data-linea="1">
						<label for="numero">Número de nivel</label>
						<input id="numero" name="numero" type="text" />
					</div>
					<div>
						<label for="contenido">Contenido</label>
					</div>
					<div>
						<textarea id="contenido" name="contenido"></textarea>
					</div>
					<div>
						<button type="submit" class="mas">Añadir categoría</button>
					</div>
				</fieldset>
			</form>
			<fieldset>
				<table id="estructuras">
					<?php 
						while ($estructura = pg_fetch_assoc($estructuras)){
							echo $cr->imprimirLineaEstructura($estructura['id_estructura'], $estructura['nivel'] . " " . $estructura['numero'], $estructura['id_resolucion'],'estructuras');
						}
					?>
				</table>
			</fieldset>




<script type="text/javascript">

	$(document).ready(function(){
		cargarValorDefecto("nivelP","<?php echo $estructuraPadre['nivel'];?>");
		distribuirLineas();
		acciones("#nuevaEstructura","#estructuras");
	});

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$("textarea").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});
		

	$("#datosEstructura").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
	});

</script>

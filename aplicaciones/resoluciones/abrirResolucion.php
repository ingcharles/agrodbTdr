<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorResoluciones.php';

$conexion = new Conexion();
$cr = new ControladorResoluciones();


$idResolucion = $_POST['id'];


$resolucion = pg_fetch_assoc($cr->abrirResolucion($conexion, $idResolucion));
$palabrasClave = $cr->cargarPalabrasClave($conexion, $idResolucion);
$estructuras = $cr->cargarEstructuras($conexion, $idResolucion);

?>

<header>
	<h1>Datos de la resolución</h1>
</header>
<div id="estado"></div>
	
	
		<form id="datosResolucion" data-rutaAplicacion="resoluciones" data-opcion="actualizarResolucion" data-accionEnExito="ACTUALIZAR" >
			<fieldset>
				<input id="numero" name="numero" type="hidden" value="<?php echo $idResolucion; ?>" />
				<legend>Información básica</legend>		
				<div data-linea="1">
					<label>Número resolución</label>
					<?php echo $resolucion['numero_resolucion'];?>
				</div>
				<div data-linea="1">
					<label>Fecha</label> 
					<input id="fecha" name="fecha" value="<?php echo $resolucion['fecha']; ?>" disabled="disabled"/>
				</div>
				<div data-linea="2">
					<label>Nombre</label> 
					<input id="nombre" name="nombre" value="<?php echo $resolucion['nombre']; ?>" disabled="disabled"/>								
				</div>
				<div data-linea="2">
					<label>Estado</label> 
						<select id="estadoDocumento" name="estadoDocumento" disabled="disabled">
							<option value="Vigente">Vigente</option>
							<option value="No vigente">No vigente</option>
							<option value="Reformado">Reformado</option>
							<option value="Derogado">Derogado</option>
						</select>								
				</div>
				<div data-linea="3">
					<label>Observación</label> 
					<input id="observacion" name="observacion" value="<?php echo $resolucion['observacion']; ?>" disabled="disabled"/>								
				</div>
				<div data-linea="4">
					<label>Archivo documento</label> <?php echo ($resolucion['ruta_archivo']=='0'? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$resolucion['ruta_archivo'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
				</div>
				<div data-linea="4">			
					<label>Archivo anexo</label> <?php echo ($resolucion['ruta_anexo']=='0'? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$resolucion['ruta_anexo'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
				</div>
				<div>
				<p>
					<button id="modificar" type="button" class="editar">Editar</button>
					<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
				</p>
				</div>
			</fieldset>	
		</form>
	 <!-- SECCION DE PALABRAS CLAVE -->
			<form id="nuevaPalabraClave" data-rutaAplicacion="resoluciones" data-opcion="nuevaPalabraClave" >
				<input id="resolucion1" name="resolucion" type="hidden" value="<?php echo $idResolucion?>" />
				<fieldset>
					<legend>Palabras clave para busqueda</legend>
					
					<div data-linea="1">
						<label for="palabraClave">Pala clave nueva</label>
						<input id="palabraClave" name="palabraClave" type="text" />
						<button type="submit" class="mas">Añadir palabra clave</button>		
					</div>

				</fieldset>
			</form>
			<fieldset>
				<table id="palabrasClave">
					<?php 
						while ($palabraClave = pg_fetch_assoc($palabrasClave)){
							echo $cr->imprimirLineaPalabrasClave($palabraClave['id_palabra'], $palabraClave['palabra'], $idResolucion);						}
					?>
				</table>
			</fieldset>	
		<!-- SECCION DE ESTRUCTURA DEL DOCUMENTO -->
			<form id="nuevaEstructura" data-rutaAplicacion="resoluciones" data-opcion="nuevaEstructura" >
				<input id="resolucion2" name="resolucion" type="hidden" value="<?php echo $idResolucion?>" />
				<input id="idEstructuraPadre" name="idEstructuraPadre" type="hidden" value="null" />
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
							echo $cr->imprimirLineaEstructura($estructura['id_estructura'], $estructura['nivel'] . " " . $estructura['numero'], $idResolucion, 'estructurasPadre');
						}
					?>
				</table>
			</fieldset>


<script type="text/javascript">

	$(document).ready(function(){
		$('#listadoItems #<?php echo $idResolucion?>').addClass("abierto");
		cargarValorDefecto("estadoDocumento","<?php echo $resolucion['estado'];?>");
		
		distribuirLineas();
		
		$( "#fecha" ).datepicker({
	      changeMonth: true,
	      changeYear: true
	    });

		acciones("#nuevaEstructura","#estructuras");
		acciones("#nuevaPalabraClave","#palabrasClave");
		
	});
		
				
	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

	$("#datosResolucion").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
	});

</script>

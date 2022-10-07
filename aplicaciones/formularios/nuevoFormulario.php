<?php 
session_start();
?>

<header>
	<h1>Nuevo formulario</h1>
</header>

<div id="estado"></div>

<form id="nuevoFormulario" data-rutaAplicacion="formularios"
	data-opcion="guardarFormulario" data-destino="detalleItem">
	<fieldset>
		<legend>Detalles del formularios</legend>
		<div data-linea="1">
			<label for="codigo">Código</label>
			<input id="codigo" name="codigo" type="text" />
		</div>
		<div data-linea="1">
			<label for="nombre">Nombre</label>
			<input id="nombre" name="nombre" type="text" />
		</div>
		<div data-linea="2">
			<label for="descripcion">Descripción</label>
			<input name="descripcion" id="descripcion" type="text" />
		</div>
	</fieldset>

	<button type="submit" class="guardar">Guardar formulario</button>
</form>
<script type="text/javascript">

	$("document").ready(function(){
		distribuirLineas();
	});

	$("#nuevoFormulario").submit(function(event){
		abrir($(this),event,false);
	});
	
</script>

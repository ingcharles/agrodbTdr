<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProcesosAdministrativosJuridico' data-opcion='modeloadministrativo/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
	<fieldset>
		<legend>ModeloAdministrativo</legend>				

		<div data-linea="1">
			<label for="id_modelo_administrativo">id_modelo_administrativo </label>
			<input type="text" id="id_modelo_administrativo" name="id_modelo_administrativo" value="<?php echo $this->modeloModeloAdministrativo->getIdModeloAdministrativo(); ?>"
			placeholder="Llave primaria de la tabla" required maxlength="8" />
		</div>				

		<div data-linea="2">
			<label for="ruta_modelo">ruta_modelo </label>
			<input type="text" id="ruta_modelo" name="ruta_modelo" value="<?php echo $this->modeloModeloAdministrativo->getRutaModelo(); ?>"
			placeholder="Ruta del modelo adminstrativo" required maxlength="1024" />
		</div>				

		<div data-linea="3">
			<label for="nombre_modelo">nombre_modelo </label>
			<input type="text" id="nombre_modelo" name="nombre_modelo" value="<?php echo $this->modeloModeloAdministrativo->getNombreModelo(); ?>"
			placeholder="Nombre del modelo administrativo" required maxlength="128" />
		</div>				

		<div data-linea="4">
			<label for="estado">estado </label>
			<input type="text" id="estado" name="estado" value="<?php echo $this->modeloModeloAdministrativo->getEstado(); ?>"
			placeholder="Estado del registro" required maxlength="12" />
		</div>				

		<div data-linea="5">
			<label for="fecha_creacion">fecha_creacion </label>
			<input type="text" id="fecha_creacion" name="fecha_creacion" value="<?php echo date('j/n/Y',strtotime($this->modeloTipoDocumento->getFechaCreacion())); ?>"
			placeholder="Fecha de creación del registro" required maxlength="8" />
		</div>

		<div data-linea="6">
			<button type="submit" class="guardar">Guardar</button>
		</div>
	</fieldset >
</form >
<script type ="text/javascript">
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
	 });

	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		if (!error) {
			abrir($(this), event, false);
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>

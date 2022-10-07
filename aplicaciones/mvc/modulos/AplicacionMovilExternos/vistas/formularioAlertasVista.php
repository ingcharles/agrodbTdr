<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>AplicacionMovilExternos' data-opcion='Alertas/Guardar' data-destino="detalleItem" method="post" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="id_alerta" name="id_alerta" value="<?php echo $this->modeloAlertas->getIdAlerta(); ?>" />
				
	<fieldset>
		<legend>Información de la Alerta</legend>

		<div data-linea="1">
			<label for="titulo">Título: </label>
			<input type="text" id="titulo" name="titulo" required value="<?php echo $this->modeloAlertas->getTitulo(); ?>" placeholder="Título de la alerta"  maxlength="128" />
		</div>

		<label for="alerta">Detalle de la Alerta: </label>
		<div data-linea="2">
			<textarea id="alerta" name="alerta" rows="10" required placeholder="Contenido de la alerta" maxlength="2056"><?php echo $this->modeloAlertas->getAlerta(); ?></textarea>
		</div>

		<div data-linea="3" class="abrir">
			<label for="estadoAlerta">estado </label>
			<select id='estadoAlerta' name='estado'>
				<?php echo $this->comboEstado($this->modeloAlertas->getEstado()); ?>
			</select>
		</div>
	</fieldset>
	
	<div data-linea="4">
		<button type="submit" class="guardar">Guardar</button>
	</div>
	
</form>

<script type="text/javascript">

	var bandera = <?php echo json_encode($this->formulario); ?>;

	$(document).ready(function() {
		construirValidador();
		distribuirLineas();

		if (bandera == 'nuevo') {
			$(".abrir").hide();
			$("#estadoAlerta").removeAttr("required");
		} else {
			$(".abrir").show();
			$("#estadoAlerta").attr("required");
		}

	 });

	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		
		if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);		
	        if (respuesta.estado == 'exito'){
	        	$("#estado").html("Se han guardado los datos con éxito.").addClass("exito");
	        	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');
	        	abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
	        }	
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
	
</script>
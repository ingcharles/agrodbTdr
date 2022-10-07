<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>AplicacionMovilExternos' data-opcion='Denuncia/actualizar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
	<fieldset>
		<legend>Denuncia</legend>					
		
		<input type="hidden" id="id_denuncia" name="id_denuncia" value="<?php echo $this->modeloDenuncia->getIdDenuncia() ?> ">

		<div data-linea="1">
			<label for="id_motivo">Motivo de denuncia: </label>
			<input type="text" id="id_motivo" name="id_motivo" value="<?php echo $this->motivoDenuncia ?>"
			placeholder="Llave foránea de la tabla a_movil_externos.motivos_denuncia" readonly maxlength="8" />
		</div>				

		<div data-linea="2">
			<label for="descripcion">Descripción: </label>
			<textarea id="descripcion" rows="4" cols="50" style="vertical-align:top;" readonly><?php echo $this->modeloDenuncia->getDescripcion(); ?></textarea>
			
		</div>				

		<div data-linea="3">
			<label for="lugar">Lugar: </label>
			<input type="text" id="lugar" name="lugar" value="<?php echo $this->modeloDenuncia->getLugar(); ?>"
			placeholder="Lugar de donde se hace la denuncia" readonly maxlength="256" />
		</div>				

		<div data-linea="4">
			<label for="latitud">Latitud: </label>
			<input type="text" id="latitud" name="latitud" value="<?php echo $this->modeloDenuncia->getLatitud(); ?>"
			placeholder="Coordenada de latitud del lugar de la denuncia" readonly maxlength="128" />
		</div>	
		
		<div data-linea="4">
			<label for="longitud">Longitud: </label>
			<input type="text" id="longitud" name="longitud" value="<?php echo $this->modeloDenuncia->getLongitud(); ?>"
			placeholder="Coordenada de longitud del lugar de la denuncia" readonly maxlength="128" />
		</div>

		<div data-linea="5">
			<label for="nombre_denunciante">Nombre Denunciante: </label>
			<input type="text" id="nombre_denunciante" name="nombre_denunciante" value="<?php echo $this->modeloDenuncia->getNombreDenunciante(); ?>"
			placeholder="Nombre de la persona que realiza la denuncia" readonly maxlength="512" />
		</div>				

		<div data-linea="6">
			<label for="correo_denunciante">Correo Denunciante: </label>
			<input type="text" id="correo_denunciante" name="correo_denunciante" value="<?php echo $this->modeloDenuncia->getCorreoDenunciante(); ?>"
			placeholder="Correo electrónico del denunciante" readonly maxlength="256" />
		</div>				

		<div data-linea="7">
			<label for="telefono">Teléfono: </label>
			<input type="text" id="telefono" name="telefono" value="<?php echo $this->modeloDenuncia->getTelefono(); ?>"
			placeholder="Número de teléfono del denunciante" readonly maxlength="16" />
		</div>				

		<div data-linea="8">
			<label for="fecha_registro">Fecha Denuncia: </label>
			<input type="text" id="fecha_registro" name="fecha_registro" value="<?php echo $this->modeloDenuncia->getFechaRegistro(); ?>"
			placeholder="Fecha de registro de la denuncia" readonly maxlength="8" />
		</div>				

		<div data-linea="9">
			<label for="ruta_archivo">Imagen: </label><?php echo ($this->modeloDenuncia->getRutaArchivo()==''? '<span class="alerta">No ha cargado ninguna imagen</span>':'<a href="'.$this->modeloDenuncia->getRutaArchivo().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click aquí para ver la imagen</a>')?>
		</div>

		<div data-linea="10">
			<label for="estado">Estado: </label>
			
			<select name="estado" required="required">
				<?php echo $this->comboEstado($this->modeloDenuncia->getEstado()); ?>
			</select>
		</div>

		<div data-linea="11">
			<label for="observacion">Comentario </label>
			<textarea id="observacion" name="observacion" rows="4" cols="50" style="vertical-align:top;" maxlength="1024"><?php echo $this->modeloDenuncia->getObservacion(); ?></textarea>
		</div>

		<div data-linea="14">
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

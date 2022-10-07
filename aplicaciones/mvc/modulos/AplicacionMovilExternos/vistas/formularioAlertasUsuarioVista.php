<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>AplicacionMovilExternos' data-opcion='AlertasUsuario/actualizar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
	<fieldset>
		<legend>AlertasUsuario</legend>			
		<input type="hidden" id="id_alerta" name="id_alerta" value="<?php echo $this->modeloAlertasUsuario->getIdAlerta() ?> ">
		<div data-linea="2">
			<label for="id_tipo_alerta">Tipo de alerta: </label>
			<input type="text" id="id_tipo_alerta" name="id_tipo_alerta" value="<?php echo $this->tipoAlerta; ?>"
			placeholder="Llave foránea de la tabla a_movil_externos.tipos_alerta" readonly required maxlength="8" />
		</div>				

		<div data-linea="3">
			<label for="descripcion">Descripción: </label>
			<textarea id="descripcion" rows="4" cols="50" style="vertical-align:top;" readonly><?php echo $this->modeloAlertasUsuario->getDescripcion(); ?></textarea>			
		</div>				

		<div data-linea="4">
			<label for="lugar">Lugar: </label>
			<input type="text" id="lugar" name="lugar" value="<?php echo $this->modeloAlertasUsuario->getLugar(); ?>"
			placeholder="Lugar de donde se hace la alerta" required maxlength="256" readonly/>
		</div>				

		<div data-linea="5">
			<label for="latitud">Latitud: </label>
			<input type="text" id="latitud" name="latitud" value="<?php echo $this->modeloAlertasUsuario->getLatitud(); ?>"
			placeholder="Coordenada de latitud del lugar de la alerta" required maxlength="128" readonly/>
		</div>				

		<div data-linea="5">
			<label for="longitud">Longitud: </label>
			<input type="text" id="longitud" name="longitud" value="<?php echo $this->modeloAlertasUsuario->getLongitud(); ?>"
			placeholder="Coordenada de longitud del lugar de donde se hace la alerta" required maxlength="128" readonly/>
		</div>							

		<div data-linea="8">
			<label for="nombre_usuario">Nombre Usuario: </label>
			<input type="text" id="nombre_usuario" name="nombre_usuario" value="<?php echo $this->modeloAlertasUsuario->getNombreUsuario(); ?>"
			placeholder="Nombre de la persona que realiza la alerta" required maxlength="512" readonly/>
		</div>				

		<div data-linea="9">
			<label for="correo_usuario">Correo usuario: </label>
			<input type="text" id="correo_usuario" name="correo_usuario" value="<?php echo $this->modeloAlertasUsuario->getCorreoUsuario(); ?>"
			placeholder="Correo electrónico del usuario" required maxlength="256" readonly/>
		</div>				

		<div data-linea="10">
			<label for="telefono">Teléfono: </label>
			<input type="text" id="telefono" name="telefono" value="<?php echo $this->modeloAlertasUsuario->getTelefono(); ?>"
			placeholder="Número de teléfono del usuario" required maxlength="16" readonly/>
		</div>				

		<div data-linea="11">
			<label for="fecha_registro">Fecha alerta: </label>
			<input type="text" id="fecha_registro" name="fecha_registro" value="<?php echo date('Y-m-d h:i:s',strtotime($this->modeloAlertasUsuario->getFechaRegistro())); ?>"
			placeholder="Fecha de registro de la usuario" required maxlength="8" readonly/>
		</div>				
	
		<div data-linea="12">
			<label for="ruta_archivo">Imagen: </label><?php echo ($this->modeloAlertasUsuario->getRutaImagen()==''? '<span class="alerta">No ha cargado ninguna imagen</span>':'<a href="'.$this->modeloAlertasUsuario->getRutaImagen().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click aquí para ver la imagen</a>')?>
		</div>		

		<div data-linea="13">
			<label for="estado">estado </label>
			<select name="estado" required="required">
				<?php echo $this->comboEstado($this->modeloAlertasUsuario->getEstado()); ?>
			</select>
		</div>

		<div data-linea="14">
			<label for="observacion">Comentario </label>
			<textarea id="observacion" name="observacion" rows="4" cols="50" style="vertical-align:top;" maxlength="1024"><?php echo $this->modeloAlertasUsuario->getObservacion(); ?></textarea>
		</div>

		<div data-linea="15">
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

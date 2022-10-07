<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id="regresar" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='Metodos/editar' data-destino="detalleItem">
		<input type="hidden" name="id_metodo" value="<?php echo $this->modeloRangos->getIdMetodo(); ?>" />
		<button class="regresar">Regresar a Método</button>
</form>

<form id='formularioMetodo' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='Rangos/actualizar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<fieldset>
		<legend>Rango</legend>
		
		<input type="hidden" name="id_rango" value="<?php echo $this->modeloRangos->getIdRango(); ?>" />
		<input type="hidden" name="id_metodo" value="<?php echo $this->modeloRangos->getIdMetodo(); ?>" />
		<input type="hidden" name="descripcion_original" value="<?php echo $this->modeloRangos->getDescripcion(); ?>" />
		
		<div data-linea="1">
			<label for="descripcion">Descripción</label>
			<input type="text" id="descripcion" name="descripcion" placeholder="Nombre del parametro"  value="<?php echo $this->modeloRangos->getDescripcion(); ?>" maxlength="256" class="validacion" disabled="disabled"/>
		</div>
		
		<div data-linea="2">
			<button id="modificar" type="button" class="editar">Editar </button>
			<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
		</div>
	</fieldset>
</form>

<script type="text/javascript">

	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		mostrarMensaje("","EXITO");
	 });

	$("#formularioMetodo").submit(function (event) {
		event.preventDefault();
		var error = false;
		$(".alertaCombo").removeClass("alertaCombo");

		$('#formularioMetodo .validacion').each(function(i, obj) {
			if(!$.trim($(this).val())){
				error = true;
				$(this).addClass("alertaCombo");
			}
		});

		if (!error) {
				var respuesta = JSON.parse(ejecutarJson($("#formularioMetodo")).responseText);
				filtrarFormulario('noRefrescar');
				if(respuesta.estado == 'EXITO'){
					mostrarMensaje(respuesta.mensaje,respuesta.estado);
				}else{
					mostrarMensaje(respuesta.mensaje,respuesta.estado);
				}
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

	$("#regresar").submit(function(event){
		event.stopImmediatePropagation();
		abrir($(this),event,false);
	});

</script>

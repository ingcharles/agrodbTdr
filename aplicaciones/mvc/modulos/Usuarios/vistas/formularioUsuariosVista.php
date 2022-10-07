
<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='reseteoClave' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>usuarios' data-opcion='usuarios/guardar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
<input type="hidden" id="tipo_usuario" name="tipo_usuario" value="<?php echo $this->tipoUsuario; ?>">
	<fieldset>
		<legend>Usuarios</legend>
		<div data-linea="1">
			<label for="identificador">identificador </label> 
				<input type="text" id="identificador" name="identificador" value="<?php echo $this->modeloUsuarios->getIdentificador(); ?>" placeholder="Cedula de identidad o pasaporte." readonly="readonly" maxlength="13" />
		</div>

		<div data-linea="2">
			<label for="nombre_usuario">Nombre usuario </label> 
				<input type="text" id="nombre_usuario" name="nombre_usuario" value="<?php echo $this->modeloUsuarios->getNombreUsuario(); ?>" placeholder="Nombre de usuario" readonly="readonly"  maxlength="256" />
		</div>

		<div data-linea="4">
			<label for="estado">Estado</label> 
				<select name="estado" required="required">
        			<?php
                        echo $this->comboActivoInactivo($this->modeloUsuarios->getEstadoNomenclatura());
                    ?>
			</select>
		</div>

		<div data-linea="11">
		<label for="correo_usuario">Correo</label> 
				<input type="email" id="correo_usuario" name="correo_usuario" value="<?php echo $this->correoUsuario?>" placeholder="Correo electrónico del usuario" required="required" maxlength="256" />
		</div>

		<div data-linea="12">
			<label for="observacion_usuario">Observación</label> 
				<input type="text" id="observacion_usuario" name="observacion_usuario" placeholder="Observación para seguimiento de auditoria" required="required" maxlength="256" />
		</div>

		<div data-linea="13">
			<button type="submit" class="editar">Restablecer</button>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Datos de auditoria</legend>
		<div class="nota">Solo se visualizan los 15 ultimos registros de auditoria.</div>
		<?php echo $this->historialIngreso;?>
	</fieldset>
</form>

<script type="text/javascript">

	var banderaUsuario = <?php echo json_encode($this->banderaUsuario); ?>;
	var mensajeBanderaUsuario = <?php echo json_encode($this->mensajeBanderaUsuario); ?>;

	$(document).ready(function() {
		if(banderaUsuario == "NO"){    		
    		$("#detalleItem").html('<div class="mensajeInicial">Su perfil no permite actualizar información de ' + mensajeBanderaUsuario + '</div>');
        }	
		construirValidador();
		distribuirLineas();
	});

	
	$("#reseteoClave").submit(function (event) {
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		
    	var error = false;
    	if(!$.trim($("#observacion_usuario").val()) || !esCampoValido("#observacion_usuario")){
			error = true;
			$("#observacion_usuario").addClass("alertaCombo");
		}
		
		if (!error) {
			var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
			if (respuesta.estado == 'exito'){
				fn_filtrar();
			}
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>

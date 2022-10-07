<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<fieldset>
		<legend>Información de la Solicitud <?php echo $this->modeloSolicitud->getIdExpediente(); ?></legend>				

		<div data-linea="1">
			<label for="nombre_producto">Nombre del producto: </label>
			<?php echo $this->modeloSolicitud->getNombreProducto()?>
		</div>				

		<div data-linea="2">
			<label for="tipo_producto">Tipo de producto: </label>
			<?php echo $this->modeloSubtipoProducto->getNombre()?>
		</div>
		
		<div data-linea="3">
			<label for="tipo_solicitud">Tipo de solicitud: </label>
			<?php echo $this->modeloSolicitud->getTipoSolicitud()?>
		</div>

		<hr>
		
		<div data-linea="4">
			<label for="razon_social">Razón Social: </label>
			<?php echo $this->modeloOperadores->getRazonSocial(); ?>
		</div>				

		<div data-linea="5">
			<label for="identificador">RUC / RISE: </label>
			<?php echo $this->modeloOperadores->getIdentificador(); ?>
		</div>
		
		<div data-linea="6">
			<label for="provincia">Provincia: </label>
			<?php echo $this->modeloOperadores->getProvincia(); ?>
		</div>
		
		<div data-linea="7">
			<label for="telefono">Teléfono: </label>
			<?php echo $this->modeloOperadores->getTelefonoUno(); ?>
		</div>
		
		<div data-linea="8">
			<label for="correo">Correo electrónico: </label>
			<?php echo $this->modeloOperadores->getCorreo(); ?>
		</div>
		
		<hr class="tiempo">
		
		<div data-linea="9" class="tiempo">
			<label for="tiempo_subsanacion">Tiempo del usuario para remitir subsanación: </label>
			<?php echo $this->modeloSolicitud->getTiempoSubsanacion() .' días hábiles.'; ?>
		</div>	
	</fieldset >

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>DossierPecuario' data-opcion='solicitud/guardar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
	<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $this->modeloSolicitud->getIdSolicitud(); ?>" />
	<input type="hidden" id="estado_solicitud" name="estado_solicitud" value="<?php echo $this->modeloSolicitud->getEstadoSolicitud(); ?>" />
	<input type="hidden" id="fase_revision" name="fase_revision" value="AsignarTecnico" />
	<input type="hidden" id="observacion_revision" name="observacion_revision" value="El Administrador asignó la solicitud a un técnico" />
    		
    <fieldset>
    	<legend>Técnico a asignar</legend>
    
    	<div data-linea="1">
			<label for="id_provincia_revision">Provincia: </label>
			<select id="id_provincia_revision" name="id_provincia_revision" required>
                <option value="">Seleccione....</option>
                <?php echo $this->comboProvinciasEC(null)?>
            </select>
		</div>				

		<div data-linea="1">
			<label for="identificador_tecnico">Técnico: </label>
			<select id="identificador_tecnico" name="identificador_tecnico" required>
                <option>Seleccione....</option>
            </select>
		</div>    
    
    </fieldset>
	
	<div data-linea="2">
		<button type="submit" class="guardar">Guardar</button>
	</div>
</form >

<script type ="text/javascript">
var identificadorUsuario = <?php echo json_encode($this->modeloSolicitud->getIdentificador()); ?>;
var tipoSolicitud = <?php echo json_encode($this->modeloSolicitud->getTipoSolicitud()); ?>;
var combo = "<option value>Seleccione....</option>";

	$(document).ready(function() {
		if(tipoSolicitud != 'Modificacion'){
			$(".tiempo").show();
		}else{
			$(".tiempo").hide();
		}
		construirValidador();
		distribuirLineas();
	 });

	$("#id_provincia_revision").change(function () {
		$("#identificador_tecnico").val('');
    	
        if ($("#id_provincia_revision option:selected").val() !== "") {
        	fn_cargarTecnicosXProvincia();

        }
    });

	//Lista de técnicos con perfil de revisión de soliciudes RIA
	function fn_cargarTecnicosXProvincia() {
        var idProvincia = $("#id_provincia_revision option:selected").val();
        
        if (idProvincia !== '') {
        	$.post("<?php echo URL ?>DossierPecuario/AdministracionSolicitudes/comboTecnicosXProvincia/", 
			{
        		idProvincia : idProvincia
			},
            function (data) {
                $("#identificador_tecnico").html(combo+data);
            });
        }else{
        	$("#identificador_tecnico").val('');
        }
    }

	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;

		if(!$.trim($("#id_provincia_revision").val())){
        	error = true;
        	$("#id_provincia_revision").addClass("alertaCombo");
		}

		if(!$.trim($("#identificador_tecnico").val())){
        	error = true;
        	$("#identificador_tecnico").addClass("alertaCombo");
		}
		
		if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
	       		$("#estado").html("Se han guardado los datos con éxito.").addClass("exito");
	        }else{
	        	$("#estado").html(respuesta.mensaje).addClass("alerta");
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>
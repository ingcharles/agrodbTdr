<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Importaciones' data-opcion='importaciones/guardar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
	
	<fieldset>
		<legend>Datos de Importación</legend>
		
			<input type="hidden" id="id_importacion" name="id_importacion" value="<?php echo $this->modeloImportaciones->getIdImportacion(); ?>" class="validacion"/>
			<input type="hidden" id="id_area" name="id_area" value="<?php echo $this->modeloImportaciones->getIdArea(); ?>" class="validacion"/>
			<input type="hidden" id="identificador_operador" name="identificador_operador" value="<?php echo $this->modeloImportaciones->getIdentificadorOperador(); ?>" class="validacion"/>
			<input type="hidden" id="id_pais_exportacion" name="id_pais_exportacion" value="<?php echo $this->modeloImportaciones->getIdPaisExportacion(); ?>" class="validacion"/>
			<input type="hidden" id=id_vue name="id_vue" value="<?php echo $this->modeloImportaciones->getIdVue(); ?>" class="validacion"/>
			
			<div data-linea="1">
				<label>Identificador VUE: </label><?php echo $this->modeloImportaciones->getIdVue(); ?>
			</div>
			
			<div data-linea="2">
				<label>Tipo Certificado: </label><?php echo $this->modeloImportaciones->getTipoCertificado(); ?>
			</div>
			
			<div data-linea="3">
				<label>Razón social: </label><?php echo $this->modeloOperadores->getRazonSocial(); ?>
			</div>
			
			<div data-linea="4">
				<label>Representante legal: </label><?php echo $this->modeloOperadores->getRazonSocial(); ?>
			</div>
			
			<div data-linea="5">
				<label>Estado de solicitud: </label><?php echo ucfirst($this->modeloImportaciones->getEstado()); ?>
			</div>
				
	</fieldset>
	
	<fieldset>
		<legend>Datos de Importación</legend>

		<div data-linea="1">
			<label for="nombre_exportador">Nombre exportador: </label>
			<input type="text" id="nombre_exportador" name="nombre_exportador" value="<?php echo $this->modeloImportaciones->getNombreExportador(); ?>" placeholder="Se registra el nombre de exportador" class="validacion" maxlength="512" />
		</div>

		<div data-linea="2">
			<label for="direccion_exportador">Dirección exportador:</label>
			<input type="text" id="direccion_exportador" name="direccion_exportador" value="<?php echo $this->modeloImportaciones->getDireccionExportador(); ?>" placeholder="Se registra la direccion del exportador" class="validacion" maxlength="512" />
		</div>
		
		<div data-linea="3">
			<label for="nombre_embarcador">Nombre embarcador:</label>
			<input type="text" id="nombre_embarcador" name="nombre_embarcador" value="<?php echo $this->modeloImportaciones->getNombreEmbarcador(); ?>" placeholder="Se registra el nombre del embarcador" class="validacion" maxlength="256" />
		</div>

		<div data-linea="4">
			<label for="tipo_transporte">Medio transporte:</label>
			<select id="id_tipo_transporte" name="id_tipo_transporte" class="validacion">
				<option value="">Seleccionar....</option>
                <?php 
                	echo $this->comboMediosTransportePorNombre($this->modeloImportaciones->getTipoTransporte());
                ?>
            </select>
            <input type="hidden" id="tipo_transporte" name="tipo_transporte" value="<?php echo $this->modeloImportaciones->getTipoTransporte(); ?>" class="validacion"/>
		</div>

		<div data-linea="4">
			<label>País origen: </label><?php echo $this->modeloImportaciones->getPaisExportacion(); ?>
		</div>
		
		<div data-linea="5">
			<label for="id_localizacion">País embarque:</label>
			<select id="id_localizacion" name="id_localizacion" class="validacion">
				<option value="">Seleccionar....</option>
                <?php 
                	echo $this->comboPaises($this->modeloImportaciones->getIdLocalizacion());
                ?>
            </select>
			<input type="hidden" id="pais_embarque" name="pais_embarque" value="<?php echo $this->modeloImportaciones->getPaisEmbarque(); ?>"/>
		</div>
		
		<div data-linea="5">
			<label for="id_puerto_embarque">Puerto embarque:</label>
			<select id="id_puerto_embarque" name="id_puerto_embarque" class="validacion">
				<option value="">Seleccionar....</option>
                <?php 
                	echo $this->comboPuertosPorIdentificador($this->modeloImportaciones->getIdLocalizacion(), $this->modeloImportaciones->getIdPuertoEmbarque());
                ?>
            </select>
			<input type="hidden" id="puerto_embarque" name="puerto_embarque" value="<?php echo $this->modeloImportaciones->getPuertoEmbarque(); ?>" class="validacion"/>
		</div>

		<div data-linea="6">
			<label for="id_puerto_destino">Puerto destino:</label>
			<select id="id_puerto_destino" name="id_puerto_destino" class="validacion">
				<option value="">Seleccionar....</option>
                <?php 
                	echo $this->comboPuertosPorIdentificador(null, $this->modeloImportaciones->getIdPuertoDestino(), 'EC');
                ?>
            </select>
			<input type="hidden" id="puerto_destino" name="puerto_destino" value="<?php echo $this->modeloImportaciones->getPuertoDestino(); ?>" class="validacion"/>
		</div>

		<div data-linea="6">
			<label for="moneda">Moneda:</label>
			<select id="moneda" name="moneda" class="validacion">
				<option value="">Seleccionar....</option>
                <?php 
                echo $this->comboMoneda($this->modeloImportaciones->getMoneda());
                ?>
            </select>
		</div>

		<div data-linea="7">
			<label for="regimen_aduanero">Régimen aduanero:</label>
			<select id="regimen_aduanero" name="regimen_aduanero" class="validacion">
				<option value="">Seleccionar....</option>
                <?php 
                echo $this->comboRegimenAduanero($this->modeloImportaciones->getRegimenAduanero());
                ?>
            </select>
		</div>

		<div data-linea="8">
			<label for="fecha_inicio">Fecha inicio vigencia:</label> <?php echo $this->modeloImportaciones->getFechaInicio(); ?>
		</div>

		<div data-linea="8">
			<label for="fecha_vigencia">Fecha fin vigencia:</label>
			<input type="text" id="fecha_vigencia" name="fecha_vigencia" value="<?php echo $this->modeloImportaciones->getFechaVigencia(); ?>" class="validacion" readonly/>
			<input type="hidden" id="fecha_vigencia_antigua" name="fecha_vigencia_antigua" value="<?php echo $this->modeloImportaciones->getFechaVigencia(); ?>" class="validacion"/>
		</div>
		
		<div data-linea="9">
			<label for="observacion">Observación:</label>
			<input type="text" id="observacion_rectificacion" name="observacion_rectificacion" class="validacion" maxlength="512"/>
		</div>
	</fieldset>
	
	<?php echo $this->productos; ?>
	
	<div data-linea="10">
			<button type="submit" class="guardar">Guardar</button>
	</div>
	
</form >
<script type ="text/javascript">
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();

		$("#fecha_vigencia").datepicker({
	    	changeMonth: true,
	        changeYear: true,
	        dateFormat: 'yy-mm-dd',
	        minDate: $('#fecha_vigencia').val()
		});
	 });

	$("#formulario").submit(function (event) {

		event.preventDefault();
		var error = false;
		$(".alertaCombo").removeClass("alertaCombo");

		$('#formulario .validacion').each(function(i, obj) {

			if(!$.trim($(this).val())){
				error = true;
				$(this).addClass("alertaCombo");
			}

			if($(this).attr('type') == 'number'){
				if($(this).val() <= 0){
					error = true;
					$(this).addClass("alertaCombo");
				}
			}
		});

		if (!error) {
			JSON.parse(ejecutarJson($("#formulario")).responseText);
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#id_tipo_transporte").change(function(event){
		mostrarMensaje("","EXITO");
	    if($("#id_tipo_transporte").val() != ''){
	    	$("#tipo_transporte").val($("#id_tipo_transporte option:selected").text());
	    }else{
	    	mostrarMensaje("Por favor seleccione un valor","FALLO");
		}

		if($("#id_localizacion").val() != ''){
			cargarCombo();
		}
	});

	$("#id_localizacion").change(function(event){
		mostrarMensaje("","EXITO");
	    if($("#id_localizacion").val() != ''){
	    	$("#pais_embarque").val($("#id_localizacion option:selected").text());
	    	cargarCombo();
	    }else{
	    	mostrarMensaje("Por favor seleccione un valor","FALLO");
		}
	});

	$("#id_puerto_embarque").change(function(event){
		mostrarMensaje("","EXITO");
	    if($("#id_puerto_embarque").val() != ''){
	    	$("#puerto_embarque").val($("#id_puerto_embarque option:selected").attr('data-nombre'));
	    }else{
	    	mostrarMensaje("Por favor seleccione un valor","FALLO");
		}
	});

	$("#id_puerto_destino").change(function(event){
		mostrarMensaje("","EXITO");
	    if($("#id_puerto_destino").val() != ''){
	    	$("#puerto_destino").val($("#id_puerto_destino option:selected").attr('data-nombre'));
	    }else{
	    	mostrarMensaje("Por favor seleccione un valor","FALLO");
		}
	});

	function cargarCombo(){
		$.post("<?php echo URL ?>Importaciones/importaciones/buscarPuertosPorPaisMedioTransporte",
			{
				medioTransporte : $("#id_tipo_transporte option:selected").text(),
				paisEmbarque: $("#id_localizacion").val()

			}, function (data) {
				if (data.estado === 'EXITO') {
					$("#id_puerto_embarque").html(data.comboPuertosEmbarque);
					$("#id_puerto_destino").html(data.comboPuertosEcuador);
				}
			}, 'json');
	}
	
</script>

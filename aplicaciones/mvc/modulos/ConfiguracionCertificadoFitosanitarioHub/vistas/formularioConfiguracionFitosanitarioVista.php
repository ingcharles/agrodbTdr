<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ConfiguracionCertificadoFitosanitarioHub' data-opcion='ConfiguracionFitosanitario/guardar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
	<input type="hidden" id="id_configuracion_fitosanitario" name="id_configuracion_fitosanitario" value="<?php echo $this->modeloConfiguracionFitosanitario->getIdConfiguracionFitosanitario(); ?>" />
	<input type="hidden" id="usuario_responsable_fitosanitario" name="usuario_responsable_fitosanitario" value="<?php echo $_SESSION['usuario']; ?>" />
	<fieldset>
		<legend>ConfiguracionFitosanitario</legend>				

		
		<div data-linea="1">
			<label for="tipo_configuracion_fitosanitario">Tipo de configuración: </label>
			<select id="tipo_configuracion_fitosanitario" name="tipo_configuracion_fitosanitario" required="required" class="validacion">
				<option value="">Seleccione....</option>
				<option value="emision">Emisión</option>
				<!-- option value="recepcion">Recepción</option-->
			</select>
		</div>				

		<div data-linea="2">
			<label for="id_localizacion_fitosanitario">País: </label>
			<select id="id_localizacion_fitosanitario" name="id_localizacion_fitosanitario" required="required" class="validacion">
				<option value="">Seleccionar....</option>
                <?php 
                echo $this->comboPaises($this->modeloConfiguracionFitosanitario->getIdLocalizacionFitosanitario());
                ?>
            </select>
			<input type="hidden" id="nombre_pais_fitosanitario" name="nombre_pais_fitosanitario" value="<?php echo $this->modeloConfiguracionFitosanitario->getNombrePaisFitosanitario(); ?>" />			
		</div>				

		<div data-linea="3">
			<label for="plataforma_fitosanitario">Tipo de plataforma: </label>
			<select id="plataforma_fitosanitario" name="plataforma_fitosanitario" required="required" class="validacion">
				<option value="">Seleccione....</option>
				<option value="hub">HUB</option>
			</select>
		</div>				

		<div data-linea="4">
			<label for="certificado_digital_fitosanitario">Certificado digital: </label>
			<select id="certificado_digital_fitosanitario" name="certificado_digital_fitosanitario" required="required" class="validacion">
				<option value="">Seleccione....</option>
				<option value="SI">SI</option>
				<option value="NO">NO</option>
			</select>
		</div>		
				
		<div data-linea="5" id="hrSuperior"><hr/></div>
		
		<div data-linea="6" id="cargarAdjunto">
			<input type="hidden" class="ruta_certificado_digital_fitosanitario" id="ruta_certificado_digital_fitosanitario" name="ruta_certificado_digital_fitosanitario" value="<?php echo $this->modeloConfiguracionFitosanitario->getRutaCertificadoDigitalFitosanitario(); ?>" />
			<input type="file"  id="estadoCarga" class="archivo" accept="application/msword | application/pdf | image/*"/>
            <div class="estadoCarga" >En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
           	<button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo CON_FIT_DOC_ADJ.$this->rutaFecha ?>">Subir archivo</button> 
		      	
		</div>				

		<div data-linea="7" id="hrInferior"><hr/></div>

		<div data-linea="8">
			<label for="encriptacion_fitosanitario">Tipo de encriptación: </label>
			<label for="encriptacion_fitosanitario_aes">AES </label>
			<input type="checkbox" id="encriptacion_fitosanitario_aes" name="encriptacion_fitosanitario[]" value="aes" />
			<label for="encriptacion_fitosanitario_rca">RCA </label>
			<input type="checkbox" id="encriptacion_fitosanitario_rca" name="encriptacion_fitosanitario[]" value="rca" />
		</div>				

		<div data-linea="11">
			<button type="submit" class="guardar">Guardar</button>
		</div>
	</fieldset >
</form >
<script type ="text/javascript">
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();

		cargarValorDefecto("id_localizacion_fitosanitario","<?php echo $this->modeloConfiguracionFitosanitario->getIdLocalizacionFitosanitario();?>");
		cargarValorDefecto("plataforma_fitosanitario","<?php echo $this->modeloConfiguracionFitosanitario->getPlataformaFitosanitario();?>");
		cargarValorDefecto("certificado_digital_fitosanitario","<?php echo $this->modeloConfiguracionFitosanitario->getCertificadoDigitalFitosanitario();?>");
		cargarValorDefecto("tipo_configuracion_fitosanitario","<?php echo $this->modeloConfiguracionFitosanitario->getTipoConfiguracionFitosanitario();?>");

		valorCertificadoDigital = $("#certificado_digital_fitosanitario").val();
				
		if(valorCertificadoDigital == "SI"){
			visualizarAdjunto("ver");
		}else{
			visualizarAdjunto("ocultar");
		}

		arrayTipoEncriptacion = <?php echo json_encode($this->arrayTipoEncriptacion); ?>;

		$.each(arrayTipoEncriptacion, function(index, value){
			switch (value){
			case "aes":
				$("#encriptacion_fitosanitario_aes").prop("checked",true);
			break;
			case "rca":
				$("#encriptacion_fitosanitario_rca").prop("checked",true);
			break;
			}			
		});
	
	 });

	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		$(".alertaCombo").removeClass("alertaCombo");

		if($("#certificado_digital_fitosanitario").val() == "SI"){
			if ($("#ruta_certificado_digital_fitosanitario").val() == ""){
				error = true;
				$("#estadoCarga").addClass("alertaCombo");
			}
		}

		if ($('input[type=checkbox]:checked').length === 0) {
			error = true;
			$("#encriptacion_fitosanitario_aes").addClass("alertaCombo");
			$("#encriptacion_fitosanitario_rca").addClass("alertaCombo");
		}
				
		if (!error) {
			JSON.parse(ejecutarJson($("#formulario")).responseText);
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#id_localizacion_fitosanitario").change(function(event){
		mostrarMensaje("","EXITO");
	    if($("#id_localizacion_fitosanitario").val() != ''){
	    	$("#nombre_pais_fitosanitario").val($("#id_localizacion_fitosanitario option:selected").text());
	    }else{
	    	mostrarMensaje("Por favor seleccione un valor","FALLO");
		}
	});

	$("#certificado_digital_fitosanitario").change(function(event){
		valorCertificadoDigital = $("#certificado_digital_fitosanitario").val();
		if(valorCertificadoDigital == "SI"){
			visualizarAdjunto("ver");
		}else{
			visualizarAdjunto("ocultar");
		}		
	});	

	$("button.subirArchivo").click(function (event) {
	  	  
	  	var boton = $(this);
		var nombre_archivo = "<?php echo 'wsfitosantario_' . (md5(time())); ?>";
	    var archivo = boton.parent().find(".archivo");
	    var rutaArchivo = boton.parent().find(".ruta_certificado_digital_fitosanitario");
	    var extension = archivo.val().split('.');
	    var estado = boton.parent().find(".estadoCarga");
	
	    if (extension[extension.length - 1].toUpperCase() == 'PDF') {
	
	        subirArchivo(
	            archivo
	            , nombre_archivo
	            , boton.attr("data-rutaCarga")
	            , rutaArchivo
	            , new carga(estado, archivo, boton)
	            
	        );
	    } else {
	        estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
	        archivo.val("0");
	        }
	} );

	function visualizarAdjunto(valor){		
		switch(valor){
    		case "ver":
    			$("#cargarAdjunto").show();
    			$("#hrInferior").show();
    		break;
    		case "ocultar":
    			$("#cargarAdjunto").hide();
    			$("#hrInferior").hide();
    		break;
		}
	}
	
</script>

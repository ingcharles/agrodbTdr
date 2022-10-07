<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formularioRegistro' 	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>PasaporteEquino' 	data-opcion='Miembros/guardar' data-destino="detalleItem" 	data-accionEnExito="ACTUALIZAR" method="post">

	<fieldset>
		<legend>Datos Generales Asociación</legend>

		<div data-linea="1">
			<label>Nombre del Sitio: </label> <?php echo $this->asociacion->current()->nombre_lugar; ?>
		</div>

		<div data-linea="2">
			<label>Tipo de Área: </label> Sanidad Animal
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Datos del Asociado</legend>

		<div data-linea="3">
			<label>Identificador Asociado: </label> 
			<input type="text" id="identificador_operador" name="identificador_operador" required maxlength="13" />
		</div>
		
		<hr class="camposBuscador"/>

		<div data-linea="4" class="camposBuscador">
			<label>Provincia: </label> 
			<select id="id_provincia" name="id_provincia" required > 
				<option value="">Seleccionar....</option>
            </select>
		</div>

		<div data-linea="4" class="camposBuscador">
			<label>Predio: </label> 
			<select id="id_predio" name="id_predio" required > 
				<option value="">Seleccionar....</option>
            </select>	
		</div>		
	</fieldset>
	
	<fieldset class="resultado">
		<legend>Información del Predio</legend>

		<div data-linea="5">
			<label>Nombre del Predio: </label> 
			<input type="text" id="nombrePredio" name="nombrePredio" required readonly="readonly" />
			<input type="hidden" id="id_catastro_predio_equidos" name="id_catastro_predio_equidos" required readonly="readonly" />
		</div>
		
	</fieldset>
	
	<fieldset class="resultado">
		<legend>Ubicación y Datos Generales</legend>

		<div data-linea="6">
			<label>Provincia: </label> 
			<input type="text" id="provincia" name="provincia" required readonly="readonly" />
		</div>

		<div data-linea="6">
			<label>Cantón: </label> 
			<input type="text" id="canton" name="canton" required readonly="readonly" />
		</div>	
		
		<div data-linea="7">
			<label>Parroquia: </label> 
			<input type="text" id="parroquia" name="parroquia" required readonly="readonly" />
		</div>

		<div data-linea="8">
			<label>Dirección: </label> 
			<input type="text" id="direccion" name="direccion" required readonly="readonly" />
		</div>	
	</fieldset>
	
	<fieldset class="resultado">
		<legend>Información del Propietario</legend>

		<div data-linea="9">
			<label>Nombre: </label> 
			<input type="text" id="nombrePropietario" name="nombre_miembro" required readonly="readonly" />
		</div>

		<div data-linea="10">
			<label>Cédula: </label> 
			<input type="text" id="cedula" name="identificador_miembro" required readonly="readonly" />
		</div>	
		
		<div data-linea="10">
			<label>Teléfono: </label> 
			<input type="text" id="telefono" name="telefono" required readonly="readonly" />
		</div>

		<div data-linea="11">
			<label>Correo: </label> 
			<input type="text" id="correo" name="correo" required readonly="readonly" />
		</div>	
	</fieldset>
	
	<div data-linea="13" class="resultado">
		<button type="submit" class="guardar">Guardar</button>
	</div>
</form>

<form id='formularioModificacion' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>PasaporteEquino' data-opcion='Miembros/guardar' data-destino="detalleItem" 	data-accionEnExito="ACTUALIZAR" method="post">
	<input type="hidden" id="id_organizacion_ecuestre" name="id_organizacion_ecuestre" value="<?php echo $this->modeloMiembros->getIdOrganizacionEcuestre(); ?>" />
	<input type="hidden" id="id_miembro" name="id_miembro" value="<?php echo $this->modeloMiembros->getIdMiembro(); ?>" />
	<input type="hidden" id="identificador_miembro" name="identificador_miembro" value="<?php echo $this->modeloMiembros->getIdentificadorMiembro(); ?>" />
	<input type="hidden" id="id_catastro_predio_equidos" name="id_catastro_predio_equidos" value="<?php echo $this->modeloMiembros->getIdCatastroPredioEquidos(); ?>" />
	
	<fieldset>
		<legend>Datos Generales Asociación</legend>

		<div data-linea="1">
			<label>Nombre de la Asociación: </label> <?php echo $this->asociacion->current()->nombre_lugar; ?>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Datos del Asociado</legend>

		<div data-linea="2">
			<label>Nombre Asociado: </label> <?php echo $this->modeloMiembros->getNombreMiembro(); ?>
		</div>
		
		<div data-linea="3">
			<label>Nombre del Predio: </label> <?php echo ($this->formulario == 'editar' ? $this->miembroAsociacion->current()->nombre_predio:''); ?>
		</div>

	</fieldset>
	
	<fieldset>
		<legend>Ubicación y Datos Generales</legend>

		<div data-linea="4">
			<label>Provincia: </label> <?php echo ($this->formulario == 'editar' ? $this->miembroAsociacion->current()->provincia:''); ?>
		</div>

		<div data-linea="4">
			<label>Cantón: </label> <?php echo ($this->formulario == 'editar' ? $this->miembroAsociacion->current()->canton:''); ?>
		</div>	
		
		<div data-linea="6">
			<label>Parroquia: </label> <?php echo ($this->formulario == 'editar' ? $this->miembroAsociacion->current()->parroquia:''); ?>
		</div>

		<div data-linea="7">
			<label>Dirección: </label> <?php echo ($this->formulario == 'editar' ? $this->miembroAsociacion->current()->direccion_predio:''); ?>
		</div>	
	</fieldset>
	
	<fieldset>
		<legend>Estado del Asociado</legend>

		<div data-linea="8">
			<label>Estado actual: </label> <?php echo $this->modeloMiembros->getEstadoMiembro(); ?>
		</div>
		
		<div data-linea="8">
			<label>Fecha de modificación: </label> <?php echo ($this->modeloMiembros->getFechaModificacion()!=''?date('Y-m-d',strtotime($this->modeloMiembros->getFechaModificacion())):'No se ha modificado'); ?>
		</div>
		
		<div data-linea="9">
			<label for="ruta_archivo">Documento anexo: </label>
    			<?php echo 
			 ($this->modeloMiembros->getRutaArchivo() != '' ? '<a href="'.URL_GUIA_PROYECTO . '/' .$this->modeloMiembros->getRutaArchivo().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click para descargar documento</a>' : 'No hay un archivo adjunto'); ?>
		
		</div>

	</fieldset>
	
	<fieldset>
		<legend>Requisitos de Modificación de estado del Asociado</legend>

		<div data-linea="10">
			<label>Estado: </label>
			<select id="estado_miembro" name="estado_miembro" >
                <option value="">Seleccionar....</option>
                <?php
                    echo $this->comboEstadosMiembro();
                ?>
            </select>
		</div>
		
		<div data-linea="11">
			<label>Motivo: </label>
			<input type="text" id="motivo_cambio" name="motivo_cambio" /> 
		</div>
		
		<div data-linea="12">
			<label for="ruta_archivo">Documento anexo: </label>
			<input type="file" id="archivoSubsanacion" class="archivoSubsanacion" accept="application/pdf" /> 
			<input type="hidden" class="rutaArchivoSubsanacion" name="ruta_archivo" id="ruta_archivo" />
				
    		<div class="estadoCargaSubsanacion">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
    		<button type="button" class="subirArchivoMotivo adjunto" data-rutaCarga="<?php echo PAS_EQUI_URL . $this->modeloMiembros->getIdentificadorMiembro();?>">Subir archivo</button>
    	</div>

	</fieldset>
	
	<div data-linea="8">
		<button type="submit" class="guardar">Guardar</button>
	</div>
</form>

<script type="text/javascript">
var bandera = <?php echo json_encode($this->formulario); ?>;
var combo = "<option>Seleccione....</option>";

	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		
		$(".camposBuscador").hide();
		$(".resultado").hide();

		if(bandera == 'nuevo'){
			$("#formularioRegistro").show();
			$("#formularioModificacion").hide();
		}else{
			$("#formularioModificacion").show();
			$("#formularioRegistro").hide();
		}

		
	 });

	$("#identificador_operador").change(function () {
		$("#id_provincia").html(combo);
		$("#id_predio").html(combo);
		$(".camposBuscador").hide();
		$(".resultado").hide();
		fn_limpiarDatos();
		
		if ($("#identificador_operador").val() != '' ) {
			fn_buscarProvinciaPredios();	
			$(".camposBuscador").show();		
        }else{
			alert('Debe ingresar un número de cédula o RUC válido');
			$("#id_provincia").html(combo);
			$("#id_predio").html(combo);
			fn_limpiarDatos();
			$(".camposBuscador").hide();
        }
    });

	//Función para mostrar las provincias donde el operador tiene predios en el módulo de programas de control oficial
    function fn_buscarProvinciaPredios() {
    	var identificador = $("#identificador_operador").val();
        
        if (identificador != "" ){
        	$.post("<?php echo URL ?>PasaporteEquino/Miembros/comboProvinciasXOperador",
               {
                identificador : identificador
               }, function (data) {
            	   $("#id_provincia").html(data);
            });
        }else{
            $("#id_provincia").html(combo);
        	
        	if(!$.trim($("#identificador_operador").val())){
    			$("#identificador_operador").addClass("alertaCombo");
    		}

            $("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
    	}     
    }

    $("#id_provincia").change(function () {
		$("#id_predio").html(combo);
		fn_limpiarDatos();
		
		if (($("#identificador_operador").val() != '' ) && $("#id_provincia option:selected").val() != '' ) {
			fn_buscarPrediosXProvincia();
        }else{
			alert('Debe ingresar un número de cédula o RUC válido y seleccionar una provincia');
			$("#id_predio").html(combo);
			fn_limpiarDatos();
			$(".resultado").hide();
        }
    });

  	//Función para mostrar los predios por provincia del operador en el módulo de programas de control oficial
    function fn_buscarPrediosXProvincia() {
    	var identificador = $("#identificador_operador").val();
    	var provincia = $("#id_provincia option:selected").val();
        
        if (identificador != "" && provincia != ""){
        	$.post("<?php echo URL ?>PasaporteEquino/Miembros/comboPrediosXProvincia",
               {
        		identificador : identificador,
        		provincia : provincia
               }, function (data) {
            	   $("#id_predio").html(data);
            });
        }else{
            $("#id_predio").html(combo);
        	
        	if(!$.trim($("#identificador_operador").val())){
    			$("#identificador_operador").addClass("alertaCombo");
    		}

        	if(!$.trim($("#id_provincia").val())){
    			$("#id_provincia").addClass("alertaCombo");
    		}

            $("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
    	}     
    }

    $("#id_predio").change(function () {
		fn_limpiarDatos();
		$(".resultado").hide();
		
		if (($("#identificador_operador").val() != '' ) && ($("#id_provincia option:selected").val() != '') && ($("#id_predio option:selected").val() != '') ) {
			fn_buscarOperadorXPredio();
        }else{
			alert('Debe ingresar un número de cédula o RUC válido, seleccionar una provincia y un predio');
			fn_limpiarDatos();
			$(".resultado").hide();
        }
    });

  	//Función para mostrar los datos del operador en el módulo de programas de control oficial de acuerdo al predio
    function fn_buscarOperadorXPredio() {
    	var identificador = $("#identificador_operador").val();
    	var provincia = $("#id_provincia option:selected").val();
    	var idPredio = $("#id_predio option:selected").val();
        
        if (identificador != "" && provincia != "" && idPredio != ""){
        	mostrarMensaje("","EXITO");
        	$.post("<?php echo URL ?>PasaporteEquino/Miembros/buscarOperadorXPredio",
                    {
                     identificador : identificador,
                     provincia : provincia,
                     predio : idPredio
                    }, function (data) {
     				if(data.validacion == "Fallo"){
     	        		mostrarMensaje(data.mensaje,"FALLO");  
     	        		fn_limpiarDatos();	
     	        		$(".resultado").hide();	
     				}else{
     					fn_cargarDatosOperador(data);
     					$(".resultado").show();
     				}
                 }, 'json');
        }else{
        	fn_limpiarDatos();
        	
        	if(!$.trim($("#identificador_operador").val())){
    			$("#identificador_operador").addClass("alertaCombo");
    		}

        	if(!$.trim($("#id_provincia").val())){
    			$("#id_provincia").addClass("alertaCombo");
    		}

        	if(!$.trim($("#id_predio").val())){
    			$("#id_predio").addClass("alertaCombo");
    		}

            $("#estado").html("Por favor ingrese la información requerida en el módulo de Predio de Équidos para continuar").addClass('alerta');
    	} 
    }

  	//Función para mostrar los datos obtenidos del operador/predio
    function fn_cargarDatosOperador(data) {
    	$("#id_catastro_predio_equidos").val($("#id_predio option:selected").val());
    	$("#nombrePredio").val(data.nombrePredio);
    	$("#provincia").val(data.provincia);
    	$("#canton").val(data.canton);
    	$("#parroquia").val(data.parroquia);
    	$("#direccion").val(data.direccion);
    	$("#nombrePropietario").val(data.nombrePropietario);
    	$("#cedula").val(data.cedula);
    	$("#telefono").val(data.telefono);   	
    	$("#correo").val(data.correo);		
    } 
    
    function fn_limpiarDatos() {
    	$("#id_catastro_predio_equidos").val('');
    	$("#nombrePredio").val('');
    	$("#provincia").val('');
    	$("#canton").val('');
    	$("#parroquia").val('');
    	$("#direccion").val('');
    	$("#nombrePropietario").val('');
    	$("#cedula").val('');
    	$("#telefono").val('');
    	$("#correo").val('');
    } 

	$("#formularioRegistro").submit(function (event) {
		$(".alertaCombo").removeClass("alertaCombo");
		event.preventDefault();
		var error = false;
		
		if (!error) {			
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);

	       	if (respuesta.estado == 'exito'){
	       		$("#estado").html("Se han guardado los datos con éxito.").addClass("exito");
	        }else{
	        	$("#botonEnviar").removeAttr("disabled");
	        	$("#estado").html(respuesta.mensaje).addClass("alerta");
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	///////////////////////////////////////////////////////////////////////////////////
	
	$('button.subirArchivoMotivo').click(function (event) {
        var idExpediente = <?php echo json_encode($this->modeloMiembros->getIdentificadorMiembro());?>;
    	var nombre_archivo = "MotivoModificacionMiembro_"+idExpediente;
        var boton = $(this);
        var archivo = boton.parent().find(".archivoSubsanacion");
        var rutaArchivo = boton.parent().find(".rutaArchivoSubsanacion");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCargaSubsanacion");

        if (extension[extension.length - 1].toUpperCase() == 'PDF' || extension[extension.length - 1].toUpperCase() == 'PDF') {

            subirArchivo(
                archivo
                , nombre_archivo
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
            );

            $('#ruta_archivo').val("<?php echo PAS_EQUI_URL.$this->modeloMiembros->getIdentificadorMiembro(); ?>/"+nombre_archivo+".PDF");
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
    });

	$("#formularioModificacion").submit(function (event) {
		$(".alertaCombo").removeClass("alertaCombo");
		event.preventDefault();
		var error = false;

		if(!$.trim($("#estado_miembro").val())){
        	error = true;
        	$("#estado_miembro").addClass("alertaCombo");
		}

		if(!$.trim($("#motivo_cambio").val())){
        	error = true;
        	$("#motivo_cambio").addClass("alertaCombo");
		}

		if(!$.trim($("#ruta_archivo").val())){
        	error = true;
        	$("#archivoSubsanacion").addClass("alertaCombo");
		}
		
		if (!error) {			
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);

	       	if (respuesta.estado == 'exito'){
	       		$("#estado").html("Se han guardado los datos con éxito.").addClass("exito");
	        }else{
	        	$("#botonEnviar").removeAttr("disabled");
	        	$("#estado").html(respuesta.mensaje).addClass("alerta");
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	
	
</script>
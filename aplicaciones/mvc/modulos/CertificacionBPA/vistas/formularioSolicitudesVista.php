<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>CertificacionBPA' data-opcion='solicitudes/guardar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
	<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $this->modeloSolicitudes->getIdSolicitud(); ?>" />
	<input type="hidden" id="array_sitios_bpa" name="array_sitios_bpa" value="" readonly="readonly" />
			
	<fieldset>
		<legend>Datos Generales</legend>				

		<div data-linea="1">
			<label for="es_asociacion">Opción de Certificación: </label>
			<select id="es_asociacion" name="es_asociacion" required >
                <?php
                    echo $this->comboIndividualAsociacion(null);
                ?>
            </select>
		</div>				

		<div data-linea="2">
			<label for="tipo_solicitud">Tipo de Solicitud: </label>
			<select id="tipo_solicitud" name="tipo_solicitud" required disabled>
				<option value="">Seleccionar....</option>
                <?php
                    echo $this->comboTipoSolicitud();
                ?>
            </select>
		</div>				

		<div data-linea="3">
			<label for="tipo_explotacion">Tipo de Explotación: </label>
			<select id="tipo_explotacion" name="tipo_explotacion" required disabled>
				<option value="">Seleccionar....</option>
                <?php
                    echo $this->comboTipoExplotacion();
                ?>
            </select>
		</div>	
		
		<div data-linea="4">
			<label for="tipo_producto">Tipo de Producto: </label>
			<select id="tipo_producto" name="tipo_producto" required disabled>
				<option value="">Seleccionar....</option>
            </select>
		</div>
		
		<div data-linea="4">
			<label for="subtipo_producto">Subtipo de Producto: </label>
			<select id="subtipo_producto" name="subtipo_producto" required disabled>
				<option value="">Seleccionar....</option>
            </select>
		</div>	
		
		<div data-linea="5">
			<label for="producto">Producto: </label>
			<select id="producto" name="producto" required disabled>
				<option value="">Seleccionar....</option>
            </select>
		</div>	
		
		<div data-linea="6">
			<label for="sitio">Sitios y Áreas: </label>
			<select id="sitio" name="sitio" required disabled>
				<option value="">Seleccionar....</option>
            </select>
		</div>	

		<div data-linea="7" class="num_animales">
			<label for="num_animales">Nº Animales: </label>
			<input type="number" id="num_animales" name="num_animales" step=1 min=1 value="<?php echo $this->modeloSolicitudes->getNumAnimales(); ?>" />
		</div>			

		<div data-linea="8" class="num_animales">
			<p class="nota">El campo Nº de animales solo aplica para porcinos, vacas, aves y cuyes</p>
		</div>		

		<div data-linea="9">
    		<button type="button" class="mas" id="btnAgregarSitios">Agregar</button>
    	</div>
    	
	</fieldset >	
	
	<fieldset>
		<legend>Sitios y Áreas Agregados</legend>
		<div data-linea="10">
			<table id="tbItems" style="width:100%">
				<thead>
					<tr>
						<th >Nº</th>
						<th >Sitio</th>
                        <th >Área</th>
                        <th >Producto</th>
                        <th >Operación</th>
                        <th >Hs.</th>
                        <th >Superficie certificada</th>
                        <th >Estado</th>
                        <th ></th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
		
	</fieldset>		

	<fieldset>
		<legend>Datos del Operador</legend>
		
		<div data-linea="8">
			<label for="identificador_operador">Identificador: </label>
			<input type="text" id="identificador_operador" name="identificador_operador" value="<?php echo $this->modeloSolicitudes->getIdentificadorOperador(); ?>"
				readonly="readonly" required maxlength="13" />
		</div>

		<div data-linea="9">
			<label for="razon_social">Nombre/Razón Social: </label>
			<input type="text" id="razon_social" name="razon_social" value="<?php echo $this->modeloSolicitudes->getRazonSocial(); ?>"
				readonly="readonly" required maxlength="512" />
		</div>				

		<div data-linea="10">
			<label for="identificador_representante_legal">Identificación Representante: </label>
			<input type="text" id="identificador_representante_legal" name="identificador_representante_legal" value="<?php echo $this->modeloSolicitudes->getIdentificadorRepresentanteLegal(); ?>"
				readonly="readonly" required required maxlength="13" data-er="^[0-9]+$" />
		</div>				

		<div data-linea="11">
			<label for="nombre_representante_legal">Representante Legal: </label>
			<input type="text" id="nombre_representante_legal" name="nombre_representante_legal" value="<?php echo $this->modeloSolicitudes->getNombreRepresentanteLegal(); ?>"
				readonly="readonly" required maxlength="128" />
		</div>				

		<div data-linea="12">
			<label for="correo">E-mail: </label>
			<input type="text" id="correo" name="correo" value="<?php echo $this->modeloSolicitudes->getCorreo(); ?>"
				readonly="readonly" required maxlength="128" />
		</div>				

		<div data-linea="13">
			<label for="telefono">Teléfono: </label>
			<input type="text" id="telefono" name="telefono" value="<?php echo $this->modeloSolicitudes->getTelefono(); ?>"
				readonly="readonly" required maxlength="16" />
		</div>				

		<div data-linea="14">
			<label for="direccion">Dirección: </label>
			<input type="text" id="direccion" name="direccion" value="<?php echo $this->modeloSolicitudes->getDireccion(); ?>"
				readonly="readonly" required maxlength="512" />
		</div>		
		
		<div data-linea="15">
			<label for="direccion">Provincia: </label>
			<input type="text" id="provincia" name="provincia" value="<?php echo $this->modeloSolicitudes->getProvinciaUnidadProduccion(); ?>"
				readonly="readonly" required maxlength="512" />
		</div>	
		
		<div data-linea="16">
			<label for="direccion">Cantón: </label>
			<input type="text" id="canton" name="canton" value="<?php echo $this->modeloSolicitudes->getCantonUnidadProduccion(); ?>"
				readonly="readonly" required maxlength="512" />
		</div>	
		
		<div data-linea="17">
			<label for="direccion">Parroquia: </label>
			<input type="text" id="parroquia" name="parroquia" value="<?php echo $this->modeloSolicitudes->getParroquiaUnidadProduccion(); ?>"
				readonly="readonly" required maxlength="512" />
		</div>			

	</fieldset>
	
	<fieldset>
		<legend>Datos del Responsable Técnico de la Unidad de Producción Agrícola y/o Pecuaria</legend>
		
		<div data-linea="15">
			<label for="identificador_representante_tecnico">Identificación: </label>
			<input type="text" id="identificador_representante_tecnico" name="identificador_representante_tecnico" value="<?php echo $this->modeloSolicitudes->getIdentificadorRepresentanteTecnico(); ?>"
				readonly="readonly" required required maxlength="13" data-er="^[0-9]+$" />
		</div>				

		<div data-linea="16">
			<label for="nombre_representante_tecnico">Nombres: </label>
			<input type="text" id="nombre_representante_tecnico" name="nombre_representante_tecnico" value="<?php echo $this->modeloSolicitudes->getNombreRepresentanteTecnico(); ?>"
				readonly="readonly" required maxlength="128" />
		</div>				

		<div data-linea="17">
			<label for="correo_representante_tecnico">E-mail: </label>
			<input type="text" id="correo_representante_tecnico" name="correo_representante_tecnico" value="<?php echo $this->modeloSolicitudes->getCorreoRepresentanteTecnico(); ?>"
				readonly="readonly" required maxlength="128" data-er="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$"/>
		</div>				

		<div data-linea="18">
			<label for="telefono_representante_tecnico">Teléfono: </label>
			<input type="text" id="telefono_representante_tecnico" name="telefono_representante_tecnico" value="<?php echo $this->modeloSolicitudes->getTelefonoRepresentanteTecnico(); ?>"
				readonly="readonly" required maxlength="16" data-er="^\([0-9]{2}\) [0-9]{4}-[0-9]{4}" data-inputmask="'mask': '(99) 9999-9999'"/>
		</div>				

	</fieldset>
	
	<fieldset>
		<legend>Datos de la Unidad de Producción</legend>
		
		<div data-linea="19">
			<label for="id_sitio_unidad_produccion">Nombre del Sitio: </label>
			<select id="id_sitio_unidad_produccion" name="id_sitio_unidad_produccion" required>
				<option value="">Seleccionar....</option>
            </select>
            
			<input type="hidden" id="sitio_unidad_produccion" name="sitio_unidad_produccion" value="<?php echo $this->modeloSolicitudes->getSitioUnidadProduccion(); ?>" readonly="readonly"/>
		</div>				

		<div data-linea="20">
			<label for="provincia_unidad_produccion">Provincia: </label>
			<input type="text" id="provincia_unidad_produccion" name="provincia_unidad_produccion" value="<?php echo $this->modeloSolicitudes->getProvinciaUnidadProduccion(); ?>" readonly="readonly"/>
		</div>				

		<div data-linea="20">
			<label for="canton_unidad_produccion">Cantón: </label>
			<input type="text" id="canton_unidad_produccion" name="canton_unidad_produccion" value="<?php echo $this->modeloSolicitudes->getCantonUnidadProduccion(); ?>" readonly="readonly"/>
		</div>				

		<div data-linea="21">
			<label for="parroquia_unidad_produccion">Parroquia: </label>
			<input type="text" id="parroquia_unidad_produccion" name="parroquia_unidad_produccion" value="<?php echo $this->modeloSolicitudes->getParroquiaUnidadProduccion(); ?>" readonly="readonly"/>
		</div>				

		<div data-linea="22">
			<label for="direccion_unidad_produccion">Dirección: </label>
			<input type="text" id="direccion_unidad_produccion" name="direccion_unidad_produccion" value="<?php echo $this->modeloSolicitudes->getDireccionUnidadProduccion(); ?>" required maxlength="512" readonly="readonly"/>
		</div>				

		<hr />
		
		<div data-linea="23">
			<b>Coordenadas </b>
		</div>
		
		<div data-linea="24">
			<label for="utm_x">UTM (X): </label>
			<input type="text" id="utm_x" name="utm_x" value="<?php echo $this->modeloSolicitudes->getUtmX(); ?>" required maxlength="32" readonly="readonly"/>
		</div>				

		<div data-linea="24">
			<label for="utm_y">UTM (Y): </label>
			<input type="text" id="utm_y" name="utm_y" value="<?php echo $this->modeloSolicitudes->getUtmY(); ?>" required maxlength="32" readonly="readonly"/>
		</div>				

		<div data-linea="24">
			<label for="altitud">Altitud: </label>
			<input type="text" id="altitud" name="altitud" value="<?php echo $this->modeloSolicitudes->getAltitud(); ?>" required maxlength="4" readonly="readonly"/>
		</div>				

	</fieldset>
	
	<fieldset>
		<legend>Alcance</legend>
		
		<div data-linea="31">
			<label for="tipo_certificado">Tipo de Certificado: </label>
			<select id="tipo_certificado" name="tipo_certificado" required >
				<option value="">Seleccionar....</option>
                <?php
                    echo $this->comboTipoCertificado();
                ?>
            </select>
		</div>				

		<div data-linea="31">
			<label for="num_trabajadores">Nº de Trabajadores: </label>
			<input type="number" id="num_trabajadores" name="num_trabajadores" value="<?php echo $this->modeloSolicitudes->getNumTrabajadores(); ?>"
				required min="1" step="1" />
		</div>				

		<div data-linea="32" class="equivalente">
			<label for="codigo_equivalente">Código Equivalente: </label>
			<input type="text" id="codigo_equivalente" name="codigo_equivalente" value="<?php echo $this->modeloSolicitudes->getCodigoEquivalente(); ?>"
				placeholder="Número de certificado externo que se va a homologar" maxlength="32" />
		</div>				

		<div data-linea="33" class="equivalente">
			<label for="fecha_inicio_equivalente">Fecha de Inicio: </label>
			<input type="date" id="fecha_inicio_equivalente" name="fecha_inicio_equivalente" value="<?php echo $this->modeloSolicitudes->getFechaInicioEquivalente(); ?>" />
		</div>				

		<div data-linea="33" class="equivalente">
			<label for="fecha_fin_equivalente">Fecha de Fin: </label>
			<input type="date" id="fecha_fin_equivalente" name="fecha_fin_equivalente" value="<?php echo $this->modeloSolicitudes->getFechaFinEquivalente(); ?>" />
		</div>				

		<div data-linea="34">
			<label for="observacion_alcance">Observación: </label>
			<input type="text" id="observacion_alcance" name="observacion_alcance" value="<?php echo $this->modeloSolicitudes->getObservacionAlcance(); ?>"
				required="required" maxlength="1024" />
		</div>				

		<div data-linea="35" class="equivalente">
			<label for="ruta">Certificado: </label> <?php //echo ($this->modeloEventos->getRutaImagen()==''? '<span class="alerta">No ha cargado ninguna imagen</span>':'<a href="'.$this->modeloEventos->getRutaImagen().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click aquí para ver la imagen</a>')?>

			<input type="file" id="informe" class="archivo" accept="application/pdf" /> 
			<input type="hidden" class="rutaArchivo" name="ruta_certificado_equivalente" id="ruta_certificado_equivalente" value="0" />
				
    		<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
    		<button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo CERT_BPA_URL_CERT_EQ . $this->rutaFecha;?>">Subir archivo</button>
    	</div>
		
		<hr />
		
		<div data-linea="36">
			<b>Descripción de la población / producto </b>
		</div>
		
		<div data-linea="37">
			<label for="num_hectareas">Nº Hectáreas a certificar: </label>
			<input type="text" id="num_hectareas" name="num_hectareas" value="<?php echo $this->modeloSolicitudes->getNumHectareas(); ?>"
				required readonly="readonly" />
		</div>		
		
		<div data-linea="38" class="nacional">
			<label for="anexo">Documentos de Apoyo: </label> <?php //echo ($this->modeloEventos->getRutaImagen()==''? '<span class="alerta">No ha cargado ninguna imagen</span>':'<a href="'.$this->modeloEventos->getRutaImagen().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click aquí para ver la imagen</a>')?>

			<input type="file" id="anexo" class="archivo" accept="application/pdf" /> 
			<input type="hidden" class="rutaArchivoAnexo" name="anexo_nacional" id="anexo_nacional" value="0" />
				
    		<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
    		<button type="button" class="subirArchivoAnexo adjunto" data-rutaCarga="<?php echo CERT_BPA_URL_ANEX_NAC . $this->rutaFecha;?>">Subir archivo</button>
    	</div>		
	
	</fieldset>
	
	<fieldset>
		<legend>Tipo de Auditoría Solicitada</legend>
		<div data-linea="39" id="contenedorAuditoria">
			<?php echo $this->radioTiposAuditoria(); ?>
		</div>
	</fieldset >
	
	<div data-linea="40">
		<button type="submit" class="guardar">Guardar</button>
	</div>
</form >

<script type ="text/javascript">
var identificadorUsuario = <?php echo json_encode($_SESSION['usuario']); ?>;
var combo = "<option>Seleccione....</option>";

	$(document).ready(function() {
		construirValidador();
		distribuirLineas();

		$(".num_animales").hide();
		//$(".Expirado").hide();
		$(".subsanacion").hide();
	 });

	$("#es_asociacion").change(function () {
		$("#tipo_producto").html(combo);    	
    	$("#subtipo_producto").html(combo);
    	$("#producto").html(combo);
    	$("#sitio").html(combo);
    	
    	if ($(this).val !== "") {
    		fn_buscarDatosOperador();
    		$('#es_asociacion option:not(:selected)').attr('disabled',true);
        }
    });

	$("#tipo_solicitud").change(function () {
		$("#tipo_producto").html(combo);    	
    	$("#subtipo_producto").html(combo);
    	$("#producto").html(combo);
    	$("#sitio").html(combo);
    	
        if ($(this).val !== "") {
        	fn_cargarTipoExplotacion();
        	fn_cargarTipoCertificado();
        	fn_bloquearDesbloquearCamposAlcance();
        	fn_cargarAuditoriasEquivalentes();
        	$('#tipo_solicitud option:not(:selected)').attr('disabled',true);
        }
    });

	$("#tipo_explotacion").change(function () {
		$("#tipo_producto").html(combo);
    	$("#subtipo_producto").html(combo);
    	$("#producto").html(combo);
    	$("#sitio").html(combo);
    	
        if ($(this).val !== "") {
        	fn_cargarTipoProductos();
        	$('#tipo_explotacion option:not(:selected)').attr('disabled',true);

        	if($("#tipo_explotacion option:selected").val() === 'SA'){
        		$(".num_animales").show();
        	}else{
        		$(".num_animales").hide();
        	}
        }
    });

	$("#tipo_producto").change(function () {
		$("#subtipo_producto").html(combo);
    	$("#producto").html(combo);
    	$("#sitio").html(combo);
    	
        if ($(this).val !== "") {
        	fn_cargarSubtipoProductos();
        }
    });

	$("#subtipo_producto").change(function () {
    	$("#producto").html(combo);
    	$("#sitio").html(combo);
    	
        if ($(this).val !== "") {
        	fn_cargarProductos();
        }
    });

	$("#producto").change(function () {
    	$("#sitio").html(combo);
    	
        if ($(this).val !== "") {
        	fn_cargarSitiosAreas();
        }
    });

    $("#id_sitio_unidad_produccion").change(function () {
    	if ($("#id_sitio_unidad_produccion option:selected").val() !== "") {
        	fn_cargarSitioUnidadProduccion();
        }else{
        	limpiarDetalleDatosSitio();
        }
    });

	$("#formulario").submit(function (event) {

		event.preventDefault();
		var error = false;

		//Datos Generales
		if(!$.trim($("#es_asociacion").val())){
        	error = true;
			$("#es_asociacion").addClass("alertaCombo");
		}

		if(!$.trim($("#tipo_solicitud").val())){
        	error = true;
			$("#tipo_solicitud").addClass("alertaCombo");
		}

		if(!$.trim($("#tipo_explotacion").val())){
        	error = true;
			$("#tipo_explotacion").addClass("alertaCombo");
		}

		if($("#tipo_explotacion option:selected").val() === 'SA'){
			if(Number.parseInt($("#num_animales").val()) < Number.parseInt('0')){
				error = true;
				$("#num_animales").addClass("alertaCombo");
	      }
		}

		//Información en tabla de Sitios y Áreas
		if ($("#iSitio").length > 0){
			error = false;
		}else{
			error = true;
			alert("Por favor ingrese por lo menos un sitio, área y producto para registrar.");
		}

		//Datos Operador
		if(!$.trim($("#identificador_operador").val())){
        	error = true;
    		$("#identificador_operador").addClass("alertaCombo");
    	}

		if(!$.trim($("#razon_social").val())){
        	error = true;
    		$("#razon_social").addClass("alertaCombo");
    	}

		if(!$.trim($("#identificador_representante_legal").val()) || !esCampoValido("#identificador_representante_legal") || $("#identificador_representante_legal").val().length < 10 || ($("#identificador_representante_legal").val().length > 10 && $("#identificador_representante_legal").val().length < 13) || $("#identificador_representante_legal").val().length > 13){
			error = true;
			$("#identificador_representante_legal").addClass("alertaCombo");
		}

		if(!$.trim($("#nombre_representante_legal").val())){
        	error = true;
    		$("#nombre_representante_legal").addClass("alertaCombo");
    	}

    	if(!$.trim($("#correo").val())){
        	error = true;
    		$("#correo").addClass("alertaCombo");
    	}

    	if(!$.trim($("#telefono").val())){
        	error = true;
    		$("#telefono").addClass("alertaCombo");
    	}

    	if(!$.trim($("#direccion").val())){
        	error = true;
    		$("#direccion").addClass("alertaCombo");
    	}

    	//Datos Representante Técnico
		if(!$.trim($("#identificador_representante_tecnico").val()) || !esCampoValido("#identificador_representante_tecnico") || $("#identificador_representante_tecnico").val().length < 10 || ($("#identificador_representante_tecnico").val().length > 10 && $("#identificador_representante_tecnico").val().length < 13) || $("#identificador_representante_tecnico").val().length > 13){
			error = true;
			$("#identificador_representante_tecnico").addClass("alertaCombo");
		}

		if(!$.trim($("#nombre_representante_tecnico").val())){
        	error = true;
    		$("#nombre_representante_tecnico").addClass("alertaCombo");
    	}
		
		if(!$.trim($("#correo_representante_tecnico").val())  || !esCampoValido("#correo_representante_tecnico")){
			error = true;
			$("#correo_representante_tecnico").addClass("alertaCombo");
		}

		if(!$.trim($("#telefono_representante_tecnico").val())  || !esCampoValido("#telefono_representante_tecnico") ){
			error = true;
			$("#telefono_representante_tecnico").addClass("alertaCombo");
		}	

		//Datos Unidad Producción
		if(!$.trim($("#id_sitio_unidad_produccion option:selected").val())){
        	error = true;
    		$("#id_sitio_unidad_produccion").addClass("alertaCombo");
    	}

		if(!$.trim($("#provincia_unidad_produccion").val())){
        	error = true;
    		$("#provincia_unidad_produccion").addClass("alertaCombo");
    	}

		if(!$.trim($("#canton_unidad_produccion").val())){
        	error = true;
    		$("#canton_unidad_produccion").addClass("alertaCombo");
    	}

		if(!$.trim($("#parroquia_unidad_produccion").val())){
        	error = true;
    		$("#parroquia_unidad_produccion").addClass("alertaCombo");
    	}

		if(!$.trim($("#direccion_unidad_produccion").val())){
        	error = true;
    		$("#direccion_unidad_produccion").addClass("alertaCombo");
    	}

		if(!$.trim($("#utm_x").val())){
        	error = true;
    		$("#utm_x").addClass("alertaCombo");
    	}

		if(!$.trim($("#utm_y").val())){
        	error = true;
    		$("#utm_y").addClass("alertaCombo");
    	}

		if(!$.trim($("#altitud").val())){
        	error = true;
    		$("#altitud").addClass("alertaCombo");
    	}

		//Datos Alcance
		if(!$.trim($("#tipo_certificado").val())){
        	error = true;
    		$("#tipo_certificado").addClass("alertaCombo");
    	}

		if(!$.trim($("#num_trabajadores").val()) || Number.parseInt($("#num_trabajadores").val()) <= Number.parseInt('0')){
        	error = true;
    		$("#num_trabajadores").addClass("alertaCombo");
    	}

		if(!$.trim($("#observacion_alcance").val())){
        	error = true;
    		$("#observacion_alcance").addClass("alertaCombo");
    	}

		if(!$.trim($("#num_hectareas").val()) || Number($("#num_hectareas").val()) <= Number('0')){
        	error = true;
    		$("#num_hectareas").addClass("alertaCombo");
    	}

		if($("#tipo_solicitud option:selected").val() == 'Equivalente'){

			if(!$.trim($("#codigo_equivalente").val())){
	        	error = true;
	    		$("#codigo_equivalente").addClass("alertaCombo");
	    	}

			if(!$.trim($("#fecha_inicio_equivalente").val())){
	        	error = true;
	    		$("#fecha_inicio_equivalente").addClass("alertaCombo");
	    	}

			if(!$.trim($("#fecha_fin_equivalente").val())){
	        	error = true;
	    		$("#fecha_fin_equivalente").addClass("alertaCombo");
	    	}
		}			

		//Información en tabla de tipos de auditoría
		//Verificación del check
		var cont = 0;
		
		$('input[type=checkbox]:checked').each(function() {
            cont = cont + 1;
        });

		if(cont <= 0){
			error = true;
			alert("Seleccione por lo menos un elemento de auditoría");
		}
		
		if (!error) {

			cargarDatosDetalle();
			vaciarCampos();
			
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);

	       	if (respuesta.estado == 'exito'){
	       		$("#estado").html(respuesta.mensaje);
	       		$("#_actualizar").click();
				$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un item para revisarlo.</div>');
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	function cargarDatosDetalle(){

		var array_sitios_bpa = [];
    	
		$('#tbItems tbody tr').each(function (rows) {				

			var iSitio = $(this).find('td').find('input[name="iSitio[]"]').val();			
			var nSitio = $(this).find('td').find('input[name="nSitio[]"]').val();			
			var iArea = $(this).find('td').find('input[name="iArea[]"]').val();
			var nArea = $(this).find('td').find('input[name="nArea[]"]').val();
			var iSubtipoProducto = $(this).find('td').find('input[name="iSubtipoProducto[]"]').val();			
			var nSubtipoProducto = $(this).find('td').find('input[name="nSubtipoProducto[]"]').val();			
			var iProducto = $(this).find('td').find('input[name="iProducto[]"]').val();
			var nProducto = $(this).find('td').find('input[name="nProducto[]"]').val();
			var iOperacion = $(this).find('td').find('input[name="iOperacion[]"]').val();			
			var nOperacion = $(this).find('td').find('input[name="nOperacion[]"]').val();			
			var iHectareas = $(this).find('td').find('input[name="iHectareas[]"]').val();
			var iUnidad = $(this).find('td').find('input[name="iUnidad[]"]').val();
			var iIdentificadorSitio = $(this).find('td').find('input[name="iIdentificadorSitio[]"]').val();			
			var iEstado = $(this).find('td').find('input[name="iEstado[]"]').val();

			if ($('#tbItems tbody tr').length){		
				
				datosSitiosBPA = {	"iSitio":iSitio, "nSitio":nSitio,
            						"iArea":iArea, "nArea":nArea,
            						"iSubtipoProducto":iSubtipoProducto, "nSubtipoProducto":nSubtipoProducto,
            						"iProducto":iProducto, "nProducto":nProducto,
            						"iOperacion":iOperacion, "nOperacion":nOperacion,
            						"iHectareas":iHectareas, "iUnidad":iUnidad,
            						"iIdentificadorSitio":iIdentificadorSitio, "iEstado":iEstado};
				
				agregarElementos(array_sitios_bpa, datosSitiosBPA, $("#array_sitios_bpa"));				
			}

		});
	}

	function agregarElementos(array, datos, objeto){
    	array.push(datos);
    	objeto.val(JSON.stringify(array));
	}

	function vaciarCampos(){

		$('#tbItems tbody tr').each(function (rows) {				

			$(this).find('td').find('input[name="iSitio[]"]').removeAttr('name');			
			$(this).find('td').find('input[name="nSitio[]"]').removeAttr('name');			
			$(this).find('td').find('input[name="iArea[]"]').removeAttr('name');	
			$(this).find('td').find('input[name="nArea[]"]').removeAttr('name');	
			$(this).find('td').find('input[name="iSubtipoProducto[]"]').removeAttr('name');				
			$(this).find('td').find('input[name="nSubtipoProducto[]"]').removeAttr('name');				
			$(this).find('td').find('input[name="iProducto[]"]').removeAttr('name');	
			$(this).find('td').find('input[name="nProducto[]"]').removeAttr('name');	
			$(this).find('td').find('input[name="iOperacion[]"]').removeAttr('name');				
			$(this).find('td').find('input[name="nOperacion[]"]').removeAttr('name');				
			$(this).find('td').find('input[name="iHectareas[]"]').removeAttr('name');	
			$(this).find('td').find('input[name="iUnidad[]"]').removeAttr('name');	
			$(this).find('td').find('input[name="iIdentificadorSitio[]"]').removeAttr('name');				
			$(this).find('td').find('input[name="iEstado[]"]').removeAttr('name');	

		});
	}

	//Funciones	
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
	
	//Función para mostrar los datos del operador/asociación
    function fn_buscarDatosOperador() {
    	var asociacion = $("#es_asociacion option:selected").val();
        var identificador = identificadorUsuario;
        
        if (identificador !== "" && asociacion !== "" ){
        	mostrarMensaje("","EXITO");
        	$.post("<?php echo URL ?>CertificacionBPA/Solicitudes/obtenerDatosOperador",
               {
                identificador : identificadorUsuario,
                asociacion : $("#es_asociacion option:selected").val()
               }, function (data) {
				if(data.validacion == "Fallo"){
	        		mostrarMensaje(data.resultado,"FALLO");
	        		fn_cargarDatosOperador(data);	        		
				}else{
					fn_cargarDatosOperador(data);
					fn_bloquearDesbloquearCamposOperador();
					$("#identificador_operador").val(data.id);
					$("#tipo_solicitud").removeAttr("disabled");
				}
            }, 'json');
        }
    } 

	//Función para mostrar los datos obtenidos del operador/asociación
    function fn_bloquearDesbloquearCamposOperador() {
    	if($("#es_asociacion option:selected").val() === 'Si'){
    		$("#identificador_representante_legal").attr("readonly", "readonly");
			$("#identificador_representante_tecnico").attr("readonly", "readonly");
			$("#correo_representante_tecnico").attr("readonly", "readonly");
			$("#telefono_representante_tecnico").attr("readonly", "readonly");
		}else{
			$("#identificador_representante_legal").removeAttr("readonly");
			$("#identificador_representante_tecnico").removeAttr("readonly");
			$("#correo_representante_tecnico").removeAttr("readonly");
			$("#telefono_representante_tecnico").removeAttr("readonly");
		}
    } 

  	//Función para mostrar los datos obtenidos del operador/asociación
    function fn_cargarDatosOperador(data) {
    	if(data.validacion == "Fallo"){
    		$("#identificador_operador").val("");
			$("#razon_social").val("");
			$("#identificador_representante_legal").val("");
			$("#nombre_representante_legal").val("");
			$("#correo").val("");
			$("#telefono").val("");
			$("#direccion").val("");

			$("#identificador_representante_tecnico").val("");
			$("#nombre_representante_tecnico").val("");
			$("#correo_representante_tecnico").val("");
			$("#telefono_representante_tecnico").val("");

			$("#provincia").val("");
			$("#canton").val("");
			$("#parroquia").val("");
		}else{
			$("#identificador_operador").val(data.id);
			$("#razon_social").val(data.razon_social);
			$("#identificador_representante_legal").val(data.id_representante);
			$("#nombre_representante_legal").val(data.nombre_representante);
			$("#correo").val(data.correo);
			$("#telefono").val(data.telefono);
			$("#direccion").val(data.direccion);

			$("#identificador_representante_tecnico").val(data.id_tecnico);
			$("#nombre_representante_tecnico").val(data.nombre_tecnico);
			$("#correo_representante_tecnico").val(data.correo_tecnico);
			$("#telefono_representante_tecnico").val(data.telefono_tecnico);

			$("#provincia").val(data.provincia);
			$("#canton").val(data.canton);
			$("#parroquia").val(data.parroquia);
		}
    } 
	
	//Lista de Tipos de Productos por Área que dispone el operador/asociación
    function fn_cargarTipoProductos() {
		var asociacion = $("#es_asociacion option:selected").val();
		var identificador = $("#identificador_operador").val();
        var idTipoExplotacion = $("#tipo_explotacion option:selected").val();        
        
        if (idTipoExplotacion !== "" && asociacion !== "" && identificador !== ""){
            $.post("<?php echo URL ?>CertificacionBPA/Solicitudes/buscarTipoProductoXOperacionAreaOperador",
                {
                 id_area : $("#tipo_explotacion option:selected").val(),
                 identificador : $("#identificador_operador").val(),
                 asociacion : $("#es_asociacion option:selected").val()
                }, function (data) {
                $("#tipo_producto").removeAttr("disabled");
                $("#tipo_producto").html(data);               
            });
        }
    }

  	//Lista de Subtipos de Productos por Tipo que dispone el operador/asociación
    function fn_cargarSubtipoProductos() {
		var asociacion = $("#es_asociacion option:selected").val();
		var identificador = $("#identificador_operador").val();
		var idTipoProducto = $("#tipo_producto option:selected").val();
        
        if (idTipoProducto !== "" && asociacion !== "" && identificador !== ""){
            $.post("<?php echo URL ?>CertificacionBPA/Solicitudes/buscarSubtipoProductoXOperacionAreaOperador",
                {
                 id_tipo_producto : $("#tipo_producto option:selected").val(),
                 identificador : $("#identificador_operador").val(),
                 asociacion : $("#es_asociacion option:selected").val()
                }, function (data) {
                $("#subtipo_producto").removeAttr("disabled");
                $("#subtipo_producto").html(data);               
            });
        }
    }

  //Lista de Productos por Subtipo que dispone el operador/asociación
    function fn_cargarProductos() {
		var asociacion = $("#es_asociacion option:selected").val();
		var identificador = $("#identificador_operador").val();
		var idSubtipoProducto = $("#subtipo_producto option:selected").val();
        
        if (idSubtipoProducto !== "" && asociacion !== "" && identificador !== ""){
            $.post("<?php echo URL ?>CertificacionBPA/Solicitudes/buscarProductoXOperacionAreaOperador",
                {
                 id_subtipo_producto : $("#subtipo_producto option:selected").val(),
                 identificador : $("#identificador_operador").val(),
                 asociacion : $("#es_asociacion option:selected").val()
                }, function (data) {
                $("#producto").removeAttr("disabled");
                $("#producto").html(data);               
            });
        }
    }

  //Lista de Sitios y Áreas por Producto que dispone el operador/asociación
    function fn_cargarSitiosAreas() {
		var asociacion = $("#es_asociacion option:selected").val();
		var identificador = $("#identificador_operador").val();
		var idProducto = $("#producto option:selected").val();
        
        if (idProducto !== "" && asociacion !== "" && identificador !== ""){
            $.post("<?php echo URL ?>CertificacionBPA/Solicitudes/buscarSitioXProductoOperacionAreaOperador",
                {
                 id_producto : $("#producto option:selected").val(),
                 identificador : $("#identificador_operador").val(),
                 asociacion : $("#es_asociacion option:selected").val()
                }, function (data) {
                $("#sitio").removeAttr("disabled");
                $("#sitio").html(data);               
            });
        }
    }

  //Función para agregar elementos
    $('#btnAgregarSitios').click(function(){
    	$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#es_asociacion").val())){
			error = true;
			$("#es_asociacion").addClass("alertaCombo");
		}
		
		if(!$.trim($("#tipo_solicitud").val())){
			error = true;
			$("#tipo_solicitud").addClass("alertaCombo");
		}

		if(!$.trim($("#tipo_explotacion").val())){
			error = true;
			$("#tipo_explotacion").addClass("alertaCombo");
		}
		
		if(!$.trim($("#tipo_producto").val())){
			error = true;
			$("#tipo_producto").addClass("alertaCombo");
		}

		if(!$.trim($("#subtipo_producto").val())){
			error = true;
			$("#subtipo_producto").addClass("alertaCombo");
		}

		if(!$.trim($("#producto").val())){
			error = true;
			$("#producto").addClass("alertaCombo");
		}

		if(!$.trim($("#sitio").val())){
			error = true;
			$("#sitio").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			$("#estado").html("").removeClass('alerta');

			if($("#producto").val()!="" && $("#sitio").val()!=""){
				var codigo = 'r_'+$("#sitio option:selected").val();	
				var cadena = '';

				verificarRegistro($(this).val());

				//Consultar si el sitio/area/producto ya esta registrado y mostrar el estado (aprobado/rechazado/disponible)
				//para luego crear el listado de auditorias disponibles
				fn_validarSitioAreaProductoRegistrado();

				//revisar datos enviados y que se agregue al grid
				if($("#tbItems tbody #"+codigo.replace(/ /g,'')).length==0){
					var tipoSolicitud = $("#tipo_solicitud option:selected").val();
					var estado = $("#sitio option:selected").attr('data-estado');
					var asociacion = $("#es_asociacion option:selected").val();
					var asociacionSitio = $("#sitio option:selected").attr('data-asociacionSitio');
			        
					if((asociacion !== asociacionSitio) && (asociacionSitio !== 'NoRegistrado')) {
			        	$("#estado").html("El sitio ya ha sido registrado de manera individual o por una asociación.").addClass('alerta');
			        }else if(tipoSolicitud === "Equivalente" && estado !== 'Nuevo') {
			        	$("#estado").html("Solamente puede crear nuevas solicitudes para productos Equivalentes.").addClass('alerta');
			        }else if(tipoSolicitud === "Equivalente" && (estado == 'enviado' || estado == 'pago' || estado == 'inspeccion' || estado == 'subsanacion' || estado == 'aprobacion')) {
			        	$("#estado").html("El sitio y producto seleccionado se encuentra en un proceso de revisión.").addClass('alerta');
			        }else if(tipoSolicitud === "Nacional" && (estado == 'enviado' || estado == 'pago' || estado == 'inspeccion' || estado == 'subsanacion' || estado == 'aprobacion')) {
			        	$("#estado").html("El sitio y producto seleccionado se encuentra en un proceso de revisión.").addClass('alerta');
			        }else{
			        	cadena = "<tr id='"+codigo.replace(/ /g,'')+"'>"+
						"<td>"+
						"</td>"+
						"<td>"+$("#sitio option:selected").attr('data-nombreSitio')+
						"	<input id='iSitio' name='iSitio[]' value='"+$("#sitio option:selected").attr('data-idSitio')+"' type='hidden'>"+
						"	<input id='nSitio' name='nSitio[]' value='"+$("#sitio option:selected").attr('data-nombreSitio')+"' type='hidden'>"+
						"</td>"+
						"<td>"+$("#sitio option:selected").attr('data-nombreArea')+
						"	<input id='iArea' name='iArea[]' value='"+$("#sitio option:selected").attr('data-idArea')+"' type='hidden'>"+
						"	<input id='nArea' name='nArea[]' value='"+$("#sitio option:selected").attr('data-nombreArea')+"' type='hidden'>"+
						"</td>"+
						"<td>"+$("#subtipo_producto option:selected").text()+" - "+$("#producto option:selected").text()+
						"	<input id='iSubtipoProducto' name='iSubtipoProducto[]' value='"+$("#subtipo_producto option:selected").val()+"' type='hidden'>"+
						"	<input id='nSubtipoProducto' name='nSubtipoProducto[]' value='"+$("#subtipo_producto option:selected").text()+"' type='hidden'>"+
						"	<input id='iProducto' name='iProducto[]' value='"+$("#producto option:selected").val()+"' type='hidden'>"+
						"	<input id='nProducto' name='nProducto[]' value='"+$("#producto option:selected").text()+"' type='hidden'>"+
						"</td>"+
						"<td>"+$("#sitio option:selected").attr('data-nombreOperacion')+
						"	<input id='iOperacion' name='iOperacion[]' value='"+$("#sitio option:selected").attr('data-idOperacion')+"' type='hidden'>"+
						"	<input id='nOperacion' name='nOperacion[]' value='"+$("#sitio option:selected").attr('data-nombreOperacion')+"' type='hidden'>"+
						"</td>"+
						"<td>"+$("#sitio option:selected").attr('data-hectareas')+
						"	<input id='iHectareas' name='iHectareas[]' value='"+$("#sitio option:selected").attr('data-superficieCertificada')+"' type='hidden'>"+
						"	<input id='iUnidad' name='iUnidad[]' value='"+$("#sitio option:selected").attr('data-unidad')+"' type='hidden'>"+
						"	<input id='iIdentificadorSitio' name='iIdentificadorSitio[]' value='"+$("#sitio option:selected").attr('data-identificadorSitio')+"' type='hidden'>"+
						"</td>"+
						"<td>"+$("#sitio option:selected").attr('data-superficieCertificada')+
						"</td>"+
						"<td>"+$("#sitio option:selected").attr('data-estado')+
						"	<input id='iEstado' name='iEstado[]' value='"+$("#sitio option:selected").attr('data-estado')+"' type='hidden'>"+
						"</td>"+
						"<td>"+
						"	<button type='button' onclick='quitarSitios("+codigo.replace(/ /g,'')+")' class='menos'>Quitar</button>"+
						"</td>"+
					"</tr>"
			        }

					$("#tbItems tbody").append(cadena);
					enumerar();
					fn_agregarSitioUnidadProduccionCambios();
					//comentar para cambio de funcionalidad cuando apruebe GI e IA
					fn_calcularTotalHectareas();	
					fn_mostrarAuditoriaCambios();				
					limpiarDetalle();
					limpiarDetalleDatosSitio();			
				}else{
					$("#estado").html("No puede ingresar dos registros iguales.").addClass('alerta');
				}
			}
		}
    });

    function quitarSitios(fila){
		$("#tbItems tbody tr").eq($(fila).index()).remove();	
		fn_agregarSitioUnidadProduccionCambios();  
		//comentar para cambio de funcionalidad cuando apruebe GI e IA
		fn_calcularTotalHectareas();
		enumerar();
		limpiarDetalleDatosSitio();					 
	}

	function verificarRegistro(produ){
		$('#tbItems tbody tr').each(function (rows) {		
			var rd= $(this).find('td').eq(1).find('input[id="idOperacion"]').val();
			filas=$('#tbItems tbody tr').length;
			if (filas>0){
				if(rd == produ){
					rDuplicado=true;
			    	return false;
			    } else{
			    	rDuplicado=false;		    			    		
			    }			        
			}	    
		});
	}

	function enumerar(){			    	    
	    var tabla = document.getElementById('tbItems');
	    con=0;   
	    $("#tbItems tbody tr").each(function(row){        
	    	con+=1;    	
	    	$(this).find('td').eq(0).html(con);    	  	
	    });
	}	

	function limpiarDetalle(){
		/*$("#id_area_origen").val("");
    	$("#area_origen").val("");
    	$("#id_area_destino").html(combo);
    	$("#area_destino").val("");
    	$("#id_subtipo_producto").html(combo);
    	$("#subtipo_producto").val("");
    	$("#id_producto").html(combo);
    	$("#producto").val("");
    	$("#unidad").val("");
    	$("#cantidad").val("");
    	$("#requisitos").html("");*/
	}

	function fn_limpiar() {
		$(".alertaCombo").removeClass("alertaCombo");
		$('#estado').html('');
	}

	//Función para agregar los sitios a un nuevo combo de Sitios de Unidad de Producción
	function fn_agregarSitioUnidadProduccionCambios() {
		$("#id_sitio_unidad_produccion").html(combo);
		//descomentar para cambio de funcionalidad cuando apruebe GI e IA
		//$("#num_hectareas").val("");
		
		var tabla = document.getElementById('tbItems');		
		 
	    $("#tbItems tbody tr").each(function(row){        

			filas=$('#tbItems tbody tr').length;
			
			if (filas>0){
				var itemExists = false;
				var id= $(this).find('td').eq(1).find('input[id="iSitio"]').val();
		    	var nombre= $(this).find('td').eq(1).find('input[id="nSitio"]').val();
		    	//descomentar para cambio de funcionalidad cuando apruebe GI e IA
		    	//var area= $(this).find('td').eq(5).find('input[id="iHectareas"]').val();
		    			        
		        $("#id_sitio_unidad_produccion option").each(function() {
		            if ($(this).val() == $.trim(id)) {
		                itemExists = true;
		            }
		        });

		        if (!itemExists) {
		        	$("#id_sitio_unidad_produccion").append("<option value='"+id+"'>" + nombre + "</option>");
		        	//descomentar para cambio de funcionalidad cuando apruebe GI e IA
		        	//fn_calcularTotalHectareasIndividual(area);
		      	}        
			}	   	  	
	    });	    
	}

	//Mostrar la información del Sitio de Unidad de Producción
    function fn_cargarSitioUnidadProduccion() {
		var idSitio = $("#id_sitio_unidad_produccion option:selected").val();
        
        if (idSitio !== "" && idSitio !== "Seleccione...."){
            $.post("<?php echo URL ?>CertificacionBPA/Solicitudes/obtenerSitio",
                {
                 id_sitio : $("#id_sitio_unidad_produccion option:selected").val()
                }, function (data) {
                	if(data.validacion == "Fallo"){
    	        		mostrarMensaje(data.resultado,"FALLO");
    	        		fn_cargarDatosSitio(data);	        		
    				}else{
    					fn_cargarDatosSitio(data);
    				}
                }, 'json');
        }else{
        	limpiarDetalleDatosSitio();
        }
    }

  	//Función para mostrar los datos obtenidos del operador/asociación
    function fn_cargarDatosSitio(data) {
    	if(data.validacion == "Fallo"){
    		$("#sitio_unidad_produccion").val("");
    		$("#provincia_unidad_produccion").val("");
			$("#canton_unidad_produccion").val("");
			$("#parroquia_unidad_produccion").val("");
			$("#direccion_unidad_produccion").val("");
			$("#utm_x").val("");
			$("#utm_y").val("");
			$("#altitud").val("");
		}else{
			$("#sitio_unidad_produccion").val($("#id_sitio_unidad_produccion option:selected").text());
			$("#provincia_unidad_produccion").val(data.provincia);
			$("#canton_unidad_produccion").val(data.canton);
			$("#parroquia_unidad_produccion").val(data.parroquia);
			$("#direccion_unidad_produccion").val(data.direccion);
			$("#utm_x").val(data.latitud);
			$("#utm_y").val(data.longitud);
			$("#altitud").val(data.zona);
		}
    } 

    function limpiarDetalleDatosSitio(){
    	$("#sitio_unidad_produccion").val("");
    	$("#provincia_unidad_produccion").val("");
		$("#canton_unidad_produccion").val("");
		$("#parroquia_unidad_produccion").val("");
		$("#direccion_unidad_produccion").val("");
		$("#utm_x").val("");
		$("#utm_y").val("");
		$("#altitud").val("");
	}

  	//Función para mostrar las opciones del tipo de explotación
    function fn_cargarTipoExplotacion() {
        var tipoSolicitud = $("#tipo_solicitud option:selected").val();
        
        if (tipoSolicitud === "Equivalente") {
        	$.post("<?php echo URL ?>CertificacionBPA/Solicitudes/comboTipoExplotacionEquivalente",
            {
        		tipo_explotacion : null
        	}, 
        	function (data) {
                $("#tipo_explotacion").removeAttr("disabled");
                $("#tipo_explotacion").html(data);               
            });
        }else{
        	$("#tipo_explotacion").removeAttr("disabled");
        }
    }

  	//Función para mostrar las opciones del tipo de certificado
    function fn_cargarTipoCertificado() {
        var tipoSolicitud = $("#tipo_solicitud option:selected").val();
        
        if (tipoSolicitud === "Nacional") {
        	$.post("<?php echo URL ?>CertificacionBPA/Solicitudes/comboTipoCertificadoNacional",
            {
        		tipo_solicitud : $("#tipo_solicitud option:selected").val()
        	}, 
        	function (data) {
                $("#tipo_certificado").removeAttr("disabled");
                $("#tipo_certificado").html(data);               
            });
        }else if(tipoSolicitud === "Equivalente") {
        	$.post("<?php echo URL ?>CertificacionBPA/Solicitudes/comboTipoCertificadoEquivalente",
            {
        		tipo_solicitud : $("#tipo_solicitud option:selected").val()
        	},
        	function (data) {
                $("#tipo_certificado").removeAttr("disabled");
                $("#tipo_certificado").html(data);               
            });
        }
    }

  	//Función para calcular las hectáreas de las áreas agregadas
	function fn_calcularTotalHectareas() {
		$("#num_hectareas").val('');

		var total = 0;
		var tabla = document.getElementById('tbItems');		
		 
	    $("#tbItems tbody tr").each(function(row){        

			filas=$('#tbItems tbody tr').length;
			
			if (filas>0){
				total += Number($(this).find('td').eq(5).find('input[id="iHectareas"]').val());
			}	   	  	
	    });	  

	    $("#num_hectareas").val(total);  
	}

	var total = 0;
	function fn_calcularTotalHectareasIndividual(area) {
		total = Number($("#num_hectareas").val());		
		
		total += Number(area);

	    $("#num_hectareas").val(total);  
	}

	//Función para activar/inactivar campos de la sección Alcance
    function fn_bloquearDesbloquearCamposAlcance() {
        if($("#tipo_solicitud option:selected").val() === 'Nacional'){
    		$(".equivalente").hide();
    		$("#codigo_equivalente").attr('disabled', 'disabled');
    		$("#fecha_inicio_equivalente").attr('disabled', 'disabled');
			$("#fecha_fin_equivalente").attr('disabled', 'disabled');

			$(".nacional").show();
		}else{
			$(".equivalente").show();
			$("#codigo_equivalente").removeAttr('disabled');
			$("#fecha_inicio_equivalente").removeAttr('disabled');
			$("#fecha_fin_equivalente").removeAttr('disabled');

			$(".nacional").hide();
		}
    } 

	//Función para carga de archivo de certificado equivalente
    $('button.subirArchivo').click(function (event) {
    	var nombre_archivo = "<?php echo 'equivalente_' . time(); ?>";
        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
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
    });

  	//Función para carga de archivo de certificado equivalente
    $('button.subirArchivoAnexo').click(function (event) {
    	var nombre_archivo = "<?php echo 'nacional_' . time(); ?>";
        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivoAnexo");
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
    });

  	//Mostrar el estado de un sitio/área/producto en solicitudes anteriores y desplegar e
    function fn_validarSitioAreaProductoRegistrado() {

    	var idSitio = $("#sitio option:selected").attr('data-idSitio');
    	var idArea = $("#sitio option:selected").attr('data-idArea');
    	var idProducto = $("#producto option:selected").val();
    	var identificadorOperador = $("#sitio option:selected").attr('data-identificadorSitio');
        
        if (idSitio !== "" && idArea !== "" && idProducto !== "" && identificadorOperador !== ""){
            $.post("<?php echo URL ?>CertificacionBPA/Solicitudes/verificarSitioAreaProducto",
                {
                 id_sitio : $("#sitio option:selected").attr('data-idSitio'),
                 id_area : $("#sitio option:selected").attr('data-idArea'),
                 id_producto : $("#producto option:selected").val(),
                 identificador_sitio : $("#sitio option:selected").attr('data-identificadorSitio'),
                 asociacion : $("#es_asociacion option:selected").val()
                }, function (data) {
                	if(data.validacion == "Fallo"){
    	        		mostrarMensaje(data.resultado,"FALLO");	    		
    				}else{
    					mostrarMensaje(data.resultado,"EXITO");
    				}
                }, 'json');
        }
    }

  //Función para agregar los sitios a un nuevo combo de Sitios de Unidad de Producción
	function fn_mostrarAuditoriaCambios() {
		var estado = '';
		var tabla = document.getElementById('tbItems');	

		$(".NuevoEquivalente").attr('disabled', true);
		$(".Nuevo").attr('disabled', true);
		$(".Aprobado").attr('disabled', true);
		$(".Expirado").attr('disabled', true);
		$(".subsanacion").attr('disabled', true);	
		 
	    $("#tbItems tbody tr").each(function(row){        

			filas=$('#tbItems tbody tr').length;
			
			if (filas>0){
				estado= $(this).find('td').eq(7).find('input[id="iEstado"]').val();

				switch (estado) {
					case 'Nuevo':
        				if($("#tipo_solicitud option:selected").val() === 'Equivalente'){
				    		$(".NuevoEquivalente").attr('disabled', false);
				    		$(".Nuevo").attr('disabled', true);
				    		$(".Nuevo").prop("checked", false);
				      	}else{
				    	  	$(".NuevoEquivalente").attr('disabled', true);
				    	  	$(".NuevoEquivalente").prop("checked", false);
				    		$(".Nuevo").attr('disabled', false);
				      	}

				    	break;
					case 'Rechazado':
        				if($("#tipo_solicitud option:selected").val() === 'Equivalente'){
				    		$(".NuevoEquivalente").attr('disabled', false);
				    		$(".Nuevo").attr('disabled', true);
				    		$(".Nuevo").prop("checked", false);
				      	}else{
				    	  	$(".NuevoEquivalente").attr('disabled', true);
				    	  	$(".NuevoEquivalente").prop("checked", false);
				    		$(".Nuevo").attr('disabled', false);
				      	}

				    	break;
				  	case 'Aprobado':
				  		if($("#tipo_solicitud option:selected").val() === 'Equivalente'){
				  			$(".Aprobado").attr('disabled', true);
				  			$(".Aprobado").prop("checked", false);
				      	}else{
				      		$(".Aprobado").attr('disabled', false);
				      	}

				  		break;
				  	case 'Expirado':
				  		if($("#tipo_solicitud option:selected").val() === 'Equivalente'){
				  			$(".Expirado").attr('disabled', true);
				  			$(".Expirado").prop("checked", false);
				      	}else{
				      		$(".Expirado").attr('disabled', false);
				      	}

				  		break;
				  	case 'subsanacion':
				  		if($("#tipo_solicitud option:selected").val() === 'Equivalente'){
				  			$(".subsanacion").attr('disabled', true);
				  			$(".subsanacion").prop("checked", false);
				      	}else{
				      		$(".subsanacion").attr('disabled', false);
				      	}

				  		break;
				  	default:
				    break;
				}
			}	   	  	
	    });	    
	}

	//Función para mostrar las opciones de auditorías de tipo Equivalentes
    function fn_cargarAuditoriasEquivalentes() {
        var tipoSolicitud = $("#tipo_solicitud option:selected").val();
        
        if(tipoSolicitud === "Equivalente") {
        	$(".Nuevo").hide();
        	$(".Aprobado").hide();
        	$(".Expirado").hide();
        }
    }
</script>
<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<fieldset>
	<legend><?php echo ($this->modeloSolicitudes->getTipoRevision()=='Documental'?"Resultado de la Revisión Documental":($this->modeloSolicitudes->getTipoRevision()=='Técnico'?"Resultado de la Inspección":($this->modeloSolicitudes->getTipoRevision()=='Aprobación'?"Resultado de la Aprobación":'')))?></legend>				

	<div data-linea="51">
		<label for="resultado">Resultado: </label>
			<?php echo strtoupper($this->modeloSolicitudes->getEstado()); ?>
	</div>	
	
	<div data-linea="52">
		<label for="observaciones_revision">Observaciones: </label>
			<?php echo $this->modeloSolicitudes->getObservacionRevision();?>
	</div>	
	
	<div data-linea="53">
		<label for="fecha_revision">Fecha: </label>
			<?php echo date('Y-m-d H:i:s',strtotime($this->modeloSolicitudes->getFechaRevision())); ?>
	</div>	
	
	<div data-linea="54" class="tecnico">
		<label for="ruta_checklist" class="Inspeccion">Informe de Auditoría: </label>
			<?php echo ($this->modeloSolicitudes->getRutaChecklist()==''? '<span class="alerta">No ha cargado ningún archivo</span>':'<a href="'.$this->modeloSolicitudes->getRutaChecklist().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Descargar informe</a>')?>
	</div>			
	<!-- div data-linea="55" class="tecnico">
		<label for="ruta_formato_plan_accion" class="Inspeccion">Plan de Acción: </label>
			< ?php echo ($this->modeloSolicitudes->getRutaFormatoPlanAccion()==''? '<span class="alerta">No ha cargado ningún archivo</span>':'<a href="'.$this->modeloSolicitudes->getRutaFormatoPlanAccion().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Descargar informe</a>')?>
	</div-->	
</fieldset>		
		
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>CertificacionBPA' data-opcion='solicitudes/guardar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
	<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $this->modeloSolicitudes->getIdSolicitud(); ?>" />
	<!-- input type="hidden" id="estado" name="estado" value="< ?php echo $this->modeloSolicitudes->getEstado(); ?>" /-->
			
	<fieldset>
		<legend>Datos Generales</legend>				

		<div data-linea="1">
			<label for="es_asociacion">Opción de Certificación: </label>
				<?php echo ($this->modeloSolicitudes->getEsAsociacion()=='Si'?'Grupal':'Individual');?>
				<input type="hidden" id="es_asociacion" name="es_asociacion" value="<?php echo $this->modeloSolicitudes->getEsAsociacion(); ?>" />
		</div>				

		<div data-linea="2">
			<label for="tipo_solicitud">Tipo de Solicitud: </label>
				<?php echo $this->modeloSolicitudes->getTipoSolicitud();?>
				<input type="hidden" id="tipo_solicitud" name="tipo_solicitud" value="<?php echo $this->modeloSolicitudes->getTipoSolicitud(); ?>" />
		</div>				

		<div data-linea="3">
			<label for="tipo_explotacion">Tipo de Explotación: </label>
    			<?php echo ($this->modeloSolicitudes->getTipoExplotacion()=="SV"?"Sanidad Vegetal":($this->modeloSolicitudes->getTipoExplotacion()=="SA"?"Sanidad Animal":"Inocuidad de Alimentos"));?>
    			<input type="hidden" id="tipo_explotacion" name="tipo_explotacion" value="<?php echo $this->modeloSolicitudes->getTipoExplotacion(); ?>" />
		</div>	
		
		<div data-linea="4">
			<label for="tipo_producto">Tipo de Producto: </label>
			<select id="tipo_producto" name="tipo_producto"  >
				<option value="">Seleccionar....</option>
            </select>
		</div>
		
		<div data-linea="4">
			<label for="subtipo_producto">Subtipo de Producto: </label>
			<select id="subtipo_producto" name="subtipo_producto"  disabled>
				<option value="">Seleccionar....</option>
            </select>
		</div>	
		
		<div data-linea="5">
			<label for="producto">Producto: </label>
			<select id="producto" name="producto"  disabled>
				<option value="">Seleccionar....</option>
            </select>
		</div>	
		
		<div data-linea="6">
			<label for="sitio">Sitios y Áreas: </label>
			<select id="sitio" name="sitio"  disabled>
				<option value="">Seleccionar....</option>
            </select>
		</div>	

		<div data-linea="7" class="num_animales">
			<label for="num_animales">Nº Animales: </label>
			<input type="number" id="num_animales" name="num_animales" disabled="disabled" step=1 min=1 value="<?php echo $this->modeloSolicitudes->getNumAnimales(); ?>" />
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
						<th style="width: 10%;">Nº</th>
						<th style="width: 15%;">Sitio</th>
                        <th style="width: 15%;">Área</th>
                        <th style="width: 15%;">Producto</th>
                        <th style="width: 15%;">Operación</th>
                        <th style="width: 15%;">Hectáreas</th>
                        <th style="width: 15%;">Estado</th>
                        <th style="width: 10%;"></th>
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
			<label for="provincia">Provincia: </label>
				<?php echo $this->modeloSolicitudes->getProvinciaUnidadProduccion(); ?>
		</div>		
		
		<div data-linea="16">
			<label for="canton">Cantón: </label>
				<?php echo $this->modeloSolicitudes->getCantonUnidadProduccion(); ?>
		</div>		
		
		<div data-linea="17">
			<label for="parroquia">Parroquia: </label>
				<?php echo $this->modeloSolicitudes->getParroquiaUnidadProduccion(); ?>
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
    			<?php echo $this->modeloSolicitudes->getTipoCertificado(); ?>
		</div>				

		<div data-linea="31">
			<label for="num_trabajadores">Nº de Trabajadores: </label>
			<input type="number" id="num_trabajadores" name="num_trabajadores" value="<?php echo $this->modeloSolicitudes->getNumTrabajadores(); ?>"
				required min="1" step="1" />
		</div>				

		<div data-linea="32" class="equivalente">
			<label for="codigo_equivalente">Código Equivalente: </label>
			<input type="text" id="codigo_equivalente" name="codigo_equivalente" value="<?php echo $this->modeloSolicitudes->getCodigoEquivalente(); ?>"
				placeholder="Número de certificado externo que se va a homologar" maxlength="32" disabled="disabled"/>
		</div>				

		<div data-linea="33" class="equivalente">
			<label for="fecha_inicio_equivalente">Fecha de Inicio: </label>
			<input type="date" id="fecha_inicio_equivalente" name="fecha_inicio_equivalente" value="<?php echo $this->modeloSolicitudes->getFechaInicioEquivalente(); ?>" disabled="disabled"/>
		</div>				

		<div data-linea="33" class="equivalente">
			<label for="fecha_fin_equivalente">Fecha de Fin: </label>
			<input type="date" id="fecha_fin_equivalente" name="fecha_fin_equivalente" value="<?php echo $this->modeloSolicitudes->getFechaFinEquivalente(); ?>" disabled="disabled"/>
		</div>				

		<div data-linea="34">
			<label for="observacion_alcance">Observación: </label>
			<input type="text" id="observacion_alcance" name="observacion_alcance" value="<?php echo $this->modeloSolicitudes->getObservacionAlcance(); ?>"
				required="required" maxlength="1024" />
		</div>				

		<div data-linea="35" class="equivalente">
			<label for="ruta">Certificado: </label> 
				<?php echo ($this->modeloSolicitudes->getRutaCertificadoEquivalente()==''? '<span class="alerta">No ha cargado ningún archivo</span>':'<a href="'.$this->modeloSolicitudes->getRutaCertificadoEquivalente().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Descargar certificado</a>')?>

			<input type="file" id="informe" class="archivo" accept="application/pdf" /> 
			<input type="hidden" class="rutaArchivo" name="ruta_certificado_equivalente" id="ruta_certificado_equivalente" value="<?php echo $this->modeloSolicitudes->getRutaCertificadoEquivalente();?>" />
				
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
			<label for="anexo">Documentos de Apoyo: </label> 
				<?php echo ($this->modeloSolicitudes->getAnexoNacional()==''? '<span class="alerta">No ha cargado ningún archivo</span>':'<a href="'.$this->modeloSolicitudes->getAnexoNacional().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Descargar Documento de Apoyo</a>')?>

			<input type="file" id="anexo" class="archivo" accept="application/pdf" /> 
			<input type="hidden" class="rutaArchivoAnexo" name="anexo_nacional" id="anexo_nacional" value="<?php echo $this->modeloSolicitudes->getAnexoNacional();?>" />
				
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
	
	<fieldset class="tecnico"> <!-- class="subsanacion" -->
		<legend>Plan de Acción</legend>
		
		<div data-linea="40">
			<label for="ruta">Plan de Acción: </label>
				<?php echo ($this->modeloSolicitudes->getRutaPlanAccion()==''? '<span class="alerta">No ha cargado ningún archivo</span>':'<a href="'.$this->modeloSolicitudes->getRutaPlanAccion().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Descargar Plan de Acción</a>')?>

			<input type="file" id="informePlan" class="archivo" accept="application/pdf" /> 
			<input type="hidden" class="rutaArchivo" name="ruta_plan_accion" id="ruta_plan_accion" value="<?php echo $this->modeloSolicitudes->getRutaPlanAccion()?>" />
				
    		<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
        		<button type="button" class="subirArchivoPlan adjunto" data-rutaCarga="<?php echo CERT_BPA_URL_PLAC_OP . $this->rutaFecha;?>">Subir archivo</button>
        	</div>			
		</div>
	</fieldset>
	
	<div data-linea="41">
		<button type="submit" class="guardar">Guardar</button>
	</div>
</form >

<script type ="text/javascript">
var identificadorUsuario = <?php echo json_encode($_SESSION['usuario']); ?>;
var tipoSolicitud = <?php echo json_encode($this->modeloSolicitudes->getTipoSolicitud()); ?>;
var tipoExplotacion = <?php echo json_encode($this->modeloSolicitudes->getTipoExplotacion()); ?>;
var idSitioUnidad = <?php echo json_encode($this->modeloSolicitudes->getIdSitioUnidadProduccion()); ?>;
var tipoRevision = <?php echo json_encode($this->modeloSolicitudes->getTipoRevision()); ?>;
var auditorias = <?php echo json_encode($this->auditoria); ?>;

var combo = "<option>Seleccione....</option>";

	$(document).ready(function() {
		construirValidador();
		distribuirLineas();

		$(".num_animales").hide();
		$(".tecnico").hide();
		

		if(tipoSolicitud === 'Equivalente'){
			$(".equivalente").show();
			$("#codigo_equivalente").removeAttr("disabled");
			$("#fecha_inicio_equivalente").removeAttr("disabled");
			$("#fecha_fin_equivalente").removeAttr("disabled");
			fn_cargarAuditoriasEquivalentes();

			$(".nacional").hide();
		}else{
			$(".equivalente").hide();
			$("#codigo_equivalente").attr("disabled","disabled");
			$("#fecha_inicio_equivalente").attr("disabled","disabled");
			$("#fecha_fin_equivalente").attr("disabled","disabled");	

			$(".nacional").show();		
		}

		if(tipoExplotacion === 'SA'){
			$(".num_animales").show();
			$("#num_animales").removeAttr("disabled");
		}else{
			$("#num_animales").attr("disabled","disabled");
		}

		if(tipoRevision === 'Técnico'){
			$(".tecnico").show();
		}else{
			$(".tecnico").hide();
		}

		//Cargar datos por defecto
		//Información del operador
		fn_buscarDatosOperador();

		//Tipo de Producto
		fn_cargarTipoProductos();

		//Información de Sitios/áreas/productos registrados
		fn_mostrarDetalleEditableSitioAreaProducto();

		//Sitio Unidad Producción
		fn_cargarSitiosUnidadProduccion(idSitioUnidad);

		//Sección de auditorías solicitadas
		//fn_cargarAuditoriasSeleccionadas();
		fn_cargarAuditorias();
		
		//Revisar y habilitar los checkbox de auditoría
		//fn_mostrarAuditoriaCambios();
		
		//Revisa el tipo de solicitud y activa/inactiva los campos no pertenecientes a la misma
		fn_cargarAuditoriasEquivalentes();
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
		if($("#tipo_explotacion").val() === 'SA'){
			if(Number.parseInt($("#num_animales").val()) < Number.parseInt('0')){
				error = true;
				$("#num_animales").addClass("alertaCombo");
	      }
		}
		
		//Información en tabla de Sitios y Áreas
		if ($("#iEstado").length > 0){
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

		if($("#tipo_solicitud").val() == 'Equivalente'){

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

	//Funciones	
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	//ok
	//Función para mostrar los datos del operador/asociación
    function fn_buscarDatosOperador() {
    	var asociacion = $("#es_asociacion").val();
        var identificador = identificadorUsuario;
        
        if (identificador !== "" && asociacion !== "" ){
        	mostrarMensaje("","EXITO");
        	$.post("<?php echo URL ?>CertificacionBPA/Solicitudes/obtenerDatosOperador",
               {
                identificador : identificadorUsuario,
                asociacion : $("#es_asociacion").val()
               }, function (data) {
				if(data.validacion == "Fallo"){
	        		mostrarMensaje(data.resultado,"FALLO");
	        		fn_cargarDatosOperador(data);	        		
				}else{
					fn_cargarDatosOperador(data);
					fn_bloquearDesbloquearCamposOperador();
					$("#identificador_operador").val(data.id);
				}
            }, 'json');
        }
    } 

	//ok
	//Función para mostrar los datos obtenidos del operador/asociación
    function fn_bloquearDesbloquearCamposOperador() {
    	if($("#es_asociacion").val() === 'Si'){
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

	//ok
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
		}
    } 

	//ok
	//Lista de Tipos de Productos por Área que dispone el operador/asociación
    function fn_cargarTipoProductos() {
		var asociacion = $("#es_asociacion").val();
		var identificador = $("#identificador_operador").val();
        var idTipoExplotacion = $("#tipo_explotacion").val();        
        
        if (idTipoExplotacion !== "" && asociacion !== "" && identificador !== ""){
            $.post("<?php echo URL ?>CertificacionBPA/Solicitudes/buscarTipoProductoXOperacionAreaOperador",
                {
                 id_area : $("#tipo_explotacion").val(),
                 identificador : $("#identificador_operador").val(),
                 asociacion : $("#es_asociacion").val()
                }, function (data) {
                $("#tipo_producto").removeAttr("disabled");
                $("#tipo_producto").html(data);               
            });
        }
    }

	//ok
  	//Lista de Subtipos de Productos por Tipo que dispone el operador/asociación
    function fn_cargarSubtipoProductos() {
		var asociacion = $("#es_asociacion").val();
		var identificador = $("#identificador_operador").val();
		var idTipoProducto = $("#tipo_producto option:selected").val();
        
        if (idTipoProducto !== "" && asociacion !== "" && identificador !== ""){
            $.post("<?php echo URL ?>CertificacionBPA/Solicitudes/buscarSubtipoProductoXOperacionAreaOperador",
                {
                 id_tipo_producto : $("#tipo_producto option:selected").val(),
                 identificador : $("#identificador_operador").val(),
                 asociacion : $("#es_asociacion").val()
                }, function (data) {
                $("#subtipo_producto").removeAttr("disabled");
                $("#subtipo_producto").html(data);               
            });
        }
    }

	//ok
  //Lista de Productos por Subtipo que dispone el operador/asociación
    function fn_cargarProductos() {
		var asociacion = $("#es_asociacion").val();
		var identificador = $("#identificador_operador").val();
		var idSubtipoProducto = $("#subtipo_producto option:selected").val();
        
        if (idSubtipoProducto !== "" && asociacion !== "" && identificador !== ""){
            $.post("<?php echo URL ?>CertificacionBPA/Solicitudes/buscarProductoXOperacionAreaOperador",
                {
                 id_subtipo_producto : $("#subtipo_producto option:selected").val(),
                 identificador : $("#identificador_operador").val(),
                 asociacion : $("#es_asociacion").val()
                }, function (data) {
                $("#producto").removeAttr("disabled");
                $("#producto").html(data);               
            });
        }
    }

	//ok
  	//Lista de Sitios y Áreas por Producto que dispone el operador/asociación
    function fn_cargarSitiosAreas() {
		var asociacion = $("#es_asociacion").val();
		var identificador = $("#identificador_operador").val();
		var idProducto = $("#producto option:selected").val();
        
        if (idProducto !== "" && asociacion !== "" && identificador !== ""){
            $.post("<?php echo URL ?>CertificacionBPA/Solicitudes/buscarSitioXProductoOperacionAreaOperador",
                {
                 id_producto : $("#producto option:selected").val(),
                 identificador : $("#identificador_operador").val(),
                 asociacion : $("#es_asociacion").val()
                }, function (data) {
                $("#sitio").removeAttr("disabled");
                $("#sitio").html(data);               
            });
        }
    }

  	//Funcion que agrega una fila a la lista 
    $('#btnAgregarSitios').click(function(){ 
    	$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

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
				fn_agregarDetalle();
				
			}else{
				$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
			}
		}

        fn_mostrarAuditoriaCambios();
        fn_cargarAuditorias();
    });	

  	//Funcion que agrega una fila de la lista 
    function fn_agregarDetalle() { 
    	$.post("<?php echo URL ?>CertificacionBPA/SitiosAreasProductos/guardarDetalle",
		        {                
		            id_solicitud: $("#id_solicitud").val(),
		            identificador_operador: $("#identificador_operador").val(),
		            identificador_sitio: $("#sitio option:selected").attr('data-identificadorSitio'),
		            id_sitio: $("#sitio option:selected").attr('data-idSitio'),
		            nombre_sitio: $("#sitio option:selected").attr('data-nombreSitio'),
		            id_area: $("#sitio option:selected").attr('data-idArea'),
		            nombre_area: $("#sitio option:selected").attr('data-nombreArea'),
		            id_subtipo_producto: $("#subtipo_producto option:selected").val(),
		            nombre_subtipo_producto: $("#subtipo_producto option:selected").text(),
		            id_producto: $("#producto option:selected").val(),
		            nombre_producto: $("#producto option:selected").text(),
		            id_operacion: $("#sitio option:selected").attr('data-idOperacion'),
		            nombre_operacion: $("#sitio option:selected").attr('data-nombreOperacion'),
		            superficie: $("#sitio option:selected").attr('data-hectareas'),
		            estado: $("#sitio option:selected").attr('data-estado')
		        },
		        function (data) {
		        	//if (data.validacion == 'Exito'){
		        		fn_mostrarDetalleEditableSitioAreaProducto();
			        	fn_cargarSitiosUnidadProduccion(null);
			        	limpiarDetalleDatosSitio();
			        	fn_calcularTotalHectareas();
			        	//fn_cargarAuditoriasSeleccionadas();
			        	//fn_cargarAuditorias();
			        	//fn_mostrarAuditoriaCambios();

			        /*	mostrarMensaje(data.resultado,"EXITO");
			        }else{
			        	mostrarMensaje(data.resultado,"FALLO");
			        }*/
		        }/*, 'json'*/);
    }

  	//Funcion que elimina una fila de la lista 
    function fn_eliminarDetalle(idDetalleSitio) { 
        $.post("<?php echo URL ?>CertificacionBPA/SitiosAreasProductos/borrar",
        {                
            elementos: idDetalleSitio
        },
        function (data) {
        	fn_mostrarDetalleEditableSitioAreaProducto();
        	fn_cargarSitiosUnidadProduccion(null);
        	fn_calcularTotalHectareas();
        	//fn_cargarAuditoriasSeleccionadas();
        	fn_cargarAuditorias();
        	//fn_mostrarAuditoriaCambios();
        	limpiarDetalleDatosSitio();
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

	//ok
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

	//ok
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

  	//Función para calcular las hectáreas de las áreas agregadas
	function fn_calcularTotalHectareas() {
		var id_solicitud = $("#id_solicitud").val();
		
		$.post("<?php echo URL ?>CertificacionBPA/SitiosAreasProductos/obtenerHectareas/"+id_solicitud, function (data) {
                	if(data.validacion == "Fallo"){
    	        		mostrarMensaje(data.resultado,"FALLO");
    	        		$("#num_hectareas").val('');	        		
    				}else{
    					$("#num_hectareas").val(data.hectareas);
    				}
                }, 'json');	      
	}

	//Función para activar/inactivar campos de la sección Alcance
    function fn_bloquearDesbloquearCamposAlcance() {
        if($("#tipo_solicitud").val() === 'Nacional'){
    		$(".equivalente").hide();
    		$("#codigo_equivalente").attr('disabled', 'disabled');
    		$("#fecha_inicio_equivalente").attr('disabled', 'disabled');
			$("#fecha_fin_equivalente").attr('disabled', 'disabled');
		}else{
			$(".equivalente").show();
			$("#codigo_equivalente").removeAttr('disabled');
			$("#fecha_inicio_equivalente").removeAttr('disabled');
			$("#fecha_fin_equivalente").removeAttr('disabled');
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
                 identificador_sitio : $("#sitio option:selected").attr('data-identificadorSitio')
                }, function (data) {
                	if(data.validacion == "Fallo"){
    	        		mostrarMensaje(data.resultado,"EXITO");	    		
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
		$(".subsanacion").attr('disabled', false);	
		 
	    $("#tbItems tbody tr").each(function(row){        

			filas=$('#tbItems tbody tr').length;
			
			if (filas>0){
				estado= $(this).find('td').eq(6).find('input[id="iEstado"]').val();

				switch (estado) {
					case 'Nuevo':
        				if($("#tipo_solicitud").val() === 'Equivalente'){
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
        				if($("#tipo_solicitud").val() === 'Equivalente'){
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
				  		if($("#tipo_solicitud").val() === 'Equivalente'){
				  			$(".Aprobado").attr('disabled', true);
				  			$(".Aprobado").prop("checked", false);
				      	}else{
				      		$(".Aprobado").attr('disabled', false);
				      	}

				  		break;
				  	case 'Expirado':
				  		if($("#tipo_solicitud").val() === 'Equivalente'){
				  			$(".Expirado").attr('disabled', true);
				  			$(".Expirado").prop("checked", false);
				      	}else{
				      		$(".Expirado").attr('disabled', false);
				      	}

				  		break;
				  	case 'subsanacion':
				  		if($("#tipo_solicitud").val() === 'Equivalente'){
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

	/////////
	//ok
	//Para cargar el detalle de sitios/áreas/productos registrados
    function fn_mostrarDetalleEditableSitioAreaProducto() {
        var idSolicitud = $("#id_solicitud").val();
        
    	$.post("<?php echo URL ?>CertificacionBPA/SitiosAreasProductos/construirDetalleSitioAreaProductoEdicion/" + idSolicitud, function (data) {
            $("#tbItems tbody").html(data);
        });
    }

	//ok
  	//Para cargar el combo con la lista de sitios de unidad de producción 
  	//con los Sitios y Áreas por Producto que dispone el operador/asociación
    function fn_cargarSitiosUnidadProduccion(idSitioUnidad) {
		var idSolicitud = $("#id_solicitud").val();
        
        if (idSolicitud !== ""){
            $.post("<?php echo URL ?>CertificacionBPA/SitiosAreasProductos/buscarSitiosUnidadProduccion",
            {
            	idSolicitud : $("#id_solicitud").val(),
            	idSitio : idSitioUnidad
            					
            }, function (data) {
                $("#id_sitio_unidad_produccion").html(data);
            });
        }
    }

  	//ok-revisar
  	//Para cargar la tabla de tipos de auditorías con los elementos registrados
    function fn_cargarAuditoriasSeleccionadas() {
		var idSolicitud = $("#id_solicitud").val();
        
        if (idSolicitud !== ""){
            $.post("<?php echo URL ?>CertificacionBPA/AuditoriasSolicitadas/checkTiposAuditoriaDetalle/"+idSolicitud, function (data) {
                $("#contenedorAuditoria").html(data);
                fn_mostrarAuditoriaCambios();
            });
        }
    }

  //Para cargar la tabla de tipos de auditorías con los elementos registrados
    function fn_cargarAuditorias() {        
        var i = 0;

        fn_mostrarAuditoriaCambios();

        for (i=0; i<auditorias.length; i++){	
            $("#c"+auditorias[i]).prop("checked", true);
            $("#c"+auditorias[i]).prop("disabled", false);
		}

        //fn_mostrarAuditoriaCambios();
    }

  //Función para carga de archivo de plan de acción
    $('button.subirArchivoPlan').click(function (event) {
    	var nombre_archivo = "<?php echo 'plan_accion_' . time(); ?>";
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

    //Función para mostrar las opciones de auditorías de tipo Equivalentes
    function fn_cargarAuditoriasEquivalentes() {
        var tipoSolicitud = $("#tipo_solicitud").val();
        
        if(tipoSolicitud === "Equivalente") {
            $(".Nuevo").hide();
        	$(".Aprobado").hide();
        	$(".Expirado").hide();
        }else{
            $(".NuevoEquivalente").hide();
        }
    }

</script>
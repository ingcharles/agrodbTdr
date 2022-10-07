<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<div id="equino" class="pestania">
	<fieldset>
		<legend>Datos del Propietario</legend>				

		<div data-linea="1">
			<label>Organización Ecuestre: </label> <?php echo ($this->modeloEquinos->getEstadoEquino()!='Liberado'? $this->asociacion : 'Pendiente de asignación') ; ?>
		</div>

		<div data-linea="2">
			<label>Cédula del Propietario: </label> <?php echo $this->modeloMiembros->identificadorMiembro; ?>
		</div>
		
		<div data-linea="3">
			<label>Nombre del Propietario: </label> <?php echo $this->modeloMiembros->nombreMiembro; ?>
		</div>

		<div data-linea="4">
			<label>Provincia: </label> <?php echo $this->modeloCatastroPredioEquidos->provincia; ?>
		</div>				
    </fieldset>
        
    <form id='formularioEquino' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>PasaporteEquino' data-opcion='equinos/guardar' data-destino="detalleItem" method="post" <?php echo ($this->menu == 'liberacionTraspaso' ? 'data-accionEnExito="ACTUALIZAR"' : ($this->menu == 'deceso' ? 'data-accionEnExito="ACTUALIZAR"':'data-accionEnExito="ACTUALIZAR"'));?>><!--  class="editable" -->
    	<input type="hidden" id="id_equino" name="id_equino" value="<?php echo $this->modeloEquinos->getIdEquino(); ?>" />
    	<input type="hidden" id="idAsociacion" name="idAsociacion" value="<?php echo $this->idAsociacion; ?>" />
    	
    	<fieldset>
    		<legend>Datos generales del Equino</legend>
    		
    		<div data-linea="5">
    			<label for="nombre_equino">Nombre: </label> <?php echo $this->modeloEquinos->getNombreEquino(); ?>
    		</div>
    		
    		<div data-linea="5">
    			<label for="pasaporte">Pasaporte: </label> <?php echo $this->modeloEquinos->getPasaporte(); ?>
    		</div>				
    		
    		<div data-linea="6">
    			<label for="id_especie">Especie: </label><?php echo $this->modeloCatastroPredioEquidosEspecie->current()->nombre_especie; ?>
    		</div>				
    
    		<div data-linea="6">
    			<label for="id_raza">Raza: </label><?php echo $this->modeloCatastroPredioEquidosEspecie->current()->nombre_raza; ?>
    		</div>				
    
    		<div data-linea="7">
    			<label for="id_categoria">Categoría: </label><?php echo $this->modeloCatastroPredioEquidosEspecie->current()->nombre_categoria; ?>
    		</div>
    		
    		<div data-linea="7">
    			<label for="sexo">Sexo: </label>
    			<select id="sexo" name="sexo" >
    				<option value>Seleccione....</option>
    				<?php echo $this->comboGenero($this->modeloEquinos->getSexo()); ?>
    			</select>
    		</div>
    		
    		<div data-linea="8">
    			<label for="fecha_nacimiento">Fecha de nacimiento: </label>
    			<input type="text" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo ($this->modeloEquinos->getFechaNacimiento()!=null?date('Y-m-d',strtotime($this->modeloEquinos->getFechaNacimiento())):''); ?>" readonly="readonly" maxlength="8" />
    			
    		</div>
    		
    		<div data-linea="9">
    			<label for="tipo_identificacion">Identificación adicional: </label>
    			<select id="tipo_identificacion" name="tipo_identificacion" >
    				<option value>Seleccione....</option>
    				<?php echo $this->comboIdentificacionEquino($this->modeloEquinos->getTipoIdentificacion()); ?>
    			</select>
    		</div>				
    
    		<div data-linea="9">
    			<label for="detalle_identificacion">Número: </label>
    			<input type="text" id="detalle_identificacion" name="detalle_identificacion" value="<?php echo $this->modeloEquinos->getDetalleIdentificacion(); ?>" maxlength="32" />
    		</div>
    		
    		<div data-linea="10">
    			<label for="ruta_hoja_filiacion">Hoja de filiación: </label>
    			<?php echo ($this->modeloEquinos->getRutaHojaFiliacion() != '' ? '<a href="'.URL_GUIA_PROYECTO . '/' .$this->modeloEquinos->getRutaHojaFiliacion().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click para descargar documento</a>' : 'No hay un archivo adjunto'); ?>
		
    			<input type="file" id="archivo" class="archivo" accept="application/pdf" /> 
    			<input type="hidden" class="rutaArchivo" name="ruta_hoja_filiacion" id="ruta_hoja_filiacion" value="<?php echo $this->modeloEquinos->getRutaHojaFiliacion(); ?>" />
    				
        		<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
        		<button id="botonFiliacion" type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo PAS_EQUI_URL . $this->modeloEquinos->getPasaporte();?>">Subir archivo</button>
    		</div>
    	</fieldset>
    	
    	<fieldset>
    		<legend>Estado y Motivo de cambio</legend>
    		
    		<div data-linea="1">
    			<label for="estado_equino">Estado: </label>
    			<select id="estado_equino" name="estado_equino" >
    				<option value>Seleccione....</option>
    				<?php 
    				switch($this->menu){
    				    case  'emisionPasaporte':
    				        echo $this->comboActivoInactivo($this->modeloEquinos->getEstadoEquino());
    				        break;
    				    case  'liberacionTraspaso':
    				        if($this->modeloEquinos->getEstadoEquino() == 'Activo' || $this->modeloEquinos->getEstadoEquino() == 'Inactivo'){
    				            echo $this->comboLiberacionTraspasoEquino($this->modeloEquinos->getEstadoEquino());
    				            
    				        }else if($this->modeloEquinos->getEstadoEquino() == 'Liberado'){
    				            echo $this->comboVinculacionEquino($this->modeloEquinos->getEstadoEquino()); 
    				            
    				        }else{
    				            echo $this->comboDecesoEquino($this->modeloEquinos->getEstadoEquino());
    				        }
    				        break;
    				    case  'deceso':
    				        if($this->modeloEquinos->getEstadoEquino() == 'Activo' || $this->modeloEquinos->getEstadoEquino() == 'Inactivo'){
    				            echo $this->comboDecesoEquino($this->modeloEquinos->getEstadoEquino());    				            
    				        }
    				        break;
    				    default:    				        
    				        break;
    				}    				
    				    //echo ($this->menu == 'emisionPasaporte'?$this->comboActivoInactivo($this->modeloEquinos->getEstadoEquino()):($this->menu == 'liberacionTraspaso'?$this->comboLiberacionTraspasoEquino($this->modeloEquinos->getEstadoEquino()):($this->menu == 'deceso'?$this->comboDecesoEquino($this->modeloEquinos->getEstadoEquino()):''))); 
    				?>
    			</select>
    		</div>	
    		
    		<div data-linea="2" class="documento">
    			<label for="motivo_cambio">Motivo de cambio: </label><?php echo ($this->modeloEquinos->getMotivoCambio()!=''?$this->modeloEquinos->getMotivoCambio():'No se han realizado cambios'); ?>
    		</div>	
    		
    		<div data-linea="3" class="documento">
    			<label for="ruta_motivo_cambio">Documento anexo: </label>
    			<?php echo ($this->modeloEquinos->getRutaMotivoCambio() != '' ? '<a href="'.URL_GUIA_PROYECTO . '/' .$this->modeloEquinos->getRutaMotivoCambio().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click para descargar documento</a>' : 'No hay un archivo adjunto'); ?>		
    		</div>		
    
    		<div data-linea="4" class="motivo">
    			<label for="motivo_cambio">Motivo de cambio: </label>
    			<input type="text" id="motivo_cambio" name="motivo_cambio" maxlength="512" disabled="disabled"/>
    		</div>	
    		
    		<div data-linea="5" class="motivo">
    			<label for="ruta_motivo_cambio">Documento anexo: </label>
    			
    			<input type="file" id="archivo_cambio" class="archivo" accept="application/pdf" /> 
    			<input type="hidden" class="rutaArchivoMotivoCambio" name="ruta_motivo_cambio" id="ruta_motivo_cambio" value="<?php //echo $this->modeloEquinos->getRutaMotivoCambio(); ?>" />
    				
        		<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
        		<button type="button" class="subirArchivoMotivoCambio adjunto" data-rutaCarga="<?php echo PAS_EQUI_URL . $this->modeloEquinos->getPasaporte();?>">Subir archivo</button>
    		</div>	
    		
    		<div data-linea="6" class="vinculacion">
    			<label for="provincia">Provincia: </label>
    			<select id="provincia" name="provincia" disabled="disabled" >
    				<?php echo $this->comboProvinciasXOrganizacionEcuestre(); ?>
    			</select>
    		</div>
    		
    		<div data-linea="7" class="vinculacion">
    			<label for="id_organizacion_ecuestre">Organización ecuestre: </label>
    			<select id="id_organizacion_ecuestre" name="id_organizacion_ecuestre" disabled="disabled" >
    			</select>
    		</div>
    		
    		<div data-linea="8" class="traspaso">
    			<label for="id_miembro">Miembro: </label>
    			<select id="id_miembro" name="id_miembro" disabled="disabled" >
    			</select>
    		</div>
    		
    		<div data-linea="9" class="deceso">
    			<label for="fecha_deceso">Fecha deceso: </label>
    			<input type="text" id="fecha_deceso" name="fecha_deceso" value="<?php echo $this->modeloEquinos->getFechaDeceso(); ?>" readonly="readonly" disabled="disabled" />
    		</div>				
    
    		<div data-linea="10" class="deceso">
    			<label for="causa_muerte">Causa de muerte: </label>
    			<select id="causa_muerte" name="causa_muerte" disabled="disabled" >
    			<?php echo $this->comboEnfermedadesEquinas($this->modeloEquinos->getCausaMuerte()); ?>
    			</select>
    		</div>				
    
    		<div data-linea="11" class="deceso">
    			<label for="motivo_deceso">Observación: </label>
    			<input type="text" id="motivo_deceso" name="motivo_deceso" value="<?php echo $this->modeloEquinos->getMotivoDeceso(); ?>" maxlength="512" disabled="disabled" />
    		</div>
    	</fieldset>
    	
    	<div data-linea="11">
			<button type="submit" class="guardar">Guardar</button>
		</div>	
	</form >
</div>	
		
<div id="examen" class="pestania">

	<form id='formularioExamenes' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>PasaporteEquino' data-opcion='ExamenesEquino/guardar' data-destino="detalleItem" method="post" class="editable">
		<input type="hidden" id="id_equino" name="id_equino" value="<?php echo $this->modeloEquinos->getIdEquino(); ?>" />
		
    	<fieldset>
    		<legend>Exámenes de Anemia Infecciosa Equina</legend>				
    
    		<div data-linea="1">
    			<label for="fecha_examen">Fecha: </label>
    			<input type="text" id="fecha_examen" name="fecha_examen" readonly="readonly" maxlength="8" />
    		</div>
    		
    		<div data-linea="2">
    			<label for="resultado_examen">Resultado: </label>
    			<select id="resultado_examen" name="resultado_examen" >
                    <option value>Seleccionar....</option>
                    <?php
                        echo $this->comboPositivoNegativo();
                    ?>
                </select>
    		</div>				
    
    		<div data-linea="3">
    			<label for="laboratorio">Laboratorio: </label>
    			<input type="text" id="laboratorio" name="laboratorio" maxlength="128" />
    		</div>				
    
    		<div data-linea="4">
    			<label for="num_informe">N° Informe: </label>
    			<input type="text" id="num_informe" name="num_informe" maxlength="16" />
    		</div>				

    		<div data-linea="5">
    			<button type="submit" class="guardar">Agregar</button>
    		</div>
    	</fieldset >
    </form >

	<div id="tablaExamenes">
        <fieldset>
        	<legend>Detalle de exámenes</legend>
            	<div data-linea="3">
        			<table id="tbItemsExamenes" style="width:100%">
        				<thead>
        					<tr>
        						<th style="width: 5%;">#</th>
        						<th style="width: 20%;">Fecha</th>
        						<th style="width: 30%;">Laboratorio</th>
        						<th style="width: 20%;">Informe</th>
        						<th style="width: 20%;">Resultado</th>
                                <th style="width: 5%;"></th>
        					</tr>
        				</thead>
        				<tbody>
        				</tbody>
        			</table>
        		</div>		
    	</fieldset>
    </div>
    
</div>	
		
<div id="vacuna" class="pestania">    
  
  <form id='formularioVacunas' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>PasaporteEquino' data-opcion='VacunasEquino/guardar' data-destino="detalleItem" method="post" class="editable">
		<input type="hidden" id="id_equino" name="id_equino" value="<?php echo $this->modeloEquinos->getIdEquino(); ?>" />
		
    	<fieldset>
    		<legend>Vacunaciones</legend>				
    
    		<div data-linea="1">
    			<label for="fecha_enfermedad">Fecha: </label>
    			<input type="text" id="fecha_enfermedad" name="fecha_enfermedad" readonly="readonly" maxlength="8" />
    		</div>
    		
    		<div data-linea="2">
    			<label for="enfermedad">Enfermedad: </label>
    			<input type="text" id="enfermedad" name="enfermedad" maxlength="128" />
    		</div>				
    
    		<div data-linea="3">
    			<label for="laboratorio_lote">Laboratorio / Lote: </label>
    			<input type="text" id="laboratorio_lote" name="laboratorio_lote" maxlength="256" />
    		</div>				

    		<div data-linea="4">
    			<button type="submit" class="guardar">Agregar</button>
    		</div>
    	</fieldset >
    </form >

	<div id="tablaVacunas">
        <fieldset>
        	<legend>Detalle de vacunaciones</legend>
            	<div data-linea="5">
        			<table id="tbItemsVacunas" style="width:100%">
        				<thead>
        					<tr>
        						<th style="width: 5%;">#</th>
        						<th style="width: 20%;">Fecha</th>
        						<th style="width: 30%;">Enfermedad</th>
        						<th style="width: 40%;">Laboratorio / Lote</th>
                                <th style="width: 5%;"></th>
        					</tr>
        				</thead>
        				<tbody>
        				</tbody>
        			</table>
        		</div>		
    	</fieldset>
    </div>
  
</div>	
		
<div id="fotos" class="pestania">    
   
   	<fieldset id="fotos">
		<legend>Fotos del equino</legend>	
		
		<div data-linea="1">
			<img name="ifFrente" id="ifFrente" src="<?php echo ($this->modeloEquinos->getFotoFrente()!=''?$this->modeloEquinos->getFotoFrente():PAS_EQUI_URL_IMG_DEF);?>" width="200" height="200">
			<br />
			<label for="foto_frente">Foto frontal: </label>
			
			<div class="editable">
    			<input type="hidden" id="id_equino" name="id_equino" value="<?php echo $this->modeloEquinos->getIdEquino(); ?>" />
    			<input type="hidden" id="bandera" name="bandera" class="bandera" value="Frente" />
    			
    			<input type="file" id="archivoFotoFrente" class="archivo" accept="image/jpeg" />
    			<input type="hidden" class="rutaArchivo" name="foto_frente" id="foto_frente"  />
    			
    			<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
    			<button type="button" class="subirArchivoFoto adjunto" data-rutaCarga="<?php echo PAS_EQUI_URL . $this->modeloEquinos->getPasaporte();?>">Subir archivo</button>
			</div>
		</div>	
		
		<div data-linea="1">
			<img name="ifAtras" id="ifAtras" src="<?php echo ($this->modeloEquinos->getFotoAtras()!=''?$this->modeloEquinos->getFotoAtras():PAS_EQUI_URL_IMG_DEF);?>" width="200" height="200">
			<br />
			<label for="foto_atras">Foto posterior: </label>
			
			<div class="editable">    			
    			<input type="hidden" id="id_equino" name="id_equino" value="<?php echo $this->modeloEquinos->getIdEquino(); ?>" />
    			<input type="hidden" id="bandera" name="bandera" class="bandera" value="Atras" />
    			
    			<input type="file" id="archivoFotoAtras" class="archivo" accept="image/jpeg" />
    			<input type="hidden" class="rutaArchivo" name="foto_atras" id="foto_atras"  />
    			
    			<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
    			<button type="button" class="subirArchivoFoto adjunto" data-rutaCarga="<?php echo PAS_EQUI_URL . $this->modeloEquinos->getPasaporte();?>">Subir archivo</button>
    		</div>
		</div>	
		
		<div data-linea="2">
			<img name="ifDerecha" id="ifDerecha" src="<?php echo ($this->modeloEquinos->getFotoDerecha()!=''?$this->modeloEquinos->getFotoDerecha():PAS_EQUI_URL_IMG_DEF);?>" width="200" height="200">
			<br />
			<label for="foto_derecha">Foto lateral derecha: </label>
			
			<div class="editable">    			
    			<input type="hidden" id="id_equino" name="id_equino" value="<?php echo $this->modeloEquinos->getIdEquino(); ?>" />
    			<input type="hidden" id="bandera" name="bandera" class="bandera" value="Derecha" />
    				
    			<input type="file" id="archivoFotoDerecha" class="archivo" accept="image/jpeg" />
    			<input type="hidden" class="rutaArchivo" name="foto_derecha" id="foto_derecha"  />
    				
    			<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
    			<button type="button" class="subirArchivoFoto adjunto" data-rutaCarga="<?php echo PAS_EQUI_URL . $this->modeloEquinos->getPasaporte();?>">Subir archivo</button>
    		</div>
		</div>	
		
		<div data-linea="2">
			<img name="ifIzquierda" id="ifIzquierda" src="<?php echo ($this->modeloEquinos->getFotoIzquierda()!=''?$this->modeloEquinos->getFotoIzquierda():PAS_EQUI_URL_IMG_DEF);?>" width="200" height="200">
			<br />
			<label for="foto_izquierda">Foto lateral izquierda: </label>
			
			<div class="editable">    			
    			<input type="hidden" id="id_equino" name="id_equino" value="<?php echo $this->modeloEquinos->getIdEquino(); ?>" />
    			<input type="hidden" id="bandera" name="bandera" class="bandera" value="Izquierda" />
    				
    			<input type="file" id="archivoFotoIzquierda" class="archivo" accept="image/jpeg" />
    			<input type="hidden" class="rutaArchivo" name="foto_izquierda" id="foto_izquierda"  />
    				
    			<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
    			<button type="button" class="subirArchivoFoto adjunto" data-rutaCarga="<?php echo PAS_EQUI_URL . $this->modeloEquinos->getPasaporte();?>">Subir archivo</button>
    		</div>
		</div>	
	</fieldset>
	
</div>

<script type ="text/javascript">
var bandera = <?php echo json_encode($this->formulario); ?>;
var combo = "<option>Seleccione....</option>";
var estadoEquino = <?php echo json_encode($this->modeloEquinos->getEstadoEquino()); ?>;

	$(document).ready(function() {
		construirAnimacion($(".pestania"));
		construirValidador();
		distribuirLineas();

		if(bandera!='editarEquino'){
			$(".editable").hide();
		}else{
			$(".editable").show();
		}

		
		///Paso 1
		$(".documento").show();
		$(".motivo").hide();
		$(".vinculacion").hide();
		$(".traspaso").hide();
		$(".deceso").hide();

		if (estadoEquino == 'Liberado'){
			$("#sexo").attr('disabled','disabled');
			$("#fecha_nacimiento").attr('disabled','disabled');
			$("#tipo_identificacion").attr('disabled','disabled');
			$("#detalle_identificacion").attr('disabled','disabled');
			$("#ruta_hoja_filiacion").attr('disabled','disabled');
			$("#botonFiliacion").attr('disabled','disabled');
		}

		if(<?php echo json_encode($this->modeloEquinos->getSexo()); ?> != ''){
			$("#sexo").attr('disabled','disabled');
		}
		if(<?php echo json_encode($this->modeloEquinos->getFechaNacimiento()); ?> != ''){
			$("#fecha_nacimiento").attr('disabled','disabled');
		}
		if(<?php echo json_encode($this->modeloEquinos->getTipoIdentificacion()); ?> != ''){
			$("#tipo_identificacion").attr('disabled','disabled');
		}
		if(<?php echo json_encode($this->modeloEquinos->getDetalleIdentificacion()); ?> != ''){
			$("#detalle_identificacion").attr('disabled','disabled');
		}
		if(<?php echo json_encode($this->modeloEquinos->getRutaHojaFiliacion()); ?> != ''){
			$("#ruta_hoja_filiacion").attr('disabled','disabled');
			$("#botonFiliacion").attr('disabled','disabled');
		}

		///Paso 2
		fn_mostrarDetalleExamenes();

		///Paso 3
		fn_mostrarDetalleVacunas();
				
	 });

	///Paso 1
	$("#fecha_nacimiento").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd'
	 });

	$("#fecha_deceso").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd'
	 });

	function fn_limpiar() {
    	$(".alertaCombo").removeClass("alertaCombo");
    	$('#estado').html('');
    }

	$("#detalle_identificacion").change(function () {
		if (($(this).val !== "")  ) {
			fn_validarIdentificacionAdicional();
        }
    });
    
    //Función para buscar si existe un producto con el nombre ingresado
    function fn_validarIdentificacionAdicional() {
    	var tipoIdentificacion = $("#tipo_identificacion option:selected").val();
    	var detalleIdentificacion = $("#detalle_identificacion").val();

        if(tipoIdentificacion != '' && detalleIdentificacion!=''){
            $.post("<?php echo URL ?>PasaporteEquino/Equinos/validarIdentificacionAdicional",
                    {
                    	tipoIdentificacion : tipoIdentificacion,
                    	detalleIdentificacion : detalleIdentificacion
                    }, function (data) {
                    	if(data.validacion == "Exito"){
        					mostrarMensaje(data.nombre,"EXITO");
        				}else{					
        					mostrarMensaje(data.nombre,"FALLO");
        	        		$("#detalle_identificacion").val("");
        	        		alert('El tipo de identificación y número ya se encuentran registrados.');
        				}
                 }, 'json');
        }else{
        	if(!$.trim($("#tipo_identificacion").val())){
    			$("#tipo_identificacion").addClass("alertaCombo");
    		}

        	if(!$.trim($("#detalle_identificacion").val())){
    			$("#detalle_identificacion").addClass("alertaCombo");
    		}
        }
    }

	$('button.subirArchivo').click(function (event) {
        var idExpediente = <?php echo json_encode($this->modeloEquinos->getPasaporte());?>;
    	var nombre_archivo = "HojaFiliacionEquino_"+idExpediente;
        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF' || extension[extension.length - 1].toUpperCase() == 'PDF') {

            subirArchivo(
                archivo
                , nombre_archivo
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
            );

            $('#ruta_hoja_filiacion').val("<?php echo PAS_EQUI_URL.$this->modeloEquinos->getPasaporte(); ?>/"+nombre_archivo+".PDF");
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
    });

	$("#estado_equino").change(function () {
		$("#motivo_cambio").attr('disabled', 'disabled');
		$("#provincia").attr('disabled', 'disabled');
		$("#id_organizacion_ecuestre").attr('disabled', 'disabled');
		$("#id_miembro").attr('disabled', 'disabled');
		$("#fecha_deceso").attr('disabled', 'disabled');
		$("#causa_muerte").attr('disabled', 'disabled');
		$("#motivo_deceso").attr('disabled', 'disabled');

		if ($("#estado_equino option:selected").val() != '') {
			if($("#estado_equino option:selected").val()=='Activo' || $("#estado_equino option:selected").val()=='Inactivo' || $("#estado_equino option:selected").val()=='Liberado'){
    			if($("#estado_equino option:selected").val()!=estadoEquino){
    				$(".motivo").show();
    				$(".documento").hide();
    				$(".vinculacion").hide();
    				$(".traspaso").hide();
    				$(".deceso").hide();

    				$("#motivo_cambio").removeAttr('disabled');
    				$("#provincia").attr('disabled', 'disabled');
    				$("#id_organizacion_ecuestre").attr('disabled', 'disabled');
    				$("#id_miembro").attr('disabled', 'disabled');
    				$("#fecha_deceso").attr('disabled', 'disabled');
    				$("#causa_muerte").attr('disabled', 'disabled');
    				$("#motivo_deceso").attr('disabled', 'disabled');
    				
    			}else{
    				$(".motivo").hide();
    				$(".documento").show();
    				$(".vinculacion").hide();
    				$(".traspaso").hide();
    				$(".deceso").hide();

    				$("#motivo_cambio").attr('disabled', 'disabled');
    				$("#provincia").attr('disabled', 'disabled');
    				$("#id_organizacion_ecuestre").attr('disabled', 'disabled');
    				$("#id_miembro").attr('disabled', 'disabled');
    				$("#fecha_deceso").attr('disabled', 'disabled');
    				$("#causa_muerte").attr('disabled', 'disabled');
    				$("#motivo_deceso").attr('disabled', 'disabled');
    			}	
			}else if ($("#estado_equino option:selected").val()=='Vinculacion'){
				$(".motivo").hide();
				$(".documento").hide();
				$(".vinculacion").show();
				$(".traspaso").hide();
				$(".deceso").hide();

				$("#motivo_cambio").attr('disabled', 'disabled');
				$("#provincia").removeAttr('disabled');
				$("#id_organizacion_ecuestre").attr('disabled', 'disabled');
				$("#id_miembro").attr('disabled', 'disabled');
				$("#fecha_deceso").attr('disabled', 'disabled');
				$("#causa_muerte").attr('disabled', 'disabled');
				$("#motivo_deceso").attr('disabled', 'disabled');
				
			}else if ($("#estado_equino option:selected").val()=='Traspaso'){
				$(".motivo").hide();
				$(".documento").hide();
				$(".vinculacion").show();
				$(".traspaso").show();
				$(".deceso").hide();

				$("#motivo_cambio").attr('disabled', 'disabled');
				$("#provincia").removeAttr('disabled');
				$("#id_organizacion_ecuestre").attr('disabled', 'disabled');
				$("#id_miembro").attr('disabled', 'disabled');
				$("#fecha_deceso").attr('disabled', 'disabled');
				$("#causa_muerte").attr('disabled', 'disabled');
				$("#motivo_deceso").attr('disabled', 'disabled');
				
			}else if ($("#estado_equino option:selected").val()=='Deceso'){
				$(".motivo").hide();
				$(".documento").hide();
				$(".vinculacion").hide();
				$(".traspaso").hide();
				$(".deceso").show();

				$("#motivo_cambio").attr('disabled', 'disabled');
				$("#provincia").attr('disabled', 'disabled');
				$("#id_organizacion_ecuestre").attr('disabled', 'disabled');
				$("#id_miembro").attr('disabled', 'disabled');
				$("#fecha_deceso").removeAttr('disabled');
				$("#causa_muerte").removeAttr('disabled');
				$("#motivo_deceso").removeAttr('disabled');
			}else{
				$(".motivo").hide();
				$(".documento").show();
				$(".vinculacion").hide();
				$(".traspaso").hide();
				$(".deceso").show();

				$("#motivo_cambio").attr('disabled', 'disabled');
				$("#provincia").attr('disabled', 'disabled');
				$("#id_organizacion_ecuestre").attr('disabled', 'disabled');
				$("#id_miembro").attr('disabled', 'disabled');
				$("#fecha_deceso").attr('disabled', 'disabled');
				$("#causa_muerte").attr('disabled', 'disabled');
				$("#motivo_deceso").attr('disabled', 'disabled');
			}	
        }else{
			alert('Debe seleccionar un estado para el equino');
			$(".motivo").hide();
			$(".documento").show();
			$(".vinculacion").hide();
			$(".traspaso").hide();
			$(".deceso").show();

			$("#motivo_cambio").attr('disabled', 'disabled');
			$("#provincia").attr('disabled', 'disabled');
			$("#id_organizacion_ecuestre").attr('disabled', 'disabled');
			$("#id_miembro").attr('disabled', 'disabled');
			$("#fecha_deceso").attr('disabled', 'disabled');
			$("#causa_muerte").attr('disabled', 'disabled');
			$("#motivo_deceso").attr('disabled', 'disabled');
        }
    });
    
	$('button.subirArchivoMotivoCambio').click(function (event) {
        var idExpediente = <?php echo json_encode($this->modeloEquinos->getPasaporte());?>;
    	var nombre_archivo = "MotivoCambioEquino_"+idExpediente;
        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF' || extension[extension.length - 1].toUpperCase() == 'PDF') {

            subirArchivo(
                archivo
                , nombre_archivo
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
            );

            $('#ruta_motivo_cambio').val("<?php echo PAS_EQUI_URL.$this->modeloEquinos->getPasaporte(); ?>/"+nombre_archivo+".PDF");
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
    });

	$("#provincia").change(function () {
		$("#id_organizacion_ecuestre").html(combo);
		$("#id_organizacion_ecuestre").attr('disabled', 'disabled');
		$("#id_miembro").attr('disabled', 'disabled');
		
		if ($("#provincia option:selected").val() != '' ) {
			fn_buscarOrganizacionesXProvincia();
        }else{
			alert('Debe seleccionar una provincia');
			$("#id_organizacion_ecuestre").html(combo);
        }
    });

  	//Función para mostrar las organizaciones ecuetsres registradas por provincia
    function fn_buscarOrganizacionesXProvincia() {
    	var provincia = $("#provincia option:selected").val();
        
        if (provincia != ""){
        	$("#id_organizacion_ecuestre").removeAttr('disabled');
        	
        	$.post("<?php echo URL ?>PasaporteEquino/Equinos/comboOrganizacionesXProvincia",
               {
        		provincia : provincia
               }, function (data) {
            	   $("#id_organizacion_ecuestre").html(data);
            });
        }else{
            $("#id_organizacion_ecuestre").html(combo);
        	
        	if(!$.trim($("#provincia").val())){
    			$("#provincia").addClass("alertaCombo");
    		}

        	$("#id_organizacion_ecuestre").attr('disabled', 'disabled');
    		$("#id_miembro").attr('disabled', 'disabled');

        	$("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
    	}     
    }

    $("#id_organizacion_ecuestre").change(function () {
		$("#id_miembro").html(combo);
		$("#id_miembro").attr('disabled', 'disabled');

		if($("#estado_equino option:selected").val()=='Traspaso'){
    		if ($("#id_organizacion_ecuestre option:selected").val() != '' ) {
    			fn_buscarMiembroXOrganizacionesXProvincia();
            }else{
    			alert('Debe seleccionar una provincia y una organización ecuestre');
    			$("#id_miembro").html(combo);
            }
		}else{
			$("#id_miembro").attr('disabled', 'disabled');
		}
    });

  	//Función para mostrar los miembros por organizaciones ecuestres registradas por provincia
    function fn_buscarMiembroXOrganizacionesXProvincia() {
    	var provincia = $("#provincia option:selected").val();
    	var idOrganizacionEcuestre = $("#id_organizacion_ecuestre option:selected").val();
    	var idMiembroActual = $("#id_organizacion_ecuestre option:selected").val();
        
        if (provincia != "" && idOrganizacionEcuestre != ""){
        	$("#id_miembro").removeAttr('disabled');
            
        	$.post("<?php echo URL ?>PasaporteEquino/Equinos/comboMiembroXOrganizacionesXProvincia",
               {
        		provincia : provincia,
        		idOrganizacionEcuestre : idOrganizacionEcuestre,
        		idMiembroActual : <?php echo json_encode($this->modeloEquinos->getIdMiembro()); ?>
               }, function (data) {
            	   $("#id_miembro").html(data);
            });
        }else{
            $("#id_miembro").html(combo);
        	
        	if(!$.trim($("#provincia").val())){
    			$("#provincia").addClass("alertaCombo");
    		}

        	if(!$.trim($("#id_organizacion_ecuestre").val())){
    			$("#id_organizacion_ecuestre").addClass("alertaCombo");
    		}

        	$("#id_miembro").attr('disabled', 'disabled');

        	$("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
    	}     
    }

	$("#formularioEquino").submit(function (event) {
		fn_limpiar();
		event.preventDefault();
		var error = false;
		
		if(!$.trim($("#sexo option:selected").val())){
        	error = true;
        	$("#sexo").addClass("alertaCombo");
		}

        if(!$.trim($("#fecha_nacimiento").val())){
        	error = true;
        	$("#fecha_nacimiento").addClass("alertaCombo");
		}

        if(!$.trim($("#tipo_identificacion option:selected").val())){
        	error = true;
        	$("#tipo_identificacion").addClass("alertaCombo");
		}

        if(!$.trim($("#detalle_identificacion").val())){
        	error = true;
        	$("#detalle_identificacion").addClass("alertaCombo");
		}

        if(!$.trim($("#ruta_hoja_filiacion").val())){
        	error = true;
        	$("#archivo").addClass("alertaCombo");
		}

        if(!$.trim($("#estado_equino option:selected").val())){
        	error = true;
        	$("#estado_equino").addClass("alertaCombo");
		}else{
			if($("#estado_equino option:selected").val() == 'Activo' || $("#estado_equino option:selected").val() == 'Inactivo' || $("#estado_equino option:selected").val() == 'Liberado'){
    			if($("#estado_equino option:selected").val() != estadoEquino){
    				
    				if(!$.trim($("#motivo_cambio").val())){
    		        	error = true;
    		        	$("#motivo_cambio").addClass("alertaCombo");
    				}
    
    				if(!$.trim($("#ruta_motivo_cambio").val())){
    		        	error = true;
    		        	$("#archivo_cambio").addClass("alertaCombo");
    				}
    			}
    			
			}else if($("#estado_equino option:selected").val() == 'Vinculacion'){
				if(!$.trim($("#provincia option:selected").val())){
		        	error = true;
		        	$("#provincia").addClass("alertaCombo");
				}

				if(!$.trim($("#id_organizacion_ecuestre option:selected").val())){
		        	error = true;
		        	$("#id_organizacion_ecuestre").addClass("alertaCombo");
				}
				
			}else if($("#estado_equino option:selected").val() == 'Traspaso'){
				if(!$.trim($("#provincia option:selected").val())){
		        	error = true;
		        	$("#provincia").addClass("alertaCombo");
				}

				if(!$.trim($("#id_organizacion_ecuestre option:selected").val())){
		        	error = true;
		        	$("#id_organizacion_ecuestre").addClass("alertaCombo");
				}

				if(!$.trim($("#id_miembro option:selected").val())){
		        	error = true;
		        	$("#id_miembro").addClass("alertaCombo");
				}
				
			}else if($("#estado_equino option:selected").val() == 'Deceso'){
				if(!$.trim($("#fecha_deceso").val())){
		        	error = true;
		        	$("#fecha_deceso").addClass("alertaCombo");
				}

				if(!$.trim($("#causa_muerte option:selected").val())){
		        	error = true;
		        	$("#causa_muerte").addClass("alertaCombo");
				}

				if(!$.trim($("#motivo_deceso").val())){
		        	error = true;
		        	$("#motivo_deceso").addClass("alertaCombo");
				}
			}
		}
		
        if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
	       		mostrarMensaje(respuesta.mensaje,"EXITO");
	        }else{
				mostrarMensaje(respuesta.mensaje,"FALLO");
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});


	///Paso 2
	
	$("#fecha_examen").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd'
	 });
	 
	$("#formularioExamenes").submit(function (event) {
		fn_limpiar();
		event.preventDefault();
		var error = false;
		
		if(!$.trim($("#fecha_examen").val())){
        	error = true;
        	$("#fecha_examen").addClass("alertaCombo");
		}
		
		if(!$.trim($("#resultado_examen").val())){
        	error = true;
        	$("#resultado_examen").addClass("alertaCombo");
		}

		if(!$.trim($("#laboratorio").val())){
        	error = true;
        	$("#laboratorio").addClass("alertaCombo");
		}

		if(!$.trim($("#num_informe").val())){
        	error = true;
        	$("#num_informe").addClass("alertaCombo");
		}

		if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
	       		fn_mostrarDetalleExamenes();
	       		fn_limpiarExamenesCompleto();
	        }else{
	        	fn_mostrarDetalleExamenes();
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
	
    //Para cargar el detalle de Exámenes
    function fn_mostrarDetalleExamenes() {
        var idEquino = $("#id_equino").val();
        
    	$.post("<?php echo URL ?>PasaporteEquino/ExamenesEquino/construirDetalleExamenes/",
    	{
    		idEquino : idEquino,
			fase : bandera
		}, function (data) {
            $("#tbItemsExamenes tbody").html(data);
        });
    }

  	//Funcion que elimina una fila de la lista 
    function fn_eliminarDetalleExamenes(idDetalleExamen) { 
        $.post("<?php echo URL ?>PasaporteEquino/ExamenesEquino/borrar",
        {                
            elementos: idDetalleExamen
        },
        function (data) {
        	fn_mostrarDetalleExamenes();
        });
	}

    function fn_limpiarExamenesCompleto() {
    	$("#fecha_examen").val("");
    	$("#resultado_examen").val("");
    	$("#laboratorio").val("");
    	$("#num_informe").val("");
    }

  ///Paso 3
  
  $("#fecha_enfermedad").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd'
	 });
	 
	$("#formularioVacunas").submit(function (event) {
		fn_limpiar();
		event.preventDefault();
		var error = false;
		
		if(!$.trim($("#fecha_enfermedad").val())){
        	error = true;
        	$("#fecha_enfermedad").addClass("alertaCombo");
		}
		
		if(!$.trim($("#enfermedad").val())){
        	error = true;
        	$("#enfermedad").addClass("alertaCombo");
		}

		if(!$.trim($("#laboratorio_lote").val())){
        	error = true;
        	$("#laboratorio_lote").addClass("alertaCombo");
		}

		if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
	       		fn_mostrarDetalleVacunas();
	       		fn_limpiarVacunasCompleto();
	        }else{
	        	fn_mostrarDetalleVacunas();
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
	
    //Para cargar el detalle de Vacunas
    function fn_mostrarDetalleVacunas() {
        var idEquino = $("#id_equino").val();
        
    	$.post("<?php echo URL ?>PasaporteEquino/VacunasEquino/construirDetalleVacunas/",
    	{
    		idEquino : idEquino,
			fase : bandera
		}, function (data) {
            $("#tbItemsVacunas tbody").html(data);
        });
    }

  	//Funcion que elimina una fila de la lista 
    function fn_eliminarDetalleVacunas(idDetalleVacuna) { 
        $.post("<?php echo URL ?>PasaporteEquino/VacunasEquino/borrar",
        {                
            elementos: idDetalleVacuna
        },
        function (data) {
        	fn_mostrarDetalleVacunas();
        });
	}

    function fn_limpiarVacunasCompleto() {
    	$("#fecha_enfermedad").val("");
    	$("#enfermedad").val("");
    	$("#laboratorio_lote").val("");
    }
  
  
  ///Paso 4
  $('button.subirArchivoFoto').click(function (event) {
	  var boton = $(this);
      var banderaImagen = boton.parent().find(".bandera").val();
	  var numero_aleatorio = Math.floor(Math.random() * (1000 - 1)) + 1;
      var idExpediente = <?php echo json_encode($this->modeloEquinos->getPasaporte());?>;
   	  var nombre_archivo = banderaImagen+"_"+numero_aleatorio+"_"+idExpediente;
      var archivo = boton.parent().find(".archivo");
      var rutaArchivo = boton.parent().find(".rutaArchivo");
      var extension = archivo.val().split('.');
      var estado = boton.parent().find(".estadoCarga");
        

        if (extension[extension.length - 1].toUpperCase() == 'JPG') {

            subirArchivo(
                archivo
                , nombre_archivo
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
            );

            boton.parent().find(".rutaArchivo").val("<?php echo PAS_EQUI_URL.$this->modeloEquinos->getPasaporte(); ?>/"+nombre_archivo+".jpg");
            $('#if'+banderaImagen).html("");
            
            fn_guardarFotos(banderaImagen);
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato JPG');
            archivo.val("");
        }
    });

  //Para guardar las fotos
    function fn_guardarFotos(banderaImagen) {
      var idEquino = $("#id_equino").val();
      var foto;

      switch (banderaImagen){
          case 'Frente':
        	  foto = $('#foto_frente').val();
        	  break;
          case 'Atras':
        	  foto = $('#foto_atras').val();
        	  break;
          case 'Derecha':
        	  foto = $('#foto_derecha').val();
        	  break;
          case 'Izquierda':
        	  foto = $('#foto_izquierda').val();
        	  break;
    	  default:
			  break;		
      }
        
    	$.post("<?php echo URL ?>PasaporteEquino/Equinos/guardarImagenEquino/",
    	{
    		id_equino : idEquino,
    		foto : foto,
    		bandera : banderaImagen
    	},function (data) {
    	  	$('#if'+banderaImagen).attr('src', data.contenido);
      }, 'json');
    }
	
</script>
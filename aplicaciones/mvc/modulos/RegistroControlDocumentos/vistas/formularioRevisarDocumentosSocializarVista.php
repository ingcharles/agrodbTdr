<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario'
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>RegistroControlDocumentos'
	data-opcion='registroSgc/guardarRevisarSocializar' data-destino="detalleItem"
	data-accionEnExito="ACTUALIZAR" method="post">
	<input type="hidden" id="id_registro_sgc" name="id_registro_sgc"
		value="<?php echo $this->modeloRegistroSgc->getIdRegistroSgc()?>">
		<input type="hidden" id="id_detalle_socializacion" name="id_detalle_socializacion"
		value="<?php echo $this->modeloDetalleSocializacion->getIdDetalleSocializacion();?>">
		<input type="hidden" id="id_detalle_destinatario" name="id_detalle_destinatario"
		value="<?php echo $this->modeloDetalleSocializacion->getIdDetalleDestinatario();?>">
	<fieldset>
		<legend>Información documento SGC</legend>

		<div data-linea="1">
			<label for="coordinacionD">Coordinación/Dirección solicitante: </label>
			<select id="coordinacionD" name="coordinacionD" disabled>
				<?php echo $this->comboAreas('DE',$this->modeloRegistroSgc->getCoordinacion());?>
			</select>
		</div>
		<div data-linea="2">
			<label for="subproceso">Subproceso: </label> <select id="subproceso"
				name="subproceso" disabled>
				<?php echo $this->comboAreas($this->modeloRegistroSgc->getCoordinacion(),$this->modeloRegistroSgc->getSubproceso());?>
			</select>
		</div>

		<div data-linea="3">
			<label for="formato">Formato: </label> <select id="formato"
				name="formato" disabled>
				<?php echo $this->comboFormato($this->modeloRegistroSgc->getFormato());?>
			</select>
		</div>

		<div data-linea="4">
			<label for="nombre_documento">Nombre del documento: </label> <input
				type="text" id="nombre_documento" disabled name="nombre_documento"
				value="<?php echo $this->modeloRegistroSgc->getNombreDocumento(); ?>"
				placeholder="Nombre del documento"  maxlength="128" />
		</div>

		<div data-linea="5">
			<label for="fecha_aprobacion">Fecha aprobación: </label> <input
				type="text" id="fecha_aprobacion"  
				name="fecha_aprobacion"
				value="<?php echo $this->modeloRegistroSgc->getFechaAprobacion(); ?>"
				placeholder="Fecha de aprobación" disabled maxlength="10" />
		</div>

		<div data-linea="6">
			<label for="edicion">Edición: </label> <select id="edicion" disabled
				name="edicion">
				<?php echo $this->comboNumeros(100,$this->modeloRegistroSgc->getEdicion());?>
			</select>
		</div>

		<div data-linea="7">
			<label for="resolucion">Resolución: </label> <input type="text"
				id="resolucion" name="resolucion"
				value="<?php echo $this->modeloRegistroSgc->getResolucion(); ?>"
				placeholder="Resolucion" disabled maxlength="128" />
		</div>

		<div data-linea="8">
			<label for="observacion">Observaciones: </label> <input type="text"
				id="observacion" name="observacion"
				value="<?php echo $this->modeloRegistroSgc->getObservacion(); ?>"
				placeholder="Observación" disabled maxlength="1024" />
		</div>

		<div data-linea="9">
			<label for="estado_registro">Estado registro: </label> <select
				id="estado_registro" name="estado_registro" disabled>
				<?php echo $this->comboEstado($this->modeloRegistroSgc->getEstadoRegistro());?>
			</select>
		</div>

		<div data-linea="10">
			<label for="socializar">¿Este registro necesita socialización? </label>
			<select id="socializar" name="socializar" disabled>
				<?php echo $this->comboSocializar($this->modeloRegistroSgc->getSocializar());?>
			</select>
		</div>
	</fieldset>
	<fieldset>
		<legend>Detalle Registro</legend>

		<div data-linea="1" id="socializar1">
			<label for="numero_memorando">No. Memorando: </label> <input type="text"
				id="numero_memorando"  name="numero_memorando" disabled
				value="<?php echo $this->modeloRegistroSgc->getNumeroMemorando(); ?>"
				placeholder="No. Memorando"  maxlength="12" />
		</div>
		<div data-linea="2">
			<label for="numero_glpi">No. GLPI: </label> <input type="text"
				id="numero_glpi" disabled name="numero_glpi"
				value="<?php echo $this->modeloRegistroSgc->getNumeroGlpi(); ?>"
				placeholder="GLPI"  maxlength="12" />
		</div>
		<div data-linea="3">
			<label for="asunto">Asunto: </label> <input type="text" id="asunto"
				required name="asunto"
				value="<?php echo $this->modeloRegistroSgc->getAsunto(); ?>"
				placeholder="Asunto" disabled maxlength="128" />
		</div>
		<div data-linea="4" id="socializar2">
			<label for="fecha_notificacion">Fecha de notificación: </label> <input type="text" id="fecha_notificacion"
				required name="fecha_notificacion" disabled
				value="<?php echo ($this->modeloRegistroSgc->getFechaNotificacion() != null) ? $this->modeloRegistroSgc->getFechaNotificacion():date('Y-m-d'); ?>"
				placeholder="Fecha de notificación" maxlength="10" />
		</div>
		<div data-linea="5" id="socializar3">
			<label for="fecha_vigencia">Fecha vigencia socialización hasta: </label> <input type="text" id="fecha_vigencia"
				 name="fecha_vigencia" disabled
				value="<?php echo $this->modeloRegistroSgc->getFechaVigencia(); ?>"
				placeholder="Fecha vigencia socialización hasta"  maxlength="10" />
		</div>
		
		<div data-linea="8" id="listaDestinatarioRegistrado">
			<?php echo $this->listarDestinatariosRegistrados($this->modeloRegistroSgc->getIdRegistroSgc(),'No')?>
		</div>
		
		<div data-linea="9">
			<label for="enlace_socializar">Para la descarga de los archivos, usar
				los siguientes enlaces: </label>
		</div>
		
		<div data-linea="11" id="listarEnlace">
			<?php echo $this->listarEnlaces($this->modeloRegistroSgc->getIdRegistroSgc(),'No');?>
		</div>
		<hr>

	</fieldset>
	<fieldset id="listDetalleDocumento">
	<legend>Documentos Adjuntos</legend>
		<?php echo $this->listarDocumentos($this->modeloRegistroSgc->getIdRegistroSgc());?>
	</fieldset>
	
	
	<fieldset id="listaTecnico">
	<legend>Técnico asignado</legend>
		<div data-linea="1" id="listaTecnicoAsignado">
			<?php echo $this->listarTecnicoRegistradoRevisar($this->modeloDetalleSocializacion->getIdDetalleDestinatario(),'No');?>
		</div>
	</fieldset>
	<fieldset id="evidenciaList">
		<legend>Evidencia socialización</legend>

		<div data-linea="1" id="fecha_socializacion">
			<label for="fecha_socializacion">Fecha socialización: </label> <input type="text"
				id="fecha_socializacion"  name="fecha_socializacion" readonly
				value="<?php echo date('Y-m-d'); ?>"
				placeholder="Fecha"  maxlength="12" />
		</div>
		<div data-linea="2">
			<label for="nombre">Nombre responsable socialización: </label> <input type="text"
				id="nombre" name="nombre" disabled
				value="<?php echo $this->modeloTecnico->getNombre(); ?>"
				placeholder="Nombre responsable"  maxlength="12" />
		</div>
		<div data-linea="3">
			<label for="provincia">Provincia responsable socialización: </label> <input type="text" id="provincia"
				disabled name="provincia"
				value="<?php echo $this->modeloDetalleSocializacion->getProvincia(); ?>"
				placeholder="Provincia"  maxlength="128" />
		</div>
		<div data-linea="4">
			<label for="oficina">Oficina: </label> <input type="text" id="oficina"
				required name="oficina" disabled
				value="<?php echo $this->modeloDetalleSocializacion->getOficina(); ?>"
				placeholder="oficina" maxlength="10" />
		</div>
		<div data-linea="5">
			<label for="coordinacion">Coordinación: </label> <input type="text" id="coordinacion"
				 name="coordinacion" disabled
				value="<?php echo $this->modeloDetalleSocializacion->getCoordinacion(); ?>"
				placeholder="Coordinación"  maxlength="128" />
		</div>
		<div data-linea="6">
			<label for="direccion">Dirección - Oficina Técnica: </label> <input type="text" id="direccion"
				 name="direccion" disabled
				value="<?php echo $this->modeloDetalleSocializacion->getDireccion(); ?>"
				placeholder="Dirección"  maxlength="128" />
		</div>
		<div data-linea="7" id="evidenciaAdjuntaSubir">
			<label for="documento_socializar">Evidencia socialización: </label> <input type="hidden"
				class="rutaArchivo" name="archivo_adjunto" value="0" /> <input
				type="file" class="archivo" accept="application/pdf" />
			<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
			<button type="button" class="subirArchivo adjunto"
				data-rutaCarga="<?php echo URL_MVC_MODULO ?>RegistroControlDocumentos">Subir
				archivo</button>
		</div>

	</fieldset>
	<fieldset  id="evidenciaAdjunta">
	<legend>Evidencia Adjunta</legend>
		<?php echo $this->listarDocumentoSocializar($this->modeloDetalleSocializacion->getIdDetalleDestinatario());?>
	</fieldset>
	<div data-linea="13">
		<button type="submit" class="guardar">Enviar</button>
	</div>
</form>
<div id="cargarMensajeTemporal"></div>

<script type="text/javascript">

	
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		$(".guardar").attr('disabled', true);
	      if($("#socializar").val() == 'No'){
		     $("#socializar1").hide();
		     $("#socializar2").hide();
		     $("#socializar3").hide();
		     $("#socializar4").hide();
		     $("#socializar5").hide();
	      }
	 });

	$("#formulario").submit(function (event) {
		  mostrarMensaje("", "");
     	  event.preventDefault();
		  var error = false;
		  $(".alertaCombo").removeClass("alertaCombo");
			    	
		if (!error) {
			abrir($(this), event, false);
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});



	$(".guardarTecnico").click(function(event){
		event.preventDefault();
		var error = false;
		$(".alertaCombo").removeClass("alertaCombo");
		 if(!$.trim($("#tecnico").val())){
			   $("#tecnico").addClass("alertaCombo");
			   error = true;
		  }
		if (!error) {
			$("#estado").html("").removeClass('alerta');
			$("#cargarMensajeTemporal").html("<div id='cargando'>Cargando...</div>");
	    	$.post("<?php echo URL ?>RegistroControlDocumentos/RegistroSgc/guardarTecnico", 
	                {
	    		          id_tecnico:$("#tecnico").val(),
	    		          id_registro_sgc:$("#id_registro_sgc").val(),
	    		             		  		     
	                }, function (data) {
	                $("#cargarMensajeTemporal").html("");
	                	if (data.estado === 'EXITO') {
	                		    $("#id_registro_sgc").val(data.contenido);
	                		    $("#listaTecnicoAsignado").html(data.lista);
	    	                    mostrarMensaje(data.mensaje, data.estado);
	                        } else {
	                        	mostrarMensaje(data.mensaje, "FALLO");
	                        }
	            }, 'json');
		}else{
			 $("#tecnico").addClass("alertaCombo");
			 $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
	

       
	   function eliminarTecnico(id){
       	$("#cargarMensajeTemporal").html("<div id='cargando'>Cargando...</div>");
       	$.post("<?php echo URL ?>RegistroControlDocumentos/registroSgc/eliminarTecnico", 
   	              {
   			        id:id,
   			        id_registro_sgc:$("#id_registro_sgc").val(),
   	              }, function (data) {
   	            	 $("#cargarMensajeTemporal").html("");
   	            	 if (data.estado === 'EXITO') {
	                		    $("#listaTecnicoAsignado").html(data.lista);
	    	                    mostrarMensaje(data.mensaje, data.estado);
	                        } else {
	                        	mostrarMensaje(data.mensaje, "FALLO");
	                        }
   	      }, 'json');
       }
	 //****************************subir documentos ***********
	   $('button.subirArchivo').click(function (event) {
		   var texto = "Por favor revise los campos obligatorios.";
			$(".alertaCombo").removeClass("alertaCombo");
			mostrarMensaje("", "FALLO");
			var error = false;
				
		   if(!$.trim($(".archivo").val())){
				   $(".archivo").addClass("alertaCombo");
				   error = true;
			  }
       
	    	    var boton = $(this);
	    	    var archivo = boton.parent().find(".archivo");
	    	    var rutaArchivo = boton.parent().find(".rutaArchivo");
	    	    var extension = archivo.val().split('.');
	    	    var estado = boton.parent().find(".estadoCarga");

		    if(!error){	  
		       var file = archivo[0].files[0];
		   	   var data = new FormData();
		   	   data.append('archivo',file);
		   	   var url = "<?php echo URL ?>RegistroControlDocumentos/registroSgc/agregarDocumentoSocializar";
		   	   var get = "?id_registro_sgc="+$("#id_registro_sgc").val()+"&fecha_socializacion="+$("#fecha_socializacion").val()+"&id_detalle_socializacion="+$("#id_detalle_socializacion").val()+"&id_detalle_destinatario="+$("#id_detalle_destinatario").val();
		   	   var elemento = rutaArchivo;
	           var funcion = new cargaAdjunto(estado, archivo, boton);
	    	   $.ajax({
	  	   		  url:url+get,
	  	   		  type:'POST',
	  	   		  contentType:false,
	  	   		  data:data,
	  	   		  processData:false,
	  	   		  cache:false,
	  						beforeSend:function(info){
	  						  funcion.esperar("");
	  						},
	  						success:function(info){
	  							var obj = JSON.parse(info);
	  							if(obj.estado  == 'EXITO'){
	  								elemento.val(obj.estado);
	  								$(".archivo").val('');
	  								$(".guardar").attr('disabled', false);
	  								$("#evidenciaAdjunta").html(obj.lista);
	  								mostrarMensaje(obj.mensaje, obj.estado);
	  								$("#evidenciaAdjuntaSubir").hide();
	  								distribuirLineas();
	  							}else{
	  								elemento.val('0');
	  								$(".guardar").attr('disabled', true);
	  								mostrarMensaje(obj.mensaje, obj.estado);
	  							}
	  							
	  						},
	              	   		 error: function(info) {
	              	   			    var obj = JSON.parse(info);
	     								elemento.val('0');
	     								funcion.error(obj.mensaje);
	     								mostrarMensaje(obj.mensaje, obj.estado);
	     							}
	  	   		});

		    }else{
		    	mostrarMensaje(texto, "FALLO");
		    }
		});
	   function cargaAdjunto(estado, archivo, boton) {
	        this.esperar = function (msg) {
	            estado.html("Cargando el archivo...");
	            archivo.removeClass("rojo");
	            archivo.addClass("amarillo");
	        };

	        this.exito = function (msg) {
	            estado.html("En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)");
	            archivo.removeClass("amarillo rojo");
	        };

	        this.error = function (msg) {
	            estado.html(msg);
	            archivo.removeClass("amarillo verde");
	            archivo.addClass("rojo");
	        };
	    }
</script>

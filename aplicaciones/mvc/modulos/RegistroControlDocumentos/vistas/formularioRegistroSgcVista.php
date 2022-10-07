<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>RegistroControlDocumentos' data-opcion='registroSgc/guardar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
	
	<input type="hidden" id="id_registro_sgc" name="id_registro_sgc"
		value="<?php echo $this->modeloRegistroSgc->getIdRegistroSgc()?>">
	<fieldset>
		<legend>Información documento SGC</legend>

		<div data-linea="1">
			<label for="coordinacion">Coordinación/Dirección solicitante: </label>
			<select id="coordinacion" name="coordinacion" required class="inactivo">
				<?php echo $this->comboAreas('DE',$this->modeloRegistroSgc->getCoordinacion());?>
			</select>
		</div>
		<div data-linea="2">
			<label for="subproceso">Subproceso: </label> <select id="subproceso"
				name="subproceso" required class="inactivo">
				<?php echo $this->comboAreas($this->modeloRegistroSgc->getCoordinacion(),$this->modeloRegistroSgc->getSubproceso());?>
			</select>
		</div>

		<div data-linea="3">
			<label for="formato">Formato: </label> <select id="formato"
				name="formato" required class="inactivo">
				<?php echo $this->comboFormato($this->modeloRegistroSgc->getFormato());?>
			</select>
		</div>

		<div data-linea="4">
			<label for="nombre_documento">Nombre del documento: </label> <input
				type="text" id="nombre_documento" required name="nombre_documento"
				value="<?php echo $this->modeloRegistroSgc->getNombreDocumento(); ?>"
				placeholder="Nombre del documento" required maxlength="128"  class="inactivo"/>
		</div>

		<div data-linea="5">
			<label for="fecha_aprobacion">Fecha aprobación: </label> <input
				type="text" id="fecha_aprobacion" required readonly
				name="fecha_aprobacion"
				value="<?php echo $this->modeloRegistroSgc->getFechaAprobacion(); ?>"
				placeholder="Fecha de aprobación" required maxlength="10"  class="inactivo"/>
		</div>

		<div data-linea="6">
			<label for="edicion">Edición: </label> <select id="edicion"
				name="edicion" class="inactivo">
				<?php echo $this->comboNumeros(100,$this->modeloRegistroSgc->getEdicion());?>
			</select>
		</div>

		<div data-linea="7">
			<label for="resolucion">Resolución: </label> <input type="text"
				id="resolucion" name="resolucion"
				value="<?php echo $this->modeloRegistroSgc->getResolucion(); ?>"
				placeholder="Resolucion" required maxlength="128" class="inactivo" />
		</div>

		<div data-linea="8">
			<label for="observacion">Observaciones: </label> <input type="text"
				id="observacion" name="observacion"
				value="<?php echo $this->modeloRegistroSgc->getObservacion(); ?>"
				placeholder="Observación" required maxlength="1024" class="inactivo" />
		</div>

		<div data-linea="9">
			<label for="estado_registro">Estado registro: </label> <select
				id="estado_registro" name="estado_registro" required class="inactivo">
				<?php echo $this->comboEstado($this->modeloRegistroSgc->getEstadoRegistro());?>
			</select>
		</div>

		<div data-linea="10">
			<label for="socializar">¿Este registro necesita socialización? </label>
			<select id="socializar" name="socializar" required class="inactivo">
				<?php echo $this->comboSocializar($this->modeloRegistroSgc->getSocializar());?>
			</select>
		</div>
	</fieldset>
	<fieldset>
		<legend>Detalle Registro</legend>

		<div data-linea="1" id="socializar1">
			<label for="numero_memorando">No. Memorando: </label> <input type="text"
				id="numero_memorando"  name="numero_memorando"
				value="<?php echo $this->modeloRegistroSgc->getNumeroMemorando(); ?>"
				placeholder="No. Memorando"  maxlength="64"  class="inactivo"/>
		</div>
		<div data-linea="2">
			<label for="numero_glpi">No. GLPI: </label> <input type="text"
				id="numero_glpi" required name="numero_glpi"
				value="<?php echo $this->modeloRegistroSgc->getNumeroGlpi(); ?>"
				placeholder="GLPI" required maxlength="12"  class="inactivo"/>
		</div>
		<div data-linea="3">
			<label for="asunto">Asunto: </label> <input type="text" id="asunto"
				required name="asunto"
				value="<?php echo $this->modeloRegistroSgc->getAsunto(); ?>"
				placeholder="Asunto" required maxlength="128"  class="inactivo"/>
		</div>
		<div data-linea="4" id="socializar2">
			<label for="fecha_notificacion">Fecha de notificación: </label> <input type="text" id="fecha_notificacion"
				required name="fecha_notificacion" readonly
				value="<?php echo ($this->modeloRegistroSgc->getFechaNotificacion() != null) ? $this->modeloRegistroSgc->getFechaNotificacion():date('Y-m-d'); ?>"
				placeholder="Fecha de notificación" required maxlength="10"  class="inactivo"/>
		</div>
		<div data-linea="5" id="socializar3">
			<label for="fecha_vigencia">Fecha vigencia socialización hasta: </label> <input type="text" id="fecha_vigencia"
				 name="fecha_vigencia"
				value="<?php echo $this->modeloRegistroSgc->getFechaVigencia(); ?>"
				placeholder="Fecha vigencia socialización hasta"  maxlength="10"  class="inactivo"/>
		</div>
		<div data-linea="6" id="socializar4">
			<label for="destinatario">Para: </label> <select id="destinatario" name="destinatario"  class="inactivo">
				<?php echo $this->comboDestinatario();?>
			</select>
		</div>
        <div data-linea="7" id="listaDestinatario">
		</div>
		<button type="button" id="socializar5" class="guardarDestinatario inactivo">Agregar</button>
		<div data-linea="8" id="listaDestinatarioRegistrado">
			<?php echo $this->listarDestinatariosRegistrados($this->modeloRegistroSgc->getIdRegistroSgc(),'No')?>
		</div>
		
		<hr>
		<div data-linea="9">
			<label for="enlace_socializar">Para la descarga de los archivos, usar
				los siguientes enlaces: </label>
		</div>
		<div data-linea="10">
			<input type="text" id="enlace_socializar" name="enlace_socializar"
				value="" placeholder="Enlace" maxlength="1024"  class="inactivo"/>
		</div>
		<button type="button" class="guardarSocializar inactivo">Agregar</button>
		<div data-linea="11" id="listarEnlace">
			<?php echo $this->listarEnlaces($this->modeloRegistroSgc->getIdRegistroSgc());?>
		</div>
		<hr>
		<div data-linea="12" id="documentoAdjunto">
			<label for="formato">Cargar documentos: </label> <input type="hidden"
				class="rutaArchivo" name="archivo_adjunto" value="0" /> <input
				type="file" class="archivo" accept="application/pdf" />
			<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
			<button type="button" class="subirArchivo adjunto inactivo"
				data-rutaCarga="<?php echo URL_MVC_MODULO ?>RegistroControlDocumentos">Subir
				archivo</button>
		</div>

	</fieldset>
	<fieldset id="listDetalleDocumento">
	<legend>Documentos Adjuntos</legend>
		<?php echo $this->listarDocumentos($this->modeloRegistroSgc->getIdRegistroSgc());?>
	</fieldset>
	<div data-linea="13">
		<button type="submit" class="guardar inactivo">Guardar</button>
	</div>
</form>
<div id="cargarMensajeTemporal"></div>

<script type="text/javascript">
var accion=<?php echo json_encode($this->accion);?>;
var estado=<?php echo json_encode($this->modeloRegistroSgc->getEstadoRegistro());?>;

	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		 $("#fecha_aprobacion").datepicker({
		    	yearRange: "c:c",
		    	changeMonth: false,
		        changeYear: false,
		        dateFormat: 'yy-mm-dd',
		        
		      });
		
		$("#fecha_vigencia").datepicker({
	    	yearRange: "c:c",
	    	changeMonth: false,
	        changeYear: false,
	        dateFormat: 'yy-mm-dd',
	        minDate: $("#fecha_notificacion" ).val(),
	        
	      });

	      if($("#socializar").val() == 'No'){
		     $("#socializar1").hide();
		     $("#socializar2").hide();
		     $("#socializar3").hide();
		     $("#socializar4").hide();
		     $("#socializar5").hide();
	      }

	      if(estado == 'Obsoleto'){
	    	  $(".inactivo").attr('disabled','disabled');
	      }   
	 });

	$("#formulario").submit(function (event) {
		  mostrarMensaje("", "");
     	  event.preventDefault();
		  var error = false;
		  $(".alertaCombo").removeClass("alertaCombo");
		 if(!$.trim($("#coordinacion").val())){
			   $("#coordinacion").addClass("alertaCombo");
			   error = true;
		  }

		 if(!$.trim($("#subproceso").val())){
			   $("#subproceso").addClass("alertaCombo");
			   error = true;
		  }
		 if(!$.trim($("#formato").val())){
			   $("#formato").addClass("alertaCombo");
			   error = true;
		  }
		 if(!$.trim($("#nombre_documento").val())){
			   $("#nombre_documento").addClass("alertaCombo");
			   error = true;
		  }
		 if(!$.trim($("#fecha_aprobacion").val())){
			   $("#fecha_aprobacion").addClass("alertaCombo");
			   error = true;
		  }
		 if(!$.trim($("#estado_registro").val())){
			   $("#estado_registro").addClass("alertaCombo");
			   error = true;
		  }
		 if(!$.trim($("#socializar").val())){
			   $("#socializar").addClass("alertaCombo");
			   error = true;
		  }
		  if(!$.trim($("#numero_glpi").val())){
			   $("#numero_glpi").addClass("alertaCombo");
			   error = true;
		  }
		 if(!$.trim($("#asunto").val())){
			   $("#asunto").addClass("alertaCombo");
			   error = true;
		  }
			    	
		if (!error) {
			abrir($(this), event, false);
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#coordinacion").change(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		if( $("#coordinacion").val() != ""){
			$("#estado").html("").removeClass('alerta');
			$("#cargarMensajeTemporal").html("<div id='cargando'>Cargando...</div>");
	    	$.post("<?php echo URL ?>RegistroControlDocumentos/RegistroSgc/comboCoordinacion", 
	                {
	    		        coordinacion:$("#coordinacion").val()
	    	  		         		  		     
	                }, function (data) {
	                $("#cargarMensajeTemporal").html("");
	                	if (data.estado === 'EXITO') {
	                		    $("#subproceso").html(data.contenido);
	    	                    mostrarMensaje(data.mensaje, data.estado);
	                        } else {
	                        	$("#subproceso").html(data.contenido);
	                        	mostrarMensaje(data.mensaje, "FALLO");
	                        }
	            }, 'json');
		}else{
			$("#coordinacion").val('');
		}
	});

	$(".guardarSocializar").click(function(event){
		event.preventDefault();
		var error = false;
		$(".alertaCombo").removeClass("alertaCombo");
		 if(!$.trim($("#coordinacion").val())){
			   $("#coordinacion").addClass("alertaCombo");
			   error = true;
		  }

		 if(!$.trim($("#subproceso").val())){
			   $("#subproceso").addClass("alertaCombo");
			   error = true;
		  }
		 if(!$.trim($("#formato").val())){
			   $("#formato").addClass("alertaCombo");
			   error = true;
		  }
		 if(!$.trim($("#nombre_documento").val())){
			   $("#nombre_documento").addClass("alertaCombo");
			   error = true;
		  }
		 if(!$.trim($("#fecha_aprobacion").val())){
			   $("#fecha_aprobacion").addClass("alertaCombo");
			   error = true;
		  }
		 if(!$.trim($("#estado_registro").val())){
			   $("#estado_registro").addClass("alertaCombo");
			   error = true;
		  }
		 if(!$.trim($("#socializar").val())){
			   $("#socializar").addClass("alertaCombo");
			   error = true;
		  }
		 if(!$.trim($("#numero_glpi").val())){
			   $("#numero_glpi").addClass("alertaCombo");
			   error = true;
		  }
		 if(!$.trim($("#asunto").val())){
			   $("#asunto").addClass("alertaCombo");
			   error = true;
		  }
		 if(!$.trim($("#enlace_socializar").val())){
			   $("#enlace_socializar").addClass("alertaCombo");
			   error = true;
		  }

		if (!error) {
			$("#estado").html("").removeClass('alerta');
			$("#cargarMensajeTemporal").html("<div id='cargando'>Cargando...</div>");
	    	$.post("<?php echo URL ?>RegistroControlDocumentos/RegistroSgc/guardarEnlace", 
	                {
	    		          enlace_socializar:$("#enlace_socializar").val(),
	    		          id_registro_sgc:$("#id_registro_sgc").val(),
	    		          coordinacion:$("#coordinacion").val(),
	    		          subproceso:$("#subproceso").val(),
	    		          formato:$("#formato").val(),
	    		          nombre_documento:$("#nombre_documento").val(),
	    		          fecha_aprobacion:$("#fecha_aprobacion").val(),
	    		          estado_registro:$("#estado_registro").val(),
	    		          numero_glpi:$("#numero_glpi").val(),
	    		          asunto:$("#asunto").val(),
	    		          socializar:$("#socializar").val()
	    		             		  		     
	                }, function (data) {
	                $("#cargarMensajeTemporal").html("");
	                	if (data.estado === 'EXITO') {
	                		    $("#id_registro_sgc").val(data.contenido);
	                		    $("#listarEnlace").html(data.lista);
	                		    $("#enlace_socializar").val('');
	    	                    mostrarMensaje(data.mensaje, data.estado);
	                        } else {
	                        	mostrarMensaje(data.mensaje, "FALLO");
	                        }
	            }, 'json');
		}else{
			 $("#enlace_socializar").addClass("alertaCombo");
			 $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
	
	//****************************subir documentos ***********
	   $('button.subirArchivo').click(function (event) {
		   var texto = "Por favor revise los campos obligatorios.";
			$(".alertaCombo").removeClass("alertaCombo");
			mostrarMensaje("", "FALLO");
			var error = false;
			 if(!$.trim($("#coordinacion").val())){
				   $("#coordinacion").addClass("alertaCombo");
				   error = true;
			  }

			 if(!$.trim($("#subproceso").val())){
				   $("#subproceso").addClass("alertaCombo");
				   error = true;
			  }
			 if(!$.trim($("#formato").val())){
				   $("#formato").addClass("alertaCombo");
				   error = true;
			  }
			 if(!$.trim($("#nombre_documento").val())){
				   $("#nombre_documento").addClass("alertaCombo");
				   error = true;
			  }
			 if(!$.trim($("#fecha_aprobacion").val())){
				   $("#fecha_aprobacion").addClass("alertaCombo");
				   error = true;
			  }
			 if(!$.trim($("#estado_registro").val())){
				   $("#estado_registro").addClass("alertaCombo");
				   error = true;
			  }
			 if(!$.trim($("#socializar").val())){
				   $("#socializar").addClass("alertaCombo");
				   error = true;
			  }
			 if(!$.trim($("#numero_glpi").val())){
				   $("#numero_glpi").addClass("alertaCombo");
				   error = true;
			  }
			 if(!$.trim($("#asunto").val())){
				   $("#asunto").addClass("alertaCombo");
				   error = true;
			  }
				
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
		   	   var url = "<?php echo URL ?>RegistroControlDocumentos/registroSgc/agregarDocumentosAdjuntos";
		   	   var get = "?id_registro_sgc="+$("#id_registro_sgc").val()+"&coordinacion="+$("#coordinacion").val()+"&subproceso="+$("#subproceso").val()+"&formato="+$("#formato").val()+"&nombre_documento="+$("#nombre_documento").val()+"&fecha_aprobacion="+$("#fecha_aprobacion").val()+"&estado_registro="+$("#estado_registro").val()+"&socializar="+$("#socializar").val()+"&numero_glpi="+$("#numero_glpi").val()+"&asunto="+$("#asunto").val();
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
	  								$("#listDetalleDocumento").html(obj.lista);
	  								$("#id_registro_sgc").val(obj.contenido);
	  								mostrarMensaje(obj.mensaje, obj.estado);
	  								$("#documentoAdjunto").hide();
	  								distribuirLineas();
	  							}else{
	  								elemento.val('0');
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
	   
	   function eliminarEnlace(id){
        	$("#cargarMensajeTemporal").html("<div id='cargando'>Cargando...</div>");
        	$.post("<?php echo URL ?>RegistroControlDocumentos/registroSgc/eliminarEnlace", 
    	              {
    			        id:id,
    			        id_registro_sgc:$("#id_registro_sgc").val(),
    	              }, function (data) {
    	            	 $("#cargarMensajeTemporal").html("");
    	            	 if (data.estado === 'EXITO') {
	                		    $("#listarEnlace").html(data.lista);
	    	                    mostrarMensaje(data.mensaje, data.estado);
	                        } else {
	                        	mostrarMensaje(data.mensaje, "FALLO");
	                        }
    	      }, 'json');
        }
       
	   function eliminarDestinatario(id){
       	$("#cargarMensajeTemporal").html("<div id='cargando'>Cargando...</div>");
       	$.post("<?php echo URL ?>RegistroControlDocumentos/registroSgc/eliminarDestinatario", 
   	              {
   			        id:id,
   			        id_registro_sgc:$("#id_registro_sgc").val(),
   	              }, function (data) {
   	            	 $("#cargarMensajeTemporal").html("");
   	            	 if (data.estado === 'EXITO') {
	                		    $("#listaDestinatarioRegistrado").html(data.lista);
	    	                    mostrarMensaje(data.mensaje, data.estado);
	                        } else {
	                        	mostrarMensaje(data.mensaje, "FALLO");
	                        }
   	      }, 'json');
       }

	   $("#socializar").change(function(event){
			event.preventDefault();
			$(".alertaCombo").removeClass("alertaCombo");
			if( $("#socializar").val() != ""){
				if($("#socializar").val()=='Si'){
					$("#socializar1").show();
				     $("#socializar2").show();
				     $("#socializar3").show();
				     $("#socializar4").show();
				     $("#socializar5").show();
				     $("#listaDestinatario").show();
				     $("#listaDestinatarioRegistrado").show();
				}else{
					$("#socializar1").hide();
				     $("#socializar2").hide();
				     $("#socializar3").hide();
				     $("#socializar4").hide();
				     $("#socializar5").hide();
				     $("#listaDestinatario").hide();
				     $("#listaDestinatarioRegistrado").hide();
				}
			}else{
				$("#socializar").val('');
			}
		});
	       
	   $("#destinatario").change(function(event){
			event.preventDefault();
			$(".alertaCombo").removeClass("alertaCombo");
			if( $("#destinatario").val() != ""){
				$("#estado").html("").removeClass('alerta');
				$("#cargarMensajeTemporal").html("<div id='cargando'>Cargando...</div>");
		    	$.post("<?php echo URL ?>RegistroControlDocumentos/RegistroSgc/buscarDestinatario", 
		                {
		    		      destinatario:$("#destinatario").val(),
		    		      id_registro_sgc: $("#id_registro_sgc").val(),
		    	  		         		  		     
		                }, function (data) {
		                $("#cargarMensajeTemporal").html("");
		                	if (data.estado === 'EXITO') {
		                		    $("#listaDestinatario").html(data.contenido);
		    	                    mostrarMensaje(data.mensaje, data.estado);
		                        } else {
		                        	$("#listaDestinatario").html(data.contenido);
		                        	mostrarMensaje(data.mensaje, "FALLO");
		                        }
		            }, 'json');
			}else{
				$("#destinatario").val('');
			}
		});

		function selecionarTodos(id)
		{
			if(!$("input[name='seleccionarItem']").is(':checked') ){ 
				$("input[name='check[]']").map(function(){ $(this).prop("checked",false)}).get();
			}else{
				$("input[name='check[]']").map(function(){ $(this).prop("checked",true)}).get();
			}
			   
		}
		function verificarDestinatario(id)
		{ 
			$(".alertaCombo").removeClass("alertaCombo");
				$("#estado").html("").removeClass('alerta');
				$("#cargarMensajeTemporal").html("<div id='cargando'>Cargando...</div>");
		    	$.post("<?php echo URL ?>RegistroControlDocumentos/RegistroSgc/verificarDestinatario", 
		                {
		    		      identificador:id,
		    		      id_registro_sgc: $("#id_registro_sgc").val(),
		    	  		         		  		     
		                }, function (data) {
		                $("#cargarMensajeTemporal").html("");
		                	if (data.estado === 'EXITO') {
		    	                    mostrarMensaje(data.mensaje, data.estado);
		                        } else {
			                        $("#"+id).prop("checked",false);
		                        	mostrarMensaje(data.mensaje, "FALLO");
		                        }
		            }, 'json');
			
		}

		$(".guardarDestinatario").click(function(event){
			event.preventDefault();
			var error = false;
			var seleccion = []; 
			$(".alertaCombo").removeClass("alertaCombo");
			var check =  $("input[name='check[]']").map(function(){ if($(this).prop("checked")){return 1;}}).get();

			
			 if(!$.trim($("#coordinacion").val())){
				   $("#coordinacion").addClass("alertaCombo");
				   error = true;
			  }

			 if(!$.trim($("#subproceso").val())){
				   $("#subproceso").addClass("alertaCombo");
				   error = true;
			  }
			 if(!$.trim($("#formato").val())){
				   $("#formato").addClass("alertaCombo");
				   error = true;
			  }
			 if(!$.trim($("#nombre_documento").val())){
				   $("#nombre_documento").addClass("alertaCombo");
				   error = true;
			  }
			 if(!$.trim($("#fecha_aprobacion").val())){
				   $("#fecha_aprobacion").addClass("alertaCombo");
				   error = true;
			  }
			 if(!$.trim($("#estado_registro").val())){
				   $("#estado_registro").addClass("alertaCombo");
				   error = true;
			  }
			 if(!$.trim($("#socializar").val())){
				   $("#socializar").addClass("alertaCombo");
				   error = true;
			  }
			 if(!$.trim($("#numero_glpi").val())){
				   $("#numero_glpi").addClass("alertaCombo");
				   error = true;
			  }
			 if(!$.trim($("#asunto").val())){
				   $("#asunto").addClass("alertaCombo");
				   error = true;
			  }
			 if(check == ''){ 
					error = true;
					$("#listaDestinatario").addClass("alertaCombo");
					var texto = "Debe seleccionar un funcionario..!!.";
				}

			if (!error) {
				$("input[name='check[]']").map(function(){ if($(this).prop("checked")){seleccion.push($(this).val());} }).get();
				$("#estado").html("").removeClass('alerta');
				$("#cargarMensajeTemporal").html("<div id='cargando'>Cargando...</div>");
		    	$.post("<?php echo URL ?>RegistroControlDocumentos/RegistroSgc/guardarDestinatario", 
		                {
		    		          enlace_socializar:$("#enlace_socializar").val(),
		    		          id_registro_sgc:$("#id_registro_sgc").val(),
		    		          coordinacion:$("#coordinacion").val(),
		    		          subproceso:$("#subproceso").val(),
		    		          formato:$("#formato").val(),
		    		          nombre_documento:$("#nombre_documento").val(),
		    		          fecha_aprobacion:$("#fecha_aprobacion").val(),
		    		          estado_registro:$("#estado_registro").val(),
		    		          numero_glpi:$("#numero_glpi").val(),
		    		          asunto:$("#asunto").val(),
		    		          socializar:$("#socializar").val(),
		    		          funcionarios:seleccion,
		    		          accion:accion
		    		             		  		     
		                }, function (data) {
		                $("#cargarMensajeTemporal").html("");
		                	if (data.estado === 'EXITO') {
		                		    $("#id_registro_sgc").val(data.contenido);
		                		    $("#listaDestinatario").html('');
		                		    $("#destinatario").val('');
		                		    $("#listaDestinatarioRegistrado").html(data.lista);
		    	                    mostrarMensaje(data.mensaje, data.estado);
		                        } else {
		                        	mostrarMensaje(data.mensaje, "FALLO");
		                        }
		            }, 'json');
			}else{
				 $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
			}
		});

		
</script>

<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<div class="pestania">
	<fieldset id="divFuncionario">
	<?php echo $this->paciente;?>
    </fieldset>
    <fieldset id="divFirma">
    <?php echo $this->firma;?>
    </fieldset >
  </div>
	<div class="pestania">
	<embed src="<?php echo $this->rutaArchivo;?>" type="application/pdf" width="100%" height="400px" />
	</div> 
	<div class="pestania">
	<fieldset id="bloqueAdjunto">
		<legend>Adjuntar documento</legend>				

		     <div data-linea="2" id="documentoAdjunto">				
				<input type="hidden" class="rutaArchivo" name="archivo_adjunto" value="0"/>
				<input type="file" class="archivo" accept="application/pdf"/>
				<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
				<button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo URL_MVC_MODULO ?>HistoriasClinicas/archivos/adjuntosHistoriaClinica" >Subir archivo</button>		
			</div>
	
	</fieldset >
	<fieldset>
		<legend>Documento adjunto</legend>	
		<div id="listaAdjuntosCertificado" style="width:100%"><?php echo $this->listarAdjuntosCertificado($this->modeloCertificadoMedico->getIdCertificadoMedico());?></div>
	</fieldset>
	<div data-linea="5" id="botonGuardar">
			<button type="button" class="guardar" id="aceptarCertificado">Aceptar</button>
		</div>
	</div> 
<script type ="text/javascript">
	var idAdjunto=<?php echo json_encode($this->idAdjunto);?>;
	var id_certificado_medico=<?php echo json_encode($this->modeloCertificadoMedico->getIdCertificadoMedico());?>;
	var descripcion_certificado=<?php echo json_encode($this->modeloCertificadoMedico->getDescripcionCertificado());?>;
	var id_historia_clinica =<?php echo json_encode($this->modeloCertificadoMedico->getIdHistoriaClinica());?>;
	var estado =<?php echo json_encode($this->estado);?>;
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		construirAnimacion($(".pestania"));
		if(estado == 'Registrado'){
			$("#bloqueAdjunto").hide();
			$("#botonGuardar").hide();
			}
	 });

	//****************************subir documentos adjuntos examenes clinicos***********
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
		   	   var url = "<?php echo URL ?>HistoriasClinicas/certificadoMedico/actualizarDocumentosAdjuntos";
		   	   var get = "?id_adjuntos_certificado_medico="+idAdjunto+"&id_certificado_medico="+id_certificado_medico+"&descripcion_certificado="+descripcion_certificado+"&id_historia_clinica="+id_historia_clinica;
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
									funcion.exito(obj.mensaje);
									$("#listaAdjuntosCertificado").html(obj.contenido);
									funcion.exito(obj.mensaje);
									mostrarMensaje(obj.mensaje, obj.estado);
									distribuirLineas();
								}else{
									elemento.val('0');
									funcion.error(obj.mensaje);
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
	           //estado.html("En espera de archivo... (Tamaño máximo < ?php echo ini_get('upload_max_filesize'); ? >B)");
	           archivo.removeClass("amarillo rojo");
	           boton.attr("disabled", "disabled");
	           estado.html("El archivo ha sido cargado.");
	           archivo.addClass("verde");
	       };

	       this.error = function (msg) {
	           estado.html(msg);
	           archivo.removeClass("amarillo verde");
	           archivo.addClass("rojo");
	       };
	   }

	 //Función que acepta el certificado ingresado
	    $("#aceptarCertificado").click(function(){
	    	var texto = "Por favor revise los campos obligatorios.";
			$(".alertaCombo").removeClass("alertaCombo");
			mostrarMensaje("", "FALLO");
			
		 	$.post("<?php echo URL ?>HistoriasClinicas/certificadoMedico/aceptarCertificado", 
	              {
		 		    id_adjuntos_certificado_medico:idAdjunto
	              }, function (data) {
	              	if (data.estado === 'EXITO') {
		                   	 mostrarMensaje(data.mensaje, data.estado);
		                   	abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
	                  } else {
	                  	  mostrarMensaje(data.mensaje, "FALLO");
	                  }
	      }, 'json');
	      
	  });
</script>

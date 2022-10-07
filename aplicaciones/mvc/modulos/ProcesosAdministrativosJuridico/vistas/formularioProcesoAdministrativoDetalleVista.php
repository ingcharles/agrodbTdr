<header>
<form id="regresar" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProcesosAdministrativosJuridico' data-opcion='procesoAdministrativo/editar' data-destino="detalleItem" method="post">
		<input type="hidden" name="id" value="<?php echo $this->modeloProcesoAdministrativo->getIdProcesoAdministrativo();?>"/>
		<button type="submit" class="regresar">Regresar nivel anterior</button>

</form>
	<h1><?php echo $this->accion; ?></h1>
</header>
	
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProcesosAdministrativosJuridico' data-opcion='procesoAdministrativo/guardarProceso' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
	<fieldset>
		<legend>Creación de Proceso Administrativo</legend>				

		<div data-linea="1">
			<label for="provincia">Provincia: </label>
			<span><?php echo $this->modeloProcesoAdministrativo->getProvincia();?></span>
		</div>				

		<div data-linea="2">
			<label for="area_tecnica">Área Técnica: </label>
			<span><?php echo $this->modeloProcesoAdministrativo->getAreaTecnica();?></span>
		</div>				

	   <div data-linea="3" id="NumProceso">
			<label for="numero_proceso">Número del Expediente: </label>
			<span><?php echo $this->modeloProcesoAdministrativo->getNumeroProceso(); ?></span>
		</div>	
		
		<div data-linea="4">
			<label for="nombre_accionado">Nombre del Accionado: </label>
			<span><?php echo $this->modeloProcesoAdministrativo->getNombreAccionado(); ?></span>
		</div>				

		<div data-linea="5">
			<label for="nombre_establecimiento">Nombre del Establecimiento: </label>
			<span><?php echo $this->modeloProcesoAdministrativo->getNombreEstablecimiento(); ?></span>
		</div>				

	</fieldset >
	<fieldset id="field1">
		<legend>Tipo de Documento Jurídico</legend>				
		<div data-linea="4">
			<label for="tipo_documento">Tipo de Documento: </label>
			<span><?php echo $this->modeloModeloAdministrativo->getNombreModelo(); ?></span>
		</div>				
      
	</fieldset >
	  
	<fieldset id="adjuntarDocumento">
		<legend>Subir Documento PDF</legend>	
		 <div data-linea="1" id="resolucion1">
			<label for="detalle_sancion">Detalle de Sanción: </label>
			<input type="text" id="detalle_sancion" name="detalle_sancion" value=""
			placeholder="Detalle de sanción" required maxlength="1024" />
		</div>	
		
		<div data-linea="2" id="resolucion2">
			<label for="resultado_tramite">Resultado del Trámite: </label>
			<select id="resultado_tramite" name="resultado_tramite" >
				<option value="">Seleccione....</option>
			 	<?php echo $this->comboDetalleSancion();?>
			</select>
		</div>				

		<div data-linea="3" id="resolucion3">
			<label for="observacion">Observación: </label>
			<input type="text" id="observacion" name="observacion" value=""
			placeholder="Observación" required maxlength="1024" />
		</div>	
		 <div data-linea="4" id="anexo1">
			<label for="nombre_anexo">Nombre de Anexo: </label>
			<input type="text" id="nombre_anexo" name="nombre_anexo" value=""
			placeholder="Detalle de sanción" required maxlength="64" />
		</div>	
		<div data-linea="5" id="documentoAdjunto">				
				<input type="hidden" class="rutaArchivo" name="archivo_adjunto" value="0"/>
				<input type="file" class="archivo" accept="application/pdf"/>
				<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
				<button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo URL_MVC_MODULO ?>ProcesosAdministrativosJuridico/archivos/documentosProceso/" >Subir archivo</button>		
			</div>
	</fieldset>
	
	<fieldset id="listDetalleDocumento">
		<?php echo $this->listaDetalleDocumento;?>
	</fieldset>
</form >
<script type ="text/javascript">
var id_tipo_documento = <?php echo json_encode($this->modeloTipoDocumento->getIdTipoDocumento()); ?>;
var orden = <?php echo json_encode($this->modeloModeloAdministrativo->getOrden()); ?>;
var idProcesoAdministrativo = <?php echo json_encode($this->modeloProcesoAdministrativo->getIdProcesoAdministrativo()); ?>;
var rutaDocumento = <?php echo json_encode($this->modeloTipoDocumento->getRutaDocumento());?>;
var estado = <?php echo json_encode($this->modeloProcesoAdministrativo->getEstado()); ?>;


	$(document).ready(function() {
		mostrarMensaje("", "FALLO");
		construirValidador();
		distribuirLineas();
		$("#anexo1").hide();
		if(rutaDocumento !=''){
			$("#adjuntarDocumento").hide();
		}
		if(orden == 8){
			$("#resolucion1").show();
			$("#resolucion2").show();
			$("#resolucion3").show();
		}else{
			$("#resolucion1").hide();
			$("#resolucion2").hide();
			$("#resolucion3").hide();
		}
		if(orden == 9){
			$("#anexo1").show();
		}
		if(estado != 'creado'){
			if(orden == 9 || orden == 5){
				if(rutaDocumento !=''){
					$("#adjuntarDocumento").hide();
				}
			}else{
				$("#adjuntarDocumento").hide();
				}
		}
		if(orden == 5 && estado != 'creado'){
		}
		
	 });

	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		if (!error) {
			abrir($(this), event, false);
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
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
 	  if(orden == 8){
 		 if(!$.trim($("#detalle_sancion").val())){
			   $("#detalle_sancion").addClass("alertaCombo");
			   error = true;
		  }
 		 if(!$.trim($("#resultado_tramite").val())){
			   $("#resultado_tramite").addClass("alertaCombo");
			   error = true;
		  }
 		if(!$.trim($("#observacion").val())){
			   $("#observacion").addClass("alertaCombo");
			   error = true;
		  }
		}
 	 if(orden == 9){
 		 if(!$.trim($("#nombre_anexo").val())){
			   $("#nombre_anexo").addClass("alertaCombo");
			   error = true;
		  }
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
 	   	   var url = "<?php echo URL ?>ProcesosAdministrativosJuridico/procesoAdministrativo/agregarDocumentosAdjuntos";
 	   	if(orden == 8){
 	   	    var get = "?id_tipo_documento="+id_tipo_documento+"&detalle_sancion="+$("#detalle_sancion").val()+"&resultado_tramite="+$("#resultado_tramite").val()+"&observacion="+$("#observacion").val();
 	   	}else{
 	   		var get = "?id_tipo_documento="+id_tipo_documento+"&nombre_anexo="+$("#nombre_anexo").val();
 	 	   	}
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
 								$("#descripcion_adjunto").val('');
 								$(".archivo").val('');
 								$("#listDetalleDocumento").html(obj.contenido);
 								funcion.exito(obj.mensaje);
 								mostrarMensaje(obj.mensaje, obj.estado);
 								$("#adjuntarDocumento").hide();
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
            estado.html("En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)");
            archivo.removeClass("amarillo rojo");
            //boton.attr("disabled", "disabled");
           // estado.html("El archivo ha sido cargado.");
           // archivo.addClass("verde");
        };

        this.error = function (msg) {
            estado.html(msg);
            archivo.removeClass("amarillo verde");
            archivo.addClass("rojo");
        };
    }
    // eliminar anexos
    function eliminarAnexo(id){
        $.post("<?php echo URL ?>ProcesosAdministrativosJuridico/procesoAdministrativo/eliminarAnexo", 
                {
                   id_tipo_documento: id
 	  		         		  		     
                }, function (data) {
                	if (data.estado === 'EXITO') {
                		$("#listDetalleDocumento").html(data.contenido);
						mostrarMensaje(data.mensaje, data.estado);
						$("#adjuntarDocumento").hide();
 	                    distribuirLineas();
                    } else {
                    	mostrarMensaje(data.mensaje, "FALLO");
                    }
        }, 'json');

     }

    $("#regresar").submit(function(event){
		event.preventDefault();
				abrir($(this),event,false);
	}); 
</script>

<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario'
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>RegistroControlDocumentos'
	data-opcion='registroSgc/guardarAsignarTecnico' data-destino="detalleItem"
	data-accionEnExito="ACTUALIZAR" method="post">
	<input type="hidden" id="id_registro_sgc" name="id_registro_sgc"
		value="<?php echo $this->modeloRegistroSgc->getIdRegistroSgc()?>">
		<input type="hidden" id="id_detalle_destinatario" name="id_detalle_destinatario"
		value="<?php echo $this->modeloDetalleDestinatario->getIdDetalleDestinatario()?>">
	<fieldset>
		<legend>Información documento SGC</legend>

		<div data-linea="1">
			<label for="coordinacion">Coordinación/Dirección solicitante: </label>
			<select id="coordinacion" name="coordinacion" disabled>
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
	
	<fieldset id="asignarTecnico">
	<legend>Asignar técnico</legend>
		<div data-linea="1">
			<label for="tecnico">Técnico: </label>
			<select id="tecnico" name="tecnico" >
				<?php echo $this->comboTecnicos();?>
			</select>
		</div>
		<button type="button" class="guardarTecnico">Agregar funcionario</button>
	</fieldset>
	<fieldset id="listaTecnico">
	<legend>Técnico asignado</legend>
		<div data-linea="1" id="listaTecnicoAsignado">
			<?php echo $this->listarTecnicoRegistrado($this->modeloDetalleDestinatario->getIdDetalleDestinatario());?>
		</div>
	</fieldset>
	
	<div data-linea="13">
		<button type="submit" class="guardar">Enviar solicitud</button>
	</div>
</form>
<div id="cargarMensajeTemporal"></div>

<script type="text/javascript">

	
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		
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
	    		          identificador:$("#tecnico").val(),
	    		          tecnico: $("#tecnico option:selected").text(),
	    		          id_registro_sgc:$("#id_registro_sgc").val(),
	    		          id_detalle_destinatario:$("#id_detalle_destinatario").val(),
	    		             		  		     
	                }, function (data) {
	                $("#cargarMensajeTemporal").html("");
	                	if (data.estado === 'EXITO') {
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
		
</script>

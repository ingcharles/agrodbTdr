<link rel='stylesheet'
	href='<?php echo URL_MVC_MODULO ?>InspeccionAntePostMortemCF/vistas/estilos/estiloModal.css'>
<link rel='stylesheet'
	href='<?php echo URL_RESOURCE ?>estilos/bootstrap.min.css'>
<script src="<?php echo URL_RESOURCE ?>js/bootstrap.min.js"
	type="text/javascript"></script>
<script
	src="<?php echo URL ?>modulos/InspeccionAntePostMortemCF/vistas/js/funcionCf.js"></script>

<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario'
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>InspeccionAntePostMortemCF'
	data-opcion='detalleanteaves/guardar' data-destino="detalleItem"
	data-accionEnExito="ACTUALIZAR" method="post">
	<input type="hidden" id="id_formulario_ante_mortem"
		name="id_formulario_ante_mortem"
		value="<?php echo $this->idFormularioAnteMortem;?>" />

	<fieldset>
		<legend>Identificación del Centro de Faenamiento</legend>

		<div data-linea="1">
			<label for="provincia">Provincia: </label> <input type="text"
				id="provincia" name="provincia"
				value="<?php echo $this->provincia; ?>" readonly />
		</div>
		<div data-linea="1">
			<label for="canton">Cantón:</label> <input type="text" id="canton"
				name="canton" value="<?php echo $this->canton; ?>" readonly />
		</div>
		<div data-linea="1">
			<label for="parroquia">Parroquia: </label> <input type="text"
				id="parroquia" name="parroquia"
				value="<?php echo $this->parroquia; ?>" readonly />
		</div>
		<div data-linea="2">
			<label for="razonSocial">Nombre del Establecimiento: </label> <input
				type="text" id="razonSocial" name="razonSocial"
				value="<?php echo $this->razonSocial; ?>" readonly />
		</div>
		<div data-linea="3">
			<label for="nombreMedico">Nombre del Médico Veterinario Autorizado </label>
			<input type="text" id="nombreMedico" name="nombreMedico"
				value="<?php echo $this->nombreMedico; ?>" readonly />
		</div>
	</fieldset>

	<fieldset id="generalidades">
		<legend>Generalidades</legend>

		<div data-linea="1">
			<label for="fecha_formulario">Fecha: </label> <input type="text"
				id="fecha_formulario" name="fecha_formulario" readonly
				placeholder="Fecha del formulario" />
		</div>
		<div data-linea="1">
			<label for="especie">Especie: </label> <select id="especie"
				name="especie">
            		<?php
														echo $this->comboEspecie;
														?>
        		</select>
		</div>
		<div data-linea="2">
			<label for="categoria_etaria">Etapa productiva (cat. etaria):</label>
			<select id="categoria_etaria" name="categoria_etaria">
				<option value="">Seleccione...</option>
			</select>
		</div>
		<div data-linea="2">
			<label for="num_csmi">Nro. de CSMI: </label> <input type="text"
				id="num_csmi" name="num_csmi" value="" placeholder="Número de CSMI"
				maxlength="8" />
		</div>
		<div data-linea="3">
			<label for="num_lote">Nro. de Lote: </label> <input type="text"
				id="num_lote" name="num_lote" value="" placeholder="Número de lote"
				maxlength="8" />
		</div>
		<div data-linea="3">
			<label for="peso_vivo_promedio">Peso vivo promedio: </label> <input
				type="text" id="peso_vivo_promedio" name="peso_vivo_promedio"
				placeholder="Peso vivo promedio" />
		</div>
		<div data-linea="4">
			<label for="num_machos">Nro. Machos: </label> <input type="text"
				id="num_machos" name="num_machos" value=""
				placeholder="Número de machos" maxlength="8" />
		</div>

		<div data-linea="4">
			<label for="num_hembras">Nro. Hembras: </label> <input type="text"
				id="num_hembras" name="num_hembras" value=""
				placeholder="Números de hembras" maxlength="8" />
		</div>
		<div data-linea="5">
			<label for="num_total_animales">Nro. Total de animales: </label> <input
				type="text" id="num_total_animales" name="num_total_animales"
				value="" placeholder="Número total de animales" maxlength="8" />
		</div>
		<div data-linea="5">
			<label for="hallazgos">Existen hallazgos: </label> <select
				id="hallazgos" name="hallazgos">
				<?php
				echo $this->comboSiNo();
				?>
			</select>
		</div>


	</fieldset>

	<fieldset id="animalesMuertos">
		<legend>Animales Muertos</legend>

		<div data-linea="1">
			<label for="num_animales_muertos">Nro. de animales muertos: </label>
			<input type="text" id="num_animales_muertos"
				name="num_animales_muertos" value=""
				placeholder="Número de animales muertos" maxlength="8" />
		</div>
		<div data-linea="1">
			<label for="causa_probable">Causa probable: </label> <input
				type="text" id="causa_probable" name="causa_probable" value=""
				placeholder="Causa probable de muerte" maxlength="1024" />
		</div>
		<div data-linea="2">
			<label for="decomiso">Decomiso: </label> <select id="decomiso"
				name="decomiso">
				<?php
				echo $this->comboParcialTotal();
				?>
			</select>
		</div>
		<div data-linea="2">
			<label for="aprovechamiento">Aprovechamiento: </label> <select
				id="aprovechamiento" name="aprovechamiento">
				<?php
				echo $this->comboParcialTotal();
				?>
			</select>
		</div>

	</fieldset>

	<fieldset id="signosClinicos">
		<legend>Signos Clínicos Visibles</legend>

		<div data-linea="1">
			<label for="num_animales_nerviosos">Nro. de animales con síndrome
				nervioso: </label> <input type="text" id="num_animales_nerviosos"
				name="num_animales_nerviosos" value=""
				placeholder="Número de animales nerviosos" maxlength="8" />
		</div>
		<div data-linea="1">
			<label for="num_animales_vesicular">Nro. de animales con sídrome
				vesicular: </label> <input type="text" id="num_animales_vesicular"
				name="num_animales_vesicular" value=""
				placeholder="Números de animales con signos vesiculares"
				maxlength="8" />
		</div>
		<div data-linea="2">
			<label for="num_animales_digestivo">Nro. de animales con síndrome
				digestivo: </label> <input type="text" id="num_animales_digestivo"
				name="num_animales_digestivo" value=""
				placeholder="Números de animales  con signos digestivos"
				maxlength="8" />
		</div>
		<div data-linea="2">
			<label for="num_animales_reproductivo">Nro. de animales con síndrome
				reproductivo: </label> <input type="text"
				id="num_animales_reproductivo" name="num_animales_reproductivo"
				value="" placeholder="Números de animales con signos reproductivos"
				maxlength="8" />
		</div>
		<div data-linea="3">
			<label for="num_animales_respiratorio">Nro. de animales con síndrome
				respiratorio: </label> <input type="text"
				id="num_animales_respiratorio" name="num_animales_respiratorio"
				value="" placeholder="Número de animales con signos respiratorios"
				maxlength="8" />
		</div>

	</fieldset>
	<fieldset id="locomocion">
		<legend>Locomocíon</legend>

		<div data-linea="1">
			<label for="num_animales_cojera">Nro. de animales con cojera: </label>
			<input type="text" id="num_animales_cojera"
				name="num_animales_cojera" value=""
				placeholder="Número de animales con cojera" maxlength="8" />
		</div>
		<div data-linea="1">
			<label for="num_animales_ambulatorios">Nro. de animales no ambulatorios:
			</label> <input type="text" id="num_animales_ambulatorios"
				name="num_animales_ambulatorios" value=""
				placeholder="Número de animales no ambulatorios" maxlength="8" />
		</div>
	</fieldset>

	<fieldset id="dictamen">
		<legend>Dictamen</legend>

		<div data-linea="1">
			<label for="matanza_normal">Matanza normal (Nro.): </label> <input
				type="text" id="matanza_normal" name="matanza_normal" value=""
				placeholder="Número de animales requieren matanza normal"
				maxlength="8" />
		</div>
		<div data-linea="1">
			<label for="matanza_emergencia">Matanza de emergencia (Nro.): </label>
			<input type="text" id="matanza_emergencia" name="matanza_emergencia"
				placeholder="Número de animales que requieren matanza de emergencia"
				maxlength="8" />
		</div>

		<div data-linea="2">
			<label for="matanza_especiales">Matanza bajo precauciones especiales
				(Nro.): </label> <input type="text" id="matanza_especiales"
				name="matanza_especiales"
				placeholder="Número de animales que requieren matanza especial"
				maxlength="8" />
		</div>

		<div data-linea="2">
			<label for="aplazamiento_matanza">Aplazamiento de matanza: </label> <input
				type="text" id="aplazamiento_matanza" name="aplazamiento_matanza"
				placeholder="Número de animales que requieren aplazamiento de matanza"
				maxlength="8" />
		</div>

	</fieldset>
	<fieldset id="observaciones">
		<legend>Observaciones</legend>

		<div data-linea="1">
			<input type="text" id="observacion" name="observacion" value=""
				placeholder="Observaciones del formulario" maxlength="1024" />
		</div>

	</fieldset>
	<div data-linea="1">
		<button id="agregarFormulario" type="button" class="mas">Guardar
			registro</button>
	</div>
	<fieldset>
		<legend>Detalle de los registros guardados</legend>
		<table id="detalleProducto" style="width: 100%">
			<tbody>

				<tr>
					<th># Registro</th>
					<th>Fecha</th>
					<th>Especie</th>
					<th>Previsualizar</th>
				</tr>
			
			
			<tbody id="bodyTbl">
			<?php echo $this->datosDetalleFormulario;?>
			</tbody>
		</table>

	</fieldset>

	<div data-linea="1">
		<button type="button" id="enviarRevision">Enviar a revisión</button>
		<button type="button" id="aprobar" >Aprobar</button>
		<button type="button" id="generar" >Generar</button>
	</div>

</form>
<!-- Modal para datos del detalle del formulario -->
<div class="modal fade" id="modalDetalle" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">DETALLE DEL REGISTRO</h4>
				<div id="estado"></div>
			</div>
			<div class="modal-body">

				<div id="divDetalle"></div>

			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			<div id="estadoDetalle"></div>

		</div>
	</div>
</div>
<iframe id="formularioCreado" width="100%" height="100%"
	src="<?php echo $this->urlPdf; ?>" frameborder="0" allowfullscreen></iframe>

<script type="text/javascript">
    var estadoRegistro = <?php echo json_encode($this->modeloFormularioAnteMortem->getEstado());?>;
    var fechaInical = <?php echo json_encode($this->fechaInicial);?>;
    var idCentroFaenamiento = <?php echo json_encode($this->idCentroFaenamiento);?>;
    var idOperadorTipoOperacion = <?php echo json_encode($this->idOperadorTipoOperacion); ?>;
    var fechaActual = <?php echo json_encode(date("Y-m-d"));?>;
    var idFormularioDetalle = <?php echo json_encode($this->idFormularioEditar);?>;
    var perfilUsuario = <?php echo json_encode($this->perfilUsuario); ?>;
    var arreglo = [];
	$(document).ready(function() {
		construirValidador();
		establecerFechas('fecha_formulario',fechaActual);
		setearVariablesIniciales();
		mostrarMensaje("", "FALLO");

		$("#animalesMuertos").hide();
        $("#signosClinicos").hide();
        $("#locomocion").hide();
        $("#enviarRevision").hide();
        $("#aprobar").hide();
        if($("#id_formulario_ante_mortem").val() != null && $("#id_formulario_ante_mortem").val() != ''){
        	  $("#aprobar").show();
          }else{
              $("#aprobar").hide();
         }
                
        if(estadoRegistro == 'Aprobado_AM'){
        	$("#agregarFormulario").hide();
        	$("#dictamen").hide();
        	$("#observaciones").hide();
        	$("#generalidades").hide();
        	$("#aprobar").hide();
           }else{
        	$("#generar").hide();
           }
        distribuirLineas();
	 });

	
	//Cuando seleccionamos la especie buscar etaria
    $("#especie").change(function() {
        if($("#especie").val() != ''){
        $.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioAnteMortem/buscarProductosXespecie", 
				{
				    especie: $("#especie").val(),
				    idOperadorTipoOperacion: idOperadorTipoOperacion
				},
				function (data) {
	            	$("#categoria_etaria").html(data);
	            	$("#categoria_etaria").removeAttr("disabled");
	        	});
        }else {
        	 $("#categoria_etaria").html('<option value="">Seleccione...</option>');
            }
	});
  //*********enviar a revision el formulario
    $("#enviarRevision").click(function() {
    	if($("#id_formulario_ante_mortem").val() != ''){
    	$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioAnteMortem/enviarRevisionAnimales", 
				{
    		        id_formulario_ante_mortem: $("#id_formulario_ante_mortem").val(),
    		        estado: 'Por revisar'
				},
				function (data) {
					 if(data.estado == 'EXITO'){
						        if(idFormularioDetalle != '' && idFormularioDetalle != null){
							    	$('#tablaItems #'+idFormularioDetalle+' td:eq(1)').html('<b>Por revisar</b>'); 
							    	$("#detalleItem").html("<div id='cargando'>Cargando...</div>").wait(170).html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
							    }else{ 
							    	abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#detalleItem",false);
								}
							    mostrarMensaje(data.mensaje, "EXITO")
							    $("#estado").html(data.mensaje).wait(170).html('');
						  }else{
							  alert(data.mensaje);
							 mostrarMensaje(data.mensaje, "FALLO");
						  }
	        	}, 'json'); 
    	}else{
    		mostrarMensaje("Debe existir el id del formulario.", "FALLO");
        	}
	});

    //*********Aprobar el formulario
    $("#aprobar").click(function() {
    	if($("#id_formulario_ante_mortem").val() != ''){
    	$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioAnteMortem/aprobarFormularioAnimales", 
				{
    		        id_formulario_ante_mortem: $("#id_formulario_ante_mortem").val(),
    		        estado: 'Aprobado_AM'
				},
				function (data) {
					 if(data.estado == 'EXITO'){
						 if(idFormularioDetalle != '' && idFormularioDetalle != null){
						    	$('#tablaItems #'+idFormularioDetalle+' td:eq(1)').html('<b>Aprobado_AM</b>'); 
						    	$("#detalleItem").html("<div id='cargando'>Cargando...</div>").wait(170).html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
						    }else{ 
						    	abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#detalleItem",false);
							}
						    mostrarMensaje(data.mensaje, "EXITO")
						    $("#estado").html(data.mensaje).wait(170).html('');
						  }else{
							  alert(data.mensaje);
							 mostrarMensaje(data.mensaje, "FALLO");
						  }
	        	}, 'json'); 
    	}else{
    		mostrarMensaje("Debe existir el id del formulario.", "FALLO");
        	}
	});
    //*********Aprobar el formulario
    $("#generar").click(function() {
    	if($("#id_formulario_ante_mortem").val() != ''){
    	$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioAnteMortem/generarFormularioAnimales", 
				{
    		        id_formulario_ante_mortem: $("#id_formulario_ante_mortem").val(),
    		        estado: 'Aprobado_AM',
    		        idFormularioDetalle: idFormularioDetalle
				},
				function (data) {
					 if(data.estado == 'EXITO'){
							 mostrarMensaje(data.mensaje, "EXITO");
							 $("#formularioCreado").attr("src", data.ruta);
							// abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
						  }else{
							  alert(data.mensaje);
							 mostrarMensaje(data.mensaje, "FALLO");
						  }
	        	}, 'json'); 
    	}else{
    		mostrarMensaje("Debe existir el id del formulario.", "FALLO");
        	}
	});
//*****************************************************************************************************
	$("#agregarFormulario").click(function () {
        $(".alertaCombo").removeClass("alertaCombo");
        mostrarMensaje("", "FALLO");
      	var error = false;
      	if($("#hallazgos").val() == 'Si'){
      		error = verificarCamposObligatorios(1);
      	}else{
      		error = verificarCamposObligatorios(2);
      	}
        if(!error){
        		$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioAnteMortem/agregarFormularioAnimales", 
                        {
        			        //*****cabecera*******
        			        id_formulario_ante_mortem: $("#id_formulario_ante_mortem").val(),
        			        idCentroFaenamiento: idCentroFaenamiento,
							//*****generalidades*****
			      		    fecha_formulario: $("#fecha_formulario").val(),
			      		    especie: $("#especie option:selected").text(),
			      		    categoria_etaria: $("#categoria_etaria option:selected").text(),
			      		    num_csmi: $("#num_csmi").val(),
			      		    num_lote: $("#num_lote").val(),
			      		    peso_vivo_promedio: $("#peso_vivo_promedio").val(),
			      		    num_machos: $("#num_machos").val(),
			      		    num_hembras: $("#num_hembras").val(),
			      		    num_total_animales: $("#num_total_animales").val(),
			      		    hallazgos: $("#hallazgos").val(),
			      		    //*****animale muertos
			                num_animales_muertos: $("#num_animales_muertos").val(),
			                causa_probable: $("#causa_probable").val(),
			                decomiso: $("#decomiso").val(),
			                aprovechamiento: $("#aprovechamiento").val(),
			                //*****signos clinicos visibles*****
			        		num_animales_nerviosos: $("#num_animales_nerviosos").val(),
			        		num_animales_digestivo: $("#num_animales_digestivo").val(),
			        		num_animales_respiratorio: $("#num_animales_respiratorio").val(),
			        		num_animales_vesicular: $("#num_animales_vesicular").val(),
			        		num_animales_reproductivo: $("#num_animales_reproductivo").val(),
			        		//*****locomocion*****
			        		num_animales_cojera: $("#num_animales_cojera").val(),
			        		num_animales_ambulatorios: $("#num_animales_ambulatorios").val(),
			        		//*****dictamen*****
			        		matanza_normal: $("#matanza_normal").val(),
			        		matanza_especiales: $("#matanza_especiales").val(),
			        		matanza_emergencia: $("#matanza_emergencia").val(),
			        		aplazamiento_matanza: $("#aplazamiento_matanza").val(),
        		            //********observacion*****
        		            observacion: $("#observacion").val()
                        	
     					},
     					function (data) {
     						  if(data.estado === 'EXITO'){
     							 $("#bodyTbl").html(data.contenido);
     							 $("#id_formulario_ante_mortem").val(data.id);
     							$("#aprobar").show();
     							 setearVariablesRegistro();
     							 mostrarMensaje(data.mensaje, "EXITO");
     						  }else{
     							  mostrarMensaje(data.mensaje, "FALLO");
     						  }
     		        	}, 'json');  
        	           
  		}else{
  			mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
  		}
	});
//*****************************************************************************************************
//validar si tiene hallazgos o no el formulario******
    $("#hallazgos").change(function () {

    	if($("#hallazgos").val() == 'Si'){
    		    $("#animalesMuertos").show();
    	        $("#signosClinicos").show();
    	        $("#locomocion").show();
    	        distribuirLineas();
    	}else{
    		    $("#animalesMuertos").hide();
    	        $("#signosClinicos").hide();
    	        $("#locomocion").hide();
    	}
       
        });
    //************setear los campos**********
	function setearVariablesIniciales(){
		//************generalidades*******************************
		$("#num_csmi").numeric();
		$("#num_lote").numeric();
		$("#peso_vivo_promedio").val('');
		$("#num_machos").val('');
		$("#num_hembras").val('');
		$("#num_total_animales").val('');
		 
		//*****animale muertos
        $("#num_animales_muertos").numeric();
		//*****signos clinicos visibles*****
		$("#num_animales_nerviosos").numeric();
		$("#num_animales_digestivo").numeric();
		$("#num_animales_respiratorio").numeric();
		$("#num_animales_vesicular").numeric();
		$("#num_animales_reproductivo").numeric();
		//*****locomocion*****
		$("#num_animales_cojera").numeric();
		$("#num_animales_ambulatorios").numeric();
		//*****dictamen*****
		$("#matanza_normal").numeric();
		$("#matanza_especiales").numeric();
		$("#matanza_emergencia").numeric();
		$("#aplazamiento_matanza").numeric();
		//**********************************
		$("#categoria_etaria").attr("disabled","disabled");
		
	}

	 //************setear los campos cuando se guarde un registro**********
	function setearVariablesRegistro(){
		//************generalidades*******************************
		$("#fecha_formulario").val(fechaActual);
		$("#especie").val('');
		$("#categoria_etaria").val('');
		$("#categoria_etaria").attr("disabled","disabled");
		$("#num_csmi").val('');
		$("#num_lote").val('');
		$("#peso_vivo_promedio").val('');
		$("#num_machos").val('');
		$("#num_hembras").val('');
		$("#num_total_animales").val('');
		 
		//*****animale muertos
        $("#num_animales_muertos").val('');
        $("#causa_probable").val('');
        $("#decomiso").val('');
        $("#aprovechamiento").val('');
		//*****signos clinicos visibles*****
		$("#num_animales_nerviosos").val('');
		$("#num_animales_digestivo").val('');
		$("#num_animales_respiratorio").val('');
		$("#num_animales_vesicular").val('');
		$("#num_animales_reproductivo").val('');
		//*****locomocion*****
		$("#num_animales_cojera").val('');
		$("#num_animales_ambulatorios").val('');
		//*****dictamen*****
		$("#matanza_normal").val('');
		$("#matanza_especiales").val('');
		$("#matanza_emergencia").val('');
		$("#aplazamiento_matanza").val('');
		//*****observacion********
		$("#observacion").val('');
	}
//************verificar campos obligatorios*******
	function verificarCamposObligatorios(opt){
		var error = false;
		switch (opt) { 
		case 1: 
			error = verificarAnimalesMuertos();
			//*****generalidades*****
			  if(!$.trim($("#fecha_formulario").val())){
	  			   $("#fecha_formulario").addClass("alertaCombo");
	  			   error = true;
	  		  }
	          if(!$.trim($("#especie").val())){
		  			$("#especie").addClass("alertaCombo");
		  			error =  true;
		  		  }
	          if (!$.trim($("#categoria_etaria").val())) {
	  			   $("#categoria_etaria").addClass("alertaCombo");
	  			   error = true;
	          }

	          //verificar el tipo de especie Cavia y Cunícola
	          if($("#especie option:selected").text() == 'Cavia' || $("#especie option:selected").text() == 'Cunícola' ){
		          //no verifique
	          }else{
		          if (!$.trim($("#num_csmi").val())) {
			  			$("#num_csmi").addClass("alertaCombo");
			  			error =  true;
			        }
	          }
	          if (!$.trim($("#num_lote").val())) {
		  			$("#num_lote").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#peso_vivo_promedio").val())) {
		  			$("#peso_vivo_promedio").addClass("alertaCombo");
		  			error =  true;
		      }
		      if(!$.trim($("#num_machos").val())){
		  			$("#num_machos").addClass("alertaCombo");
		  			error =  true;
		  		  }
		      if(!$.trim($("#num_hembras").val())){
		  			$("#num_hembras").addClass("alertaCombo");
		  			error =  true;
		  		  }
		      if(!$.trim($("#num_total_animales").val())){
		  			$("#num_total_animales").addClass("alertaCombo");
		  			error =  true;
		  		  }
		      if (!$.trim($("#hallazgos").val())) {
		  			$("#hallazgos").addClass("alertaCombo");
		  			error =  true;
		      }

			if(verificarAnimalesMuertosGrupo() == true && verificarSignosClinicos() == true && verificarLocomocion() == true ){
                error = true;
                //*****animale muertos
                $("#num_animales_muertos").addClass("alertaCombo");
                $("#causa_probable").addClass("alertaCombo");
                $("#decomiso").addClass("alertaCombo");
                $("#aprovechamiento").addClass("alertaCombo");
        		//*****signos clinicos visibles*****
        		$("#num_animales_nerviosos").addClass("alertaCombo");
        		$("#num_animales_digestivo").addClass("alertaCombo");
        		$("#num_animales_respiratorio").addClass("alertaCombo");
        		$("#num_animales_vesicular").addClass("alertaCombo");
        		$("#num_animales_reproductivo").addClass("alertaCombo");
        		//*****locomocion*****
        		$("#num_animales_cojera").addClass("alertaCombo");
        		$("#num_animales_ambulatorios").addClass("alertaCombo");
        		
				}
			
			//*****dictamen*****
			if (!$.trim($("#matanza_normal").val())) {
		  			$("#matanza_normal").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#matanza_especiales").val())) {
		  			$("#matanza_especiales").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#matanza_emergencia").val())) {
		  			$("#matanza_emergencia").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#aplazamiento_matanza").val())) {
		  			$("#aplazamiento_matanza").addClass("alertaCombo");
		  			error =  true;
		      }
		    //*****observacion***
		    if (!$.trim($("#observacion").val())) {
		  			$("#observacion").addClass("alertaCombo");
		  			error =  true;
		      }
			break;
		case 2: 
			//*****generalidades*****
			  if(!$.trim($("#fecha_formulario").val())){
	  			   $("#fecha_formulario").addClass("alertaCombo");
	  			   error = true;
	  		  }
	          if(!$.trim($("#especie").val())){
		  			$("#especie").addClass("alertaCombo");
		  			error =  true;
		  		  }
	          if (!$.trim($("#categoria_etaria").val())) {
	  			   $("#categoria_etaria").addClass("alertaCombo");
	  			   error = true;
	          }
	        //verificar el tipo de especie Cavia y Cunícola
	          if($("#especie option:selected").text() == 'Cavia' || $("#especie option:selected").text() == 'Cunícola' ){
		          //no verifique
	          }else{ 
		          if (!$.trim($("#num_csmi").val())) {
			  			$("#num_csmi").addClass("alertaCombo");
			  			error =  true;
			        }
	          }
	          if (!$.trim($("#num_lote").val())) {
		  			$("#num_lote").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#peso_vivo_promedio").val())) {
		  			$("#peso_vivo_promedio").addClass("alertaCombo");
		  			error =  true;
		      }
		      if(!$.trim($("#num_machos").val())){
		  			$("#num_machos").addClass("alertaCombo");
		  			error =  true;
		  		  }
		      if(!$.trim($("#num_hembras").val())){
		  			$("#num_hembras").addClass("alertaCombo");
		  			error =  true;
		  		  }
		      if(!$.trim($("#num_total_animales").val())){
		  			$("#num_total_animales").addClass("alertaCombo");
		  			error =  true;
		  		  }
		      if (!$.trim($("#hallazgos").val())) {
		  			$("#hallazgos").addClass("alertaCombo");
		  			error =  true;
		      }
		     
			
			//*****dictamen*****
			if (!$.trim($("#matanza_normal").val())) {
		  			$("#matanza_normal").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#matanza_especiales").val())) {
		  			$("#matanza_especiales").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#matanza_emergencia").val())) {
		  			$("#matanza_emergencia").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#aplazamiento_matanza").val())) {
		  			$("#aplazamiento_matanza").addClass("alertaCombo");
		  			error =  true;
		      }
		    //*****observacion***
		    if (!$.trim($("#observacion").val())) {
		  			$("#observacion").addClass("alertaCombo");
		  			error =  true;
		      }
			break;
			
		}
		return error;
	    }

	//************previsualizar detalle formulario***********************
    function btnPrevisualizar(id){
    	$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioAnteMortem/detalleFormularioAnimalesPrevisualizar",{
    		    id_detalle_ante_animales : id,
    		    estadoRegistro : estadoRegistro
	      		},
	      		function (data) {
                    $('#modalDetalle').modal('show');
                    $("#divDetalle").html(data);
                });
    	
     }

    //*************verificar Animales Muertos*********************************
    function verificarAnimalesMuertos(){

    	falla = true;
	     if ($.trim($("#num_animales_muertos").val()) != '') {
	  			falla =  false;
	      }
	     if ($.trim($("#causa_probable").val()) != '') {
	  			falla =  false;
	      }
	     if ($.trim($("#decomiso").val()) != '') {
	  			falla =  false;
	      }
	     if ($.trim($("#aprovechamiento").val()) != '') {
	  			falla =  false;
	      }
	      if(falla == false){
		    	  if (!$.trim($("#num_animales_muertos").val())) {
			  			$("#num_animales_muertos").addClass("alert-danger");
			  			falla = true;
			      }
			    if (!$.trim($("#causa_probable").val())) {
			  			$("#causa_probable").addClass("alert-danger");
			  			falla = true;
			      }
			    if (!$.trim($("#decomiso").val())) {
			  			$("#decomiso").addClass("alert-danger");
			  			falla = true;
			      }
			    if (!$.trim($("#aprovechamiento").val())) {
			  			$("#aprovechamiento").addClass("alert-danger");
			  			falla = true;
			      }
			      return falla;
	    	  
		      }else{
				  return false;
			      }
        }
  //*************verificar Animales Muertos grupo*********************************
    function verificarAnimalesMuertosGrupo(){
 			falla = true;
		    if ($.trim($("#num_animales_muertos").val()) != '') {
		  			falla =  false;
		      }
		    if ($.trim($("#causa_probable").val()) != '') {
		  			falla =  false;
		      }
		    if ($.trim($("#decomiso").val()) != '') {
		  			falla =  false;
		      }
		    if ($.trim($("#aprovechamiento").val()) != '') {
		  			falla =  false;
		      }
		      return falla;
    	
        }
    //*************verificar Signos clinicos visibles*********************************
    function verificarSignosClinicos(){
			if ($.trim($("#num_animales_nerviosos").val()) != '') {
				return  false;
			}else if($.trim($("#num_animales_digestivo").val()) != ''){
				return  false;
			}else if($.trim($("#num_animales_respiratorio").val()) != ''){
				return  false;
			}else if($.trim($("#num_animales_vesicular").val()) != ''){
				return  false;
			}else if($.trim($("#num_animales_reproductivo").val()) != ''){
				return  false;
			}else{
				return  true;
					}
        }
    //*************verificar locomocion*********************************
    function verificarLocomocion(){
	    	if ($.trim($("#num_animales_cojera").val()) != '') {
				return  false;
			}else if($.trim($("#num_animales_ambulatorios").val()) != ''){
				return  false;
			}else{
				return  true;
			}
        }

</script>

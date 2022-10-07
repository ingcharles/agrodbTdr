<script
	src="<?php echo URL ?>modulos/InspeccionAntePostMortemCF/vistas/js/funcionCf.js"></script>
<form id='formularioDetalleAnteAnimales'>
	<input type="hidden" id="id_detalle_ante_animales"
		name="id_detalle_ante_animales"
		value="<?php echo $this->modeloDetalleAnteAnimales->getIdDetalleAnteAnimales();?>" />
	<input type="hidden" id="id_formulario_ante_mortem"
		name="id_formulario_ante_mortem"
		value="<?php echo $this->modeloDetalleAnteAnimales->getIdFormularioAnteMortem();?>" />
	<input type="hidden" id="id_hallazgos_animales_muertos"
		name="id_hallazgos_animales_muertos"
		value="<?php echo $this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesMuertos();?>" />
	<input type="hidden" id="id_hallazgos_animales_clinicos"
		name="id_hallazgos_animales_clinicos"
		value="<?php echo $this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesClinicos();?>" />
	<input type="hidden" id="id_hallazgos_animales_locomocion"
		name="id_hallazgos_animales_locomocion"
		value="<?php echo $this->modeloDetalleAnteAnimales->getIdHallazgosAnimalesLocomocion();?>" />

	<fieldset>
		<legend>Generalidades</legend>
		<div class="form-horizontal">
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Fecha: </label>
					<div class="col-lg-8">
						<input type="text" id="fecha_formulario_detalle"
							class="form-control" name="fecha_formulario_detalle" readonly
							placeholder="Fecha del formulario" />
					</div>
				</div>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Especie: </label>
					<div class="col-lg-8">
						<select id="especie_detalle" name="especie_detalle" class="form-control"> 
            		    <?php
						echo $this->comboEspecie;
						?>
        		        </select>
					</div>
				</div>
			</div>
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Etapa productiva (cat.
						etaria): </label>
					<div class="col-lg-8">
						<select id="categoria_etaria_detalle" name="categoria_etaria_detalle"
							class="form-control"> 
            		<?php
						echo $this->comboProducto;
						?>
        		</select>
					</div>
				</div>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Nro. de CSMI: </label>
					<div class="col-lg-8">
						<input type="text" id="num_csmi_detalle" name="num_csmi_detalle"
							class="form-control"
							value="<?php echo $this->modeloDetalleAnteAnimales->getNumCsmi(); ?>"
							placeholder="Número de CSMI" maxlength="8" />
					</div>
				</div>
			</div>
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Nro. de Lote: </label>
					<div class="col-lg-8">
						<input type="text" id="num_lote_detalle" name="num_lote_detalle"
							class="form-control"
							value="<?php echo $this->modeloDetalleAnteAnimales->getNumLote(); ?>"
							placeholder="Número de lote" maxlength="64" />
					</div>
				</div>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Peso vivo promedio: </label>
					<div class="col-lg-8">
						<input type="text" id="peso_vivo_promedio_detalle"
							name="peso_vivo_promedio_detalle" class="form-control"
							value="<?php echo $this->modeloDetalleAnteAnimales->getPesoVivoPromedio(); ?>"
							placeholder="Peso vivo promedio" maxlength="8" />
					</div>
				</div>
			</div>
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Nro. Machos: </label>
					<div class="col-lg-8">
						<input type="text" id="num_machos_detalle" name="num_machos_detalle"
							class="form-control"
							value="<?php echo $this->modeloDetalleAnteAnimales->getNumMachos(); ?>"
							placeholder="Número de machos" maxlength="8" />
					</div>
				</div>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Nro. Hembras: </label>
					<div class="col-lg-8">
						<input type="text" id="num_hembras_detalle" name="num_hembras_detalle"
							class="form-control"
							value="<?php echo $this->modeloDetalleAnteAnimales->getNumHembras(); ?>"
							placeholder="Número de hembras" maxlength="8" />
					</div>
				</div>
			</div>
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Nro. Total de
						animales: </label>
					<div class="col-lg-8">
						<input type="text" id="num_total_animales_detalle"
							name="num_total_animales_detalle" class="form-control"
							value="<?php echo $this->modeloDetalleAnteAnimales->getNumTotalAnimales(); ?>"
							placeholder="Número total de animales" maxlength="8" />
					</div>
				</div>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Existen hallazgos: </label>
					<div class="col-lg-8">
						<select id="hallazgos_detalle" name="hallazgos_detalle"
							class="form-control">
							<?php
							echo $this->comboSiNo($this->modeloDetalleAnteAnimales->getHallazgos());
							?>
						</select>
					</div>
				</div>
			</div>
		</div>
	</fieldset>

	<fieldset id="animalesMuertosDetalle">
		<legend>Animales Muertos</legend>
		<div class="form-horizontal">
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Nro. de animales
						muertos: </label>
					<div class="col-lg-8">
						<input type="text" id="num_animales_muertos_detalle"
							name="num_animales_muertos_detalle"
							value="<?php echo $this->modeloHallazgosAnimalesMuertos->getNumAnimalesMuertos();?>"
							class="form-control" placeholder="Número de aves muertas"
							maxlength="8" />
					</div>
				</div>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Causa probable: </label>
					<div class="col-lg-8">
						<input type="text" id="causa_probable_detalle" name="causa_probable_detalle"
							class="form-control"
							value="<?php echo $this->modeloHallazgosAnimalesMuertos->getCausaProbable();?>"
							placeholder="Causa probable de muerte" maxlength="1024" />
					</div>
				</div>
			</div>
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Decomiso: </label>
					<div class="col-lg-8">
						<select id="decomiso_detalle" name="decomiso_detalle" class="form-control"> 
							<?php
							echo $this->comboParcialTotal($this->modeloHallazgosAnimalesMuertos->getDecomiso());
							?>
			</select>
					</div>
				</div>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Aprovechamiento: </label>
					<div class="col-lg-8">
						<select id="aprovechamiento_detalle" name="aprovechamiento_detalle" class="form-control">
							<?php
							echo $this->comboParcialTotal($this->modeloHallazgosAnimalesMuertos->getAprovechamiento());
							?>
			</select>
					</div>
				</div>
			</div>

		</div>
	</fieldset>

	<fieldset id="signosClinicosDetalle">
		<legend>Signos Clínicos Visibles</legend>
		<div class="form-horizontal">
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Nro. de animales con
						síndrome nervioso: </label>
					<div class="col-lg-8">
						<input type="text" id="num_animales_nerviosos_detalle"
							name="num_animales_nerviosos_detalle"
							value="<?php echo $this->modeloHallazgosAnimalesClinicos->getNumAnimalesNerviosos();?>"
							class="form-control" placeholder="Número de animales nerviosos"
							maxlength="8" />
					</div>
				</div>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Nro. de animales con
						sídrome vesicular: </label>
					<div class="col-lg-8">
						<input type="text" id="num_animales_vesicular_detalle"
							name="num_animales_vesicular_detalle" class="form-control"
							value="<?php echo $this->modeloHallazgosAnimalesClinicos->getNumAnimalesVesicular();?>"
							placeholder="Números de animales con signos vesiculares"
							maxlength="8" />
					</div>
				</div>
			</div>
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Nro. de animales con
						síndrome digestivo: </label>
					<div class="col-lg-8">
						<input type="text" id="num_animales_digestivo_detalle"
							name="num_animales_digestivo_detalle"
							value="<?php echo $this->modeloHallazgosAnimalesClinicos->getNumAnimalesDigestivo();?>"
							class="form-control"
							placeholder="Números de animales  con signos digestivos"
							maxlength="8" />
					</div>
				</div>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Nro. de animales con
						síndrome reproductivo: </label>
					<div class="col-lg-8">
						<input type="text" id="num_animales_reproductivo_detalle"
							name="num_animales_reproductivo_detalle"
							value="<?php echo $this->modeloHallazgosAnimalesClinicos->getNumAnimalesReproductivo();?>"
							class="form-control"
							placeholder="Números de animales con signos reproductivos"
							maxlength="8" />
					</div>
				</div>
			</div>
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Nro. de animales con
						síndrome respiratorio: </label>
					<div class="col-lg-8">
						<input type="text" id="num_animales_respiratorio_detalle"
							name="num_animales_respiratorio_detalle"
							value="<?php echo $this->modeloHallazgosAnimalesClinicos->getNumAnimalesRespiratorio();?>"
							class="form-control"
							placeholder="Número de animales con signos respiratorios"
							maxlength="8" />
					</div>
				</div>
			</div>
		</div>
	</fieldset>
	<fieldset id="locomocionDetalle">
		<legend>Locomocíon</legend>
		<div class="form-horizontal">
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Nro. de animales con
						cojera: </label>
					<div class="col-lg-8">
						<input type="text" id="num_animales_cojera_detalle"
							name="num_animales_cojera_detalle"
							value="<?php echo $this->modeloHallazgosAnimalesLocomocion->getNumAnimalesCogera();?>"
							class="form-control" placeholder="Número de animales con cojera"
							maxlength="8" />
					</div>
				</div>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Nro. de animales no
						ambulatorios: </label>
					<div class="col-lg-8">
						<input type="text" id="num_animales_ambulatorios_detalle"
							name="num_animales_ambulatorios_detalle"
							value="<?php echo $this->modeloHallazgosAnimalesLocomocion->getNumAnimalesAmbulatorios();?>"
							class="form-control"
							placeholder="Número de animales no ambulatorios" maxlength="8" />
					</div>
				</div>
			</div>

		</div>
	</fieldset>

	<fieldset>
		<legend>Dictamen</legend>
		<div class="form-horizontal">
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Matanza normal (Nro.):
					</label>
					<div class="col-lg-8">
						<input type="text" id="matanza_normal_detalle" name="matanza_normal_detalle"
							value="<?php echo $this->modeloDetalleAnteAnimales->getMatanzaNormal(); ?>"
							class="form-control"
							placeholder="Número de animales requieren matanza normal"
							maxlength="8" />
					</div>
				</div>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Matanza de emergencia
						(Nro.): </label>
					<div class="col-lg-8">
						<input type="text" id="matanza_emergencia_detalle"
							value="<?php echo $this->modeloDetalleAnteAnimales->getMatanzaEmergencia(); ?>"
							name="matanza_emergencia_detalle" class="form-control"
							placeholder="Número de animales que requieren matanza de emergencia"
							maxlength="8" />
					</div>
				</div>
			</div>
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Matanza bajo
						precauciones especiales (Nro.): </label>
					<div class="col-lg-8">
						<input type="text" id="matanza_especiales_detalle"
							value="<?php echo $this->modeloDetalleAnteAnimales->getMatanzaEspeciales(); ?>"
							name="matanza_especiales_detalle" class="form-control"
							placeholder="Número de animales que requieren matanza especial"
							maxlength="8" />
					</div>
				</div>

				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Aplazamiento de
						matanza: </label>
					<div class="col-lg-8">
						<input type="text" id="aplazamiento_matanza_detalle"
							value="<?php echo $this->modeloDetalleAnteAnimales->getAplazamientoMatanza(); ?>"
							name="aplazamiento_matanza_detalle" class="form-control"
							placeholder="Número de animales que requieren aplazamiento de matanza"
							maxlength="8" />
					</div>
				</div>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend>Observaciones</legend>
		<div class="form-horizontal">
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Observación:</label>
					<div class="col-lg-8">
						<input type="text" id="observacion_detalle"
							name="observacion_detalle"
							value="<?php echo $this->modeloDetalleAnteAnimales->observacion; ?>"
							class="form-control" placeholder="Observaciones del formulario"
							size="100" maxlength="1024" />
					</div>
				</div>
			</div>
		</div>
	</fieldset>

	<div data-linea="1">
		<button type="button" class="guardar" id="agregarFormularioDetalle">Actualizar</button>
	</div>
</form>

<script type="text/javascript">
	$(document).ready(function() {
		var estadoRegistro = <?php echo json_encode($this->estadoRegistro);?>;
	    var fechaInical = <?php echo json_encode($this->fechaInicial);?>;
	    var fechaActual = <?php echo json_encode($this->modeloDetalleAnteAnimales->getFechaFormulario());?>;
	    var hallazgos = <?php echo json_encode($this->modeloDetalleAnteAnimales->getHallazgos());?>;
		distribuirLineas();
		establecerFechas('fecha_formulario_detalle', fechaActual);
		setearVariablesInicialesDetalle();
		$("#estadoDetalle").html("");
		if(hallazgos == 'No'){
			$("#animalesMuertosDetalle").hide();
	        $("#signosClinicosDetalle").hide();
	        $("#locomocionDetalle").hide();
		}

		 if(estadoRegistro == 'Aprobado_AM'){
			  $("#agregarFormularioDetalle").hide();
			  bloquearCampos();
	       }
		
	 });

	//Cuando seleccionamos la especie buscar etaria
    $("#especie_detalle").change(function() {
        if($("#especie_detalle").val() != ''){
        $.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioAnteMortem/buscarProductosXespecie", 
				{
				    especie: $("#especie_detalle").val(),
				    idOperadorTipoOperacion: idOperadorTipoOperacion
				},
				function (data) {
	            	$("#categoria_etaria_detalle").html(data);
	            	$("#categoria_etaria_detalle").removeAttr("disabled");
	        	});
        }else {
        	 $("#categoria_etaria_detalle").html('<option value="">Seleccione...</option>');
            }
	});
	

//*****************************************************************************************************
	$("#agregarFormularioDetalle").click(function () {
        $(".alert-danger").removeClass("alert-danger");
        $(".alerta").removeClass("alerta");
        $("#estadoDetalle").html('');
        mostrarMensaje("", "FALLO");
      	var error = false;
      	if($("#hallazgos_detalle").val() == 'Si'){
      		error = verificarCamposObligatoriosDetalle(1);
      	}else{
      		error = verificarCamposObligatoriosDetalle(2);
      	}
        if(!error){
        		$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioAnteMortem/actualizarDetalleFormularioAnimales", 
                        {
		        			 //*****cabecera*******
					        id_detalle_ante_animales: $("#id_detalle_ante_animales").val(),
					        id_formulario_ante_mortem: $("#id_formulario_ante_mortem").val(),
							//*****generalidades*****
			      		    fecha_formulario: $("#fecha_formulario_detalle").val(),
			      		    especie: $("#especie_detalle option:selected").text(),
			      		    categoria_etaria: $("#categoria_etaria_detalle option:selected").text(),
			      		    num_csmi: $("#num_csmi_detalle").val(),
			      		    num_lote: $("#num_lote_detalle").val(),
			      		    peso_vivo_promedio: $("#peso_vivo_promedio_detalle").val(),
			      		    num_machos: $("#num_machos_detalle").val(),
			      		    num_hembras: $("#num_hembras_detalle").val(),
			      		    num_total_animales: $("#num_total_animales_detalle").val(),
			      		    hallazgos: $("#hallazgos_detalle").val(),
			      		    //*****animale muertos
			                num_animales_muertos: $("#num_animales_muertos_detalle").val(),
			                causa_probable: $("#causa_probable_detalle").val(),
			                decomiso: $("#decomiso_detalle").val(),
			                aprovechamiento: $("#aprovechamiento_detalle").val(),
			                //*****signos clinicos visibles*****
			        		num_animales_nerviosos: $("#num_animales_nerviosos_detalle").val(),
			        		num_animales_digestivo: $("#num_animales_digestivo_detalle").val(),
			        		num_animales_respiratorio: $("#num_animales_respiratorio_detalle").val(),
			        		num_animales_vesicular: $("#num_animales_vesicular_detalle").val(),
			        		num_animales_reproductivo: $("#num_animales_reproductivo_detalle").val(),
			        		//*****locomocion*****
			        		num_animales_cojera: $("#num_animales_cojera_detalle").val(),
			        		num_animales_ambulatorios: $("#num_animales_ambulatorios_detalle").val(),
			        		//*****dictamen*****
			        		matanza_normal: $("#matanza_normal_detalle").val(),
			        		matanza_especiales: $("#matanza_especiales_detalle").val(),
			        		matanza_emergencia: $("#matanza_emergencia_detalle").val(),
			        		aplazamiento_matanza: $("#aplazamiento_matanza_detalle").val(),
        		            //********observacion*****
        		            observacion: $("#observacion_detalle").val()
                        	
     					},
     					function (data) {
     						  if(data.estado === 'EXITO'){
     							 $("#bodyTbl").html(data.contenido);
     							$("#estadoDetalle").html(data.mensaje).addClass("exito");
     						  }else{
     							 $("#estadoDetalle").html(data.mensaje).addClass("alerta");
     						  }
     		        	}, 'json');  
        	           
  		}else{
  			$("#estadoDetalle").html("Por favor revise los campos obligatorios.").addClass("alerta");
  		}
	});
//*****************************************************************************************************
//validar si tiene hallazgos o no el formulario******
    $("#hallazgos_detalle").change(function () {

    	if($("#hallazgos_detalle").val() == 'Si'){
    		    $("#animalesMuertosDetalle").show();
    	        $("#signosClinicosDetalle").show();
    	        $("#locomocionDetalle").show();
    	        distribuirLineas();
    	}else{
    		    $("#animalesMuertosDetalle").hide();
    	        $("#signosClinicosDetalle").hide();
    	        $("#locomocionDetalle").hide();
    	}
       
        });
    //************setear los campos**********
	function setearVariablesInicialesDetalle(){
		//************generalidades*******************************
		$("#num_csmi_detalle").numeric();
		$("#num_lote_detalle").numeric();
		$("#peso_vivo_promedio_detalle").numeric();
		$("#num_machos_detalle").numeric();
		$("#num_hembras_detalle").numeric();
		$("#num_total_animales_detalle").numeric();
		//*****animale muertos
        $("#num_animales_muertos_detalle").numeric();
		//*****signos clinicos visibles*****
		$("#num_animales_nerviosos_detalle").numeric();
		$("#num_animales_digestivo_detalle").numeric();
		$("#num_animales_respiratorio_detalle").numeric();
		$("#num_animales_vesicular_detalle").numeric();
		$("#num_animales_reproductivo_detalle").numeric();
		//*****locomocion*****
		$("#num_animales_cojera_detalle").numeric();
		$("#num_animales_ambulatorios_detalle").numeric();
		//*****dictamen*****
		$("#matanza_normal_detalle").numeric();
		$("#matanza_especiales_detalle").numeric();
		$("#matanza_emergencia_detalle").numeric();
		$("#aplazamiento_matanza_detalle").numeric();
	}

//************verificar campos obligatorios*******
	function verificarCamposObligatoriosDetalle(opt){

		var error = false;
		switch (opt) { 
		case 1: 
			  error = verificarAnimalesMuertosDetalle();
			//*****generalidades*****
			  if(!$.trim($("#fecha_formulario_detalle").val())){
	  			   $("#fecha_formulario_detalle").addClass("alert-danger");
	  			   error = true;
	  		  }
	          if(!$.trim($("#especie_detalle").val())){
		  			$("#especie_detalle").addClass("alert-danger");
		  			error =  true;
		  		  }
	          if (!$.trim($("#categoria_etaria_detalle").val())) {
	  			   $("#categoria_etaria_detalle").addClass("alert-danger");
	  			   error = true;
	          }

	        //verificar el tipo de especie Cavia y Cunícola
	          if($("#especie_detalle option:selected").text() == 'Cavia' || $("#especie_detalle option:selected").text() == 'Cunícola' ){
		          //no verifique
	          }else{ 
		          if (!$.trim($("#num_csmi_detalle").val())) {
		        	  $("#num_csmi_detalle").addClass("alert-danger");
			  			error =  true;
			        }
	          }
	          
	          if (!$.trim($("#num_lote_detalle").val())) {
		  			$("#num_lote_detalle").addClass("alert-danger");
		  			error =  true;
		      }
		      if (!$.trim($("#peso_vivo_promedio_detalle").val())) {
		  			$("#peso_vivo_promedio_detalle").addClass("alert-danger");
		  			error =  true;
		      }
		      if(!$.trim($("#num_machos_detalle").val())){
		  			$("#num_machos_detalle").addClass("alert-danger");
		  			error =  true;
		  		  }
		      if(!$.trim($("#num_hembras_detalle").val())){
		  			$("#num_hembras_detalle").addClass("alert-danger");
		  			error =  true;
		  		  }
		      if(!$.trim($("#num_total_animales_detalle").val())){
		  			$("#num_total_animales_detalle").addClass("alert-danger");
		  			error =  true;
		  		  }
		      if (!$.trim($("#hallazgos_detalle").val())) {
		  			$("#hallazgos_detalle").addClass("alert-danger");
		  			error =  true;
		      }

			if(verificarAnimalesMuertosGrupoDetalle() == true && verificarSignosClinicosDetalle() == true && verificarLocomocionDetalle() == true ){
                error = true;
                //*****animale muertos
                $("#num_animales_muertos_detalle").addClass("alert-danger");
                $("#causa_probable_detalle").addClass("alert-danger");
                $("#decomiso_detalle").addClass("alert-danger");
                $("#aprovechamiento_detalle").addClass("alert-danger");
        		//*****signos clinicos visibles*****
        		$("#num_animales_nerviosos_detalle").addClass("alert-danger");
        		$("#num_animales_digestivo_detalle").addClass("alert-danger");
        		$("#num_animales_respiratorio_detalle").addClass("alert-danger");
        		$("#num_animales_vesicular_detalle").addClass("alert-danger");
        		$("#num_animales_reproductivo_detalle").addClass("alert-danger");
        		//*****locomocion*****
        		$("#num_animales_cojera_detalle").addClass("alert-danger");
        		$("#num_animales_ambulatorios_detalle").addClass("alert-danger");
        		
				}
			
			//*****dictamen*****
			if (!$.trim($("#matanza_normal_detalle").val())) {
		  			$("#matanza_normal_detalle").addClass("alert-danger");
		  			error =  true;
		      }
		      if (!$.trim($("#matanza_especiales_detalle").val())) {
		  			$("#matanza_especiales_detalle").addClass("alert-danger");
		  			error =  true;
		      }
		      if (!$.trim($("#matanza_emergencia_detalle").val())) {
		  			$("#matanza_emergencia_detalle").addClass("alert-danger");
		  			error =  true;
		      }
		      if (!$.trim($("#aplazamiento_matanza_detalle").val())) {
		  			$("#aplazamiento_matanza_detalle").addClass("alert-danger");
		  			error =  true;
		      }
		    //*****observacion***
		    if (!$.trim($("#observacion_detalle").val())) {
		  			$("#observacion_detalle").addClass("alert-danger");
		  			error =  true;
		      }
			break;
		case 2: 
			//*****generalidades*****
			  if(!$.trim($("#fecha_formulario_detalle").val())){
	  			   $("#fecha_formulario_detalle").addClass("alert-danger");
	  			   error = true;
	  		  }
	          if(!$.trim($("#especie_detalle").val())){
		  			$("#especie_detalle").addClass("alert-danger");
		  			error =  true;
		  		  }
	          if (!$.trim($("#categoria_etaria_detalle").val())) {
	  			   $("#categoria_etaria_detalle").addClass("alert-danger");
	  			   error = true;
	          }
	          //verificar el tipo de especie Cavia y Cunícola
	          if($("#especie_detalle option:selected").text() == 'Cavia' || $("#especie_detalle option:selected").text() == 'Cunícola' ){
		          //no verifique
	          }else{ 
		          if (!$.trim($("#num_csmi_detalle").val())) {
		        	  $("#num_csmi_detalle").addClass("alert-danger");
			  			error =  true;
			        }
	          }
	          if (!$.trim($("#num_lote_detalle").val())) {
		  			$("#num_lote_detalle").addClass("alert-danger");
		  			error =  true;
		      }
		      if (!$.trim($("#peso_vivo_promedio_detalle").val())) {
		  			$("#peso_vivo_promedio_detalle").addClass("alert-danger");
		  			error =  true;
		      }
		      if(!$.trim($("#num_machos_detalle").val())){
		  			$("#num_machos_detalle").addClass("alert-danger");
		  			error =  true;
		  		  }
		      if(!$.trim($("#num_hembras_detalle").val())){
		  			$("#num_hembras_detalle").addClass("alert-danger");
		  			error =  true;
		  		  }
		      if(!$.trim($("#num_total_animales_detalle").val())){
		  			$("#num_total_animales_detalle").addClass("alert-danger");
		  			error =  true;
		  		  }
		      if (!$.trim($("#hallazgos_detalle").val())) {
		  			$("#hallazgos_detalle").addClass("alert-danger");
		  			error =  true;
		      }
		     
			
			//*****dictamen*****
			if (!$.trim($("#matanza_normal_detalle").val())) {
		  			$("#matanza_normal_detalle").addClass("alert-danger");
		  			error =  true;
		      }
		      if (!$.trim($("#matanza_especiales_detalle").val())) {
		  			$("#matanza_especiales_detalle").addClass("alert-danger");
		  			error =  true;
		      }
		      if (!$.trim($("#matanza_emergencia_detalle").val())) {
		  			$("#matanza_emergencia_detalle").addClass("alert-danger");
		  			error =  true;
		      }
		      if (!$.trim($("#aplazamiento_matanza_detalle").val())) {
		  			$("#aplazamiento_matanza_detalle").addClass("alert-danger");
		  			error =  true;
		      }
		    //*****observacion***
		    if (!$.trim($("#observacion_detalle").val())) {
		  			$("#observacion_detalle").addClass("alert-danger");
		  			error =  true;
		      }
			break;
			
		}
		return error;
	    }

	

    //*************verificar Animales Muertos*********************************
    function verificarAnimalesMuertosDetalle(){

    	falla = true;
	     if ($.trim($("#num_animales_muertos_detalle").val()) != '') {
	  			falla =  false;
	      }
	     if ($.trim($("#causa_probable_detalle").val()) != '') {
	  			falla =  false;
	      }
	     if ($.trim($("#decomiso_detalle").val()) != '') {
	  			falla =  false;
	      }
	     if ($.trim($("#aprovechamiento_detalle").val()) != '') {
	  			falla =  false;
	      }
	      if(falla == false){
		    	  if (!$.trim($("#num_animales_muertos_detalle").val())) {
			  			$("#num_animales_muertos_detalle").addClass("alert-danger");
			  			falla = true;
			      }
			    if (!$.trim($("#causa_probable_detalle").val())) {
			  			$("#causa_probable_detalle").addClass("alert-danger");
			  			falla = true;
			      }
			    if (!$.trim($("#decomiso_detalle").val())) {
			  			$("#decomiso_detalle").addClass("alert-danger");
			  			falla = true;
			      }
			    if (!$.trim($("#aprovechamiento_detalle").val())) {
			  			$("#aprovechamiento_detalle").addClass("alert-danger");
			  			falla = true;
			      }
			      return falla;
	    	  
		      }else{
				  return false;
			      }
        }
  //*************verificar Animales Muertos grupo*********************************
    function verificarAnimalesMuertosGrupoDetalle(){
 			falla = true;
		    if ($.trim($("#num_animales_muertos_detalle").val()) != '') {
		  			falla =  false;
		      }
		    if ($.trim($("#causa_probable_detalle").val()) != '') {
		  			falla =  false;
		      }
		    if ($.trim($("#decomiso_detalle").val()) != '') {
		  			falla =  false;
		      }
		    if ($.trim($("#aprovechamiento_detalle").val()) != '') {
		  			falla =  false;
		      }
		      return falla;
    	
        }
    //*************verificar Signos clinicos visibles*********************************
    function verificarSignosClinicosDetalle(){
			if ($.trim($("#num_animales_nerviosos_detalle").val()) != '') {
				return  false;
			}else if($.trim($("#num_animales_digestivo_detalle").val()) != ''){
				return  false;
			}else if($.trim($("#num_animales_respiratorio_detalle").val()) != ''){
				return  false;
			}else if($.trim($("#num_animales_vesicular_detalle").val()) != ''){
				return  false;
			}else if($.trim($("#num_animales_reproductivo_detalle").val()) != ''){
				return  false;
			}else{
				return  true;
					}
        }
    //*************verificar locomocion*********************************
    function verificarLocomocionDetalle(){
	    	if ($.trim($("#num_animales_cojera_detalle").val()) != '') {
				return  false;
			}else if($.trim($("#num_animales_ambulatorios_detalle").val()) != ''){
				return  false;
			}else{
				return  true;
			}
        }

    //****************bloquear campos********************************
    function bloquearCampos(){
    	 $("#fecha_formulario_detalle").attr("disabled","disabled");
		 $("#especie_detalle").attr("disabled","disabled");
		 $("#categoria_etaria_detalle").attr("disabled","disabled");
		 $("#num_csmi_detalle").attr("disabled","disabled");
		 $("#num_lote_detalle").attr("disabled","disabled");
		 $("#peso_vivo_promedio_detalle").attr("disabled","disabled");
		 $("#num_machos_detalle").attr("disabled","disabled");
		 $("#num_hembras_detalle").attr("disabled","disabled");
		 $("#num_total_animales_detalle").attr("disabled","disabled");
		 $("#hallazgos_detalle").attr("disabled","disabled");
		    //*****animale muertos
         $("#num_animales_muertos_detalle").attr("disabled","disabled");
         $("#causa_probable_detalle").attr("disabled","disabled");
         $("#decomiso_detalle").attr("disabled","disabled");
         $("#aprovechamiento_detalle").attr("disabled","disabled");
         //*****signos clinicos visibles*****
 		 $("#num_animales_nerviosos_detalle").attr("disabled","disabled");
 		 $("#num_animales_digestivo_detalle").attr("disabled","disabled");
 		 $("#num_animales_respiratorio_detalle").attr("disabled","disabled");
 		 $("#num_animales_vesicular_detalle").attr("disabled","disabled");
 		 $("#num_animales_reproductivo_detalle").attr("disabled","disabled");
 		//*****locomocion*****
 		 $("#num_animales_cojera_detalle").attr("disabled","disabled");
 		 $("#num_animales_ambulatorios_detalle").attr("disabled","disabled");
 		//*****dictamen*****
 		 $("#matanza_normal_detalle").attr("disabled","disabled");
 		 $("#matanza_especiales_detalle").attr("disabled","disabled");
 		 $("#matanza_emergencia_detalle").attr("disabled","disabled");
 		 $("#aplazamiento_matanza_detalle").attr("disabled","disabled");
         //********observacion*****
         $("#observacion_detalle").attr("disabled","disabled");
        }

</script>

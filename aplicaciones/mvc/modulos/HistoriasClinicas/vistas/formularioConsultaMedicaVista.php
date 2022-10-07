<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
	<link rel='stylesheet'
	href='<?php echo URL_MVC_MODULO ?>HistoriasClinicas/vistas/estilos/estiloModal.css'>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>HistoriasClinicas' data-opcion='consultaMedica/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">

<div class="pestania">
<fieldset id="contenedorBusqueda">
		<legend>Nueva Consulta Médica</legend>	
		<div data-linea="1">
			<label for="identificador">Documento de identificación:</label>
			<input type="text" id="identificador" name="identificador" value=""
			placeholder="Identificador" maxlength="16" />
		</div>	
				
		<div data-linea="1">
			<button type="button" class="buscar">Buscar</button>
		</div>
</fieldset>
	<fieldset id="divFuncionario">
		<?php echo $this->divInformacion;?>
	</fieldset>
	
	<fieldset id="divCargo">
		<?php echo $this->divCargo;?>
	</fieldset >
	
	<fieldset id="discapacidad">
		<?php echo $this->divDiscapacidad;?>
	</fieldset >
		<fieldset id="ausentismo">
		<?php echo $this->divAusent;?>	
	</fieldset >
	<fieldset>
		<legend>Antecedentes de Salud (Personales) Agregados</legend>	
		<div id="listaAntecedentesSalud" style="width:100%"><?php echo $this->antecedentesSalud;?></div>
	</fieldset>
	<div data-linea="5" id="opcionCrearConculta">
			<button type="button" class="guardar" id="crearConsultaMedica">Crear consulta médica</button>
		</div>
	</div>
	<div class="pestania">
<fieldset>
		<legend>Examen físico</legend>				

		<div data-linea="1">
			<label for="tension_arterial">Tensión arterial (mm Hg):</label>
			<input type="text" id="tension_arterial" name="tension_arterial" value="<?php echo $this->modeloExamenFisico->getTensionArterial();?>"
			placeholder="Tension arterial"  maxlength="9" />
		</div>				

		<div data-linea="1">
			<label for="saturacion_oxigeno">Saturación de Oxígeno: </label>
			<input type="text" id="saturacion_oxigeno" name="saturacion_oxigeno" value="<?php echo $this->modeloExamenFisico->getSaturacionOxigeno();?>"
			placeholder="Saturación de oxígeno"  maxlength="6" />
		</div>				

		<div data-linea="2">
			<label for="frecuencia_cardiaca">Frecuencia cardiaca (x min):</label>
			<input type="text" id="frecuencia_cardiaca" name="frecuencia_cardiaca" value="<?php echo $this->modeloExamenFisico->getFrecuenciaCardiaca();?>"
			placeholder="Frecuencia cardiaca"  maxlength="6" />
		</div>				

		<div data-linea="2">
			<label for="frecuencia_respiratoria">Frecuencia respiratoria (x min): </label>
			<input type="text" id="frecuencia_respiratoria" name="frecuencia_respiratoria" value="<?php echo $this->modeloExamenFisico->getFrecuenciaRespiratoria();?>"
			placeholder="Frecuencia respiratoria"  maxlength="6" />
		</div>				

		<div data-linea="3">
			<label for="talla_mts">Talla (mts): </label>
			<input type="text" id="talla_mts" name="talla_mts" value="<?php echo $this->modeloExamenFisico->getTallaMts();?>"
			placeholder="Talla en mts"  maxlength="6" />
		</div>				

		<div data-linea="3">
			<label for="temperatura_c">Temperatura (°C):</label>
			<input type="text" id="temperatura_c" name="temperatura_c" value="<?php echo $this->modeloExamenFisico->getTemperaturaC();?>"
			placeholder="Temperatura"  maxlength="6" />
		</div>				

		<div data-linea="3">
			<label for="peso_kg">Peso (Kg):</label>
			<input type="text" id="peso_kg" name="peso_kg" value="<?php echo $this->modeloExamenFisico->getPesoKg();?>"
			placeholder="Peso"  maxlength="6" />
		</div>				

		<div data-linea="4">
			<label for="imc">Índice de masa corporal IMC (Peso (kg) / Talla (m2)):</label>
			<input type="text" id="imc" name="imc" value="<?php echo $this->modeloExamenFisico->getImc();?>"
			placeholder="Imc"  maxlength="6" readonly/>
		</div>				

		<div data-linea="5">
			<label for="interpretacion_imc">Interpretación IMC:</label>
			<input type="text" id="interpretacion_imc" name="interpretacion_imc" value="<?php echo $this->modeloExamenFisico->getInterpretacionImc();?>"
			placeholder="Interpretación IMC"  maxlength="16" readonly/>
		</div>				

	</fieldset >
	<fieldset>
		<legend>Motivo de la consulta</legend>				
		<div data-linea="1">
			<label for="fecha">Fecha consulta médica:</label>
			<span><?php echo $this->fechaConsulta;?></span>
		</div>				

		<div data-linea="2">
			<label for="sintomas">Síntomas: </label>
			<input type="text" id="sintomas" name="sintomas" value="<?php echo $this->modeloConsultaMedica->getSintomas();?>"
			placeholder="síntomas"  maxlength="512" />
		</div>				
	</fieldset >
	<fieldset>
		<legend>Diagnóstico</legend>				

		<div data-linea="1">
			<label for="enfermedad_general_diagnosticada">Enfermedad General: </label>
			<select id="enfermedad_general_diagnosticada" name= "enfermedad_general_diagnosticada">
				<?php echo $this->comboCie10('descripcion');?>
			</select>
		</div>				

		<div data-linea="1">
			<label for="id_cie_diagnosticada">Código CIE 10:</label>
			<select id="id_cie_diagnosticada" name= "id_cie_diagnosticada">
				<?php echo $this->comboCie10('codigo');?>
			</select>
		</div>			

		<div data-linea="2">
			<label for="diagnostico">Diagnóstico:</label>
			<input type="text" id="diagnostico" name="diagnostico" value=""
			placeholder="diagnóstico"  maxlength="128" />
		</div>				
		<div data-linea="3">
			<label for="estado_diagnostico">Estado: </label>
		</div>				
		<div data-linea="3">
			<input type="radio" name="estado_diagnostico[]" value="Presuntivo" />
			  <label for="estado_diagnostico">Presuntivo </label>
		</div>				

		<div data-linea="3">
			<input type="radio"  name="estado_diagnostico[]" value="Comprobado" />
			<label for="estado_diagnostico">Comprobado</label>
		</div>				

		<div data-linea="4">
			<label for="observaciones_diagnostico">Observaciones:</label>
			<input type="text" id="observaciones_diagnostico" name="observaciones_diagnostico" value=""
			placeholder="Observaciones" maxlength="256" />
		</div>				
        <div data-linea="5">
			<button type="button" class="mas" id="agregarDiagnostico">Agregar</button>
		</div>
	</fieldset >
	<fieldset>
		<legend>Diagnósticos agregados</legend>	
		<div id="listaDiagnostico" style="width:100%"><?php echo $this->listarDiagnostico($this->idConsultaMedica);?></div>
	</fieldset>
	<fieldset>
		<legend>Adjuntar exámenes </legend>				

		<div data-linea="1">
			<label for="descripcion_adjunto">Descripción de adjunto:</label>
			<input type="text" id="descripcion_adjunto" name="descripcion_adjunto" value=""
			placeholder="Descripción de archivo adjunto" maxlength="512" />
		</div>	
		
		<div data-linea="2" id="documentoAdjunto">				
				<input type="hidden" class="documento_adjunto" name="documento_adjunto" value="0"/>
				<input type="file" class="archivo" accept="application/pdf"/>
				<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
				<button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo URL_MVC_MODULO ?>HistoriasClinicas/archivos/adjuntosConsultaMedica" >Subir archivo</button>		
			</div>
	
	</fieldset >
	<fieldset>
		<legend>Exámenes adjuntos</legend>	
		<div id="listaAdjuntosConsulta" style="width:100%"><?php echo $this->listarAdjuntosConsulta($this->idConsultaMedica);?></div>
	</fieldset>
	</div>
	<div class="pestania">
	<fieldset>
		<legend>Resultados de la valoración médica</legend>				

		<div data-linea="1">
			<label for="medicacion">Necesita medicación:</label>
			<select id="medicacion" name= "medicacion">
				<?php echo $this->comboOpcion();?>
			</select>	
		</div>		
    </fieldset>		
    <fieldset id="valoracionMedica">
		<legend>Valoración médica</legend>				
    
		<div data-linea="2">
			<label for="medicamento">Medicamento:</label>
			<input type="text" id="medicamento" name="medicamento" value=""
			placeholder="Medicamento" maxlength="256" />
		</div>				

		<div data-linea="3">
			<label for="forma_farmaceutica">Forma farmacéutica:</label>
			<input type="text" id="forma_farmaceutica" name="forma_farmaceutica" value=""
			placeholder="Forma farmacéutica"  maxlength="64" />
		</div>				

		<div data-linea="3">
			<label for="concentracion">Concentración: </label>
			<input type="text" id="concentracion" name="concentracion" value=""
			placeholder="Concentración"  maxlength="64" />
		</div>		
		<div data-linea="4">
			<label for="id">Indicaciones: </label>
		</div>	
		<div data-linea="5">
		        <textarea id="indicaciones" name="indicaciones" maxlength="1024" placeholder="Indicaciones" rows="6"></textarea>
        	</div>	
		<div data-linea="8">
			<button type="button" class="mas" id="agregarValoracionMedicamentos">Agregar</button>
		</div>			
	</fieldset >
	<fieldset id="medicamentosAgregados">
		<legend>Medicamentos agregados</legend>	
		<div id="listaValoracionMedi" style="width:100%"><?php echo $this->listarValoracionMedica($this->idConsultaMedica);?></div>		
	</fieldset>
	<div data-linea="8" id="activarReceta">
		<div id="crearRecetaMedicaCarga"></div>
			<button type="button" class="" id="crearRecetaMedica">Generar Receta Médica</button>
		</div>
	</div>
	<div class="pestania">
	<fieldset>
		<legend>Resultados de la valoración médica</legend>				

		<div data-linea="1">
			<label for="reposo_medico">Necesita reposo médico:</label>
			<select id="reposo_medico" name= "reposo_medico">
				<?php echo $this->comboOpcion();?>
			</select>	
		</div>	
	</fieldset>			
    <fieldset id="valoracionMedicaReposo">
		<legend>Valoración médica</legend>	
		<div data-linea="2">
			<label for="dias_reposo">Días de reposo:</label>
			<select id="dias_reposo" name= "dias_reposo">
				<?php echo $this->comboNumeros(15,1,$this->modeloConsultaMedica->getDiasReposo());?>
			</select>
		</div>				

		<div data-linea="3">
			<label for="fecha_desde">Fecha desde:</label>
			<input type="text" id="fecha_desde" name="fecha_desde" value="<?php echo $this->fechaConsulta;?>"
			placeholder="Fecha desde" disabled readonly />
		</div>				

		<div data-linea="3">
			<label for="fecha_hasta">Fecha hasta: </label>
			<input type="text" id="fecha_hasta" name="fecha_hasta" value="<?php echo $this->modeloConsultaMedica->getFechaHasta()?>"
			placeholder="Fecha hasta" disabled readonly/>
		</div>		
		<div data-linea="4">
			<label for="observaciones_consulta">Observaciones: </label>
		</div>	
		<div data-linea="5">
		        <textarea id="observaciones_consulta" name="observaciones_consulta" maxlength="1024" placeholder="Observaciones" rows="6"><?php echo $this->modeloConsultaMedica->getObservaciones()?></textarea>
        	</div>	
        <div data-linea="6" id="certificadoConsulta">
		<div id="generarCertificadoCarga"></div>
			<button type="button" class="" id="generarCertificado">Generar certificado médico</button>
		</div>
	</fieldset >
	<fieldset id="recetaCertificado">
		<legend>Archivos generados</legend>	
		<div id="listaRecetaCertificado" style="width:100%"><?php echo $this->listarRecetaCertificado($this->idConsultaMedica);?></div>
	</fieldset >
	      <div data-linea="5" id="finalizarConsulta">
			<button type="button" class="guardar" id="finalizarConsultaMedica">Finalizar consulta médica</button>
		</div>
		
	</div>
        
</form >
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

				<div id="divDetalle">
				
				</div>

			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			<div id="estadoDetalle"></div>

		</div>
	</div>
</div>
<div id="imprimirReceta"></div>
<script type ="text/javascript">
var id_consulta_medica=<?php echo json_encode($this->idConsultaMedica);?>;
var id_historia_clinica=<?php echo json_encode($this->idHistorialClinica);?>;
var estadoConsultaMedica=<?php echo json_encode($this->estadoConsultaMedica);?>;
var identificadorPaciente=null;
var descripcion_concepto=<?php echo json_encode('');?>;
var ausentismo =  <?php echo json_encode('');?>;
var estado = <?php echo json_encode('');?>;
var reubicacion_laboral = <?php echo json_encode(''); ?>;
	$(document).ready(function() {
		$("#tiempo").numeric();
		mostrarMensaje("", "FALLO");
		construirValidador();
		distribuirLineas();
		$("#modalDetalle").hide();
		construirAnimacion($(".pestania"));
		$("#valoracionMedica").hide();
		$("#medicamentosAgregados").hide();
		$("#activarReceta").hide();
		$("#imprimirReceta").hide();
		$("#valoracionMedicaReposo").hide();
		$("#certificadoConsulta").hide();
		$("#finalizarConsulta").hide();
		$("#saturacion_oxigeno").numeric();
		$("#frecuencia_cardiaca").numeric();
		$("#frecuencia_respiratoria").numeric();
		$("#talla_mts").numeric();
		$("#temperatura_c").numeric();
		$("#peso_kg").numeric();
		if(estadoConsultaMedica == 'Activo'){
			$("#contenedorBusqueda").hide();
			$("#opcionCrearConculta").hide();
			}
		
	 });

	$("#finalizarConsultaMedica").click(function (event) {
		event.preventDefault();
		var texto = "Por favor revise los campos obligatorios.";
		$(".alertaCombo").removeClass("alertaCombo");
		mostrarMensaje("", "FALLO");
		var error = false;
	
		if(!$.trim($("#sintomas").val())){
			   $("#sintomas").addClass("alertaCombo");
			   texto="Por favor revise los campos obligatorios en motivo de la consulta";
			   error = true;
		  }
		if(!$.trim($("#reposo_medico").val())){
			   $("#reposo_medico").addClass("alertaCombo");
			   texto="Por favor revise los campos obligatorios en recomendaciones";
			   error = true;
		}

		if($("#reposo_medico").val() == 'Si'){
    		if(!$.trim($("#dias_reposo").val())){
    			   $("#dias_reposo").addClass("alertaCombo");
    			   error = true;
    		  }
    		if(!$.trim($("#fecha_desde").val())){
    			   $("#fecha_desde").addClass("alertaCombo");
    			   error = true;
    		  }
    		if(!$.trim($("#fecha_hasta").val())){
    			   $("#fecha_hasta").addClass("alertaCombo");
    			   error = true;
    		  }
		}
		if(!$.trim($("#tension_arterial").val())){
			   $("#tension_arterial").addClass("alertaCombo");
			   texto="Por favor revise los campos obligatorios en examen físico";
			   error = true;
		}
		 if(!esCampoValidoExp("#tension_arterial",4)){
			 $("#tension_arterial").addClass("alertaCombo");
			   texto="Por favor solo números y / en examen físico";
			   error = true;
		 }
		if(!$.trim($("#saturacion_oxigeno").val())){
			   $("#saturacion_oxigeno").addClass("alertaCombo");
			   texto="Por favor revise los campos obligatorios en examen físico";
			   error = true;
		}
		if(!$.trim($("#frecuencia_cardiaca").val())){
			   $("#frecuencia_cardiaca").addClass("alertaCombo");
			   texto="Por favor revise los campos obligatorios en examen físico";
			   error = true;
		}
		if(!$.trim($("#frecuencia_respiratoria").val())){
			   $("#frecuencia_respiratoria").addClass("alertaCombo");
			   texto="Por favor revise los campos obligatorios en examen físico";
			   error = true;
		}
		if(!$.trim($("#talla_mts").val())){
			   $("#talla_mts").addClass("alertaCombo");
			   texto="Por favor revise los campos obligatorios en examen físico";
			   error = true;
		}
		if(!$.trim($("#temperatura_c").val())){
			   $("#temperatura_c").addClass("alertaCombo");
			   texto="Por favor revise los campos obligatorios en examen físico";
			   error = true;
		}
		if(!$.trim($("#peso_kg").val())){
			   $("#peso_kg").addClass("alertaCombo");
			   texto="Por favor revise los campos obligatorios en examen físico";
			   error = true;
		}
		if(!$.trim($("#imc").val())){
			   $("#imc").addClass("alertaCombo");
			   texto="Por favor revise los campos obligatorios en examen físico";
			   error = true;
		}
		if(!$.trim($("#interpretacion_imc").val())){
			   $("#interpretacion_imc").addClass("alertaCombo");
			   texto="Por favor revise los campos obligatorios en examen físico";
			   error = true;
		}
		//***********************************************************
		if (!error) {
			$.post("<?php echo URL ?>HistoriasClinicas/consultaMedica/guardarConsultaMedica", 
                {
				    id_consulta_medica: id_consulta_medica,
				    sintomas:$("#sintomas").val(),
				    dias_reposo:$("#dias_reposo").val(),
				    medicacion:$("#medicacion").val(),
				    reposo_medico:$("#reposo_medico").val(),
				    fecha_desde:$("#fecha_desde").val(),
				    fecha_hasta:$("#fecha_hasta").val(),
				    observaciones:$("#observaciones_consulta").val(),
				    tension_arterial:$("#tension_arterial").val(),
				    saturacion_oxigeno:$("#saturacion_oxigeno").val(),
				    frecuencia_cardiaca:$("#frecuencia_cardiaca").val(),
				    frecuencia_respiratoria:$("#frecuencia_respiratoria").val(),
				    talla_mts:$("#talla_mts").val(),
				    temperatura_c:$("#temperatura_c").val(),
				    peso_kg:$("#peso_kg").val(),
				    imc:$("#imc").val(),
				    interpretacion_imc:$("#interpretacion_imc").val(),
                }, function (data) {
                	if (data.estado === 'EXITO') {
	                   	 mostrarMensaje(data.mensaje, data.estado);
	                     abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
                    } else {
                    	mostrarMensaje(data.mensaje, "FALLO");
                    }
        }, 'json');
			
		} else {
			$("#estado").html(texto).addClass("alerta");
		}
	});

	//Función que agrega información del funcionario
      $(".buscar").click(function(){
    	$(".alertaCombo").removeClass("alertaCombo");
    	if($('#identificador').val()){
    	mostrarMensaje("", "FALLO");
    	$.post("<?php echo URL ?>HistoriasClinicas/consultaMedica/buscarFuncionario", 
                {
            		identificador: $('#identificador').val()
                }, function (data) {
                	if (data.estado === 'EXITO') {
                		 $("#divFuncionario").html(data.paciente);
	                   	 $("#divCargo").html(data.puesto);
	                   	 $("#discapacidad").html(data.discapacidad);
	                   	 $("#listaAntecedentesSalud").html(data.antecede);
	                   	 $("#ausentismo").html(data.ausentismo);
	                   	 id_historia_clinica = data.idHistoria;
	                   	 identificadorPaciente = $('#identificador').val();
	                   	 mostrarMensaje(data.mensaje, data.estado);
	                     distribuirLineas();
                    } else {
                    	mostrarMensaje(data.mensaje, "FALLO");
                        $("#divFuncionario").html(data.paciente);
                        $("#divCargo").html(data.puesto);
                        $("#discapacidad").html(data.discapacidad);
                        $("#listaAntecedentesSalud").html('');
	                   	$("#ausentismo").html(data.ausentismo);
	                   	id_historia_clinica = null;
	                   	identificadorPaciente = null;
                        distribuirLineas();
                    }
        }, 'json');
        
    	}else{
    		mostrarMensaje("El campo esta vacio !!", "FALLO");
    		$('#identificador').addClass("alertaCombo");
    	}
    });

      //************verificar valoracion médica****************
    $("#medicacion").change(function () {
    	if($("#medicacion").val() == 'Si'){
    		$("#valoracionMedica").show();
    		$("#medicamentosAgregados").show();
    	}else{
    		$("#valoracionMedica").hide();
    		$("#medicamentosAgregados").hide();
        	}
	});
	//crear consulta médica
	$("#crearConsultaMedica").click(function (){
		if($("#identificador_paciente").val() != ''){
			 $.post("<?php echo URL ?>HistoriasClinicas/consultaMedica/crearConsultaMedica", 
                  {
              		identificador_paciente: identificadorPaciente,
              		id_historia_clinica: id_historia_clinica
                  }, function (data) {
                  	if (data.estado === 'EXITO') {
                  		id_consulta_medica = data.contenido;
                		$(".buscar").attr('disabled','disabled');
                		$("#crearConsultaMedica").attr('disabled','disabled');
                		mostrarMensaje(data.mensaje, data.estado);
                      	distribuirLineas();
                      } else {
                    	 mostrarMensaje(data.mensaje, "FALLO");
                         $("#subtipos").html(data.contenido);
                         distribuirLineas();
                      }
          }, 'json');
		}else{
			mostrarMensaje("Debe seleccionar un funcionario !!", "FALLO");
			}
		});


	 //*****************************agregar valoraciones********************* 
	 $("#agregarValoracionMedicamentos").click(function (){
		 event.preventDefault();
			var texto = "Por favor revise los campos obligatorios.";
			$(".alertaCombo").removeClass("alertaCombo");
			mostrarMensaje("", "FALLO");
			var error = false;
			if(!$.trim($("#medicacion").val())){
				   $("#medicacion").addClass("alertaCombo");
				   error = true;
			  }
			if(!$.trim($("#medicamento").val())){
				   $("#medicamento").addClass("alertaCombo");
				   error = true;
			  }
			if(!$.trim($("#forma_farmaceutica").val())){
				   $("#forma_farmaceutica").addClass("alertaCombo");
				   error = true;
			  }
			if(!$.trim($("#concentracion").val())){
				   $("#concentracion").addClass("alertaCombo");
				   error = true;
			  }
			if (!error) {
			 $.post("<?php echo URL ?>HistoriasClinicas/consultaMedica/agregarValoracionMedicamentos", 
                  {
				    id_consulta_medica: id_consulta_medica,
				    medicacion:$("#medicacion").val(),
				    medicamento:$("#medicamento").val(),
				    forma_farmaceutica:$("#forma_farmaceutica").val(),
				    concentracion:$("#concentracion").val(),
				    indicaciones:$("#indicaciones").val()
                  }, function (data) {
                  	if (data.estado === 'EXITO') {
                  		$("#listaValoracionMedi").html(data.contenido);
                  		$("#medicacion").attr('disabled','disabled');
                		$("#activarReceta").show();
                		$("#medicamento").val('');
                		$("#forma_farmaceutica").val('');
                		$("#concentracion").val('');
                		$("#indicaciones").val('')
                		mostrarMensaje(data.mensaje, data.estado);
                      	distribuirLineas();
                      } else {
                    	 mostrarMensaje(data.mensaje, "FALLO");
                         distribuirLineas();
                      }
          }, 'json');
		}else{
			mostrarMensaje(texto, "FALLO");
			}
		});
	 //****************************************************************
	  // eliminar valoración
        function eliminarValoracion(id){
            $.post("<?php echo URL ?>HistoriasClinicas/consultaMedica/eliminarValoracion", 
                    {
            	       id_consulta_medica: id_consulta_medica,
            	       id_valoracion_consulta_medica: id
        	  		         		  		     
                    }, function (data) {
                    	if (data.estado === 'EXITO') {
                    		    $("#listaValoracionMedi").html(data.contenido);
                    		    if(data.campo){
                    		    	$("#activarReceta").hide();
                    		    	$("#medicacion").removeAttr('disabled');
                        		}
        	                    mostrarMensaje(data.mensaje, data.estado);
        	                    distribuirLineas();
                        } else {
                        	mostrarMensaje(data.mensaje, "FALLO");
                        }
            }, 'json');
        
         }
		
     //**********cie10*********************************************************
     $("#enfermedad_general").change(function () {
	     $("#id_cie").val($(this).val());
     });
     $("#id_cie").change(function () {
    	 $("#enfermedad_general").val($(this).val());
     });
   
   // previsualizar información antecedentes de salud agregados  
   function informacionAntecedentesSalud(id){
       $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/informacionAntecedentesSalud", 
               {
                  id_historia_clinica: id_historia_clinica,
                  id_antecedentes_salud: id
	  		         		  		     
               }, function (data) {
               		    $('#modalDetalle').modal('show');
                        $("#divDetalle").html(data);
       });

    }

 
//*************************examen físico****************************************
     $("#talla_mts").change(function () {
         if($(this).val() != 0 && $(this).val() !='' ){
				if($("#peso_kg").val() != '' ){
						var imc = $("#peso_kg").val() / ($(this).val()*$(this).val());
						if(imc != 0){
							$("#imc").val(imc.toFixed(2));
							$("#interpretacion_imc").val(resultadoImc(imc.toFixed(2)));
							}else{
								$("#imc").val('');
								$("#interpretacion_imc").val('');
								}
					}else{
						$("#imc").val('');
						$("#interpretacion_imc").val('');
						}
             }else{
            	 $("#imc").val('');
            	 $("#interpretacion_imc").val('');
                 }
     });
     $("#peso_kg").change(function () {
    	 if($(this).val() != 0 && $(this).val() !='' ){
				if($("#talla_mts").val() != ''  && $("#talla_mts").val() != 0){
						var imc = $("#peso_kg").val() / ($("#talla_mts").val()*$("#talla_mts").val());
						if(imc != 0){
							$("#imc").val(imc.toFixed(2));
							$("#interpretacion_imc").val(resultadoImc(imc.toFixed(2)));
							}else{
								$("#imc").val('');
								$("#interpretacion_imc").val('');
								}
					}else{
						$("#imc").val('');
						$("#interpretacion_imc").val('');
						}
          }else{
        	  $("#imc").val('');
        	  $("#interpretacion_imc").val('');
              }
     });
     function resultadoImc(imc){
    	 if(imc >= 18.5 && imc <= 24.9){
				return 'Normal';
				}
    	 if(imc >= 25 && imc <= 29.9){
				return 'Sobrepeso';
				}
    	 if(imc >= 30 && imc <= 34.9){
				return 'Obeso';
				}
    	 if(imc >= 35 && imc <= 39.9){
				return 'Obeso severo';
				}
    	 if(imc >= 40){
				return 'Obeso morvido';
				}
         }
//****************************subir documentos adjuntos de consulta medica***********
   $('button.subirArchivo').click(function (event) {
	   var texto = "Por favor revise los campos obligatorios.";
		$(".alertaCombo").removeClass("alertaCombo");
		mostrarMensaje("", "FALLO");
		var error = false;
	   if(!$.trim($("#descripcion_adjunto").val())){
			   $("#descripcion_adjunto").addClass("alertaCombo");
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
	   	   var url = "<?php echo URL ?>HistoriasClinicas/consultaMedica/agregarDocumentosAdjuntos";
	   	   var get = "?id_historia_clinica="+id_historia_clinica+"&id_consulta_medica="+id_consulta_medica+"&descripcion_adjunto="+$("#descripcion_adjunto").val();
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
								$("#listaAdjuntosConsulta").html(obj.contenido);
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
           
           archivo.removeClass("amarillo rojo");
          // boton.attr("disabled", "disabled");
           estado.html("El archivo ha sido cargado.");
          // archivo.addClass("verde");
           estado.html("En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)");
       };

       this.error = function (msg) {
           estado.html(msg);
           archivo.removeClass("amarillo verde");
           archivo.addClass("rojo");
       };
   }
   // eliminar adjunto
   function eliminarAdjunto(id){
       $.post("<?php echo URL ?>HistoriasClinicas/consultaMedica/eliminarAdjunto", 
               {
       	          id_consulta_medica: id_consulta_medica,
       	          id_adjuntos_consulta_medica: id
   	  		         		  		     
               }, function (data) {
               	if (data.estado === 'EXITO') {
               		    $("#listaAdjuntosConsulta").html(data.contenido);
   	                    mostrarMensaje(data.mensaje, data.estado);
   	                    distribuirLineas();
                   } else {
                   	mostrarMensaje(data.mensaje, "FALLO");
                   }
       }, 'json');
   
    } 

   //**************************diagnosticada****************************
    $("#enfermedad_general_diagnosticada").change(function () {
	     $("#id_cie_diagnosticada").val($(this).val());
     });
     $("#id_cie_diagnosticada").change(function () {
    	 $("#enfermedad_general_diagnosticada").val($(this).val());
     });

     $("#agregarDiagnostico").click(function () {
 		    event.stopImmediatePropagation();
			var texto = "Por favor revise los campos obligatorios.";
			$(".alertaCombo").removeClass("alertaCombo");
			mostrarMensaje("", "FALLO");
			var error = false;
			if(!$.trim($("#id_cie_diagnosticada").val())){
	  			   $("#id_cie_diagnosticada").addClass("alertaCombo");
	  			   error = true;
	  		  }
			if(!$.trim($("#enfermedad_general_diagnosticada").val())){
	  			   $("#enfermedad_general_diagnosticada").addClass("alertaCombo");
	  			   error = true;
	  		  }
			if(!$("input[name='estado_diagnostico[]']").is(':checked') ){
	  			 texto = "Debe seleccionar un estado !!.";
	  			$("input[name='estado_diagnostico[]']").addClass("alertaCombo");
	  			 error = true;
		  		}
			if(!$.trim($("#diagnostico").val())){
	  			   $("#diagnostico").addClass("alertaCombo");
	  			   error = true;
	  		  }
	  		
			if (!error) {
			  	  $.post("<?php echo URL ?>HistoriasClinicas/consultaMedica/agregarDiagnostico", 
		                  {
			  		         id_consulta_medica:id_consulta_medica,
			  		         id_cie:$("#id_cie_diagnosticada").val(),
			  		         observaciones:$("#observaciones_diagnostico").val(),
			  		         estado_diagnostico:$("input[name='estado_diagnostico[]']").map(function(){ if($(this).prop("checked")){return $(this).val();}}).get()
			  		         
		                  }, function (data) {
		                  	if (data.estado === 'EXITO') {
		                  		$("#listaDiagnostico").html(data.contenido);
		           		        $("#id_cie_diagnosticada").val('');
		           		        $("#enfermedad_general_diagnosticada").val('');
		           		        $("#diagnostico").val('');
		           		     	$("#observaciones_diagnostico").val('');
		           		  		$("input[name='estado_diagnostico[]']").prop("checked", false);
			                    mostrarMensaje(data.mensaje, data.estado);
			                    distribuirLineas();
		                      } else {
		                      	mostrarMensaje(data.mensaje, "FALLO");
		                      }
		          }, 'json');
			} else {
				mostrarMensaje(texto, "FALLO");
			}
         });
         // eliminar diagnostico
        function eliminarDiagnostico(id){
            $.post("<?php echo URL ?>HistoriasClinicas/consultaMedica/eliminarDiagnostico", 
                    {
            	       id_consulta_medica: id_consulta_medica,
                       id_impresion_diagnostica: id
        	  		         		  		     
                    }, function (data) {
                    	if (data.estado === 'EXITO') {
                    		    $("#listaDiagnostico").html(data.contenido);
        	                    mostrarMensaje(data.mensaje, data.estado);
        	                    distribuirLineas();
                        } else {
                        	mostrarMensaje(data.mensaje, "FALLO");
                        }
            }, 'json');
        
         } 

        //***************************************************
        $("#crearRecetaMedica").click(function () {
        	$("#crearRecetaMedicaCarga").html("<div id='cargando'>Cargando...</div>").fadeIn();
        	$.post("<?php echo URL ?>HistoriasClinicas/consultaMedica/crearRecetaMedica", 
                    {
            	       id_consulta_medica: id_consulta_medica,
            	       id_historia_clinica: id_historia_clinica
        	  		         		  		     
                    }, function (data) {
                    	
                    	if (data.estado === 'EXITO') {
                        		$("#listaRecetaCertificado").html(data.archivo);
                    		    $("#crearRecetaMedicaCarga").html('');
                    		    $("#imprimirReceta").html('<a id="imprimirUrl" href="'+data.contenido+'" target="_blank"></a>');
                    		    $("#imprimirUrl").get(0).click();
                    		    
        	                    mostrarMensaje(data.mensaje, data.estado);
        	                    distribuirLineas();
                        } else {
                        	mostrarMensaje(data.mensaje, "FALLO");
                        }
            }, 'json');

        });
        //***************************************************
        $("#generarCertificado").click(function () {
        	var texto = "Por favor revise los campos obligatorios.";
			$(".alertaCombo").removeClass("alertaCombo");
			mostrarMensaje("", "FALLO");
			var error = false;
			if(!$.trim($("#dias_reposo").val())){
				   $("#dias_reposo").addClass("alertaCombo");
				   error = true;
			  }
			if(!$.trim($("#fecha_desde").val())){
				   $("#fecha_desde").addClass("alertaCombo");
				   error = true;
			  }
			if(!$.trim($("#fecha_hasta").val())){
				   $("#fecha_hasta").addClass("alertaCombo");
				   error = true;
			  }
			if (!error) {
				$("#generarCertificadoCarga").html("<div id='cargando'>Cargando...</div>").fadeIn();
            	$.post("<?php echo URL ?>HistoriasClinicas/consultaMedica/generarCertificado", 
                        {
                	       id_consulta_medica: id_consulta_medica,
                	       id_historia_clinica: id_historia_clinica,
                	       dias_reposo:$("#dias_reposo").val(),
                	       fecha_desde:$("#fecha_desde").val(),
                	       fecha_hasta:$("#fecha_hasta").val()
            	  		         		  		     
                        }, function (data) {
                        	$("#generarCertificadoCarga").html('');
                        	if (data.estado === 'EXITO') {
                        		    $("#listaRecetaCertificado").html(data.archivo);
                        		    $("#imprimirReceta").html('<a id="imprimirUrl" href="'+data.contenido+'" target="_blank"></a>');
                        		    $("#imprimirUrl").get(0).click();
            	                    mostrarMensaje(data.mensaje, data.estado);
            	                    distribuirLineas();
                                } else {
                                	mostrarMensaje(data.mensaje, "FALLO");
                                }
                    }, 'json');
			} else {
            	mostrarMensaje(texto, "FALLO");
            }
        });
//**********************verificar si existe reposo**********************
        $("#reposo_medico").change(function () {
        	if($(this).val() == 'Si'){
        		$("#valoracionMedicaReposo").show();
        		$("#medicamentosAgregados").show();
        		$("#certificadoConsulta").show();
        		$("#finalizarConsulta").show();
        	}else{
        		$("#valoracionMedicaReposo").hide();
        		$("#medicamentosAgregados").hide();
        		$("#certificadoConsulta").hide();
        		$("#finalizarConsulta").show();
            	}
    	}); 

     $("#dias_reposo").change(function () {
         if($.trim($("#fecha_desde").val())){
             	$.post("<?php echo URL ?>HistoriasClinicas/consultaMedica/sumarFecha", 
                         {
                 	       dias:$(this).val(),
                 	       fecha_desde:$("#fecha_desde").val()
                         }, function (data) {
                         	if (data.estado === 'EXITO') {
                         		    $("#fecha_hasta").val(data.contenido);
                             } else {
                             	mostrarMensaje(data.mensaje, "FALLO");
                             }
                	 	}, 'json');
             }else{
            	 mostrarMensaje("No existe una fecha incial..!!", "FALLO");
             }
    	
    	 });
</script>
<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
	<link rel='stylesheet'
	href='<?php echo URL_MVC_MODULO ?>HistoriasClinicas/vistas/estilos/estiloModal.css'>

<div class="pestania">

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
</div>	
	<div class="pestania">
<fieldset>
		<legend>Examen físico</legend>				

		<div data-linea="1">
			<label for="tension_arterial">Tensión arterial (mm Hg):</label>
			<input type="text" id="tension_arterial" name="tension_arterial" disabled value="<?php echo $this->modeloExamenFisico->getTensionArterial();?>"
			placeholder="Tension arterial"  maxlength="6" />
		</div>				

		<div data-linea="1">
			<label for="saturacion_oxigeno">Saturación de Oxígeno: </label>
			<input type="text" id="saturacion_oxigeno" name="saturacion_oxigeno" disabled value="<?php echo $this->modeloExamenFisico->getSaturacionOxigeno();?>"
			placeholder="Saturación de oxígeno"  maxlength="6" />
		</div>				

		<div data-linea="2">
			<label for="frecuencia_cardiaca">Frecuencia cardiaca (x min):</label>
			<input type="text" id="frecuencia_cardiaca" name="frecuencia_cardiaca" disabled value="<?php echo $this->modeloExamenFisico->getFrecuenciaCardiaca();?>"
			placeholder="Frecuencia cardiaca"  maxlength="6" />
		</div>				

		<div data-linea="2">
			<label for="frecuencia_respiratoria">Frecuencia respiratoria (x min): </label>
			<input type="text" id="frecuencia_respiratoria" name="frecuencia_respiratoria" disabled value="<?php echo $this->modeloExamenFisico->getFrecuenciaRespiratoria();?>"
			placeholder="Frecuencia respiratoria"  maxlength="6" />
		</div>				

		<div data-linea="3">
			<label for="talla_mts">Talla (mts): </label>
			<input type="text" id="talla_mts" name="talla_mts" disabled value="<?php echo $this->modeloExamenFisico->getTallaMts();?>"
			placeholder="Talla en mts"  maxlength="6" />
		</div>				

		<div data-linea="3">
			<label for="temperatura_c">Temperatura (°C):</label>
			<input type="text" id="temperatura_c" name="temperatura_c" disabled value="<?php echo $this->modeloExamenFisico->getTemperaturaC();?>"
			placeholder="Temperatura"  maxlength="6" />
		</div>				

		<div data-linea="3">
			<label for="peso_kg">Peso (Kg):</label>
			<input type="text" id="peso_kg" name="peso_kg" disabled value="<?php echo $this->modeloExamenFisico->getPesoKg();?>"
			placeholder="Peso"  maxlength="6" />
		</div>				

		<div data-linea="4">
			<label for="imc">Índice de masa corporal IMC (Peso (kg) / Talla (m2)):</label>
			<input type="text" id="imc" name="imc" disabled value="<?php echo $this->modeloExamenFisico->getImc();?>"
			placeholder="Imc"  maxlength="6" readonly/>
		</div>				

		<div data-linea="5">
			<label for="interpretacion_imc">Interpretación IMC:</label>
			<input type="text" id="interpretacion_imc" name="interpretacion_imc" disabled value="<?php echo $this->modeloExamenFisico->getInterpretacionImc();?>"
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
			<input type="text" id="sintomas" name="sintomas" disabled value="<?php echo $this->modeloConsultaMedica->getSintomas();?>"
			placeholder="Síntomas"  maxlength="512" />
		</div>				
	</fieldset >
	
	<fieldset>
		<legend>Diagnósticos agregados</legend>	
		<div id="listaDiagnostico" style="width:100%"><?php echo $this->listarDiagnostico($this->idConsultaMedica,0);?></div>
	</fieldset>
	<fieldset>
		<legend>Exámenes adjuntos</legend>	
		<div id="listaAdjuntosConsulta" style="width:100%"><?php echo $this->listarAdjuntosConsulta($this->idConsultaMedica,0);?></div>
	</fieldset>
	</div>
	<div class="pestania">
	<fieldset id="medicamentosAgregados">
		<legend>Medicamentos agregados</legend>	
		<div id="listaValoracionMedi" style="width:100%"><?php echo $this->listarValoracionMedica($this->idConsultaMedica,0);?></div>		
	</fieldset>
	<fieldset>
		<legend>Resultados de la valoración médica</legend>				

		<div data-linea="1">
			<label for="reposo_medico">Necesita reposo médico:</label>
			<select id="reposo_medico" name= "reposo_medico" disabled>
				<?php echo $this->comboOpcion($this->modeloConsultaMedica->getReposoMedico());?>
			</select>	
		</div>	
	</fieldset>			
    <fieldset id="valoracionMedicaReposo">
		<legend>Valoración médica</legend>	
		<div data-linea="2">
			<label for="dias_reposo">Días de reposo:</label>
			<select id="dias_reposo" name= "dias_reposo" disabled>
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
		        <textarea id="observaciones_consulta" disabled name="observaciones_consulta" maxlength="1024" placeholder="Observaciones" rows="6"><?php echo $this->modeloConsultaMedica->getObservaciones()?></textarea>
        	</div>	
	</fieldset >
    <fieldset id="recetaCertificado">
		<legend>Archivos generados</legend>	
		<div id="listaRecetaCertificado" style="width:100%"><?php echo $this->listarRecetaCertificado($this->idConsultaMedica);?></div>
	</fieldset >
	</div>
        

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
<script type ="text/javascript">
var valoracion = <?php echo json_encode($this->modeloConsultaMedica->getReposoMedico());?>;
	$(document).ready(function() {
		mostrarMensaje("", "FALLO");
		construirValidador();
		distribuirLineas();
		$("#modalDetalle").hide();
		construirAnimacion($(".pestania"));
		if(valoracion=='No'){
			$("#valoracionMedicaReposo").hide();
			}
	 });

	
</script>
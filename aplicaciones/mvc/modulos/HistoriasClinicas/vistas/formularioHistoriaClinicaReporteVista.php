<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
	<link rel='stylesheet'
	href='<?php echo URL_MVC_MODULO ?>HistoriasClinicas/vistas/estilos/estiloModal.css'>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>HistoriasClinicas' data-opcion='historiaclinica/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">

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
	</div>
	<div class="pestania">
	<fieldset id="ausentismo">
	<legend>Ausentismo médico en el último trimestre</legend>				
								 				
		<div data-linea="1">
		    <input  name="ausentismo[]" type="radio" id="Si" disabled value="Si" onclick="verificarAusentismo(id)"><span>Si</span>&nbsp;&nbsp;&nbsp;&nbsp;
			<input  name="ausentismo[]" type="radio" id="No" disabled value="No"onclick="verificarAusentismo(id)"><span>No</span>
		</div>				

		<div data-linea="2">
			<label for="causa">Causa: </label>
			<input type="text" id="causa" name="causa" disabled value="<?php echo  $this->modeloAusentismoMedico->getCausa(); ?>"
			placeholder="Causa de ausentismo" maxlength="128" />
		</div>						 
		<div data-linea="3">
			<label for="tiempo">Tiempo (horas): </label>
			<input type="text" id="tiempo" name="tiempo" disabled value="<?php echo $this->modeloAusentismoMedico->getTiempo(); ?>"
			placeholder="Tiempo de ausentismo" maxlength="3" />
		</div>				
			
	</fieldset >
	<fieldset>
		<legend>Historia Ocupacional Agregada</legend>	
		<div id="listaHistoriaOcupacional" style="width:100%"><?php echo $this->listarHistoriaOcupacional($this->idHistorialClinica,0);?></div>	
	</fieldset>	
	<fieldset>
		<legend>Accidentes laborales agregados</legend>	
		<div id="listaAccidenteLaboral" style="width:100%"><?php echo $this->listarAccidenteLaboral($this->idHistorialClinica,0);?></div>		
	</fieldset>
	<fieldset>
		<legend>Elementos de Protección actual o último</legend>	
		<?php echo $this->listarElementosProteccion($this->idHistorialClinica);?>			
	</fieldset>
	<fieldset>
		<legend>Enfermedad profesional en empresa actual o anterior</legend>				

		<div data-linea="1">
			<label for="tiene_enfermedad">¿Tiene enfermedad profesional?:</label>
			<select id="tiene_enfermedad" name= "tiene_enfermedad" disabled>
				<?php echo $this->comboOpcion($this->modeloEnfermedadProfesional->getTieneEnfermedad());?>
			</select>
		</div>				

		<div data-linea="2">
			<label for="fecha_diagnostico">Fecha de Diagnóstico o Calificación: </label>
			<input type="text" id="fecha_diagnostico" disabled name="fecha_diagnostico" value="<?php echo $this->modeloEnfermedadProfesional->getFechaDiagnostico(); ?>"
			placeholder="Seleccionar fecha"  maxlength="10" readonly/>
		</div>				

		<div data-linea="3">
			<label for="descripcion">Cuál(es):</label>
			<input type="text" id="descripcion" name="descripcion" disabled value="<?php echo $this->modeloEnfermedadProfesional->getDescripcion(); ?>"
			placeholder="Descripción"  maxlength="2000" />
		</div>				
		
	</fieldset >
	</div>
	<div class="pestania">
	<fieldset>
		<legend>Antecedentes de Salud (Familiares) Agregados</legend>
		<div id="listaAntecedentesFamiliares" style="width:100%"><?php echo $this->listarAntecedentesFamiliares($this->idHistorialClinica,0);?></div>
	</fieldset>
	<fieldset>
		<legend>Antecedentes de Salud (Personales) Agregados</legend>	
		<div id="listaAntecedentesSalud" style="width:100%"><?php echo $this->listarAntecedentesSalud($this->idHistorialClinica,0);?></div>
	</fieldset>
	<fieldset>
		<legend>Revisión actual de órganos y sistemas - Agregados</legend>	
		<div id="listaRevisionOrganos" style="width:100%"><?php echo $this->listarRevisionOrganos($this->idHistorialClinica,0);?></div>
	</fieldset>
	<fieldset>
		<legend>Inmunización - Agregadas</legend>				
	    <div id="listaInmunizacion" style="width:100%"><?php echo $this->listarInmunizacion($this->idHistorialClinica,0);?></div>
	</fieldset>
	<fieldset>
		<legend>Hábitos - Agregados</legend>		
		<div id="listaHabitos" style="width:100%"><?php echo $this->listarHabitos($this->idHistorialClinica,0);?></div>
	</fieldset>
	<fieldset>
		<legend>Actividades - Agregadas</legend>
		<div id="listaActividades" style="width:100%"><?php echo $this->listarActividad($this->idHistorialClinica,0);?></div>
	</fieldset>
	<fieldset>
		<legend>Examen físico</legend>				

		<div data-linea="1">
			<label for="tension_arterial">Tensión arterial (mm Hg):</label>
			<input type="text" id="tension_arterial" disabled name="tension_arterial" value="<?php echo $this->modeloExamenFisico->getTensionArterial();?>"
			placeholder="Tension arterial"  maxlength="6" />
		</div>				

		<div data-linea="1">
			<label for="saturacion_oxigeno">Saturación de Oxígeno: </label>
			<input type="text" id="saturacion_oxigeno" disabled name="saturacion_oxigeno" value="<?php echo $this->modeloExamenFisico->getSaturacionOxigeno();?>"
			placeholder="Saturación de oxígeno"  maxlength="6" />
		</div>				

		<div data-linea="2">
			<label for="frecuencia_cardiaca">Frecuencia cardiaca (x min):</label>
			<input type="text" id="frecuencia_cardiaca" disabled name="frecuencia_cardiaca" value="<?php echo $this->modeloExamenFisico->getFrecuenciaCardiaca();?>"
			placeholder="Frecuencia cardiaca"  maxlength="6" />
		</div>				

		<div data-linea="2">
			<label for="frecuencia_respiratoria">Frecuencia respiratoria (x min): </label>
			<input type="text" id="frecuencia_respiratoria" disabled name="frecuencia_respiratoria" value="<?php echo $this->modeloExamenFisico->getFrecuenciaRespiratoria();?>"
			placeholder="Frecuencia respiratoria"  maxlength="6" />
		</div>				

		<div data-linea="3">
			<label for="talla_mts">Talla (mts): </label>
			<input type="text" id="talla_mts" disabled name="talla_mts" value="<?php echo $this->modeloExamenFisico->getTallaMts();?>"
			placeholder="Talla en mts"  maxlength="6" />
		</div>				

		<div data-linea="3">
			<label for="temperatura_c">Temperatura (°C):</label>
			<input type="text" id="temperatura_c" disabled name="temperatura_c" value="<?php echo $this->modeloExamenFisico->getTemperaturaC();?>"
			placeholder="Temperatura"  maxlength="6" />
		</div>				

		<div data-linea="3">
			<label for="peso_kg">Peso (Kg):</label>
			<input type="text" id="peso_kg" disabled ame="peso_kg" value="<?php echo $this->modeloExamenFisico->getPesoKg();?>"
			placeholder="Peso"  maxlength="6" />
		</div>				

		<div data-linea="4">
			<label for="imc">Índice de masa corporal IMC (Peso (kg) / Talla (m2)):</label>
			<input type="text" id="imc" disabled name="imc" value="<?php echo $this->modeloExamenFisico->getImc();?>"
			placeholder="Imc"  maxlength="6" readonly/>
		</div>				

		<div data-linea="5">
			<label for="interpretacion_imc">Interpretación IMC:</label>
			<input type="text" id="interpretacion_imc" disabled name="interpretacion_imc" value="<?php echo $this->modeloExamenFisico->getInterpretacionImc();?>"
			placeholder="Interpretación IMC"  maxlength="16" readonly/>
		</div>				

	</fieldset >
	</div>
	<div class="pestania">
	<fieldset>
		<legend>Evaluación Primaria</legend>				
        <?php echo $this->listarEvaluacion($this->idHistorialClinica);?>
	</fieldset >
	</div>
	<div class="pestania">
	<fieldset>
		<legend>Exámenes Clínicos - Agregados</legend>
		<div id="listaExamenesClinicos" style="width:100%"><?php echo $this->listarExamenesClinicos($this->idHistorialClinica,0);?></div>
	</fieldset>
	<fieldset>
		<legend>Exámenes clínicos adjuntos</legend>	
		<div id="listaAdjuntosHistoria" style="width:100%"><?php echo $this->listarAdjuntosHistoria($this->idHistorialClinica);?></div>
	</fieldset>
	<fieldset>
		<legend>Exámenes paraclínicos agregados</legend>
		<div id="listaParaclinicos" style="width:100%"><?php echo $this->listarParaclinicos($this->idHistorialClinica,0);?></div>
	</fieldset>	
	<fieldset>
		<legend>Diagnósticos agregados</legend>	
		<div id="listaDiagnostico" style="width:100%"><?php echo $this->listarDiagnostico($this->idHistorialClinica,0);?></div>
	</fieldset>
	<fieldset>
		<legend>Concepto</legend>				

        <div data-linea="1">
			<input type="radio" name="descripcion_concepto[]" value="Apto" />
			  <label for="descripcion_concepto">Apto </label>
		</div>				

		<div data-linea="1">
			<input type="radio"  name="descripcion_concepto[]" disabled value="Apto condicionado" />
			<label for="descripcion_concepto">Apto condicionado</label>
		</div>
		<div data-linea="1">
			<input type="radio"  name="descripcion_concepto[]" disabled value="No apto" />
			<label for="descripcion_concepto">No apto</label>
		</div>			
		
		<div data-linea="2">
			<label for=tipo_restriccion_limitacion>Tipo de restricciones o limitaciones: </label>
			<input type="text" id="tipo_restriccion_limitacion" name="tipo_restriccion_limitacion" disabled value="<?php echo $this->modeloHistoriaClinica->getTipoRestriccionLimitacion(); ?>"
			placeholder="Tipo de restricción o limitación" maxlength="128" />
		</div>				
	</fieldset >
	<fieldset>
		<legend>Recomendaciones</legend>				

		<div data-linea="1">
			<label for="descripcion_recomendaciones">Recomendaciones:</label>
			<input type="text" id="descripcion_recomendaciones" disabled name="descripcion_recomendaciones" value="<?php echo $this->modeloRecomendaciones->getDescripcion(); ?>"
			placeholder="Recomendaciones"  maxlength="1024" />
		</div>				

		<div data-linea="2">
			<label for="reubicacion_laboral">Reubicación laboral: </label>
		</div>				
       <div data-linea="2">
			<input type="radio"  name="reubicacion_laboral[]" disabled value="Si" />
			<label for="reubicacion_laboral">Si</label>
		</div>
		<div data-linea="2">
			<input type="radio"  name="reubicacion_laboral[]" disabled value="No" />
			<label for="reubicacion_laboral">No</label>
		</div>	

	</fieldset >
	<fieldset>
		<?php echo $this->firma;?>	
	</fieldset >
	
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

<script type ="text/javascript">
var reubicacion_laboral = <?php echo json_encode($this->modeloRecomendaciones->getReubicacionLaboral()); ?>;
var ausentismo =  <?php echo json_encode($this->modeloAusentismoMedico->getAusentismo());?>;
var descripcion_concepto=<?php echo json_encode($this->modeloHistoriaClinica->getDescripcionConcepto());?>;
	$(document).ready(function() {
		mostrarMensaje("", "FALLO");
		construirValidador();
		distribuirLineas();
		$("#modalDetalle").hide();
		construirAnimacion($(".pestania"));

		//************************************************
		$("input[name='elementoProteccion[]']").map(function(){ 
				$(this).attr('disabled','disabled');
    	 }).get(); 
		$("input[name='descripcion_concepto[]']").map(function(){ 
			$(this).attr('disabled','disabled');
	    }).get(); 
		$("input[name='evaluacionPrimaria[]']").map(function(){ 
			$(this).attr('disabled','disabled');
    	 }).get(); 
    	$("input[name='evaluacionPrimariatxt[]']").map(function(){ 
    		$(this).attr('disabled','disabled');
        }).get();
    	$("input[name='reubicacion_laboral[]']").map(function(){ 
			if($(this).val() == reubicacion_laboral){
				$(this).prop('checked', true);
				}
    	 }).get(); 
    	$("input[name='ausentismo[]']").map(function(){ 
			if($(this).val() == ausentismo){
				$(this).prop('checked', true);
				}
    	 }).get(); 
    	$("input[name='descripcion_concepto[]']").map(function(){ 
			if($(this).val() == descripcion_concepto){
				$(this).prop('checked', true);
				}
    	 }).get(); 
	 });

 
</script>
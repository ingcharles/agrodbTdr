<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
	<link rel='stylesheet'
	href='<?php echo URL_MVC_MODULO ?>HistoriasClinicas/vistas/estilos/estiloModal.css'>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>HistoriasClinicas' data-opcion='historiaclinica/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">

<div class="pestania">
<fieldset id="contenedorBusqueda">
		<legend>Nueva Historia Clínica</legend>	
		<div data-linea="1">
			<label for="identificador">Documento de identificación:</label>
			<input type="text" id="identificador" name="identificador" value=""
			placeholder="Identificador" maxlength="16" />
		</div>	
				
		<div data-linea="1">
			<button type="button" class="buscar">Buscar</button>
		</div>
		<div data-linea="2">
			<label for="fecha_creacion">Fecha creación Historia Clínica:</label>
			<span><?php echo date("Y-m-d");  ?></span>
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
    	<div data-linea="3" id="contenedorCrearHistorio">
    			<button type="button" id="crearHistoria" class="guardar" >Crear Historia Clínica</button>
    	</div>
	</div>
	<div class="pestania">
	<fieldset id="ausentismo">
	<legend>Ausentismo médico en el último trimestre</legend>				
								 				
		<div data-linea="1">
		    <input  name="ausentismo[]" type="radio" id="Si" value="Si" onclick="verificarAusentismo(id)"><span>Si</span>&nbsp;&nbsp;&nbsp;&nbsp;
			<input  name="ausentismo[]" type="radio" id="No" value="No"onclick="verificarAusentismo(id)"><span>No</span>
		</div>				

		<div data-linea="2">
			<label for="causa">Causa: </label>
			<input type="text" id="causa" name="causa" value="<?php echo  $this->modeloAusentismoMedico->getCausa(); ?>"
			placeholder="Causa de ausentismo" maxlength="128" />
		</div>						 
		<div data-linea="3">
			<label for="tiempo">Tiempo (horas): </label>
			<input type="text" id="tiempo" name="tiempo" value="<?php echo $this->modeloAusentismoMedico->getTiempo(); ?>"
			placeholder="Tiempo de ausentismo" maxlength="3" />
		</div>				
			
	</fieldset >
	<fieldset>
		<legend>Historia Ocupacional</legend>				

		<div data-linea="1">
			<label for="empresa">Nombre de la empresa donde labora o laboró:</label>
			<input type="text" id="empresa" name="empresa" value=""
			placeholder="Nombre de la empresa"  maxlength="64" />
		</div>				

		<div data-linea="2">
			<label for="cargo">Nombre del cargo desempeñado: </label>
			<input type="text" id="cargo" name="cargo" value=""
			placeholder="Nombre del cargo desempeñado"  maxlength="64" />
		</div>				

		<div data-linea="3">
			<label for="id_tipo_procedimiento_medico">Tipo de Exposición:</label>
			<select
				id="id_tipo_procedimiento_medico" name="id_tipo_procedimiento_medico">
				 <?php echo $this->comboTipoProcedimiento('Exposición'); ?>
			</select>
		</div>				

		<div data-linea="3">
			<label for="tiempo_exposicion">Tiempo de Exposición (años): </label>
			<input type="text" id="tiempo_exposicion" name="tiempo_exposicion" value=""
			placeholder="Tiempo" maxlength="3" />
		</div>				

		<div data-linea="4" id="subtipos">
		
		</div>
		
		<div data-linea="5">
		<button type="button" class="mas" id="agregarExposicion">Agregar</button>
		</div>								
	</fieldset >
	<fieldset>
		<legend>Historia Ocupacional Agregada</legend>	
		<div id="listaHistoriaOcupacional" style="width:100%"><?php echo $this->listarHistoriaOcupacional($this->idHistorialClinica);?></div>	
	</fieldset>	
	</div>
	<div class="pestania">
	<fieldset>
		<legend>Accidentes Laborales</legend>				

		<div data-linea="1">
			<label for="accidente_laboral">¿Ha tenido accidente laboral?:</label>
			<select id="accidente_laboral" name= "accidente_laboral">
				<?php echo $this->comboOpcion();?>
			</select>	
		</div>				

		<div data-linea="2">
			<label for="mes">Fecha de ocurrencia: Mes:</label>
			<select id="mes" name= "mes">
				<?php echo $this->comboMeses();	?>
			</select>
		</div>				

		<div data-linea="2">
			<label for="anio">Año: </label>
			<select id="anio" name= "anio">
				<?php echo $this->comboAnios();?>
			</select>
		</div>				

		<div data-linea="3">
			<label for="reportado_iess">¿Fué calificado por el Instituto de Seguridad Social correspondiente?: </label>
			<select id="reportado_iess" name= "reportado_iess">
				<?php echo $this->comboOpcion();?>
			</select>
		</div>				

		<div data-linea="4">
			<label for="id_historia_ocupacional_accidente">Nombre de la empresa donde se presentó el accidente:</label>
			<select id="id_historia_ocupacional_accidente" name= "id_historia_ocupacional_accidente">
				<?php echo $this->comboHistoriaOcupacional($this->idHistorialClinica);?>
			</select>
		</div>				

		<div data-linea="5">
			<label for="naturaleza_lesion">Naturaleza de la lesión:</label>
			<select id="naturaleza_lesion" name= "naturaleza_lesion">
				<?php echo $this->comboNaturalezaLesion();?>
			</select>
		</div>				

		<div data-linea="5">
			<label for="dias_incapacidad">Días de incapacidad:</label>
			<input type="text" id="dias_incapacidad" name="dias_incapacidad" value=""
			placeholder="Días de vincapacidad" maxlength="3" />
		</div>				

		<div data-linea="6">
			<label for="parte_afectada">Parte del cuerpo afectada:</label>
			<input type="text" id="parte_afectada" name="parte_afectada" value=""
			placeholder="Partes afectadas"  maxlength="32" />
		</div>				

		<div data-linea="7">
			<label for="secuelas">Secuelas: </label>
			<input type="text" id="secuelas" name="secuelas" value=""
			placeholder="Secuelas"  maxlength="32" />
		</div>		
		<div data-linea="8">
			<button type="button" class="mas" id="agregarAccidente">Agregar</button>
		</div>			
	</fieldset >
	<fieldset>
		<legend>Accidentes laborales agregados</legend>	
		<div id="listaAccidenteLaboral" style="width:100%"><?php echo $this->listarAccidenteLaboral($this->idHistorialClinica);?></div>		
	</fieldset>
	</div>
	<div class="pestania">
	<fieldset>
		<legend>Elementos de Protección actual o último</legend>	
		<?php echo $this->listarElementosProteccion($this->idHistorialClinica);?>			
	</fieldset>
	<fieldset>
		<legend>Enfermedad profesional en empresa actual o anterior</legend>				

		<div data-linea="1">
			<label for="tiene_enfermedad">¿Tiene enfermedad profesional?:</label>
			<select id="tiene_enfermedad" name= "tiene_enfermedad" >
				<?php echo $this->comboOpcion($this->modeloEnfermedadProfesional->getTieneEnfermedad());?>
			</select>
		</div>				

		<div data-linea="2">
			<label for="fecha_diagnostico">Fecha de Diagnóstico o Calificación: </label>
			<input type="text" id="fecha_diagnostico" name="fecha_diagnostico" value="<?php echo $this->modeloEnfermedadProfesional->getFechaDiagnostico(); ?>"
			placeholder="Seleccionar fecha"  maxlength="10" readonly/>
		</div>				

		<div data-linea="3">
			<label for="descripcion">Cuál(es):</label>
			<input type="text" id="descripcion" name="descripcion" value="<?php echo $this->modeloEnfermedadProfesional->getDescripcion(); ?>"
			placeholder="Descripción"  maxlength="2000" />
		</div>				
		
	</fieldset >
	</div>
	<div class="pestania">
	<fieldset>
		<legend>Antecedentes de Salud (Familiares)</legend>				

		<div data-linea="1">
			<label for="id_tipo_procedimiento_medico_accidente">Parentesco:</label>
			<select id="id_tipo_procedimiento_medico_accidente" name= "id_tipo_procedimiento_medico_accidente">
				<?php echo $this->comboTipoProcedimiento('Parentesco');?>
			</select>
		</div>				

		<div data-linea="2">
			<label for="enfermedad_general">Enfermedad General: </label>
			<select id="enfermedad_general" name= "enfermedad_general">
				<?php echo $this->comboCie10('descripcion');?>
			</select>
		</div>				

		<div data-linea="2">
			<label for="id_cie">Código CIE 10:</label>
			<select id="id_cie" name= "id_cie">
				<?php echo $this->comboCie10('codigo');?>
			</select>
		</div>				

		<div data-linea="3">
			<label for="observaciones">Observaciones: </label>
			<input type="text" id="observaciones" name="observaciones" value=""
			placeholder="Observaciones"  maxlength="128" />
		</div>				
		<div data-linea="4">
			<button type="button" class="mas" id="agregarAntecedentesFamiliares">Agregar</button>
		</div>
	</fieldset >
	<fieldset>
		<legend>Antecedentes de Salud (Familiares) Agregados</legend>
		<div id="listaAntecedentesFamiliares" style="width:100%"><?php echo $this->listarAntecedentesFamiliares($this->idHistorialClinica);?></div>
	</fieldset>
	</div>
	<div class="pestania">
	<fieldset>
		<legend>Antecedentes de Salud (Personales)</legend>				

		<div data-linea="1">
			<label for="id_tipo_procedimiento_medico_anteced_salud">Seleccione una opción:</label>
			<select id="id_tipo_procedimiento_medico_anteced_salud" name= "id_tipo_procedimiento_medico_anteced_salud">
				<?php echo $this->comboTipoProcedimiento('Antecedentes de salud');?>
			</select>
		</div>	
	</fieldset >
	<fieldset id="detalleAntecedentesSalud">
		</fieldset>
	<fieldset>
		<legend>Antecedentes de Salud (Personales) Agregados</legend>	
		<div id="listaAntecedentesSalud" style="width:100%"><?php echo $this->listarAntecedentesSalud($this->idHistorialClinica);?></div>
	</fieldset>
	</div>
	<div class="pestania">
	<fieldset>
		<legend>Revisión actual de órganos y sistemas</legend>	
		<?php echo $this->listarElementosPorAparatos($this->idHistorialClinica);?>
		<div data-linea="1" ><hr>
			<label for="observacionesRevision">Observaciones: </label><br>
			<input type="text" id="observacionesRevision" name="observacionesRevision" value=""
			placeholder="Observaciones" maxlength="512" />
		</div>
		<div data-linea="2">
			<button type="button" class="mas" id="agregarRevisionOrganos">Agregar</button>
		</div>
	</fieldset>
	<fieldset>
		<legend>Revisión actual de órganos y sistemas - Agregados</legend>	
		<div id="listaRevisionOrganos" style="width:100%"><?php echo $this->listarRevisionOrganos($this->idHistorialClinica);?></div>
	</fieldset>
	</div>
	<div class="pestania">
	<fieldset>
		<legend>Inmunización</legend>				

		<div data-linea="1">
			<label for="id_tipo_procedimiento_medico_inmunizacion">Vacuna:</label>
			<select id="id_tipo_procedimiento_medico_inmunizacion" name= "id_tipo_procedimiento_medico_inmunizacion" >
        		<?php echo $this->comboTipoProcedimiento('Inmunizaciones');?>
        	</select>
		</div>				

		<div data-linea="2">
			<label for="numero_dosis">N° Dosis: </label>
			<select id="numero_dosis" name= "numero_dosis" >
        		<?php echo $this->comboNumeros(15,1);?>
        	</select>
		</div>	
		<div data-linea="2">
			<label for="fecha_ultima_dosis">FUD (fecha última dosis): </label>
			<input type="text" id="fecha_ultima_dosis" name="fecha_ultima_dosis" placeholder="Fecha dosis" readonly/>
         </div>			
		<div data-linea="3">
			<button type="button" class="mas" id="agregarInmunizacion">Agregar</button>
		</div>
	</fieldset >
	<fieldset>
		<legend>Inmunización - Agregadas</legend>				
	    <div id="listaInmunizacion" style="width:100%"><?php echo $this->listarInmunizacion($this->idHistorialClinica);?></div>
	</fieldset>
	<fieldset>
		<legend>Hábitos</legend>				
		<div data-linea="1">
			<label for="id_tipo_procedimiento_medico_habitos">Tipo de hábito:</label>
			<select id="id_tipo_procedimiento_medico_habitos" name= "id_tipo_procedimiento_medico_habitos" >
        		<?php echo $this->comboTipoProcedimiento('Frecuencia de drogas');?>
        	</select>
		</div>				
	</fieldset >
	<fieldset id="detalleHabitos">
		</fieldset>
	<fieldset>
		<legend>Hábitos - Agregados</legend>		
		<div id="listaHabitos" style="width:100%"><?php echo $this->listarHabitos($this->idHistorialClinica);?></div>
	</fieldset>
	
	<fieldset>
		<legend>Estilo de vida</legend>				

		<div data-linea="1">
			<label for="tipo_actividad">Tipo de actividad:</label>
			<select id="tipo_actividad" name= "tipo_actividad" >
        		<?php echo $this->comboActividad();?>
        	</select>
		</div>				

		<div data-linea="2">
			<label for="frecuencia">Frecuencia: </label>
			<select id="frecuencia" name= "frecuencia" >
        		<?php echo $this->comboFrecuencia();?>
        	</select>
		</div>				

		<div data-linea="3">
			<label for="observaciones_actividad">Observaciones:</label>
			<input type="text" id="observaciones_actividad" name="observaciones_actividad" value=""
			placeholder="Observaciones" maxlength="128" />
		</div>				
        <div data-linea="4">
			<button type="button" class="mas" id="agregarActividad">Agregar</button>
		</div>
	</fieldset >
	<fieldset>
		<legend>Actividades - Agregadas</legend>
		<div id="listaActividades" style="width:100%"><?php echo $this->listarActividad($this->idHistorialClinica);?></div>
	</fieldset>
	</div>
	<div class="pestania">
	<fieldset>
		<legend>Examen físico</legend>				

		<div data-linea="1">
			<label for="tension_arterial">Tensión arterial (mm Hg):</label>
			<input type="text" id="tension_arterial" name="tension_arterial" value="<?php echo $this->modeloExamenFisico->getTensionArterial();?>"
			placeholder="Tensión arterial"  maxlength="9" />
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
		<legend>Evaluación Primaria</legend>				
        <?php echo $this->listarEvaluacion($this->idHistorialClinica);?>
	</fieldset >
	</div>
	<div class="pestania">
	<fieldset>
		<legend>Exámenes Clínicos</legend>				
		<div data-linea="1">
			<label for="id_tipo_procedimiento_medico_exa_clinicos"><strong>Tipo de examen:</strong></label>
			<select id="id_tipo_procedimiento_medico_exa_clinicos" name= "id_tipo_procedimiento_medico_exa_clinicos" >
        		<?php echo $this->comboTipoProcedimiento('Laboratorio');?>
        	</select>
		</div>				
	</fieldset >
	<fieldset id="detalleExamenesClinicos">
		</fieldset>
	<fieldset>
		<legend>Exámenes Clínicos - Agregados</legend>
		<div id="listaExamenesClinicos" style="width:100%"><?php echo $this->listarExamenesClinicos($this->idHistorialClinica);?></div>
	</fieldset>
	<fieldset>
		<legend>Adjuntar exámenes clínicos</legend>				

		<div data-linea="1">
			<label for="descripcion_adjunto">Descripción de adjunto:</label>
			<input type="text" id="descripcion_adjunto" name="descripcion_adjunto" value=""
			placeholder="Descripcion de archivo adjunto" maxlength="512" />
		</div>	
		
		<div data-linea="2" id="documentoAdjunto">				
				<input type="hidden" class="rutaArchivo" name="archivo_adjunto" value="0"/>
				<input type="file" class="archivo" accept="application/pdf"/>
				<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
				<button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo URL_MVC_MODULO ?>HistoriasClinicas/archivos/adjuntosHistoriaClinica" >Subir archivo</button>		
			</div>
	
	</fieldset >
	<fieldset>
		<legend>Exámenes clínicos adjuntos</legend>	
		<div id="listaAdjuntosHistoria" style="width:100%"><?php echo $this->listarAdjuntosHistoria($this->idHistorialClinica);?></div>
	</fieldset>
	</div>
	<div class="pestania">
	<fieldset>
		<legend>Exámenes paraclínicos practicados</legend>				
		<div data-linea="1">
			<label for="id_tipo_procedimiento_medico_paraclinico">Tipo de examen:</label>
			<select id="id_tipo_procedimiento_medico_paraclinico" name= "id_tipo_procedimiento_medico_paraclinico" >
        		<?php echo $this->comboTipoProcedimiento('Examen de gabinete');?>
        	</select>
		</div>				
	</fieldset >
	<fieldset id="detalleParaclinicos">
		</fieldset>
	<fieldset>
		<legend>Exámenes paraclínicos agregados</legend>
		<div id="listaParaclinicos" style="width:100%"><?php echo $this->listarParaclinicos($this->idHistorialClinica);?></div>
	</fieldset>	
	</div>
	<div class="pestania">
	<fieldset>
		<legend>Impresión Diagnóstica</legend>				

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
			placeholder="diagnostico"  maxlength="128" />
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
		<div id="listaDiagnostico" style="width:100%"><?php echo $this->listarDiagnostico($this->idHistorialClinica);?></div>
	</fieldset>
	<fieldset>
		<legend>Concepto</legend>				

        <div data-linea="1">
			<input type="radio" name="descripcion_concepto[]" value="Apto" />
			  <label for="descripcion_concepto">Apto </label>
		</div>				

		<div data-linea="1">
			<input type="radio"  name="descripcion_concepto[]" value="Apto condicionado" />
			<label for="descripcion_concepto">Apto condicionado</label>
		</div>
		<div data-linea="1">
			<input type="radio"  name="descripcion_concepto[]" value="No apto" />
			<label for="descripcion_concepto">No apto</label>
		</div>			
		
		<div data-linea="2">
			<label for=tipo_restriccion_limitacion>Tipo de restricciones o limitaciones: </label>
			<input type="text" id="tipo_restriccion_limitacion" name="tipo_restriccion_limitacion" value="<?php echo $this->modeloHistoriaClinica->getTipoRestriccionLimitacion(); ?>"
			placeholder="Tipo de restricción o limitación" maxlength="128" />
		</div>				
	</fieldset >
	</div>
	<div class="pestania">
	<fieldset>
		<legend>Recomendaciones</legend>				

		<div data-linea="1">
			<label for="descripcion_recomendaciones">Recomendaciones:</label>
			<input type="text" id="descripcion_recomendaciones" name="descripcion_recomendaciones" value="<?php echo $this->modeloRecomendaciones->getDescripcion(); ?>"
			placeholder="Recomendaciones"  maxlength="1024" />
		</div>				

		<div data-linea="2">
			<label for="reubicacion_laboral">Reubicación laboral: </label>
		</div>				
       <div data-linea="2">
			<input type="radio"  name="reubicacion_laboral[]" value="Si" />
			<label for="reubicacion_laboral">Si</label>
		</div>
		<div data-linea="2">
			<input type="radio"  name="reubicacion_laboral[]" value="No" />
			<label for="reubicacion_laboral">No</label>
		</div>	

	</fieldset >
	<fieldset>
		<?php echo $this->firma;?>	
	</fieldset >
	<fieldset id="pdfHistoriaClinica">
		<legend>Historia Clínica PDF</legend>
		 <a href="<?php echo $this->adjuntoHistoriaClinica;?>" target="_blank" class="archivo_cargado" id="archivo_cargado">Descargar historia clínica</a>
	</fieldset >
	<div id="actualizarHistorial"><strong>Nota: Al hacer clic en el botón Actualizar, se guardarán las modificaciones realizadas en la historia clínica y con la información del médico ocupacional actualizado.</strong></div>
	 <div data-linea="5">
	 <div id="cargarHistoriaClinica"></div>
			<button type="button" class="guardar" id="guardarHistoriaClinica">Guardar historia clinica</button>
		</div>
		<fieldset id="registroCambios">
		<legend>Registro de modificaciones</legend>				
				<?php echo $this->historico;?>
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
var id_historia_clinica=<?php echo json_encode($this->idHistorialClinica);?>;
var descripcion_concepto=<?php echo json_encode($this->modeloHistoriaClinica->getDescripcionConcepto());?>;
var ausentismo =  <?php echo json_encode($this->modeloAusentismoMedico->getAusentismo());?>;
var estado = <?php echo json_encode($this->estado);?>;
var reubicacion_laboral = <?php echo json_encode($this->modeloRecomendaciones->getReubicacionLaboral()); ?>;
var adjuntoHC = <?php echo json_encode($this->adjuntoHistoriaClinica);?>;
	$(document).ready(function() {
		$("#tiempo").numeric();
		mostrarMensaje("", "FALLO");
		construirValidador();
		distribuirLineas();
		$("#modalDetalle").hide();
		construirAnimacion($(".pestania"));
        
        if(estado == 'nuevo'){
        	$("#crearHistoria").attr('disabled','disabled');
        	$("#registroCambios").hide();
        	$("#actualizarHistorial").hide();
        }else{
        	$("#contenedorCrearHistorio").remove();
        	$("#contenedorBusqueda").remove();
        	$("#registroCambios").show();
        	$("#actualizarHistorial").show();
        }
		$("#tiempo_exposicion").numeric();
		$("#detalleAntecedentesSalud").hide();
		$("#detalleHabitos").hide();
		$("#saturacion_oxigeno").numeric();
		$("#frecuencia_cardiaca").numeric();
		$("#frecuencia_respiratoria").numeric();
		$("#dias_incapacidad").numeric();
		$("#talla_mts").numeric();
		$("#temperatura_c").numeric();
		$("#peso_kg").numeric();
		$("#detalleExamenesClinicos").hide();
		$(".archivo").val('');
		$("#detalleParaclinicos").hide();
		$("#pdfHistoriaClinica").hide();

		if(id_historia_clinica != null){
			$("#guardarHistoriaClinica").html('Actualizar historia clinica');
			}
		if(adjuntoHC != null){
			$("#pdfHistoriaClinica").show();
			}
		//************************************************
		$("input[name='descripcion_concepto[]']").map(function(){ 
			if($(this).val() == descripcion_concepto){
				$(this).prop('checked', true);
				}
    	 }).get(); 
		$("input[name='ausentismo[]']").map(function(){ 
			if($(this).val() == ausentismo){
				$(this).prop('checked', true);
				}
    	 }).get(); 
		$("input[name='reubicacion_laboral[]']").map(function(){ 
			if($(this).val() == reubicacion_laboral){
				$(this).prop('checked', true);
				}
    	 }).get(); 
	 });

	$("#guardarHistoriaClinica").click(function (event) {
		event.preventDefault();
		var texto = "Por favor revise los campos obligatorios.";
		$(".alertaCombo").removeClass("alertaCombo");
		mostrarMensaje("", "FALLO");
		var error = false;
		if(!$("input[name='ausentismo[]']").is(':checked') ){
			   $("input[name='ausentismo[]']").addClass("alertaCombo");
  			   texto="Por favor revise los campos obligatorios en ausentismo médico";
  			   error = true;
  		  }
		$("input[name='ausentismo[]']").map(function(){ if($(this).prop("checked")){ 
	 			if($(this).val() == 'Si'){
	 				if(!$.trim($("#causa").val())){
	 				   $("#causa").addClass("alertaCombo");
	 				   texto="Por favor revise los campos obligatorios en ausentismo médico";
	 				   error = true;
	 			  }
	 			if(!$.trim($("#tiempo").val())){
	 				   $("#tiempo").addClass("alertaCombo");
	 				   texto="Por favor revise los campos obligatorios en ausentismo médico";
	 				   error = true;
	 			  }
	 			}
	 			}}).get();
		if(!$.trim($("#tiene_enfermedad").val())){
			   $("#tiene_enfermedad").addClass("alertaCombo");
			   texto="Por favor revise los campos obligatorios en enfermedad profesional";
			   error = true;
		}
		if($("#tiene_enfermedad").val() == 'Si'){
			if(!$.trim($("#fecha_diagnostico").val())){
				   $("#fecha_diagnostico").addClass("alertaCombo");
				   texto="Por favor revise los campos obligatorios en enfermedad profesional";
				   error = true;
			}
			if(!$.trim($("#descripcion").val())){
				   $("#descripcion").addClass("alertaCombo");
				   texto="Por favor revise los campos obligatorios en enfermedad profesional";
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
		//******************************************************
		if(!$("input[name='evaluacionPrimaria[]']").is(':checked') ){
			   $("#bodyEvaluacion").addClass("alertaCombo");
			   texto="Por favor revise los campos obligatorios en evaluacion primaria";
			   error = true;
		  }

		if(!$("input[name='ausentismo[]']").is(':checked') ){
			   $("input[name='ausentismo[]']").addClass("alertaCombo");
			   texto="Por favor revise los campos obligatorios en ausentismo médico";
			   error = true;
		  }
		 //*************************************************
		if(!$("input[name='descripcion_concepto[]']").is(':checked') ){
			   $("input[name='descripcion_concepto[]']").addClass("alertaCombo");
			   texto="Por favor revise los campos obligatorios en concepto";
			   error = true;
		  }
		$("input[name='descripcion_concepto[]']").map(function(){ if($(this).prop("checked")){ 
	 			if($(this).val() == 'Apto condicionado'){
	 				if(!$.trim($("#tipo_restriccion_limitacion").val())){
	 				   $("#tipo_restriccion_limitacion").addClass("alertaCombo");
	 				  texto="Por favor revise los campos obligatorios en concepto";
	 				   error = true;
	 			  }
	 			}
	 			}}).get(); 
		//******************************************************
		if(!$("input[name='reubicacion_laboral[]']").is(':checked') ){
			   $("input[name='reubicacion_laboral[]']").addClass("alertaCombo");
			   texto="Por favor revise los campos obligatorios en recomendaciones";
			   error = true;
		  }	
		if(!$.trim($("#descripcion_recomendaciones").val())){
			   $("#descripcion_recomendaciones").addClass("alertaCombo");
			   texto="Por favor revise los campos obligatorios en recomendaciones";
			   error = true;
		  }
		if (!error) {
			$("#cargarHistoriaClinica").html("<div id='cargando'>Cargando...</div>").fadeIn();
			$.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/guardarRegistros", 
                {
				    id_historia_clinica: id_historia_clinica,
				    ausentismo:$("input[name='ausentismo[]']").map(function(){ if($(this).prop("checked")){return $(this).val();}}).get(),
				    causa:$("#causa").val(),
				    tiempo:$("#tiempo").val(),
				    elementoProteccion:$("input[name='elementoProteccion[]']").map(function(){ if($(this).prop("checked")){return $(this).val();}}).get(),
				    tiene_enfermedad:$("#tiene_enfermedad").val(),
				    fecha_diagnostico:$("#fecha_diagnostico").val(),
				    descripcion:$("#descripcion").val(),
				    tension_arterial:$("#tension_arterial").val(),
				    saturacion_oxigeno:$("#saturacion_oxigeno").val(),
				    frecuencia_cardiaca:$("#frecuencia_cardiaca").val(),
				    frecuencia_respiratoria:$("#frecuencia_respiratoria").val(),
				    talla_mts:$("#talla_mts").val(),
				    temperatura_c:$("#temperatura_c").val(),
				    peso_kg:$("#peso_kg").val(),
				    imc:$("#imc").val(),
				    interpretacion_imc:$("#interpretacion_imc").val(),
				    evaluacionPrimaria:$("input[name='evaluacionPrimaria[]']").map(function(){ if($(this).prop("checked")){return $(this).attr("id");}}).get(),
				    evaluacionPrimariatxt:$("input[name='evaluacionPrimariatxt[]']").map(function(){ if($(this).val()){return $(this).attr("id")+'-'+$(this).val()}}).get(),
					descripcion_concepto:$("input[name='descripcion_concepto[]']").map(function(){ if($(this).prop("checked")){return $(this).val()}}).get(),
					tipo_restriccion_limitacion:$("#tipo_restriccion_limitacion").val(),   
					descripcion_recomendaciones:$("#descripcion_recomendaciones").val(),
					reubicacion_laboral:$("input[name='reubicacion_laboral[]']").map(function(){ if($(this).prop("checked")){return $(this).val();}}).get()
				    
                }, function (data) {
                	$("#cargarHistoriaClinica").html("");
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
    	$.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/buscarFuncionario", 
                {
            		identificador: $('#identificador').val()
                }, function (data) {
                	if (data.estado === 'EXITO') {
                		 $("#divFuncionario").html(data.paciente);
	                   	 $("#divCargo").html(data.puesto);
	                   	 $("#discapacidad").html(data.discapacidad);
	                   	 $("#crearHistoria").removeAttr('disabled');
	                   	 mostrarMensaje(data.mensaje, data.estado);
	                     distribuirLineas();
                    } else {
                    	mostrarMensaje(data.mensaje, "FALLO");
                        $("#divFuncionario").html(data.paciente);
                        $("#divCargo").html(data.puesto);
                        $("#discapacidad").html(data.discapacidad);
                        $("#crearHistoria").attr('disabled','disabled');
                        distribuirLineas();
                    }
        }, 'json');
        
    	}else{
    		mostrarMensaje("El campo esta vacio !!", "FALLO");
    		$('#identificador').addClass("alertaCombo");
    	}
    });

      function verificarAusentismo(id) { 
          if(id == "No"){
        	  $('#causa').val('');
        	  $('#tiempo').val('');
        	  $("#causa").attr('disabled','disabled');
        	  $("#tiempo").attr('disabled','disabled');
          }else{
        	  $('#causa').removeAttr('disabled');
        	  $('#tiempo').removeAttr('disabled');
          }
              
      }

    //listar subtipos 
      $("#id_tipo_procedimiento_medico").change(function () {
          if($('#id_tipo_procedimiento_medico').val() != ''){
    	  $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/buscarSubtipos", 
                  {
              		tipoProcedimiento: $('#id_tipo_procedimiento_medico').val()
                  }, function (data) {
                  	if (data.estado === 'EXITO') {
                          $("#subtipos").html(data.contenido);
                          mostrarMensaje(data.mensaje, data.estado);
                          distribuirLineas();
                      } else {
                    	  mostrarMensaje(data.mensaje, "FALLO");
                          $("#subtipos").html(data.contenido);
                          distribuirLineas();
                      }
          }, 'json');
          }
      });
	
	//crear historia clinica
	$("#crearHistoria").click(function (){
		if($("#identificador_paciente").val() != ''){
			 $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/crearHistoriaClinica", 
                  {
              		identificador_paciente: $('#identificador_paciente').val()
                  }, function (data) {
                  	if (data.estado === 'EXITO') {
                  		id_historia_clinica = data.contenido;
                		$(".buscar").attr('disabled','disabled');
                		$("#crearHistoria").attr('disabled','disabled');
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
 //********historial ocupacional************************************
      $("#agregarExposicion").click(function () {
			var texto = "Por favor revise los campos obligatorios.";
			$(".alertaCombo").removeClass("alertaCombo");
			mostrarMensaje("", "FALLO");
			var error = false;
			if(!$.trim($("#empresa").val())){
	  			   $("#empresa").addClass("alertaCombo");
	  			   error = true;
	  		  }
			if(!$.trim($("#cargo").val())){
	  			   $("#cargo").addClass("alertaCombo");
	  			   error = true;
	  		  }
			if(!$.trim($("#id_tipo_procedimiento_medico").val())){
	  			   $("#id_tipo_procedimiento_medico").addClass("alertaCombo");
	  			   error = true;
	  		  }
			if(!$.trim($("#tiempo_exposicion").val())){
	  			   $("#tiempo_exposicion").addClass("alertaCombo");
	  			   error = true;
	  		  }
			if(!id_historia_clinica){
				   texto = "Debe crear la Historia Clínica !!.";
	  			   error = true;
	  		  } 
	  		if($("input[name='subtipoList[]']").length > 0 && !$("input[name='subtipoList[]']").is(':checked') ){
	  			 texto = "Debe seleccionar un subtipo de exposición !!.";
	  			$("#subtipos").addClass("alertaCombo");
	  			 error = true;
		  		}
			if (!error) {
			  	  $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/agregarExposicion", 
		                  {
			  		         id_historia_clinica: id_historia_clinica,
			  		         empresa:$("#empresa").val(),
				  		     cargo:$("#cargo").val(),
				  		     id_tipo_procedimiento_medico: $("#id_tipo_procedimiento_medico").val(),
				  		     tiempo_exposicion:$("#tiempo_exposicion").val(),
				  		     subtipoList:$("input[name='subtipoList[]']").map(function(){ if($(this).prop("checked")){return $(this).val();}}).get()
				  		     
		                  }, function (data) {
		                  	if (data.estado === 'EXITO') {
		                  		$("#listaHistoriaOcupacional").html(data.contenido);
		                  		$("#id_historia_ocupacional_accidente").html(data.accidente);
		                  		$("#cargo").val('');
		                  		$("#empresa").val('');
		                  		$("#tiempo_exposicion").val('');
		                  		$("#subtipos").html('');
		                  		$("#id_tipo_procedimiento_medico").val('');
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
      // eliminar subtipos agregados
     function eliminarSubtipo(id){
         $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/eliminarExposicion", 
                 {
                    id_historia_clinica: id_historia_clinica,
        	        id_historia_ocupacional: id
	  		         		  		     
                 }, function (data) {
                 	if (data.estado === 'EXITO') {
                 		$("#listaHistoriaOcupacional").html(data.contenido);
                 		$("#id_historia_ocupacional_accidente").html(data.accidente);
	                    mostrarMensaje(data.mensaje, data.estado);
	                    distribuirLineas();
                     } else {
                     	mostrarMensaje(data.mensaje, "FALLO");
                     }
         }, 'json');

      }
     //********accidente laboral***************************************
      $("#agregarAccidente").click(function () {
			var texto = "Por favor revise los campos obligatorios.";
			$(".alertaCombo").removeClass("alertaCombo");
			mostrarMensaje("", "FALLO");
			var error = false;
			if(!$.trim($("#accidente_laboral").val())){
	  			   $("#accidente_laboral").addClass("alertaCombo");
	  			   error = true;
	  		  }
			if(!$.trim($("#mes").val())){
	  			   $("#mes").addClass("alertaCombo");
	  			   error = true;
	  		  }
			if(!$.trim($("#anio").val())){
	  			   $("#anio").addClass("alertaCombo");
	  			   error = true;
	  		  }
			if(!$.trim($("#reportado_iess").val())){
	  			   $("#reportado_iess").addClass("alertaCombo");
	  			   error = true;
	  		  }
			if(!$.trim($("#id_historia_ocupacional_accidente").val())){
	  			   $("#id_historia_ocupacional_accidente").addClass("alertaCombo");
	  		  }
			if(!$.trim($("#naturaleza_lesion").val())){
	  			   $("#naturaleza_lesion").addClass("alertaCombo");
	  			   error = true;
	  		  }
			if(!$.trim($("#dias_incapacidad").val())){
	  			   $("#dias_incapacidad").addClass("alertaCombo");
	  			   error = true;
	  		  }
			if(!$.trim($("#parte_afectada").val())){
	  			   $("#parte_afectada").addClass("alertaCombo");
	  			   error = true;
	  		  }
			if(!$.trim($("#secuelas").val())){
	  			   $("#secuelas").addClass("alertaCombo");
	  			   error = true;
	  		  }
			if(!id_historia_clinica){
				   texto = "Debe crear la Historia Clínica !!.";
	  			   error = true;
	  		  } 
	  		
			if (!error) {
			  	  $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/agregarAccidente", 
		                  {
			  		         id_historia_clinica: id_historia_clinica,
			  		         accidente_laboral:$("#accidente_laboral").val(),
			  		         mes: $("#mes").val(),
			  		         anio:$("#anio").val(),
			  		         reportado_iess: $("#reportado_iess").val(),
			  		         id_historia_ocupacional:$("#id_historia_ocupacional_accidente").val(),
			  		         naturaleza_lesion: $("#naturaleza_lesion").val(),
			  		         dias_incapacidad:$("#dias_incapacidad").val(),
			  		         parte_afectada: $("#parte_afectada").val(),
			  		         secuelas:$("#secuelas").val()
				  		     
		                  }, function (data) {
		                  	if (data.estado === 'EXITO') {
		                  		$("#listaAccidenteLaboral").html(data.contenido);
		                  		$("#accidente_laboral").val('');
				  		        $("#mes").val('');
				  		        $("#anio").val('');
				  		        $("#reportado_iess").val('');
				  		        $("#id_historia_ocupacional_accidente").val('');
				  		        $("#naturaleza_lesion").val('');
				  		        $("#dias_incapacidad").val('');
				  		        $("#parte_afectada").val('');
				  		        $("#secuelas").val('');
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
      // eliminar accidentes agregados
     function eliminarAccidente(id){
         $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/eliminarAccidente", 
                 {
                    id_historia_clinica: id_historia_clinica,
                    id_accidentes_laborales: id
	  		         		  		     
                 }, function (data) {
                 	if (data.estado === 'EXITO') {
                 		$("#listaAccidenteLaboral").html(data.contenido);
	                    mostrarMensaje(data.mensaje, data.estado);
	                    distribuirLineas();
                     } else {
                     	mostrarMensaje(data.mensaje, "FALLO");
                     }
         }, 'json');

      }
      //seleccionar subtipos *************************************************
     function verificarCheckbox(id){
         if($(".checkTodos").prop("checked")){
        	 $(".case").prop("checked", true);
         }else{
        	 $(".case").prop("checked", false);
             }
         }
   //******fecha de diagnostico************************************************
     $("#fecha_diagnostico").datepicker({
     	yearRange: "c:c",
     	changeMonth: false,
         changeYear: false,
         dateFormat: 'yy-mm-dd',
       });
     //*******enfermedad profesional*******************************************
     $("#tiene_enfermedad").change(function () {
		if($("#tiene_enfermedad").val() == 'No'){
			
    		$("#fecha_diagnostico").attr('disabled','disabled');
    		$("#descripcion").attr('disabled','disabled');
    		$("#fecha_diagnostico").val('');
    		$("#descripcion").val('');
		}else{
			$("#fecha_diagnostico").removeAttr('disabled');
			$("#descripcion").removeAttr('disabled');
			}
     });
     //**********cie10*********************************************************
     $("#enfermedad_general").change(function () {
	     $("#id_cie").val($(this).val());
     });
     $("#id_cie").change(function () {
    	 $("#enfermedad_general").val($(this).val());
     });
     //********accidente laboral*************************************** 
     $("#agregarAntecedentesFamiliares").click(function () {
			var texto = "Por favor revise los campos obligatorios.";
			$(".alertaCombo").removeClass("alertaCombo");
			mostrarMensaje("", "FALLO");
			var error = false;
			if(!$.trim($("#id_tipo_procedimiento_medico_accidente").val())){
	  			   $("#id_tipo_procedimiento_medico_accidente").addClass("alertaCombo");
	  			   error = true;
	  		  }
			if(!$.trim($("#enfermedad_general").val())){
	  			   $("#enfermedad_general").addClass("alertaCombo");
	  			   error = true;
	  		  }
			if(!$.trim($("#id_cie").val())){
	  			   $("#id_cie").addClass("alertaCombo");
	  			   error = true;
	  		  }
			if(!$.trim($("#observaciones").val())){
	  			   $("#observaciones").addClass("alertaCombo");
	  			   error = true;
	  		  }
			if(!id_historia_clinica){
				   texto = "Debe crear la Historia Clínica !!.";
	  			   error = true;
	  		  } 
	  		
			if (!error) {
			  	  $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/agregarAntecedentesFamiliares", 
		                  {
			  		         id_historia_clinica:id_historia_clinica,
			  		         id_tipo_procedimiento_medico:$("#id_tipo_procedimiento_medico_accidente").val(),
			  		         id_cie:$("#id_cie").val(),
			  		         observaciones:$("#observaciones").val()
				  		     
		                  }, function (data) {
		                  	if (data.estado === 'EXITO') {
		                  		$("#listaAntecedentesFamiliares").html(data.contenido);
		                  		$("#id_tipo_procedimiento_medico_accidente").val('');
				  		        $("#id_cie").val('');
				  		        $("#enfermedad_general").val('');
				  		        $("#observaciones").val('');
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
     // eliminar accidentes agregados
    function eliminarAntecedentesFamiliares(id){
        $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/eliminarAntecedentesFamiliares", 
                {
                   id_historia_clinica: id_historia_clinica,
                   id_anteced_salud_familiar: id
	  		         		  		     
                }, function (data) {
                	if (data.estado === 'EXITO') {
                		$("#listaAntecedentesFamiliares").html(data.contenido);
	                    mostrarMensaje(data.mensaje, data.estado);
	                    distribuirLineas();
                    } else {
                    	mostrarMensaje(data.mensaje, "FALLO");
                    }
        }, 'json');

     }

  //********antecedentes de salud *************************************** 
  $("#id_tipo_procedimiento_medico_anteced_salud").change(function () {
	  if($("#id_tipo_procedimiento_medico_anteced_salud").val()){
	  $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/buscarAntecedentesSalud", 
              {
                 id_historia_clinica:id_historia_clinica,
                 id_tipo_procedimiento_medico: $("#id_tipo_procedimiento_medico_anteced_salud").val(),
                 tipo: $("#id_tipo_procedimiento_medico_anteced_salud option:selected").text()
	  		         		  		     
              }, function (data) {
              	if (data.estado === 'EXITO') {
              		    $("#detalleAntecedentesSalud").html(data.contenido);
              		    $("#detalleAntecedentesSalud").show();
	                    mostrarMensaje(data.mensaje, data.estado);
	                    distribuirLineas();
                  } else {
                  	mostrarMensaje(data.mensaje, "FALLO");
                  }
      }, 'json');
	  }else{
		  $("#detalleAntecedentesSalud").html('');
		  $("#detalleAntecedentesSalud").hide();
		   }
     });
  //****************************************************************************************
        function agregarAntecedentesSalud(){
    		event.stopImmediatePropagation();
			var texto = "Por favor revise los campos obligatorios.";
			$(".alertaCombo").removeClass("alertaCombo");
			mostrarMensaje("", "FALLO");
			var error = false;
			error = validarCamposAntecedentes($("#id_tipo_procedimiento_medico_anteced_salud option:selected").text());
			if (!error) {
			  	  $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/agregarAntecedentesSalud", 
		                  {
			  		         id_historia_clinica:id_historia_clinica,
			  		         id_tipo_procedimiento_medico:$("#id_tipo_procedimiento_medico_anteced_salud").val(),
			  		         id_cie:$("#id_cie_salud").val(),
			  		         enfermedad_general:$("#enfermedad_general_salud").val(),
			  		         diagnostico:$("#diagnostico_salud").val(),
			  		         observaciones:$("#observaciones_salud").val(),
			  		         ciclo_mestrual:$("#ciclo_mestrual").val(),
			  		         fecha_ultima_regla:$("#fecha_ultima_regla").val(),
			  		         fecha_ultima_citologia:$("#fecha_ultima_citologia").val(),
			  		         resultado_citologia:$("#resultado_citologia").val(),
			  		         numero_gestaciones:$("#numero_gestaciones").val(),
			  		         numero_partos:$("#numero_partos").val(),
			  		         numero_cesareas:$("#numero_cesareas").val(),
			  		         numero_abortos:$("#numero_abortos").val(),
			  		         numero_hijos_vivos:$("#numero_hijos_vivos").val(),
			  		         numero_hijos_muertos:$("#numero_hijos_muertos").val(),
			  		         embarazo:$("#embarazo").val(),
			  		         semanas_gestacion:$("#semanas_gestacion").val(),
			  		         numero_ecos:$("#numero_ecos").val(),
			  		         numero_controles_embarazo:$("#numero_controles_embarazo").val(),
			  		         complicaciones:$("#complicaciones").val(),
			  		         vida_sexual_activa:$("#vida_sexual_activa").val(),
			  		         planificacion_familiar:$("#planificacion_familiar").val(),
			  		         metodo_planificacion:$("#metodo_planificacion").val()
				  		     
		                  }, function (data) {
		                  	if (data.estado === 'EXITO') {
		                  		$("#listaAntecedentesSalud").html(data.contenido);
		                  		$("#detalleAntecedentesSalud").html('');
		           		        $("#detalleAntecedentesSalud").hide();
			                    mostrarMensaje(data.mensaje, data.estado);
			                    distribuirLineas();
		                      } else {
		                      	mostrarMensaje(data.mensaje, "FALLO");
		                      }
		          }, 'json');
			} else {
				mostrarMensaje(texto, "FALLO");
			}
    }
    // eliminar antecedentes de salud agregados  informacionAntecedentesSalud
   function eliminarAntecedentesSalud(id){
       $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/eliminarAntecedentesSalud", 
               {
                  id_historia_clinica: id_historia_clinica,
                  id_antecedentes_salud: id
	  		         		  		     
               }, function (data) {
               	if (data.estado === 'EXITO') {
               		    $("#listaAntecedentesSalud").html(data.contenido);
	                    mostrarMensaje(data.mensaje, data.estado);
	                    distribuirLineas();
                   } else {
                   	mostrarMensaje(data.mensaje, "FALLO");
                   }
       }, 'json');

    }
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
   //**********cie10*********************************************************
   $(document).on('change','#enfermedad_general_salud',function(){
	   event.stopPropagation();
	  $("#id_cie_salud").val($(this).val());
   });
   $(document).on('change','#id_cie_salud',function(){
	   event.stopPropagation();
  	 $("#enfermedad_general_salud").val($(this).val());
   });
	//*****************verificar campos***********************************
	function validarCamposAntecedentes(opt){
		var error1 = false;
		switch (opt) { 
		case 'Clínicos': 
			if(!$.trim($("#enfermedad_general_salud").val())){
	  			   $("#enfermedad_general_salud").addClass("alertaCombo");
	  			   error1 = true;
	  		  }
			if(!$.trim($("#id_cie_salud").val())){
	  			   $("#id_cie_salud").addClass("alertaCombo");
	  			   error1 = true;
	  		  }
			if(!$.trim($("#diagnostico_salud").val())){
	  			   $("#diagnostico_salud").addClass("alertaCombo");
	  			   error1 = true;
	  		  }
			if(!$.trim($("#observaciones_salud").val())){
	  			   $("#observaciones_salud").addClass("alertaCombo");
	  			   error1 = true;
	  		  }
			break;
		case 'Gineco Obstétricos': 
			if(!$.trim($("#ciclo_mestrual").val())){
	  			   $("#ciclo_mestrual").addClass("alertaCombo");
	  			   error1 = true;
	  		  }
			if(!$.trim($("#fecha_ultima_regla").val())){
	  			   $("#fecha_ultima_regla").addClass("alertaCombo");
	  			   error1 = true;
	  		  }
			if(!$("#nunca_fecha").prop("checked")){
				if(!$.trim($("#fecha_ultima_citologia").val())){
		  			   $("#fecha_ultima_citologia").addClass("alertaCombo");
		  			   error1 = true;
		  		  }
				if(!$.trim($("#resultado_citologia").val())){
		  			   $("#resultado_citologia").addClass("alertaCombo");
		  			   error1 = true;
		  		  }
	         }
			
			if(!$.trim($("#numero_gestaciones").val())){
	  			   $("#numero_gestaciones").addClass("alertaCombo");
	  			   error1 = true;
	  		  }
			if(!$.trim($("#numero_partos").val())){
	  			   $("#numero_partos").addClass("alertaCombo");
	  			   error1 = true;
	  		  }
			if(!$.trim($("#numero_cesareas").val())){
	  			   $("#numero_cesareas").addClass("alertaCombo");
	  			   error1 = true;
	  		  }
			if(!$.trim($("#numero_abortos").val())){
	  			   $("#numero_abortos").addClass("alertaCombo");
	  			   error1 = true;
	  		  }
			if(!$.trim($("#numero_hijos_vivos").val())){
	  			   $("#numero_hijos_vivos").addClass("alertaCombo");
	  			   error1 = true;
	  		  }
			if(!$.trim($("#numero_hijos_muertos").val())){
	  			   $("#numero_hijos_muertos").addClass("alertaCombo");
	  			   error1 = true;
	  		  }
			if(!$.trim($("#embarazo").val())){
	  			   $("#embarazo").addClass("alertaCombo");
	  			   error1 = true;
	  		  }
	  		  
			if($("#embarazo").val() == 'Si'){
				
			if(!$.trim($("#semanas_gestacion").val())){
	  			   $("#semanas_gestacion").addClass("alertaCombo");
	  			   error1 = true;
	  		  }
			if(!$.trim($("#numero_ecos").val())){
	  			   $("#numero_ecos").addClass("alertaCombo");
	  			   error1 = true;
	  		  }
			if(!$.trim($("#numero_controles_embarazo").val())){
	  			   $("#numero_controles_embarazo").addClass("alertaCombo");
	  			   error1 = true;
	  		  }
			if(!$.trim($("#complicaciones").val())){
	  			   $("#complicaciones").addClass("alertaCombo");
	  			   error1 = true;
	  		  }
			}
			if(!$.trim($("#vida_sexual_activa").val())){
	  			   $("#vida_sexual_activa").addClass("alertaCombo");
	  			   error1 = true;
	  		  }
			if(!$.trim($("#planificacion_familiar").val())){
	  			   $("#planificacion_familiar").addClass("alertaCombo");
	  			   error1 = true;
	  		  }
			if($("#planificacion_familiar").val() == 'Si'){
    			if(!$.trim($("#metodo_planificacion").val())){
    	  			   $("#metodo_planificacion").addClass("alertaCombo");
    	  			   error1 = true;
    	  		  }
			}
			break;
		default:
			if(!$.trim($("#diagnostico_salud").val())){
	  			   $("#diagnostico_salud").addClass("alertaCombo");
	  			   error1 = true;
	  		  }
			if(!$.trim($("#observaciones_salud").val())){
	  			   $("#observaciones_salud").addClass("alertaCombo");
	  			   error1 = true;
	  		  }
	       }
		  return error1;
	    
		}
	
	//******citologia*********************************
	 $(document).on('click','#nunca_fecha',function(){
		   event.stopPropagation();
		   if($(this).prop("checked")){
			     $("#fecha_ultima_citologia").val('');
				 $("#resultado_citologia").val('');  
				 $("#fecha_ultima_citologia").attr('disabled','disabled');
				 $("#resultado_citologia").attr('disabled','disabled');
	         }else{
	        	 $("#fecha_ultima_citologia").val('');
				 $("#resultado_citologia").val('');  
				 $("#fecha_ultima_citologia").removeAttr('disabled');
				 $("#resultado_citologia").removeAttr('disabled');
	         }
	   });

	//************embarazo**************************
	$(document).on('change','#embarazo',function(){
		   event.stopPropagation();
		   if($(this).val() == 'Si'){
				 $("#semanas_gestacion").removeAttr('disabled');
				 $("#numero_ecos").removeAttr('disabled');
				 $("#numero_controles_embarazo").removeAttr('disabled');
				 $("#complicaciones").removeAttr('disabled');
	       }else{
	        	 $("#semanas_gestacion").val('');
				 $("#numero_ecos").val('');  
				 $("#numero_controles_embarazo").val('');
				 $("#complicaciones").val('');  
	        	 $("#semanas_gestacion").attr('disabled','disabled');
				 $("#numero_ecos").attr('disabled','disabled');
				 $("#numero_controles_embarazo").attr('disabled','disabled');
				 $("#complicaciones").attr('disabled','disabled');
	      }
	   });
	//************planificación familiar**************************
	$(document).on('change','#planificacion_familiar',function(){
		   event.stopPropagation();
		   if($(this).val() == 'Si'){
				 $("#metodo_planificacion").removeAttr('disabled');
	       }else{
	        	 $("#metodo_planificacion").val('');
	        	 $("#metodo_planificacion").attr('disabled','disabled');
	      }
	   });

	//******fecha de ultima regla************************************************
	$(document).on('click',"#ciclo_mestrual", function(){
    $("#fecha_ultima_regla").datepicker({
    	yearRange: "c:c",
    	changeMonth: false,
        changeYear: false,
        dateFormat: 'yy-mm-dd',
      });
    $("#fecha_ultima_citologia").datepicker({
    	yearRange: "c:c",
    	changeMonth: false,
        changeYear: false,
        dateFormat: 'yy-mm-dd',
      });
	});
	//**************fecha inmunizacion********************************************
	 $("#fecha_ultima_dosis").datepicker({
    	yearRange: "c:c",
    	changeMonth: false,
        changeYear: false,
        dateFormat: 'yy-mm',
      });
	//*******************************sistemas *************************************
	 $("#agregarRevisionOrganos").click(function () {
    		event.stopImmediatePropagation();
			var texto = "Debe seleccionar un campo.";
			$(".alertaCombo").removeClass("alertaCombo");
			mostrarMensaje("", "FALLO");
 			var error = false;
            var subtipoList = $("input[name='revisionAparatos[]']").map(function(){ if($(this).prop("checked")){return $(this).attr("id")+','+$(this).val()}}).get();
	        var subtipoTxt = $("input[name='revisionAparatosTxt[]']").map(function(){ if($(this).val()){return $(this).attr("id")+','+$(this).val()}}).get();
        	if(subtipoList.length == 0){
        		if(subtipoTxt.length == 0){
        			$("#bodyOrganos").addClass("alertaCombo");
        			error = true;
        		}
        	}
            	if (!error) {
			  	  $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/agregarRevisionOrganos", 
		                  {
			  		         id_historia_clinica:id_historia_clinica,
			  		         observaciones:$("#observacionesRevision").val(),
			  		         subtipoList:subtipoList,
			  		         subtipoTxt:subtipoTxt
			  		         
		                  }, function (data) {
		                  	if (data.estado === 'EXITO') {
		                  		$("#listaRevisionOrganos").html(data.contenido);
		                  		$("input[name='revisionAparatos[]']").attr('checked', false);
		               		    $("input[name='revisionAparatosTxt[]']").val('');
		               		    $("#observacionesRevision").val('');
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
    // eliminar revision organos y sistemas  
   function eliminarRevisionOrganos(id){
       $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/eliminarRevisionOrganos", 
               {
                  id_historia_clinica: id_historia_clinica,
                  id_revision_organos_sistemas: id
	  		         		  		     
               }, function (data) {
               	if (data.estado === 'EXITO') {
               		    $("#listaRevisionOrganos").html(data.contenido);
               		    $("input[name='revisionAparatos[]']").attr('checked', false);
               		    $("input[name='revisionAparatosTxt[]']").val('');
               		    $("#observacionesRevision").val('');
	                    mostrarMensaje(data.mensaje, data.estado);
	                    distribuirLineas();
                   } else {
                   	mostrarMensaje(data.mensaje, "FALLO");
                   }
       }, 'json');

    }
 //*******************************inmunizaciones *************************************
	 $("#agregarInmunizacion").click(function () {
  		    event.stopImmediatePropagation();
			var texto = "Por favor revise los campos obligatorios.";
			$(".alertaCombo").removeClass("alertaCombo");
			mostrarMensaje("", "FALLO");
			var error = false;
			if(!$.trim($("#id_tipo_procedimiento_medico_inmunizacion").val())){
	  			   $("#id_tipo_procedimiento_medico_inmunizacion").addClass("alertaCombo");
	  			   error = true;
	  		  }
			if(!$.trim($("#numero_dosis").val())){
	  			   $("#numero_dosis").addClass("alertaCombo");
	  			   error = true;
	  		  }
			if(!$.trim($("#fecha_ultima_dosis").val())){
	  			   $("#fecha_ultima_dosis").addClass("alertaCombo");
	  			   error = true;
	  		  }
      	
          	if (!error) {
			  	  $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/agregarInmunizacion", 
		                  {
			  		         id_historia_clinica:id_historia_clinica,
			  		         id_tipo_procedimiento_medico:$("#id_tipo_procedimiento_medico_inmunizacion").val(),
			  		         numero_dosis:$("#numero_dosis").val(),
			  		         fecha_ultima_dosis: $("#fecha_ultima_dosis").val()
			  		         
		                  }, function (data) {
		                  	if (data.estado === 'EXITO') {
		                  		$("#listaInmunizacion").html(data.contenido);
		               		    $("#id_tipo_procedimiento_medico_inmunizacion").val('');
		               		    $("#numero_dosis").val('');
		               		    $("#fecha_ultima_dosis").val('');
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
  // eliminar Inmunizacion
 function eliminarInmunizacion(id){
     $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/eliminarInmunizacion", 
             {
                id_historia_clinica: id_historia_clinica,
                id_inmunizacion: id
	  		         		  		     
             }, function (data) {
             	if (data.estado === 'EXITO') {
             		    $("#listaInmunizacion").html(data.contenido);
             		    $("#id_tipo_procedimiento_medico_inmunizacion").val('');
              		    $("#numero_dosis").val('');
              		    $("#fecha_ultima_dosis").val('');
	                    mostrarMensaje(data.mensaje, data.estado);
	                    distribuirLineas();
                 } else {
                 	mostrarMensaje(data.mensaje, "FALLO");
                 }
     }, 'json');

  }


 //********habitos *************************************** 
 $("#id_tipo_procedimiento_medico_habitos").change(function () {
	  if($("#id_tipo_procedimiento_medico_habitos").val() && $("#id_tipo_procedimiento_medico_habitos option:selected").text() != 'Ninguno'){
        	  $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/buscarHabitos", 
                     {
                        id_historia_clinica:id_historia_clinica,
                        id_tipo_procedimiento_medico: $("#id_tipo_procedimiento_medico_habitos").val(),
                        tipo: $("#id_tipo_procedimiento_medico_habitos option:selected").text()
        	  		         		  		     
                     }, function (data) {
                     	if (data.estado === 'EXITO') {
                     		    $("#detalleHabitos").html(data.contenido);
                     		    $("#detalleHabitos").show();
        	                    mostrarMensaje(data.mensaje, data.estado);
        	                    distribuirLineas();
                         } else {
                         	mostrarMensaje(data.mensaje, "FALLO");
                         }
             }, 'json');
	  }else{
		  $("#detalleHabitos").html('');
		  $("#detalleHabitos").hide();
		  mostrarMensaje("", "FALLO");
		   }
    });
 //****************************************************************************************
       function agregarHabitos(){
   		event.stopImmediatePropagation();
			var texto = "Por favor revise los campos obligatorios.";
			$(".alertaCombo").removeClass("alertaCombo");
			mostrarMensaje("", "FALLO");
			var error = false;
			error = validarCamposHabitos($("#id_tipo_procedimiento_medico_habitos option:selected").text());
			if (!error) {
			  	  $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/agregarHabitos", 
		                  {
			  		         id_historia_clinica:id_historia_clinica,
			  		         id_tipo_procedimiento_medico:$("#id_tipo_procedimiento_medico_habitos").val(),
			  		         habito:$("input[name='habito[]']").map(function(){ if($(this).prop("checked")){return $(this).val();}}).get(),
			  		         frecuencia_habito:$("#frecuencia_habito").val(),
			  		         anios_habito:$("#anios_habito").val(),
			  		         observaciones:$("#observaciones_habito").val(),
			  		         sustancias:$("#sustancias").val(),
		                  }, function (data) {
		                  	if (data.estado === 'EXITO') {
		                  		$("#listaHabitos").html(data.contenido);
		                  		$("#detalleHabitos").html('');
		           		        $("#detalleHabitos").hide();
			                    mostrarMensaje(data.mensaje, data.estado);
			                    distribuirLineas();
		                      } else {
		                      	mostrarMensaje(data.mensaje, "FALLO");
		                      }
		          }, 'json');
			} else {
				mostrarMensaje(texto, "FALLO");
			}
   }
   // eliminar habitos
  function eliminarHabitos(id){
      $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/eliminarHabitos", 
              {
                 id_historia_clinica: id_historia_clinica,
                 id_habitos: id
	  		         		  		     
              }, function (data) {
              	if (data.estado === 'EXITO') {
              		    $("#listaHabitos").html(data.contenido);
	                    mostrarMensaje(data.mensaje, data.estado);
	                    distribuirLineas();
                  } else {
                  	mostrarMensaje(data.mensaje, "FALLO");
                  }
      }, 'json');

   }
	//*****************verificar campos habitos***********************************
	function validarCamposHabitos(opt){
		var error1 = false;
		switch (opt) { 
		case 'Alcohol': 
    		if(!$.trim($("#id_tipo_procedimiento_medico_habitos").val())){
    			   $("#id_tipo_procedimiento_medico_habitos").addClass("alertaCombo");
    			   error1 = true;
    		  }
    		if(!$("input[name='habito[]']").is(':checked') ){
	  			 texto = "Debe seleccionar un subtipo de exposición !!.";
	  			$(".habitoRadio").addClass("alertaCombo");
	  			 error1 = true;
		  		}
    		var habito = $("input[name='habito[]']").map(function(){ if($(this).prop("checked")){return $(this).val();}}).get();
		    if(habito != 'No Consumidor') {    
    			if(!$.trim($("#frecuencia_habito").val())){
    	  			   $("#frecuencia_habito").addClass("alertaCombo");
    	  			   error1 = true;
    	  		  }
    			if(!$.trim($("#anios_habito").val())){
    	  			   $("#anios_habito").addClass("alertaCombo");
    	  			   error1 = true;
    	  		  }
    			
		    }
			break;
		case 'Cigarrillo/Tabaco/Pipa': 
			if(!$.trim($("#id_tipo_procedimiento_medico_habitos").val())){
 			   $("#id_tipo_procedimiento_medico_habitos").addClass("alertaCombo");
 			   error1 = true;
 		      }
			if(!$("input[name='habito[]']").is(':checked') ){
	  			 texto = "Debe seleccionar un subtipo de exposición !!.";
	  			$(".habitoRadio").addClass("alertaCombo");
	  			 error1 = true;
		  		}
			var habito = $("input[name='habito[]']").map(function(){ if($(this).prop("checked")){return $(this).val();}}).get();
		    if(habito == 'Fumador actual' || habito == 'Exfumador') { 
    			if(!$.trim($("#frecuencia_habito").val())){
    	  			   $("#frecuencia_habito").addClass("alertaCombo");
    	  			   error1 = true;
    	  		  }
		    }
			break;
		case 'Otras Sustancias Psicoactivas':
			if(!$.trim($("#id_tipo_procedimiento_medico_habitos").val())){
 			   $("#id_tipo_procedimiento_medico_habitos").addClass("alertaCombo");
 			   error1 = true;
 		      }
			if(!$.trim($("#sustancias").val())){
	  			   $("#sustancias").addClass("alertaCombo");
	  			   error1 = true;
	  		  }
			if(!$.trim($("#frecuencia_habito").val())){
	  			   $("#frecuencia_habito").addClass("alertaCombo");
	  			   error1 = true;
	  		  }
			if(!$.trim($("#anios_habito").val())){
	  			   $("#anios_habito").addClass("alertaCombo");
	  			   error1 = true;
	  		  }
			break;
		default:
			error1 = true;
	  		  
	       }
		  return error1;
	    
		}

	$(document).on('change',"input[name='habito[]']", function(){
		
		if($(this).val() == 'Consumidor actual' || $(this).val() == 'Exconsumidor'|| $(this).val() == 'Fumador actual' || $(this).val() == 'Exfumador'){
			$("#frecuencia_habito").removeAttr('disabled');
			$("#anios_habito").removeAttr('disabled');
			$("#observaciones_habito").removeAttr('disabled');
		}else{
			$("#frecuencia_habito").attr('disabled','disabled');
			$("#anios_habito").attr('disabled','disabled');
			$("#observaciones_habito").attr('disabled','disabled');
			$("#frecuencia_habito").val('');
			$("#anios_habito").val('');
			$("#observaciones_habito").val('');
			}
		if($(this).val() == 'Otros' || $(this).val() == 'No Fumador'){
			$("#observaciones_habito").removeAttr('disabled');
		}
		
	});
	$(document).on('change',"#sustancias", function(){
		if($(this).val() != '' ){
				$("#frecuencia_habito").removeAttr('disabled');
				$("#anios_habito").removeAttr('disabled');
			}else{
				$("#frecuencia_habito").attr('disabled','disabled');
				$("#anios_habito").attr('disabled','disabled');
				$("#frecuencia_habito").val('');
				$("#anios_habito").val('');
				}
	});

	 //*****************agregar actividades***********************************************************************
     $("#agregarActividad").click(function () {
		    event.stopImmediatePropagation();
			var texto = "Por favor revise los campos obligatorios.";
			$(".alertaCombo").removeClass("alertaCombo");
			mostrarMensaje("", "FALLO");
			var error = false;
			if(!$.trim($("#tipo_actividad").val())){
	  			   $("#tipo_actividad").addClass("alertaCombo");
	  			   error = true;
	  		  }
			if(!$.trim($("#frecuencia").val())){
	  			   $("#frecuencia").addClass("alertaCombo");
	  			   error = true;
	  		  }
			if (!error) {
			  	  $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/agregarActividad", 
		                  {
			  		         id_historia_clinica:id_historia_clinica,
			  		         tipo_actividad:$("#tipo_actividad").val(),
			  		         frecuencia:$("#frecuencia").val(),
			  		         observaciones:$("#observaciones_actividad").val()
		                  }, function (data) {
		                  	if (data.estado === 'EXITO') {
		                  		$("#listaActividades").html(data.contenido);
		                  		$("#tipo_actividad").val('');
		                  		$("#frecuencia").val('');
		                  		$("#observaciones_actividad").val('');
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
// eliminar actividades
function eliminarActividad(id){
   $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/eliminarActividad", 
           {
              id_historia_clinica: id_historia_clinica,
              id_estilo_vida: id
	  		         		  		     
           }, function (data) {
           	if (data.estado === 'EXITO') {
           		    $("#listaActividades").html(data.contenido);
	                    mostrarMensaje(data.mensaje, data.estado);
	                    distribuirLineas();
               } else {
               	mostrarMensaje(data.mensaje, "FALLO");
               }
   }, 'json');

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
     //*******************************************evaluacion primaria********
     function verificarEvaPrimaria(id,tipo,sub){
         if($("#"+id).val() == 'Si'){
        	 $("#No-"+tipo+"-"+sub).prop("checked", false);
             }else {
            	 $("#Si-"+tipo+"-"+sub).prop("checked", false);
                 }
     }
     //**************************************examenes clinicos**************
  $("#id_tipo_procedimiento_medico_exa_clinicos").change(function () {
	  if($("#id_tipo_procedimiento_medico_exa_clinicos").val()){
	  $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/buscarExamenesClinicos", 
              {
                 id_historia_clinica:id_historia_clinica,
                 id_tipo_procedimiento_medico: $("#id_tipo_procedimiento_medico_exa_clinicos").val(),
                 tipo: $("#id_tipo_procedimiento_medico_exa_clinicos option:selected").text()
	  		         		  		     
              }, function (data) {
              	if (data.estado === 'EXITO') {
              		    $("#detalleExamenesClinicos").html(data.contenido);
              		    $("#detalleExamenesClinicos").show();
              		    activarFecha();
	                    mostrarMensaje(data.mensaje, data.estado);
	                    distribuirLineas();
                  } else {
                  	mostrarMensaje(data.mensaje, "FALLO");
                  }
      }, 'json');
	  }else{
		  $("#detalleExamenesClinicos").html('');
		  $("#detalleExamenesClinicos").hide();
		   }
     });
  //****************************************************************************************
        function agregarExamenesClinicos(){
    		event.stopImmediatePropagation();
			var texto = "Por favor revise los campos obligatorios.";
			$(".alertaCombo").removeClass("alertaCombo");
			mostrarMensaje("", "FALLO");
			var error = false;
			if(!$.trim($("#fecha_examen").val())){
	  			   $("#fecha_examen").addClass("alertaCombo");
	  			   error = true;
	  		  }
			if(!$("input[name='estado_clinico_Check[]']").is(':checked') ){
	  			 texto = "Debe seleccionar un campo !!.";
	  			$("#bodyExamenesClinicos").addClass("alertaCombo");
	  			 error = true;
		  		}

 			$("input[name='estado_clinico_Check[]']").map(function(){ if($(this).prop("checked")){ 
 	 			if(!$.trim($("#s-"+$(this).attr("id")).val())){
     				$("#s-"+$(this).attr("id")).addClass("alertaCombo");
     			    error = true;
 	 			}
 	 			}}).get();
			if (!error) {
			  	  $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/agregarExamenesClinicos", 
		                  {
			  		         id_historia_clinica:id_historia_clinica,
			  		         id_tipo_procedimiento_medico:$("#id_tipo_procedimiento_medico_exa_clinicos").val(),
			  		         fecha_examen:$("#fecha_examen").val(),
			  		         estado_clinico:$("select[name='estado_clinico[]']").map(function(){ if($.trim($(this).val())){return $(this).attr("id")+'-'+$(this).val();}}).get(),
			  		         observaciones:$("input[name='observaciones_examen_clinico[]']").map(function(){ if($(this).val()){return $(this).attr("id")+'-'+$(this).val();}}).get()
								  	
		                  }, function (data) {
		                  	if (data.estado === 'EXITO') {
		                  		$("#listaExamenesClinicos").html(data.contenido);
		                  		$("#detalleExamenesClinicos").html('');
		           		        $("#detalleExamenesClinicos").hide();
		           		        $("#id_tipo_procedimiento_medico_exa_clinicos").val('');
			                    mostrarMensaje(data.mensaje, data.estado);
			                    distribuirLineas();
		                      } else {
		                      	mostrarMensaje(data.mensaje, "FALLO");
		                      }
		          }, 'json');
			} else {
				mostrarMensaje(texto, "FALLO");
			}
    }
    // eliminar examenes clinicos
   function eliminarExamenesClinicos(id){
       $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/eliminarExamenesClinicos", 
               {
                  id_historia_clinica: id_historia_clinica,
                  id_detalle_examenes_clinicos: id
               }, function (data) {
               	if (data.estado === 'EXITO') {
               		    $("#listaExamenesClinicos").html(data.contenido);
	                    mostrarMensaje(data.mensaje, data.estado);
	                    distribuirLineas();
                   } else {
                   	mostrarMensaje(data.mensaje, "FALLO");
                   }
       }, 'json');

    } 

   //********************verificar opcion******
   function verificarExaClinicos(id){
	  if($("#"+id).prop("checked")){
			$("#s-"+id).removeAttr('disabled');
			$("#t-"+id).removeAttr('disabled');
		 }else{
			$("#s-"+id).attr('disabled','disabled');
			$("#t-"+id).attr('disabled','disabled');
			$("#s-"+id).val('');
			$("#t-"+id).val('');
			 }   
      }
   function activarFecha(){
	   $("#fecha_examen").datepicker({
	    	yearRange: "c:c",
	    	changeMonth: false,
	        changeYear: false,
	        dateFormat: 'yy-mm-dd',
	      });
   }
//****************************subir documentos adjuntos examenes clinicos***********
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
	   	   var url = "<?php echo URL ?>HistoriasClinicas/historiaClinica/agregarDocumentosAdjuntos";
	   	   var get = "?id_historia_clinica="+id_historia_clinica+"&descripcion_adjunto="+$("#descripcion_adjunto").val();
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
								$("#listaAdjuntosHistoria").html(obj.contenido);
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
           estado.html("En espera de archivo... (Tamaño máximo < ?php echo ini_get('upload_max_filesize'); ? >B)");
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

//**************************examenes paraclinicos*****************************
 $("#id_tipo_procedimiento_medico_paraclinico").change(function () {
	  if($("#id_tipo_procedimiento_medico_paraclinico").val()  && $("#id_tipo_procedimiento_medico_paraclinico option:selected").text() != 'Ninguno'){
	  $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/buscarParaclinicos", 
              {
                 id_historia_clinica:id_historia_clinica,
                 id_tipo_procedimiento_medico: $("#id_tipo_procedimiento_medico_paraclinico").val(),
                 tipo: $("#id_tipo_procedimiento_medico_paraclinico option:selected").text()
	  		         		  		     
              }, function (data) {
              	if (data.estado === 'EXITO') {
              		    $("#detalleParaclinicos").html(data.contenido);
              		    $("#detalleParaclinicos").show();
	                    mostrarMensaje(data.mensaje, data.estado);
	                    distribuirLineas();
                  } else {
                  	mostrarMensaje(data.mensaje, "FALLO");
                  }
      }, 'json');
	  }else{
		  $("#detalleParaclinicos").html('');
		  $("#detalleParaclinicos").hide();
		   }
     });
  //****************************************************************************************
        function agregarParaclinicos(){
    		event.stopImmediatePropagation();
			var texto = "Por favor revise los campos obligatorios.";
			$(".alertaCombo").removeClass("alertaCombo");
			mostrarMensaje("", "FALLO");
			var error = false;
			if(!$.trim($("#id_tipo_procedimiento_medico_paraclinico").val())){
	  			   $("#id_tipo_procedimiento_medico_paraclinico").addClass("alertaCombo");
	  			   error = true;
	  		  }
			if(!$("input[name='respuesta_check[]']").is(':checked') ){
	  			 texto = "Debe seleccionar un campo !!.";
	  			$("#bodyParaclinicos").addClass("alertaCombo");
	  			 error = true;
		  		}
	  		if($("#id_tipo_procedimiento_medico_paraclinico option:selected").text() == 'Audiometría'){
	  			if(!$("input[name='oido_check[]']").is(':checked') ){
		  			 texto = "Debe seleccionar un campo !!.";
		  			$("#bodyParaclinicos").addClass("alertaCombo");
		  			 error = true;
			  		}

		  		}
			if (!error) {
			  	  $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/agregarParaclinicos", 
		                  {
			  		         id_historia_clinica:id_historia_clinica,
			  		         id_tipo_procedimiento_medico:$("#id_tipo_procedimiento_medico_paraclinico").val(),
			  		         observaciones:$("#observaciones_paraclinicos").val(),
			  		         respuesta_check:$("input[name='respuesta_check[]']").map(function(){ if($(this).prop("checked")){return $(this).attr("id")+'-'+$(this).val();}}).get(),
			  		         oido_check:$("input[name='oido_check[]']").map(function(){ if($(this).prop("checked")){return $(this).attr("id")+'-'+$(this).val();}}).get()
			  		         
		                  }, function (data) {
		                  	if (data.estado === 'EXITO') {
		                  		$("#listaParaclinicos").html(data.contenido);
		                  		$("#detalleParaclinicos").html('');
		           		        $("#detalleParaclinicos").hide();
		           		        $("#id_tipo_procedimiento_medico_paraclinico").val('');
			                    mostrarMensaje(data.mensaje, data.estado);
			                    distribuirLineas();
		                      } else {
		                      	mostrarMensaje(data.mensaje, "FALLO");
		                      }
		          }, 'json');
			} else {
				mostrarMensaje(texto, "FALLO");
			}
    }
    // eliminar examenes paraclinicos
   function eliminarParaclinicos(id){
       $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/eliminarParaclinicos", 
               {
                  id_historia_clinica: id_historia_clinica,
                  id_examen_paraclinicos: id
	  		         		  		     
               }, function (data) {
               	if (data.estado === 'EXITO') {
               		    $("#listaParaclinicos").html(data.contenido);
	                    mostrarMensaje(data.mensaje, data.estado);
	                    distribuirLineas();
                   } else {
                   	mostrarMensaje(data.mensaje, "FALLO");
                   }
       }, 'json');

    } 
   function verificarRespuestaParacli(id,tipo,sub){
       if($("#"+id).val() == 'Si'){
      	 $("#n-"+tipo+"-"+sub).prop("checked", false);
           }else {
          	 $("#s-"+tipo+"-"+sub).prop("checked", false);
               }
   }
   function verificarOidoParaclinicos(id,tipo,sub){
       if($("#"+id).val() == 'Derecho'){
      	 $("#i-"+tipo+"-"+sub).prop("checked", false);
      	 $("#b-"+tipo+"-"+sub).prop("checked", false);
      }
      if($("#"+id).val() == 'Izquierdo') {
    	 $("#d-"+tipo+"-"+sub).prop("checked", false);
       	 $("#b-"+tipo+"-"+sub).prop("checked", false);
          }
      if($("#"+id).val() == 'Bilateral'){
        	$("#d-"+tipo+"-"+sub).prop("checked", false);
            $("#i-"+tipo+"-"+sub).prop("checked", false);
              }
   }

   //**************************impresion diagnosticada****************************
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
	  			 texto = "Debe seleccionar un campo !!.";
	  			$("input[name='estado_diagnostico[]']").addClass("alertaCombo");
	  			 error = true;
		  		}
			if(!$.trim($("#diagnostico").val())){
	  			   $("#diagnostico").addClass("alertaCombo");
	  			   error = true;
	  		  }
	  		
			if (!error) {
			  	  $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/agregarDiagnostico", 
		                  {
			  		         id_historia_clinica:id_historia_clinica,
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
         // eliminar impresion diagnosticada
        function eliminarDiagnostico(id){
            $.post("<?php echo URL ?>HistoriasClinicas/historiaClinica/eliminarDiagnostico", 
                    {
                       id_historia_clinica: id_historia_clinica,
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
</script>
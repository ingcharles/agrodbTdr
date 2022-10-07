<script
	src="<?php echo URL ?>modulos/InspeccionAntePostMortemCF/vistas/js/funcionCf.js"></script>
<form id='formularioDetalleAnteAves'>
	<input type="hidden" id="id_detalle_ante_aves"
		name="id_detalle_ante_aves"
		value="<?php echo $this->arrayDetalleFormulario->id_detalle_ante_aves;?>" />
	<input type="hidden" id="id_formulario_ante_mortem"
		name="id_formulario_ante_mortem"
		value="<?php echo $this->arrayDetalleFormulario->id_formulario_ante_mortem;?>" />
	<input type="hidden" id="id_hallazgos_aves_muertas"
		name="id_hallazgos_aves_muertas"
		value="<?php echo $this->arrayDetalleFormulario->id_hallazgos_aves_muertas;?>" />
	<input type="hidden" id="id_hallazgos_aves_caract"
		name="id_hallazgos_aves_caract"
		value="<?php echo $this->arrayDetalleFormulario->id_hallazgos_aves_caract;?>" />
	<input type="hidden" id="id_hallazgos_aves_sistematicos"
		name="id_hallazgos_aves_sistematicos"
		value="<?php echo $this->arrayDetalleFormulario->id_hallazgos_aves_sistematicos;?>" />
	<input type="hidden" id="id_hallazgos_aves_externas"
		name="id_hallazgos_aves_externas"
		value="<?php echo $this->arrayDetalleFormulario->id_hallazgos_aves_externas;?>" />

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
					<label for="" class="col-lg-4 control-label">Nro. de Aves (TOTAL):
					</label>
					<div class="col-lg-8">
						<input type="text" id="total_aves_detalle"
							name="total_aves_detalle"
							value="<?php echo $this->arrayDetalleFormulario['total_aves']; ?>"
							class="form-control" placeholder="Total de aves" maxlength="8" />
					</div>
				</div>
			</div>
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Tipo de ave: </label>
					<div class="col-lg-8">
						<select id="tipo_ave_detalle" name="tipo_ave_detalle"
							class="form-control"> 
            		<?php
														echo $this->comboEspecie;
														?>
        		</select>
					</div>
				</div>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Peso promedio de aves
						(kg): </label>
					<div class="col-lg-8">
						<input type="text" id="promedio_aves_detalle"
							name="promedio_aves_detalle" class="form-control"
							value="<?php echo $this->arrayDetalleFormulario['promedio_aves']; ?>"
							placeholder="Peso promedio de aves" maxlength="8" />
					</div>
				</div>
			</div>
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Lugar de procedencia
						(Granja): </label>
					<div class="col-lg-8">
						<input type="text" id="lugar_procedencia_detalle"
							name="lugar_procedencia_detalle" class="form-control"
							value="<?php echo $this->arrayDetalleFormulario['lugar_procedencia']; ?>"
							placeholder="Lugar de procedencia" maxlength="64" />
					</div>
				</div>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Existen hallazgos: </label>
					<div class="col-lg-8">
						<select id="hallazgos_detalle" name="hallazgos_detalle"
							class="form-control">
							<?php
							echo $this->comboSiNo($this->arrayDetalleFormulario['hallazgos']);
							?>
						</select>
					</div>
				</div>
			</div>
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Nro. de CSM: </label>
					<div class="col-lg-8">
						<input type="text" id="num_csmi_detalle" name="num_csmi_detalle"
							value="<?php echo $this->arrayDetalleFormulario['num_csmi']; ?>"
							class="form-control" placeholder="Número de CSMI" maxlength="8" />
					</div>
				</div>
			</div>
		</div>
	</fieldset>

	<fieldset id="avesMuertasDetalle">
		<legend>Aves Muertas</legend>
		<div class="form-horizontal">
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Nro. de aves muertas
						(al arribo): </label>
					<div class="col-lg-8">
						<input type="text" id="aves_muertas_detalle"
							name="aves_muertas_detalle"
							value="<?php echo $this->modeloHallazgosAvesMuertas->getAvesMuertas();?>"
							class="form-control" placeholder="Número de aves muertas"
							maxlength="8" />
					</div>
				</div>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">% de aves muertas: </label>
					<div class="col-lg-8">
						<input type="text" id="porcent_aves_muertas_detalle"
							name="porcent_aves_muertas_detalle" class="form-control"
							value="<?php echo $this->modeloHallazgosAvesMuertas->getPorcentAvesMuertas();?>"
							placeholder="Porcentaje de aves muertas" maxlength="8" />
					</div>
				</div>
			</div>
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Causa probable: </label>
					<div class="col-lg-8">
						<input type="text" id="causa_probable_detalle"
							name="causa_probable_detalle" class="form-control"
							value="<?php echo $this->modeloHallazgosAvesMuertas->getCausaProbable();?>"
							placeholder="Causa probable" maxlength="1024" />
					</div>
				</div>
			</div>
		</div>
	</fieldset>

	<fieldset id="caracteristicasDetalle">
		<legend>Características</legend>
		<div class="form-horizontal">
			<div class='row'>




				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Nro. de aves decaídas
						o moribundas: </label>
					<div class="col-lg-8">
						<input type="text" id="decaidas_detalle" name="decaidas_detalle"
							value="<?php echo $this->modeloHallazgosAvesCaract->getDecaidas();?>"
							class="form-control" placeholder="Aves llegaron decaídas"
							maxlength="8" />
					</div>
				</div>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">% de aves decaídas o
						moribundas: </label>
					<div class="col-lg-8">
						<input type="text" id="porcent_decaidas_detalle"
							name="porcent_decaidas_detalle" class="form-control"
							value="<?php echo $this->modeloHallazgosAvesCaract->getPorcentDecaidas();?>"
							placeholder="Porcentaje de aves que llegaron decaídas"
							maxlength="8" />
					</div>
				</div>
			</div>
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Nro. de aves con
						traumas: </label>
					<div class="col-lg-8">
						<input type="text" id="num_traumas_detalle"
							name="num_traumas_detalle"
							value="<?php echo $this->modeloHallazgosAvesCaract->getNumTraumas();?>"
							class="form-control" placeholder="Aves con traumas" maxlength="8" />
					</div>
				</div>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">% de aves traumas: </label>
					<div class="col-lg-8">
						<input type="text" id="porcent_traumas_detalle"
							name="porcent_traumas_detalle"
							value="<?php echo $this->modeloHallazgosAvesCaract->getPorcentTraumas();?>"
							class="form-control"
							placeholder="Porcentaje de aves que llegaron con traumas"
							maxlength="8" />
					</div>
				</div>
			</div>
		</div>
	</fieldset>
	<fieldset id="problemasSistemicosDetalle">
		<legend>Problemas sistémicos</legend>
		<div class="form-horizontal">
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Nro. de aves con
						problemas respiratorios: </label>
					<div class="col-lg-8">
						<input type="text" id="probl_respirat_detalle"
							name="probl_respirat_detalle"
							value="<?php echo $this->modeloHallazgosSistematicos->getProblRespirat();?>"
							class="form-control"
							placeholder="Aves con problemas respiratorios" maxlength="8" />
					</div>
				</div>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">% de aves con
						problemas respiratorios: </label>
					<div class="col-lg-8">
						<input type="text" id="porcent_probl_respirat_detalle"
							name="porcent_probl_respirat_detalle"
							value="<?php echo $this->modeloHallazgosSistematicos->getPorcentProblRespirat();;?>"
							class="form-control"
							placeholder="Porcentaje de aves con problemas respiratorios"
							maxlength="8" />
					</div>
				</div>
			</div>
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Nro. de aves con
						problemas nerviosos: </label>
					<div class="col-lg-8">
						<input type="text" id="probl_nerviosos_detalle"
							name="probl_nerviosos_detalle"
							value="<?php echo $this->modeloHallazgosSistematicos->getProblNerviosos();?>"
							class="form-control" placeholder="Aves con problemas nerviosos"
							maxlength="8" />
					</div>
				</div>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">% de aves con
						problemas nerviosos: </label>
					<div class="col-lg-8">
						<input type="text" id="porcent_proble_nerviosos_detalle"
							name="porcent_proble_nerviosos_detalle" class="form-control"
							value="<?php echo $this->modeloHallazgosSistematicos->getPorcentProbleNerviosos();?>"
							placeholder="Porcentaje de aves con problemas nerviosos" />
					</div>
				</div>
			</div>
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Nro. de aves con
						problemas digestivos: </label>
					<div class="col-lg-8">
						<input type="text" id="probl_digestivos_detalle"
							name="probl_digestivos_detalle"
							value="<?php echo $this->modeloHallazgosSistematicos->getProblDigestivos();?>"
							class="form-control" placeholder="Aves con problemas digestivos"
							maxlength="8" />
					</div>
				</div>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">% de aves con
						problemas digestivos: </label>
					<div class="col-lg-8">
						<input type="text" id="porcent_probl_digestivos_detalle"
							name="porcent_probl_digestivos_detalle" class="form-control"
							value="<?php echo $this->modeloHallazgosSistematicos->getPorcentProblDigestivos();?>"
							placeholder="Porcentaje de aves con problemas digestivos"
							maxlength="8" />
					</div>
				</div>
			</div>
		</div>
	</fieldset>
	<fieldset id="caracteristicasExternasDetalle">
		<legend>Características externas</legend>
		<div class="form-horizontal">
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Nro. de aves con
						cabeza hinchada: </label>
					<div class="col-lg-8">
						<input type="text" id="cabeza_hinchada_detalle"
							name="cabeza_hinchada_detalle" class="form-control"
							value="<?php echo $this->modeloHallazgosAvesExternas->getCabezaHinchada();?>"
							placeholder="Aves con cabeza hinchada" maxlength="8" size="50" />
					</div>
				</div>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">% de aves con con
						cabeza hinchada: </label>
					<div class="col-lg-8">
						<input type="text" id="porcent_cabeza_hinchada_detalle"
							class="form-control" name="porcent_cabeza_hinchada_detalle"
							value="<?php echo $this->modeloHallazgosAvesExternas->getPorcentCabezaHinchada();?>"
							placeholder="Porcentaje de aves con cabeza hinchada"
							maxlength="8" />
					</div>
				</div>
			</div>
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Nro. de aves con
						plumas erizadas: </label>
					<div class="col-lg-8">
						<input type="text" id="plumas_erizadas_detalle"
							name="plumas_erizadas_detalle" class="form-control"
							value="<?php echo $this->modeloHallazgosAvesExternas->getPlumasErizadas();?>"
							placeholder="Aves con plumas erizadas" maxlength="8" />
					</div>
				</div>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">% de aves con plumas
						erizadas: </label>
					<div class="col-lg-8">
						<input type="text" id="porcent_plumas_erizadas_detalle"
							name="porcent_plumas_erizadas_detalle"
							value="<?php echo $this->modeloHallazgosAvesExternas->getPorcentPlumasErizadas();?>"
							class="form-control"
							placeholder="Porcentaje de aves con plumas erizadas"
							maxlength="8" />
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
					<label for="" class="col-lg-4 control-label">Faenamiento normal (
						Nro. de aves ): </label>
					<div class="col-lg-8">
						<input type="text" id="faenamiento_normal_detalle"
							name="faenamiento_normal_detalle"
							value="<?php echo $this->arrayDetalleFormulario['faenamiento_normal']; ?>"
							class="form-control"
							placeholder="Aves recibirán fenamiento normal" maxlength="8" />
					</div>
				</div>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Faenamiento normal ( %
						de aves ): </label>
					<div class="col-lg-8">
						<input type="text" id="procent_faenamiento_normal_detalle"
							value="<?php echo $this->arrayDetalleFormulario['procent_faenamiento_normal']; ?>"
							name="procent_faenamiento_normal_detalle" class="form-control"
							placeholder="Porcentaje aves recibirán fenamiento normal"
							maxlength="8" />
					</div>
				</div>
			</div>
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Faenamiento bajo
						precauciones especiales ( Nro. de aves ): </label>
					<div class="col-lg-8">
						<input type="text" id="faenamiento_especial_detalle"
							value="<?php echo $this->arrayDetalleFormulario['faenamiento_especial']; ?>"
							name="faenamiento_especial_detalle" class="form-control"
							placeholder="Aves recibirán fenamiento bajo precauciones especiales"
							maxlength="8" />
					</div>
				</div>

				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Faenamiento bajo
						precauciones especiales ( % de aves ): </label>
					<div class="col-lg-8">
						<input type="text" id="porcent_faenamiento_especial_detalle"
							value="<?php echo $this->arrayDetalleFormulario['porcent_faenamiento_especial']; ?>"
							name="porcent_faenamiento_especial_detalle" class="form-control"
							placeholder="Porcentaje aves recibirán fenamiento bajo precauciones especiales"
							maxlength="8" />
					</div>
				</div>
			</div>
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Faenamiento de
						emergencia ( Nro. de aves ): </label>
					<div class="col-lg-8">
						<input type="text" id="faenamiento_emergencia_detalle"
							name="faenamiento_emergencia_detalle"
							value="<?php echo $this->arrayDetalleFormulario['faenamiento_emergencia']; ?>"
							class="form-control"
							placeholder="Aves recibirán faenmaiento de emergencia"
							maxlength="8" />
					</div>
				</div>

				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Faenamiento de
						emergencia ( % de aves ): </label>
					<div class="col-lg-8">
						<input type="text" id="porcent_emergencia_detalle"
							name="porcent_emergencia_detalle"
							value="<?php echo $this->arrayDetalleFormulario['porcent_emergencia']; ?>"
							class="form-control"
							placeholder="Porcentaje aves recibirán faenmaiento de emergencia"
							maxlength="8" />
					</div>
				</div>
			</div>
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Aplazamiento de
						faenamiento ( Nro. de aves ): </label>
					<div class="col-lg-8">
						<input type="text" id="aplazamiento_faenamiento_detalle"
							name="aplazamiento_faenamiento_detalle"
							value="<?php echo $this->arrayDetalleFormulario['aplazamiento_faenamiento']; ?>"
							class="form-control" placeholder="Aplazamiento del faenamiento"
							maxlength="8" />
					</div>
				</div>

				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">Aplazamiento de
						faenamiento ( % de aves ) </label>
					<div class="col-lg-8">
						<input type="text" id="porcent_aplazamiento_faenamiento_detalle"
							name="porcent_aplazamiento_faenamiento_detalle"
							value="<?php echo $this->arrayDetalleFormulario['porcent_aplazamiento_faenamiento']; ?>"
							class="form-control"
							placeholder="Porcentaje del aplazamiento del faenamiento"
							maxlength="8" />
					</div>
				</div>
			</div>
			<div class='row'>
				<div class="form-group col-md-6">
					<label for="" class="col-lg-4 control-label">TOTAL </label>
					<div class="col-lg-8">
						<input type="text" id="total_faenamiento_detalle"
							name="total_faenamiento_detalle"
							value="<?php echo $this->arrayDetalleFormulario['total_faenamiento']; ?>"
							class="form-control" placeholder="Suma total del dictamen"
							readonly maxlength="8" />
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
							value="<?php echo $this->arrayDetalleFormulario['observacion']; ?>"
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
		var perfilUsuario = <?php echo json_encode($this->perfilUsuario);?>;
	    var fechaInical = <?php echo json_encode($this->fechaInicial);?>;
	    var fechaActual = <?php echo json_encode($this->arrayDetalleFormulario['fecha_formulario']);?>;
	    var hallazgos = <?php echo json_encode($this->arrayDetalleFormulario['hallazgos']);?>;
		distribuirLineas();
		establecerFechas('fecha_formulario_detalle', fechaActual);
		setearVariablesInicialesDetalle();
		$("#estadoDetalle").html("").addClass("alerta");
		if(hallazgos == 'No'){
			$("#avesMuertasDetalle").hide();
		    $("#caracteristicasDetalle").hide();
		    $("#problemasSistemicosDetalle").hide();
		    $("#caracteristicasExternasDetalle").hide();
		}
		if(estadoRegistro == 'Aprobado_AM'){
			  $("#agregarFormularioDetalle").hide();
			  bloquearCampos();
	       }
	    if(perfilUsuario == 'PFL_APM_CF_OPA'){
	    	if(estadoRegistro == 'Por revisar'){
				  $("#agregarFormularioDetalle").hide();
				  bloquearCampos();
		       }   
	    }
	 });


	//verificar campo total
    $("#total_aves_detalle").change(function () {
    	if(!$.trim($(this).val())){
    		setearVariablesVaciarDetalle();
    	}
    });
    //verificar que campo esta vacio
    $( "#total_aves_detalle" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearVariablesVaciarDetalle();
    	}
    });
//*****************************************************************************************************
 //validar el ingreso de información de aves en cantidad
    $("#aves_muertas_detalle").change(function () {
    	validarIngresoInfo("aves_muertas_detalle","porcent_aves_muertas_detalle","total_aves_detalle",1,'detalle');
    });
    //verificar que campo esta vacio
    $( "#aves_muertas_detalle" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_aves_muertas_detalle", 2);
    	}
    });
    //validar el ingreso de información de aves en porcentaje
    $("#porcent_aves_muertas_detalle").change(function () {
    	validarIngresoInfo("aves_muertas_detalle","porcent_aves_muertas_detalle","total_aves_detalle",2,'detalle');
    });
    //verificar que campo esta vacio
    $( "#porcent_aves_muertas_detalle" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("aves_muertas_detalle", 2);
    	}
    });
//*****************************************************************************************************
//validar el ingreso de información de aves decaidas_detalle en cantidad
    $("#decaidas_detalle").change(function () {
    	validarIngresoInfo("decaidas_detalle","porcent_decaidas_detalle","total_aves_detalle",1,'detalle');
    });
    //verificar que campo esta vacio
    $( "#decaidas_detalle" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_decaidas_detalle", 2);
    	}
    });
    //validar el ingreso de información de aves decaidas_detalle en porcentaje
    $("#porcent_decaidas_detalle").change(function () {
    	validarIngresoInfo("decaidas_detalle","porcent_decaidas_detalle","total_aves_detalle",2,'detalle');
    });
    //verificar que campo esta vacio
    $( "#porcent_decaidas_detalle" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("decaidas_detalle", 2);
    	}
    });
//*****************************************************************************************************    
 //validar el ingreso de información de aves con traumas en cantidad
    $("#num_traumas_detalle").change(function () {
    	validarIngresoInfo("num_traumas_detalle","porcent_traumas_detalle","total_aves_detalle",1,'detalle');
    });
    //verificar que campo esta vacio
    $( "#num_traumas_detalle" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_traumas_detalle", 2);
    	}
    });
    //validar el ingreso de información de aves con traumas en porcentaje
    $("#porcent_traumas_detalle").change(function () {
    	validarIngresoInfo("num_traumas_detalle","porcent_traumas_detalle","total_aves_detalle",2,'detalle');
    });
    //verificar que campo esta vacio
    $( "#porcent_traumas_detalle" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("num_traumas_detalle", 2);
    	}
    });
//*****************************************************************************************************	
//validar el ingreso de información de aves con problemas respiratorios en cantidad
    $("#probl_respirat_detalle").change(function () {
    	validarIngresoInfo("probl_respirat_detalle","porcent_probl_respirat_detalle","total_aves_detalle",1,'detalle');
    });
    //verificar que campo esta vacio
    $( "#probl_respirat_detalle" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_probl_respirat_detalle", 2);
    	}
    });
    //validar el ingreso de información de aves con problemas respiratorios en porcentaje
    $("#porcent_probl_respirat_detalle").change(function () {
    	validarIngresoInfo("probl_respirat_detalle","porcent_probl_respirat_detalle","total_aves_detalle",2,'detalle');
    });
    //verificar que campo esta vacio
    $( "#porcent_probl_respirat_detalle" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("probl_respirat_detalle", 2);
    	}
    });
//*****************************************************************************************************
//validar el ingreso de información de aves con problemas nerviosos en cantidad
    $("#probl_nerviosos_detalle").change(function () {
    	validarIngresoInfo("probl_nerviosos_detalle","porcent_proble_nerviosos_detalle","total_aves_detalle",1,'detalle');
    });
    //verificar que campo esta vacio
    $( "#probl_nerviosos_detalle" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_proble_nerviosos_detalle", 2);
    	}
    });
    //validar el ingreso de información de aves con problemas nerviosos en porcentaje
    $("#porcent_proble_nerviosos_detalle").change(function () {
    	validarIngresoInfo("probl_nerviosos_detalle","porcent_proble_nerviosos_detalle","total_aves_detalle",2,'detalle');
    });
    //verificar que campo esta vacio
    $( "#porcent_proble_nerviosos_detalle" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("probl_nerviosos_detalle", 2);
    	}
    });	
//*****************************************************************************************************
//validar el ingreso de información de aves con problemas digestivos en cantidad
    $("#probl_digestivos_detalle").change(function () {
    	validarIngresoInfo("probl_digestivos_detalle","porcent_probl_digestivos_detalle","total_aves_detalle",1,'detalle');
    });
    //verificar que campo esta vacio
    $( "#probl_digestivos_detalle" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_probl_digestivos_detalle", 2);
    	}
    });
    //validar el ingreso de información de aves con problemas digestivos en porcentaje
    $("#porcent_probl_digestivos_detalle").change(function () {
    	validarIngresoInfo("probl_digestivos_detalle","porcent_probl_digestivos_detalle","total_aves_detalle",2,'detalle');
    });
    //verificar que campo esta vacio
    $( "#porcent_probl_digestivos_detalle" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("probl_digestivos_detalle", 2);
    	}
    });	
//*****************************************************************************************************
//validar el ingreso de información de aves con cabeza hinchada en cantidad
    $("#cabeza_hinchada_detalle").change(function () {
    	validarIngresoInfo("cabeza_hinchada_detalle","porcent_cabeza_hinchada_detalle","total_aves_detalle",1,'detalle');
    });
    //verificar que campo esta vacio
    $( "#cabeza_hinchada_detalle" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_cabeza_hinchada_detalle", 2);
    	}
    });
    //validar el ingreso de información de aves con cabeza hinchada en porcentaje
    $("#porcent_cabeza_hinchada_detalle").change(function () {
    	validarIngresoInfo("cabeza_hinchada_detalle","porcent_cabeza_hinchada_detalle","total_aves_detalle",2,'detalle');
    });
    //verificar que campo esta vacio
    $( "#porcent_cabeza_hinchada_detalle" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("cabeza_hinchada_detalle", 2);
    	}
    });
//*****************************************************************************************************
    //validar el ingreso de información de aves con plumas erizadas en cantidad
    $("#plumas_erizadas_detalle").change(function () {
    	validarIngresoInfo("plumas_erizadas_detalle","porcent_plumas_erizadas_detalle","total_aves_detalle",1,'detalle');
    });
    //verificar que campo esta vacio
    $( "#plumas_erizadas_detalle" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_plumas_erizadas_detalle", 2);
    	}
    });
    //validar el ingreso de información de aves con plumas erizadas en porcentaje
    $("#porcent_plumas_erizadas_detalle").change(function () {
    	validarIngresoInfo("plumas_erizadas_detalle","porcent_plumas_erizadas_detalle","total_aves_detalle",2,'detalle');
    });
    //verificar que campo esta vacio
    $( "#porcent_plumas_erizadas_detalle" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("plumas_erizadas_detalle", 2);
    	}
    });
//*****************************************************************************************************    
  //validar el ingreso de información de aves faenamiento normal en cantidad
    $("#faenamiento_normal_detalle").change(function () {
    	validarIngresoInfo("faenamiento_normal_detalle","procent_faenamiento_normal_detalle","total_aves_detalle",1,'detalle');
    	sumarDictamenDetalle("faenamiento_normal_detalle");
    });
    //verificar que campo esta vacio
    $( "#faenamiento_normal_detalle" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("procent_faenamiento_normal_detalle", 2);
    		sumarDictamenDetalle("faenamiento_normal_detalle");
    	}
    });
    //validar el ingreso de información de aves faenamiento normal en porcentaje
    $("#procent_faenamiento_normal_detalle").change(function () {
    	validarIngresoInfo("faenamiento_normal_detalle","procent_faenamiento_normal_detalle","total_aves_detalle",2,'detalle');
    	sumarDictamenDetalle("procent_faenamiento_normal_detalle");
    });
    //verificar que campo esta vacio
    $( "#procent_faenamiento_normal_detalle" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("faenamiento_normal_detalle", 2);
    		sumarDictamenDetalle("procent_faenamiento_normal_detalle");
    	}
    });
//*****************************************************************************************************
//validar el ingreso de información de aves faenamiento bajo precauciones especiales en cantidad
    $("#faenamiento_especial_detalle").change(function () {
    	validarIngresoInfo("faenamiento_especial_detalle","porcent_faenamiento_especial_detalle","total_aves_detalle",1,'detalle');
    	sumarDictamenDetalle("faenamiento_especial_detalle");
    });
    //verificar que campo esta vacio
    $( "#faenamiento_especial_detalle" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_faenamiento_especial_detalle", 2);
    		sumarDictamenDetalle("faenamiento_especial_detalle");
    	}
    });
    //validar el ingreso de información de aves faenamiento bajo precauciones especiales en porcentaje
    $("#porcent_faenamiento_especial_detalle").change(function () {
    	validarIngresoInfo("faenamiento_especial_detalle","porcent_faenamiento_especial_detalle","total_aves_detalle",2,'detalle');
    	sumarDictamenDetalle("porcent_faenamiento_especial_detalle");
    });
    //verificar que campo esta vacio
    $( "#porcent_faenamiento_especial_detalle" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("faenamiento_especial_detalle", 2);
    		sumarDictamenDetalle("porcent_faenamiento_especial_detalle");
    	}
    });
//*****************************************************************************************************
//validar el ingreso de información de aves faenamiento de emergencia en cantidad
    $("#faenamiento_emergencia_detalle").change(function () {
    	validarIngresoInfo("faenamiento_emergencia_detalle","porcent_emergencia_detalle","total_aves_detalle",1,'detalle');
    	sumarDictamenDetalle("faenamiento_emergencia_detalle");
    });
    //verificar que campo esta vacio
    $( "#faenamiento_emergencia_detalle" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_emergencia_detalle", 2);
    		sumarDictamenDetalle("faenamiento_emergencia_detalle");
    	}
    });
    //validar el ingreso de información de aves faenamiento de emergencia en porcentaje
    $("#porcent_emergencia_detalle").change(function () {
    	validarIngresoInfo("faenamiento_emergencia_detalle","porcent_emergencia_detalle","total_aves_detalle",2,'detalle');
    	sumarDictamenDetalle("porcent_emergencia_detalle");
    });
    //verificar que campo esta vacio
    $( "#porcent_emergencia_detalle" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("faenamiento_emergencia_detalle", 2);
    		sumarDictamenDetalle("porcent_emergencia_detalle");
    	}
    });
//*****************************************************************************************************
//validar el ingreso de información de aves aplazamiento de faenamiento en cantidad
    $("#aplazamiento_faenamiento_detalle").change(function () {
    	validarIngresoInfo("aplazamiento_faenamiento_detalle","porcent_aplazamiento_faenamiento_detalle","total_aves_detalle",1,'detalle');
    	sumarDictamenDetalle("aplazamiento_faenamiento_detalle");
    });
    //verificar que campo esta vacio
    $( "#aplazamiento_faenamiento_detalle" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_aplazamiento_faenamiento_detalle", 2);
    		sumarDictamenDetalle("aplazamiento_faenamiento_detalle");
    	}
    });
    //validar el ingreso de información de aves aplazamiento de faenamiento en porcentaje
    $("#porcent_aplazamiento_faenamiento_detalle").change(function () {
    	validarIngresoInfo("aplazamiento_faenamiento_detalle","porcent_aplazamiento_faenamiento_detalle","total_aves_detalle",2,'detalle');
    	sumarDictamenDetalle("porcent_aplazamiento_faenamiento_detalle");
    });
    //verificar que campo esta vacio
    $( "#porcent_aplazamiento_faenamiento_detalle" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("aplazamiento_faenamiento_detalle", 2);
    		sumarDictamenDetalle("porcent_aplazamiento_faenamiento_detalle");
    	}
    });
//*****************************************************************************************************

$("#agregarFormularioDetalle").click(function (event) {
	event.preventDefault();
        $(".alert-danger").removeClass("alert-danger");
        $("#estadoDetalle").removeClass();
      	var error = false;
      	if($("#hallazgos_detalle").val() == 'Si'){
      		error = verificarCamposObligatoriosDetalle(1);
      	}else{
      		error = verificarCamposObligatoriosDetalle(2);
      	}
        if(!error){ 
        	var totalAves = (isNaN(parseFloat($("#total_aves_detalle").val()))) ? "0" : parseFloat($("#total_aves_detalle").val());
          	var totalDictamen = (isNaN(parseFloat($("#total_faenamiento_detalle").val()))) ? "0" : parseFloat($("#total_faenamiento_detalle").val());
        	if(totalDictamen == totalAves){
        		$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioAnteMortem/actualizarDetalleFormularioAves", 
                                {
        			        //*****cabecera*******
        			        id_detalle_ante_aves: $("#id_detalle_ante_aves").val(),
        			        id_formulario_ante_mortem: $("#id_formulario_ante_mortem").val(),
							//*****generalidades*****
			      		    fecha_formulario: $("#fecha_formulario_detalle").val(),
        		            total_aves: $("#total_aves_detalle").val(),
        		            promedio_aves: $("#promedio_aves_detalle").val(),
        		            tipo_ave: $("#tipo_ave_detalle option:selected").text(),
        		            lugar_procedencia: $("#lugar_procedencia_detalle").val(),
			      		    hallazgos: $("#hallazgos_detalle").val(),
			      		    num_csmi: $("#num_csmi_detalle").val(),
                	        //*****aves muertas*****
                			aves_muertas: $("#aves_muertas_detalle").val(),
                			porcent_aves_muertas: $("#porcent_aves_muertas_detalle").val(),
                			causa_probable: $("#causa_probable_detalle").val(),
                			//*****Características*****
                			decaidas: $("#decaidas_detalle").val(),
			      		    porcent_decaidas: $("#porcent_decaidas_detalle").val(),
        		            num_traumas: $("#num_traumas_detalle").val(),
        		            porcent_traumas: $("#porcent_traumas_detalle").val(),
                			//*****Problemas sistémicos*****
                			probl_respirat: $("#probl_respirat_detalle").val(),
        		            porcent_probl_respirat: $("#porcent_probl_respirat_detalle").val(),
        		            probl_nerviosos: $("#probl_nerviosos_detalle").val(),
        		            porcent_proble_nerviosos: $("#porcent_proble_nerviosos_detalle").val(),
        		            probl_digestivos: $("#probl_digestivos_detalle").val(),
        		            porcent_probl_digestivos: $("#porcent_probl_digestivos_detalle").val(),
                			//*****Características externas*****
                			cabeza_hinchada: $("#cabeza_hinchada_detalle").val(),
        		            porcent_cabeza_hinchada: $("#porcent_cabeza_hinchada_detalle").val(),
        		            plumas_erizadas: $("#plumas_erizadas_detalle").val(),
        		            porcent_plumas_erizadas: $("#porcent_plumas_erizadas_detalle").val(),
                			//*****dictamen*****
                			faenamiento_normal: $("#faenamiento_normal_detalle").val(),
        		            procent_faenamiento_normal: $("#procent_faenamiento_normal_detalle").val(),
        		            faenamiento_especial: $("#faenamiento_especial_detalle").val(),
        		            porcent_faenamiento_especial: $("#porcent_faenamiento_especial_detalle").val(),
        		            faenamiento_emergencia: $("#faenamiento_emergencia_detalle").val(),
        		            porcent_emergencia: $("#porcent_emergencia_detalle").val(),
        		            aplazamiento_faenamiento: $("#aplazamiento_faenamiento_detalle").val(),
        		            porcent_aplazamiento_faenamiento: $("#porcent_aplazamiento_faenamiento_detalle").val(),
        		            total_faenamiento: $("#total_faenamiento_detalle").val(),
        		            //********observacion*****
        		            observacion: $("#observacion_detalle").val(),
        		            //**********hallazgos***********************************
        		            id_hallazgos_aves_muertas: $("#id_hallazgos_aves_muertas").val(),
        		            id_hallazgos_aves_caract: $("#id_hallazgos_aves_caract").val(),
        		            id_hallazgos_aves_sistematicos: $("#id_hallazgos_aves_sistematicos").val(),
        		            id_hallazgos_aves_externas: $("#id_hallazgos_aves_externas").val()
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
    			error = true;
    			$("#estadoDetalle").html("El total de DICTAMEN debe ser igual al total de AVES.").addClass("alerta");
          		$("#total_faenamiento_detalle").addClass("alert-danger");
          		$("#total_aves_detalle").addClass("alert-danger");
        		}
  		}else{
  			$("#estadoDetalle").html("Por favor revise los campos obligatorios.").addClass("alerta");
  		}
	});
//*****************************************************************************************************
//validar si tiene hallazgos o no el formulario******
    $("#hallazgos_detalle").change(function () {

    	if($("#hallazgos_detalle").val() == 'Si'){
    		    $("#avesMuertasDetalle").show();
    	        $("#caracteristicasDetalle").show();
    	        $("#problemasSistemicosDetalle").show();
    	        $("#caracteristicasExternasDetalle").show();
    	        distribuirLineas();
    	}else{
    		    $("#avesMuertasDetalle").hide();
    	        $("#caracteristicasDetalle").hide();
    	        $("#problemasSistemicosDetalle").hide();
    	        $("#caracteristicasExternasDetalle").hide();
    	        setearVariablesHallazgos();
    	}
       
        });
    //************setear los campos**********
	function setearVariablesInicialesDetalle(){
		//*****generalidades*****
		$("#total_aves_detalle").numeric();
		$("#promedio_aves_detalle").numeric();
		$("#num_csmi_detalle").numeric();
		//*****aves muertas*****
		$("#aves_muertas_detalle").numeric();
		$("#porcent_aves_muertas_detalle").numeric();
		//*****Características*****
		$("#decaidas_detalle").numeric();
		$("#porcent_decaidas_detalle").numeric();
		$("#num_traumas_detalle").numeric();
		$("#porcent_traumas_detalle").numeric();
		//*****Problemas sistémicos*****
		$("#probl_respirat_detalle").numeric();
		$("#porcent_probl_respirat_detalle").numeric();
		$("#probl_nerviosos_detalle").numeric();
		$("#porcent_proble_nerviosos_detalle").numeric();
		$("#probl_digestivos_detalle").numeric();
		$("#porcent_probl_digestivos_detalle").numeric();
		//*****Características externas*****
		$("#cabeza_hinchada_detalle").numeric();
		$("#porcent_cabeza_hinchada_detalle").numeric();
		$("#plumas_erizadas_detalle").numeric();
		$("#porcent_plumas_erizadas_detalle").numeric();
		//*****dictamen*****
		$("#faenamiento_normal_detalle").numeric();
		$("#procent_faenamiento_normal_detalle").numeric();
		$("#faenamiento_especial_detalle").numeric();
		$("#porcent_faenamiento_especial_detalle").numeric();
		$("#faenamiento_emergencia_detalle").numeric();
		$("#porcent_emergencia_detalle").numeric();
		$("#aplazamiento_faenamiento_detalle").numeric();
		$("#porcent_aplazamiento_faenamiento_detalle").numeric();
	}
	 //************setear los campos vaciar cuando se cambie el total**********
	function setearVariablesVaciarDetalle(){
		//*****aves muertas*****
		$("#aves_muertas_detalle").val('');
		$("#porcent_aves_muertas_detalle").val('');
		$("#causa_probable_detalle").val('');
		//*****Características*****
		$("#decaidas_detalle").val('');
		$("#porcent_decaidas_detalle").val('');
		$("#num_traumas_detalle").val('');
		$("#porcent_traumas_detalle").val('');
		//*****Problemas sistémicos*****
		$("#probl_respirat_detalle").val('');
		$("#porcent_probl_respirat_detalle").val('');
		$("#probl_nerviosos_detalle").val('');
		$("#porcent_proble_nerviosos_detalle").val('');
		$("#probl_digestivos_detalle").val('');
		$("#porcent_probl_digestivos_detalle").val('');
		//*****Características externas*****
		$("#cabeza_hinchada_detalle").val('');
		$("#porcent_cabeza_hinchada_detalle").val('');
		$("#plumas_erizadas_detalle").val('');
		$("#porcent_plumas_erizadas_detalle").val('');
		//*****dictamen*****
		$("#faenamiento_normal_detalle").val('');
		$("#procent_faenamiento_normal_detalle").val('');
		$("#faenamiento_especial_detalle").val('');
		$("#porcent_faenamiento_especial_detalle").val('');
		$("#faenamiento_emergencia_detalle").val('');
		$("#porcent_emergencia_detalle").val('');
		$("#aplazamiento_faenamiento_detalle").val('');
		$("#porcent_aplazamiento_faenamiento_detalle").val('');
		$("#total_faenamiento_detalle").val('');
		
	}

	 //************setear los campos de hallazgos**********
	function setearVariablesHallazgos(){
		//*****aves muertas*****
		$("#aves_muertas_detalle").val('');
		$("#porcent_aves_muertas_detalle").val('');
		$("#causa_probable_detalle").val('');
		//*****Características*****
		$("#decaidas_detalle").val('');
		$("#porcent_decaidas_detalle").val('');
		$("#num_traumas_detalle").val('');
		$("#porcent_traumas_detalle").val('');
		//*****Problemas sistémicos*****
		$("#probl_respirat_detalle").val('');
		$("#porcent_probl_respirat_detalle").val('');
		$("#probl_nerviosos_detalle").val('');
		$("#porcent_proble_nerviosos_detalle").val('');
		$("#probl_digestivos_detalle").val('');
		$("#porcent_probl_digestivos_detalle").val('');
		//*****Características externas*****
		$("#cabeza_hinchada_detalle").val('');
		$("#porcent_cabeza_hinchada_detalle").val('');
		$("#plumas_erizadas_detalle").val('');
		$("#porcent_plumas_erizadas_detalle").val('');
		
	}
//***********************************************************************************
//********sumar el total del dictamen****
	function sumarDictamenDetalle(id){
		var total = 0;
		//*****dictamen*****
		var normal = (isNaN(parseFloat($("#faenamiento_normal_detalle").val()))) ? "0" : parseFloat($("#faenamiento_normal_detalle").val());
		var especial = (isNaN(parseFloat($("#faenamiento_especial_detalle").val()))) ? "0" : parseFloat($("#faenamiento_especial_detalle").val());
		var emergencia = (isNaN(parseFloat($("#faenamiento_emergencia_detalle").val()))) ? "0" : parseFloat($("#faenamiento_emergencia_detalle").val());
		var aplazamiento = (isNaN(parseFloat($("#aplazamiento_faenamiento_detalle").val()))) ? "0" : parseFloat($("#aplazamiento_faenamiento_detalle").val());
		var totalDictamen = parseFloat(normal)+parseFloat(especial)+parseFloat(emergencia)+parseFloat(aplazamiento);
		var totalAves = (isNaN(parseFloat($("#total_aves_detalle").val()))) ? "0" : parseFloat($("#total_aves_detalle").val());
		if(totalDictamen <= totalAves){
			 $("#total_faenamiento_detalle").val(totalDictamen.toFixed(3));
			 $("#total_faenamiento_detalle").removeClass("alert-danger");
			 $("#agregarFormularioDetalle").removeAttr('disabled');
		}else{
			$("#estadoDetalle").html("El total del DICTAMEN no puede ser mayor al total de AVES...!!").addClass("alerta");
			setearCampo(id, 4);
			setearCampo("total_faenamiento_detalle", 4);
			$("#total_faenamiento_detalle").val(totalDictamen);
			$("#agregarFormularioDetalle").attr('disabled','disabled');
		}
		
	}

//*******************************************************************************************
//************verificar campos obligatorios*******
	function verificarCamposObligatoriosDetalle(opt){

		var error = false;
		switch (opt) { 
		case 1: 
			 //*****aves muertas*****
			  error = verificarAvesMuertas();
			//*****generalidades*****
			  if(!$.trim($("#fecha_formulario_detalle").val())){
	  			   $("#fecha_formulario_detalle").addClass("alert-danger");
	  			   error = true;
	  		  }
	          if (!$.trim($("#total_aves_detalle").val())) {
	  			   $("#total_aves_detalle").addClass("alert-danger");
	  			   error = true;
	          }
	          if(!$.trim($("#tipo_ave_detalle").val())){
		  			$("#tipo_ave_detalle").addClass("alert-danger");
		  			error =  true;
		  		  }
		      if (!$.trim($("#promedio_aves_detalle").val())) {
		  			$("#promedio_aves_detalle").addClass("alert-danger");
		  			error =  true;
		      }
		      if(!$.trim($("#lugar_procedencia_detalle").val())){
		  			$("#lugar_procedencia_detalle").addClass("alert-danger");
		  			error =  true;
		  		  }
		      if (!$.trim($("#hallazgos_detalle").val())) {
		  			$("#hallazgos_detalle").addClass("alert-danger");
		  			error =  true;
		      }
		      if (!$.trim($("#num_csmi_detalle").val())) {
		  			$("#num_csmi_detalle").addClass("alert-danger");
		  			error =  true;
		      }

				//*****Características*****
				//*****Problemas sistémicos*****
				//*****Características externas*****
				if(verificarAvesMuertasGrupo() == true && verificarCaracteristicas() == true && verificarProblemas() == true && verificarProblExter() == true ){
	                error = true;
	              //*****aves muertas*****
	        		$("#aves_muertas").addClass("alert-danger");
	        		$("#porcent_aves_muertas").addClass("alert-danger");
	        		//*****Características*****
	        		$("#decaidas").addClass("alert-danger");
	        		$("#porcent_decaidas").addClass("alert-danger");
	        		$("#num_traumas").addClass("alert-danger");
	        		$("#porcent_traumas").addClass("alert-danger");
	        		//*****Problemas sistémicos*****
	        		$("#probl_respirat").addClass("alert-danger");
	        		$("#porcent_probl_respirat").addClass("alert-danger");
	        		$("#probl_nerviosos").addClass("alert-danger");
	        		$("#porcent_proble_nerviosos").addClass("alert-danger");
	        		$("#probl_digestivos").addClass("alert-danger");
	        		$("#porcent_probl_digestivos").addClass("alert-danger");
	        		//*****Características externas*****
	        		$("#cabeza_hinchada").addClass("alert-danger");
	        		$("#porcent_cabeza_hinchada").addClass("alert-danger");
	        		$("#plumas_erizadas").addClass("alert-danger");
	        		$("#porcent_plumas_erizadas").addClass("alert-danger");
	        		
					}
			//*****dictamen*****
			if (!$.trim($("#faenamiento_normal_detalle").val())) {
		  			$("#faenamiento_normal_detalle").addClass("alert-danger");
		  			$("#procent_faenamiento_normal_detalle").addClass("alert-danger");
		  			error =  true;
		      }
		      if (!$.trim($("#faenamiento_especial_detalle").val())) {
		  			$("#faenamiento_especial_detalle").addClass("alert-danger");
		  			$("#porcent_faenamiento_especial_detalle").addClass("alert-danger");
		  			error =  true;
		      }
		      if (!$.trim($("#faenamiento_emergencia_detalle").val())) {
		  			$("#faenamiento_emergencia_detalle").addClass("alert-danger");
		  			$("#porcent_emergencia_detalle").addClass("alert-danger");
		  			error =  true;
		      }
		      if (!$.trim($("#aplazamiento_faenamiento_detalle").val())) {
		  			$("#aplazamiento_faenamiento_detalle").addClass("alert-danger");
		  			$("#porcent_aplazamiento_faenamiento_detalle").addClass("alert-danger");
		  			error =  true;
		      }
		      if (!$.trim($("#total_faenamiento_detalle").val())) {
		  			$("#total_faenamiento_detalle").addClass("alert-danger");
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
	          if (!$.trim($("#total_aves_detalle").val())) {
	  			   $("#total_aves_detalle").addClass("alert-danger");
	  			   error = true;
	          }
	          if(!$.trim($("#tipo_ave_detalle").val())){
		  			$("#tipo_ave_detalle").addClass("alert-danger");
		  			error =  true;
		  		  }
		      if (!$.trim($("#promedio_aves_detalle").val())) {
		  			$("#promedio_aves_detalle").addClass("alert-danger");
		  			error =  true;
		      }
		      if(!$.trim($("#lugar_procedencia_detalle").val())){
		  			$("#lugar_procedencia_detalle").addClass("alert-danger");
		  			error =  true;
		  		  }
		      if (!$.trim($("#hallazgos_detalle").val())) {
		  			$("#hallazgos_detalle").addClass("alert-danger");
		  			error =  true;
		      }
		      if (!$.trim($("#num_csmi_detalle").val())) {
		  			$("#num_csmi_detalle").addClass("alert-danger");
		  			error =  true;
		      }
			
			//*****dictamen*****
			if (!$.trim($("#faenamiento_normal_detalle").val())) {
		  			$("#faenamiento_normal_detalle").addClass("alert-danger");
		  			$("#procent_faenamiento_normal_detalle").addClass("alert-danger");
		  			error =  true;
		      }
		      if (!$.trim($("#faenamiento_especial_detalle").val())) {
		  			$("#faenamiento_especial_detalle").addClass("alert-danger");
		  			$("#porcent_faenamiento_especial_detalle").addClass("alert-danger");
		  			error =  true;
		      }
		      if (!$.trim($("#faenamiento_emergencia_detalle").val())) {
		  			$("#faenamiento_emergencia_detalle").addClass("alert-danger");
		  			$("#porcent_emergencia_detalle").addClass("alert-danger");
		  			error =  true;
		      }
		      if (!$.trim($("#aplazamiento_faenamiento_detalle").val())) {
		  			$("#aplazamiento_faenamiento_detalle").addClass("alert-danger");
		  			$("#porcent_aplazamiento_faenamiento_detalle").addClass("alert-danger");
		  			error =  true;
		      }
		      if (!$.trim($("#total_faenamiento_detalle").val())) {
		  			$("#total_faenamiento_detalle").addClass("alert-danger");
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
	//*************verificarAvesMuertas*********************************
    function verificarAvesMuertas(){

			if ($.trim($("#aves_muertas_detalle").val()) != '') {
				if ($.trim($("#causa_probable_detalle").val()) != '') {
					return  false;
				}else{
			  		$("#causa_probable_detalle").addClass("alert-danger");
					return  true;
				}
		      }else{
		    	  $("#causa_probable_detalle").val('');
		    	  return  false;
		    }
        }
  //*************verificarAvesMuertas*********************************
    function verificarAvesMuertasGrupo(){
    	if ($.trim($("#aves_muertas_detalle").val()) != '') {
			return  false;
		}else{
			return  true;
				}
        }
    //*************verificar caracteristicas*********************************
    function verificarCaracteristicas(){
			if ($.trim($("#decaidas_detalle").val()) != '') {
				return  false;
			}else if($.trim($("#num_traumas_detalle").val()) != ''){
				return  false;
			}else{
				return  true;
					}
        }
    //*************verificar problemas sistémicos*********************************
    function verificarProblemas(){
	    	if ($.trim($("#probl_respirat_detalle").val()) != '') {
				return  false;
			}else if($.trim($("#probl_nerviosos_detalle").val()) != ''){
				return  false;
			}else if($.trim($("#probl_digestivos_detalle").val()) != ''){
				return  false;
			}else{
				return  true;
			}
        }
    //*************verificar caracteristicas externas*********************************
    function verificarProblExter(){
	    	if ($.trim($("#cabeza_hinchada_detalle").val()) != '') {
				return  false;
			}else if($.trim($("#plumas_erizadas_detalle").val()) != ''){
				return  false;
			}else{
				return  true;
					}
        }

    //****************bloquear campos********************************
    function bloquearCampos(){
    	 $("#fecha_formulario_detalle").attr("disabled","disabled");
         $("#total_aves_detalle").attr("disabled","disabled");
         $("#promedio_aves_detalle").attr("disabled","disabled");
         $("#tipo_ave_detalle").attr("disabled","disabled");
         $("#lugar_procedencia_detalle").attr("disabled","disabled");
		 $("#hallazgos_detalle").attr("disabled","disabled");
		 $("#num_csmi_detalle").attr("disabled","disabled");
	     //*****aves muertas*****
		 $("#aves_muertas_detalle").attr("disabled","disabled");
	     $("#porcent_aves_muertas_detalle").attr("disabled","disabled");
		 $("#causa_probable_detalle").attr("disabled","disabled");
			//*****Características*****
		 $("#decaidas_detalle").attr("disabled","disabled");
		 $("#porcent_decaidas_detalle").attr("disabled","disabled");
         $("#num_traumas_detalle").attr("disabled","disabled");
         $("#porcent_traumas_detalle").attr("disabled","disabled");
			//*****Problemas sistémicos*****
		 $("#probl_respirat_detalle").attr("disabled","disabled");
         $("#porcent_probl_respirat_detalle").attr("disabled","disabled");
         $("#probl_nerviosos_detalle").attr("disabled","disabled");
         $("#porcent_proble_nerviosos_detalle").attr("disabled","disabled");
         $("#probl_digestivos_detalle").attr("disabled","disabled");
         $("#porcent_probl_digestivos_detalle").attr("disabled","disabled");
			//*****Características externas*****
		 $("#cabeza_hinchada_detalle").attr("disabled","disabled");
         $("#porcent_cabeza_hinchada_detalle").attr("disabled","disabled");
         $("#plumas_erizadas_detalle").attr("disabled","disabled");
         $("#porcent_plumas_erizadas_detalle").attr("disabled","disabled");
			//*****dictamen*****
		 $("#faenamiento_normal_detalle").attr("disabled","disabled");
         $("#procent_faenamiento_normal_detalle").attr("disabled","disabled");
         $("#faenamiento_especial_detalle").attr("disabled","disabled");
         $("#porcent_faenamiento_especial_detalle").attr("disabled","disabled");
         $("#faenamiento_emergencia_detalle").attr("disabled","disabled");
         $("#porcent_emergencia_detalle").attr("disabled","disabled");
         $("#aplazamiento_faenamiento_detalle").attr("disabled","disabled");
         $("#porcent_aplazamiento_faenamiento_detalle").attr("disabled","disabled");
         $("#total_faenamiento_detalle").attr("disabled","disabled");
         //********observacion*****
         $("#observacion_detalle").attr("disabled","disabled");

        }

	 
</script>


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
		value="<?php echo $this->idFormularioAnteMortem;?>" /> <input
		type="hidden" id="id_formulario_post_mortem"
		name="id_formulario_post_mortem"
		value="<?php echo $this->idFormularioPostMortem;?>" />

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
			<label for="total_aves">Nro. de Aves(TOTAL): </label> <input
				type="text" id="total_aves" name="total_aves"
				value="<?php echo $this->modeloDetalleAnteAves->getTotalAves()?>"
				readonly maxlength="8" />
		</div>
		<div data-linea="2">
			<label for="tipo_ave">Tipo de ave: </label> <input type="text"
				id="tipo_ave" name="tipo_ave" readonly
				value="<?php echo $this->modeloDetalleAnteAves->getTipoAve()?>"
				maxlength="8" />
		</div>
		<div data-linea="2">
			<label for="promedio_aves">Peso promedio de aves(kg): </label> <input
				type="text" id="promedio_aves" name="promedio_aves"
				value="<?php echo $this->modeloDetalleAnteAves->getPromedioAves();?>"
				readonly maxlength="8" />
		</div>
		<div data-linea="3">
			<label for="lugar_procedencia">Lugar de procedencia(Granja): </label>
			<input type="text" id="lugar_procedencia" name="lugar_procedencia"
				readonly
				value="<?php echo $this->modeloDetalleAnteAves->getLugarProcedencia();?>"
				maxlength="64" />
		</div>

		<div data-linea="4">
			<label for="num_csmi">Nro. de CSM: </label> <input type="text"
				readonly id="num_csmi" name="num_csmi"
				value="<?php echo $this->modeloDetalleAnteAves->getNumCsmi();?>"
				maxlength="8" />
		</div>
	</fieldset>

	<fieldset id="estadoGeneral">
		<legend>Del estado general del ave</legend>

		<div data-linea="1">
			<label for="num_descarte">Nro. Descartes (Caquexia, cianosis,
				ascitis): </label> <input type="text" id="num_descarte"
				name="num_descarte"
				value="<?php echo $this->modeloDetallePostAves->getNumDescarte(); ?>"
				placeholder="Aves con descarte" maxlength="8" />
		</div>
		<div data-linea="1">
			<label for="porcent_num_descarte">Nro. Descartes (Caquexia, cianosis,
				ascitis): </label> <input type="text" id="porcent_num_descarte"
				name="porcent_num_descarte"
				value="<?php echo $this->modeloDetallePostAves->getPorcentNumDescarte(); ?>"
				placeholder="Porcentaje de aves con descarte" maxlength="8" />
		</div>

	</fieldset>

	<fieldset id="manejoFaenamiento">
		<legend>Del manejo al faenamiento</legend>

		<div data-linea="1">
			<label for="num_colibacilosis">Nro. Colibacilosis: </label> <input
				type="text" id="num_colibacilosis" name="num_colibacilosis"
				value="<?php echo $this->modeloDetallePostAves->getNumColibacilosis(); ?>"
				placeholder="Aves con colibacilosis" maxlength="8" />
		</div>
		<div data-linea="1">
			<label for="porcent_num_colibacilosis">% Colibacilosis: </label> <input
				type="text" id="porcent_num_colibacilosis"
				name="porcent_num_colibacilosis"
				value="<?php echo $this->modeloDetallePostAves->getPorcentNumColibacilosis(); ?>"
				placeholder="Porcentaje de aves con colibacilosis" maxlength="8" />
		</div>

		<div data-linea="2">
			<label for="num_pododermatitis">Nro. Pododermatitis: </label> <input
				type="text" id="num_pododermatitis" name="num_pododermatitis"
				value="<?php echo $this->modeloDetallePostAves->getNumPododermatitis(); ?>"
				placeholder="Aves con podermatitis" maxlength="8" />
		</div>

		<div data-linea="2">
			<label for="porcent_num_pododermatitis">% Pododermatitis: </label> <input
				type="text" id="porcent_num_pododermatitis"
				value="<?php echo $this->modeloDetallePostAves->getPorcentNumPododermatitis(); ?>"
				name="porcent_num_pododermatitis"
				placeholder="Porcentaje de aves con pododermatitis" maxlength="8" />
		</div>

		<div data-linea="3">
			<label for="num_lesiones_piel">Nro. Lesiones de piel: </label> <input
				type="text" id="num_lesiones_piel" name="num_lesiones_piel"
				value="<?php echo $this->modeloDetallePostAves->getNumLesionesPiel(); ?>"
				placeholder="Aves con lesiones de piel" maxlength="8" />
		</div>

		<div data-linea="3">
			<label for="porcent_num_lesiones_piel">% Lesiones de piel: </label> <input
				type="text" id="porcent_num_lesiones_piel"
				value="<?php echo $this->modeloDetallePostAves->getPorcentNumLesionesPiel(); ?>"
				name="porcent_num_lesiones_piel"
				placeholder="Porcentaje de aves con lesiones de piel" maxlength="8" />
		</div>

		<div data-linea="4">
			<label for="num_mal_sangrado">Nro. Mal sangrado: </label> <input
				type="text" id="num_mal_sangrado" name="num_mal_sangrado"
				value="<?php echo $this->modeloDetallePostAves->getNumMalSangrado(); ?>"
				placeholder="Aves con mal sangrado" maxlength="8" />
		</div>

		<div data-linea="4">
			<label for="porcent_num_mal_sangrado">% Mal sangrado: </label> <input
				type="text" id="porcent_num_mal_sangrado"
				value="<?php echo $this->modeloDetallePostAves->getPorcentNumMalSangrado(); ?>"
				name="porcent_num_mal_sangrado"
				placeholder="Porcentaje de aves con mal sangrado" maxlength="8" />
		</div>

		<div data-linea="5">
			<label for="num_contusion_pierna">Nro. Contusión de pierna: </label>
			<input type="text" id="num_contusion_pierna"
				value="<?php echo $this->modeloDetallePostAves->getNumContusionPierna(); ?>"
				name="num_contusion_pierna"
				placeholder="Aves con contusíon de pierna" maxlength="8" />
		</div>

		<div data-linea="5">
			<label for="porcent_num_contusion_pierna">% Contusión de pierna: </label>
			<input type="text" id="porcent_num_contusion_pierna"
				value="<?php echo $this->modeloDetallePostAves->getPorcentNumContusionPierna(); ?>"
				name="porcent_num_contusion_pierna"
				placeholder="Porcentaje de aves con contusíon de pierna"
				maxlength="8" />
		</div>
		<div data-linea="6">
			<label for="num_contusion_ala">Nro. Contusión de ala: </label> <input
				type="text" id="num_contusion_ala" name="num_contusion_ala"
				value="<?php echo $this->modeloDetallePostAves->getNumContusionAla(); ?>"
				placeholder="Aves con contusíon de ala" maxlength="8" />
		</div>

		<div data-linea="6">
			<label for="porcent_num_contusion_ala">% Contusión de ala: </label> <input
				type="text" id="porcent_num_contusion_ala"
				value="<?php echo $this->modeloDetallePostAves->getPorcentNumContusionAla(); ?>"
				name="porcent_num_contusion_ala"
				placeholder="Porcentaje de aves con contusíon de ala" maxlength="8" />
		</div>
		<div data-linea="7">
			<label for="num_contusion_pechuga">Nro.Contusión de pechuga: </label>
			<input type="text" id="num_contusion_pechuga"
				value="<?php echo $this->modeloDetallePostAves->getNumContusionPechuga(); ?>"
				name="num_contusion_pechuga"
				placeholder="Aves con contusíon de pechuga" maxlength="8" />
		</div>

		<div data-linea="7">
			<label for="porcent_num_contusion_pechuga">% Contusión de pechuga: </label>
			<input type="text" id="porcent_num_contusion_pechuga"
				value="<?php echo $this->modeloDetallePostAves->getPorcentNumContusionPechuga(); ?>"
				name="porcent_num_contusion_pechuga"
				placeholder="Porcentaje de aves con contusíon de pechuga"
				maxlength="8" />
		</div>
		<div data-linea="8">
			<label for="num_alas_rotas">Nro. Alas rotas: </label> <input
				type="text" id="num_alas_rotas" name="num_alas_rotas"
				value="<?php echo $this->modeloDetallePostAves->getNumAlasRotas(); ?>"
				placeholder="Aves con alas rotas" maxlength="8" />
		</div>

		<div data-linea="8">
			<label for="porcent_num_alas_rotas">% Alas rotas: </label> <input
				type="text" id="porcent_num_alas_rotas"
				value="<?php echo $this->modeloDetallePostAves->getPorcentNumAlasRotas(); ?>"
				name="porcent_num_alas_rotas"
				placeholder="Porcentaje de aves con alas rotas" maxlength="8" />
		</div>
		<div data-linea="9">
			<label for="num_piernas_rotas">Nro. Piernas rotas: </label> <input
				type="text" id="num_piernas_rotas" name="num_piernas_rotas"
				value="<?php echo $this->modeloDetallePostAves->getNumPiernasRotas(); ?>"
				placeholder="Aves con piernas rotas" maxlength="8" />
		</div>

		<div data-linea="9">
			<label for="porcent_num_piernas_rotas">% Piernas rotas: </label> <input
				type="text" id="porcent_num_piernas_rotas"
				value="<?php echo $this->modeloDetallePostAves->getPorcentNumPiernasRotas(); ?>"
				name="porcent_num_piernas_rotas"
				placeholder="Porcentaje de aves con piernas rotas" maxlength="8" />
		</div>
		<div data-linea="10">
			<label for="total_canales_aprobados">Nro. Total de canales aprobadas:
			</label> <input type="text" id="total_canales_aprobados"
				value="<?php echo $this->modeloDetallePostAves->getTotalCanalesAprobados(); ?>"
				name="total_canales_aprobados"
				placeholder="Total de canales aprobados" maxlength="8" />
		</div>

		<div data-linea="10">
			<label for="peso_total_canales_aprobados_totalmente">Peso total de
				canales aprobadas totalmente: </label> <input type="text"
				id="peso_total_canales_aprobados_totalmente"
				name="peso_total_canales_aprobados_totalmente"
				value="<?php echo $this->modeloDetallePostAves->getPesoTotalCanalesAprobadosTotalmente(); ?>"
				placeholder="Peso total de canles aprobados" maxlength="8" />
		</div>
		<div data-linea="11">
			<label for="total_canales_aprobados_parcialmente">Nro. Total de
				canales aprobadas parcialmente: </label> <input type="text"
				id="total_canales_aprobados_parcialmente"
				name="total_canales_aprobados_parcialmente"
				value="<?php echo $this->modeloDetallePostAves->getTotalCanalesAprobadosParcialmente(); ?>"
				placeholder="Total canales aprobados parcialmente" maxlength="8" />
		</div>

		<div data-linea="11">
			<label for="peso_total_canales_aprobados_parcialmente">Peso total de
				canales aprobadas parcialmente: </label> <input type="text"
				id="peso_total_canales_aprobados_parcialmente"
				name="peso_total_canales_aprobados_parcialmente"
				value="<?php echo $this->modeloDetallePostAves->getPesoTotalCanalesAprobadosParcialmente(); ?>"
				placeholder="Peso total canales aprobados parcialmente"
				maxlength="8" />
		</div>
		<div data-linea="12">
			<label for="canales_decomiso_total">Nro. Canales con decomiso total:
			</label> <input type="text" id="canales_decomiso_total"
				value="<?php echo $this->modeloDetallePostAves->getCanalesDecomisoTotal(); ?>"
				name="canales_decomiso_total" placeholder="Canales decomiso total"
				maxlength="8" />
		</div>

		<div data-linea="12">
			<label for="canales_decomiso_parcial">Nro. Canales con decomiso
				parcial: </label> <input type="text" id="canales_decomiso_parcial"
				name="canales_decomiso_parcial"
				value="<?php echo $this->modeloDetallePostAves->getCanalesDecomisoParcial(); ?>"
				placeholder="Canales decomiso parcial" maxlength="8" />
		</div>
		<div data-linea="13">
			<label for="total_carne_decomisada">Peso total de carne decomisada: </label>
			<input type="text" id="total_carne_decomisada"
				value="<?php echo $this->modeloDetallePostAves->getTotalCarneDecomisada(); ?>"
				name="total_carne_decomisada" placeholder="Total carne decomisada"
				maxlength="8" />
		</div>

		<div data-linea="13">
			<label for="peso_promedio_canales">Peso promedio de las canales: </label>
			<input type="text" id="peso_promedio_canales"
				value="<?php echo $this->modeloDetallePostAves->getPesoPromedioCanales(); ?>"
				name="peso_promedio_canales" placeholder="Peso promedio canales"
				maxlength="8" />
		</div>
		<div data-linea="14">
			<label for="lugar_disposicion_final">Lugar de la disposición final: </label>
			<input type="text" id="lugar_disposicion_final"
				value="<?php echo $this->modeloDetallePostAves->getLugarDisposicionFinal(); ?>"
				name="lugar_disposicion_final" placeholder="Lugar disposicion final"
				maxlength="8" />
		</div>

		<div data-linea="14">
			<label for="destino_decomisos">Destino de los decomisos: </label> <select
				id="destino_decomisos" name="destino_decomisos"> 
            		<?php
						echo $this->comboDestino($this->modeloDetallePostAves->getDestinoDecomisos());
					?>
				</select>
		</div>



	</fieldset>
	<fieldset id="observaciones">
		<legend>Observaciones</legend>

		<div data-linea="1">
			<input type="text" id="observacion" name="observacion"
				value="<?php echo $this->modeloDetallePostAves->getObservacion(); ?>"
				placeholder="Observaciones del formulario" maxlength="1024" />
		</div>

	</fieldset>
	<div data-linea="1">
		<button id="agregarFormulario" type="button" class="guardar"></button>
	</div>
	<div data-linea="1">

		<button type="button" id="enviarRevision" class="">Enviar a revisión</button>
		<button type="button" id="aprobar" class="">Aprobar</button>
		<button type="button" id="generar" class="">Generar</button>
	</div>

</form>

<iframe id="formularioCreado" width="100%" height="100%"
	src="<?php echo $this->urlPdf; ?>" frameborder="0" allowfullscreen></iframe>
<script type="text/javascript">
    var fechaInical = <?php echo json_encode($this->fechaInicial);?>;
    var idCentroFaenamiento = <?php echo json_encode($this->idCentroFaenamiento);?>;
    var idFormularioDetalle = <?php echo json_encode($this->idFormularioEditar);?>;
    var idDetalleAnteAves = <?php echo json_encode($this->modeloDetalleAnteAves->getIdDetalleAnteAves());?>;  
    var idDetallePostAves = <?php echo json_encode($this->modeloDetallePostAves->getIdDetallePostAves());?>;  
    var perfilUsuario = <?php echo json_encode($this->perfilUsuario); ?>;  

    if(idDetallePostAves != '' && idDetallePostAves != null){
    	var fechaActual = <?php echo json_encode($this->modeloDetallePostAves->getFechaFormulario());?>;
    	var estadoRegistro = <?php echo json_encode($this->modeloFormularioPostMortem->getEstado());?>;
	 }else{
		var fechaActual = <?php echo json_encode(date("Y-m-d"));?>;
		var estadoRegistro = <?php echo json_encode($this->modeloFormularioAnteMortem->getEstado());?>;
	 }
	$(document).ready(function() {
		establecerFechas('fecha_formulario',fechaActual);
		setearVariablesIniciales();
		mostrarMensaje("", "FALLO");
		$("#avesMuertas").hide();
	    $("#caracteristicas").hide();
	    $("#problemasSistemicos").hide();
	    $("#caracteristicasExternas").hide();
	    
		$("#areaTrabajo #listadoItems").append('<div id="estado"></div>');

		 if(idDetallePostAves != '' && idDetallePostAves != null){
				$("#agregarFormulario").html('Actualizar registro');
			 }else{
				$("#agregarFormulario").html('Guardar registro');
			 }

		 if($("#id_formulario_post_mortem").val() != '' && $("#id_formulario_post_mortem").val() != null){
			 if(perfilUsuario == "PFL_APM_CF_OP"){
		        	$("#aprobar").show();
		        	$("#enviarRevision").hide();
				 }else{
					$("#enviarRevision").show();
					$("#aprobar").hide();
					 if(estadoRegistro == 'Por revisar'){
				        	bloquearCampos();
				        	$("#agregarFormulario").hide();
				        	$("#enviarRevision").hide();
				           }
				 }
			   
			 }else{
				 $("#enviarRevision").hide();
		         $("#aprobar").hide();
			 }
		 if(estadoRegistro == 'Aprobado_AP'){
	        	$("#agregarFormulario").hide();
	        	$("#enviarRevision").hide();
	        	$("#aprobar").hide();
	        	bloquearCampos();
	           }else{
	        	$("#generar").hide();
	           }
	    construirValidador();
		distribuirLineas();
	 });

	//verificar campo total
    $("#total_aves").change(function () {
    	if(!$.trim($(this).val())){
    		setearVariablesRegistro();
    	}
    });
    //verificar que campo esta vacio
    $( "#total_aves" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearVariablesRegistro();
    	}
    });
//*****************************************************************************************************
 //validar el ingreso de información de aves en cantidad
    $("#num_descarte").change(function () {
    	validarIngresoInfo("num_descarte","porcent_num_descarte","total_aves",1);
    });
    //verificar que campo esta vacio
    $( "#num_descarte" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_num_descarte", 2);
    	}
    });
    //validar el ingreso de información de aves en porcentaje
    $("#porcent_num_descarte").change(function () {
    	validarIngresoInfo("num_descarte","porcent_num_descarte","total_aves",2);
    });
    //verificar que campo esta vacio
    $( "#porcent_num_descarte" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("num_descarte", 2);
    	}
    });
//*****************************************************************************************************
//validar el ingreso de información de aves decaidas en cantidad
    $("#num_colibacilosis").change(function () {
    	validarIngresoInfo("num_colibacilosis","porcent_num_colibacilosis","total_aves",1);
    });
    //verificar que campo esta vacio
    $( "#num_colibacilosis" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_decaidas", 2);
    	}
    });
    //validar el ingreso de información de aves decaidas en porcentaje
    $("#porcent_num_colibacilosis").change(function () {
    	validarIngresoInfo("num_colibacilosis","porcent_num_colibacilosis","total_aves",2);
    });
    //verificar que campo esta vacio
    $( "#porcent_num_colibacilosis" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("num_colibacilosis", 2);
    	}
    });
//*****************************************************************************************************    
 //validar el ingreso de información de aves con traumas en cantidad
    $("#num_pododermatitis").change(function () {
    	validarIngresoInfo("num_pododermatitis","porcent_num_pododermatitis","total_aves",1);
    });
    //verificar que campo esta vacio
    $( "#num_pododermatitis" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_num_pododermatitis", 2);
    	}
    });
    //validar el ingreso de información de aves con traumas en porcentaje
    $("#porcent_num_pododermatitis").change(function () {
    	validarIngresoInfo("num_pododermatitis","porcent_num_pododermatitis","total_aves",2);
    });
    //verificar que campo esta vacio
    $( "#porcent_num_pododermatitis" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("num_pododermatitis", 2);
    	}
    });
//*****************************************************************************************************	
//validar el ingreso de información de aves con problemas respiratorios en cantidad
    $("#num_lesiones_piel").change(function () {
    	validarIngresoInfo("num_lesiones_piel","porcent_num_lesiones_piel","total_aves",1);
    });
    //verificar que campo esta vacio
    $( "#num_lesiones_piel" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_num_lesiones_piel", 2);
    	}
    });
    //validar el ingreso de información de aves con problemas respiratorios en porcentaje
    $("#porcent_num_lesiones_piel").change(function () {
    	validarIngresoInfo("num_lesiones_piel","porcent_num_lesiones_piel","total_aves",2);
    });
    //verificar que campo esta vacio
    $( "#porcent_num_lesiones_piel" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("num_lesiones_piel", 2);
    	}
    });
//*****************************************************************************************************
//validar el ingreso de información de aves con problemas nerviosos en cantidad
    $("#num_mal_sangrado").change(function () {
    	validarIngresoInfo("num_mal_sangrado","porcent_num_mal_sangrado","total_aves",1);
    });
    //verificar que campo esta vacio
    $( "#num_mal_sangrado" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_num_mal_sangrado", 2);
    	}
    });
    //validar el ingreso de información de aves con problemas nerviosos en porcentaje
    $("#porcent_num_mal_sangrado").change(function () {
    	validarIngresoInfo("num_mal_sangrado","porcent_num_mal_sangrado","total_aves",2);
    });
    //verificar que campo esta vacio
    $( "#porcent_num_mal_sangrado" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("num_mal_sangrado", 2);
    	}
    });	
//*****************************************************************************************************
//validar el ingreso de información de aves con problemas digestivos en cantidad
    $("#num_contusion_pierna").change(function () {
    	validarIngresoInfo("num_contusion_pierna","porcent_num_contusion_pierna","total_aves",1);
    });
    //verificar que campo esta vacio
    $( "#num_contusion_pierna" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_num_contusion_pierna", 2);
    	}
    });
    //validar el ingreso de información de aves con problemas digestivos en porcentaje
    $("#porcent_num_contusion_pierna").change(function () {
    	validarIngresoInfo("num_contusion_pierna","porcent_num_contusion_pierna","total_aves",2);
    });
    //verificar que campo esta vacio
    $( "#porcent_num_contusion_pierna" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("num_contusion_pierna", 2);
    	}
    });	
//*****************************************************************************************************
//validar el ingreso de información de aves con cabeza hinchada en cantidad
    $("#num_contusion_ala").change(function () {
    	validarIngresoInfo("num_contusion_ala","porcent_num_contusion_ala","total_aves",1);
    });
    //verificar que campo esta vacio
    $( "#num_contusion_ala" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_num_contusion_ala", 2);
    	}
    });
    //validar el ingreso de información de aves con cabeza hinchada en porcentaje
    $("#porcent_num_contusion_ala").change(function () {
    	validarIngresoInfo("num_contusion_ala","porcent_num_contusion_ala","total_aves",2);
    });
    //verificar que campo esta vacio
    $( "#porcent_num_contusion_ala" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("num_contusion_ala", 2);
    	}
    });
//*****************************************************************************************************
    //validar el ingreso de información de aves con plumas erizadas en cantidad
    $("#num_contusion_pechuga").change(function () {
    	validarIngresoInfo("num_contusion_pechuga","porcent_num_contusion_pechuga","total_aves",1);
    });
    //verificar que campo esta vacio
    $( "#num_contusion_pechuga" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_num_contusion_pechuga", 2);
    	}
    });
    //validar el ingreso de información de aves con plumas erizadas en porcentaje
    $("#porcent_num_contusion_pechuga").change(function () {
    	validarIngresoInfo("num_contusion_pechuga","porcent_num_contusion_pechuga","total_aves",2);
    });
    //verificar que campo esta vacio
    $( "#porcent_num_contusion_pechuga" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("num_contusion_pechuga", 2);
    	}
    });
  //*****************************************************************************************************
    //validar el ingreso de información de aves con plumas erizadas en cantidad
    $("#num_alas_rotas").change(function () {
    	validarIngresoInfo("num_alas_rotas","porcent_num_alas_rotas","total_aves",1);
    });
    //verificar que campo esta vacio
    $( "#num_alas_rotas" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_num_alas_rotas", 2);
    	}
    });
    //validar el ingreso de información de aves con plumas erizadas en porcentaje
    $("#porcent_num_alas_rotas").change(function () {
    	validarIngresoInfo("num_alas_rotas","porcent_num_alas_rotas","total_aves",2);
    });
    //verificar que campo esta vacio
    $( "#porcent_num_alas_rotas" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("num_alas_rotas", 2);
    	}
    });
  //*****************************************************************************************************
    //validar el ingreso de información de aves con plumas erizadas en cantidad
    $("#num_piernas_rotas").change(function () {
    	validarIngresoInfo("num_piernas_rotas","porcent_num_piernas_rotas","total_aves",1);
    });
    //verificar que campo esta vacio
    $( "#num_piernas_rotas" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_num_piernas_rotas", 2);
    	}
    });
    //validar el ingreso de información de aves con plumas erizadas en porcentaje
    $("#porcent_num_piernas_rotas").change(function () {
    	validarIngresoInfo("num_piernas_rotas","porcent_num_piernas_rotas","total_aves",2);
    });
    //verificar que campo esta vacio
    $( "#porcent_num_piernas_rotas" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("num_piernas_rotas", 2);
    	}
    });
//*****************************************************************************************************    
  
//*****************************************************************************************************
	$("#agregarFormulario").click(function () {
        $(".alertaCombo").removeClass("alertaCombo");
      	var error = false;
      	error = verificarCamposObligatorios();

        if(!error){
        		$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioPostMortem/agregarFormularioPostAves", 
                        {
        			        //*****cabecera*******
        			        id_formulario_ante_mortem: $("#id_formulario_ante_mortem").val(),
        			        id_formulario_post_mortem: $("#id_formulario_post_mortem").val(),
        			        id_detalle_ante_aves : idDetalleAnteAves,
        			        id_detalle_post_aves: idDetallePostAves,
        			        idCentroFaenamiento: idCentroFaenamiento,
							//*****generalidades*****
							fecha_formulario: $("#fecha_formulario").val(),
						    //*****estado general*****
							num_descarte: $("#num_descarte").val(),
							porcent_num_descarte: $("#porcent_num_descarte").val(),
							//*****manejo faenamiento*****
							num_colibacilosis: $("#num_colibacilosis").val(),
			        		porcent_num_colibacilosis: $("#porcent_num_colibacilosis").val(),
			        		num_pododermatitis: $("#num_pododermatitis").val(),
			        		porcent_num_pododermatitis: $("#porcent_num_pododermatitis").val(),
			        		num_lesiones_piel: $("#num_lesiones_piel").val(),
			        		porcent_num_lesiones_piel: $("#porcent_num_lesiones_piel").val(),
			        		num_mal_sangrado: $("#num_mal_sangrado").val(),
			        		porcent_num_mal_sangrado: $("#porcent_num_mal_sangrado").val(),
			        		num_contusion_pierna: $("#num_contusion_pierna").val(),
			        		porcent_num_contusion_pierna: $("#porcent_num_contusion_pierna").val(),
			        		num_contusion_ala: $("#num_contusion_ala").val(),
			        		porcent_num_contusion_ala: $("#porcent_num_contusion_ala").val(),
			        		num_contusion_pechuga: $("#num_contusion_pechuga").val(),
			        		porcent_num_contusion_pechuga: $("#porcent_num_contusion_pechuga").val(),
			        		num_alas_rotas: $("#num_alas_rotas").val(),
			        		porcent_faenamiento_normal: $("#porcent_faenamiento_normal").val(),
			        		porcent_num_alas_rotas: $("#porcent_num_alas_rotas").val(),
			        		num_piernas_rotas: $("#num_piernas_rotas").val(),
			        		porcent_num_piernas_rotas: $("#porcent_num_piernas_rotas").val(),
			        		total_canales_aprobados: $("#total_canales_aprobados").val(),
			        		peso_total_canales_aprobados_totalmente: $("#peso_total_canales_aprobados_totalmente").val(),
			        		total_canales_aprobados_parcialmente: $("#total_canales_aprobados_parcialmente").val(),
			        		peso_total_canales_aprobados_parcialmente: $("#peso_total_canales_aprobados_parcialmente").val(),
			        		canales_decomiso_parcial: $("#canales_decomiso_parcial").val(),
			        		canales_decomiso_total: $("#canales_decomiso_total").val(),
			        		peso_promedio_canales: $("#peso_promedio_canales").val(),
			        		total_carne_decomisada: $("#total_carne_decomisada").val(),
			        		destino_decomisos: $("#destino_decomisos option:selected").text(),
			        		lugar_disposicion_final: $("#lugar_disposicion_final").val(),
        		            //********observacion*****
        		            observacion: $("#observacion").val()
                        	
     					},
     					function (data) {
     						  if(data.estado === 'EXITO'){
     							 var idFormularioDetalleNew = actualizarIdTr(idFormularioDetalle, data.idDetalle); 
     							 $("#id_formulario_post_mortem").val(data.id);
     							 $("#aprobar").show();
     							 if(data.contenido != 'actualizado'){
     							 	$('#tablaItems #'+idFormularioDetalle+' td:eq(1)').html('<b>Registrado</b>'); 
     							 	$('#tablaItems #'+idFormularioDetalle).attr("id",idFormularioDetalleNew);
     							 }
						    	 $("#detalleItem").html("<div id='cargando'>Cargando...</div>").wait(170).html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
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
function actualizarIdTr(idFormularioDetalle, idDetalle){
		var res = idFormularioDetalle.split("-");
		res[4] = idDetalle;
		var unificar = res[0]+"-"+res[1]+"-"+res[2]+"-"+res[3]+"-"+res[4];
		return unificar;
	 }
    //************setear los campos**********
	function setearVariablesIniciales(){
		//*****estado general*****
		$("#num_descarte").numeric();
		$("#porcent_num_descarte").numeric();
		//*****manejo faenamiento*****
		$("#num_colibacilosis").numeric();
		$("#porcent_num_colibacilosis").numeric();
		$("#num_pododermatitis").numeric();
		$("#porcent_num_pododermatitis").numeric();
		$("#num_lesiones_piel").numeric();
		$("#porcent_num_lesiones_piel").numeric();
		$("#num_mal_sangrado").numeric();
		$("#porcent_num_mal_sangrado").numeric();
		$("#num_contusion_pierna").numeric();
		$("#porcent_num_contusion_pierna").numeric();
		$("#num_contusion_ala").numeric();
		$("#porcent_num_contusion_ala").numeric();
		$("#num_contusion_pechuga").numeric();
		$("#porcent_num_contusion_pechuga").numeric();
		$("#num_alas_rotas").numeric();
		$("#porcent_faenamiento_normal").numeric();
		$("#porcent_num_alas_rotas").numeric();
		$("#num_piernas_rotas").numeric();
		$("#porcent_num_piernas_rotas").numeric();
		$("#total_canales_aprobados").numeric();
		$("#peso_total_canales_aprobados_totalmente").numeric();
		$("#total_canales_aprobados_parcialmente").numeric();
		$("#peso_total_canales_aprobados_parcialmente").numeric();
		$("#canales_decomiso_parcial").numeric();
		$("#peso_promedio_canales").numeric();
		$("#total_carne_decomisada").numeric();
		$("#canales_decomiso_total").numeric();
	}
	 
	 //************setear los campos cuando se guarde un registro**********
	function setearVariablesRegistro(){
		 $("#fecha_formulario").val(fechaActual);
			//*****estado general*****
				$("#num_descarte").val('');
				$("#porcent_num_descarte").val('');
				//*****manejo faenamiento*****
				$("#num_colibacilosis").val('');
				$("#porcent_num_colibacilosis").val('');
				$("#num_pododermatitis").val('');
				$("#porcent_num_pododermatitis").val('');
				$("#num_lesiones_piel").val('');
				$("#porcent_num_lesiones_piel").val('');
				$("#num_mal_sangrado").val('');
				$("#porcent_num_mal_sangrado").val('');
				$("#num_contusion_pierna").val('');
				$("#porcent_num_contusion_pierna").val('');
				$("#num_contusion_ala").val('');
				$("#porcent_num_contusion_ala").val('');
				$("#num_contusion_pechuga").val('');
				$("#porcent_num_contusion_pechuga").val('');
				$("#num_alas_rotas").val('');
				$("#porcent_num_alas_rotas").val('');
				$("#num_piernas_rotas").val('');
				$("#porcent_num_piernas_rotas").val('');
				$("#total_canales_aprobados").val('');
				$("#peso_total_canales_aprobados_totalmente").val('');
				$("#total_canales_aprobados_parcialmente").val('');
				$("#peso_total_canales_aprobados_parcialmente").val('');
				$("#canales_decomiso_parcial").val('');
				$("#canales_decomiso_total").val('');
				$("#peso_promedio_canales").val('');
				$("#total_carne_decomisada").val('');
				$("#destino_decomisos").val('');
				$("#lugar_disposicion_final").val('');
				$("#observacion").val('');
		
	}
//***********************************************************************************
 //************setear los campos cuando se guarde un registro**********
	function bloquearCampos(){
		 $("#fecha_formulario").attr('disabled','disabled');
			//*****estado general*****
				$("#num_descarte").attr('readonly','readonly');
				$("#porcent_num_descarte").attr('readonly','readonly');
				//*****manejo faenamiento*****
				$("#num_colibacilosis").attr('readonly','readonly');
				$("#porcent_num_colibacilosis").attr('readonly','readonly');
				$("#num_pododermatitis").attr('readonly','readonly');
				$("#porcent_num_pododermatitis").attr('readonly','readonly');
				$("#num_lesiones_piel").attr('readonly','readonly');
				$("#porcent_num_lesiones_piel").attr('readonly','readonly');
				$("#num_mal_sangrado").attr('readonly','readonly');
				$("#porcent_num_mal_sangrado").attr('readonly','readonly');
				$("#num_contusion_pierna").attr('readonly','readonly');
				$("#porcent_num_contusion_pierna").attr('readonly','readonly');
				$("#num_contusion_ala").attr('readonly','readonly');
				$("#porcent_num_contusion_ala").attr('readonly','readonly');
				$("#num_contusion_pechuga").attr('readonly','readonly');
				$("#porcent_num_contusion_pechuga").attr('readonly','readonly');
				$("#num_alas_rotas").attr('readonly','readonly');
				$("#porcent_num_alas_rotas").attr('readonly','readonly');
				$("#num_piernas_rotas").attr('readonly','readonly');
				$("#porcent_num_piernas_rotas").attr('readonly','readonly');
				$("#total_canales_aprobados").attr('readonly','readonly');
				$("#peso_total_canales_aprobados_totalmente").attr('readonly','readonly');
				$("#total_canales_aprobados_parcialmente").attr('readonly','readonly');
				$("#peso_total_canales_aprobados_parcialmente").attr('readonly','readonly');
				$("#canales_decomiso_parcial").attr('readonly','readonly');
				$("#canales_decomiso_total").attr('readonly','readonly');
				$("#peso_promedio_canales").attr('readonly','readonly');
				$("#total_carne_decomisada").attr('readonly','readonly');
				$("#destino_decomisos").attr('disabled','disabled');
				$("#lugar_disposicion_final").attr('readonly','readonly');
				$("#observacion").attr('readonly','readonly');
		
	}
//************verificar campos obligatorios*******
	function verificarCamposObligatorios(){
		error = false;
			//*****generalidades*****
			  if(!$.trim($("#fecha_formulario").val())){
	  			   $("#fecha_formulario").addClass("alertaCombo");
	  			   error = true;
	  		  }
	          if (!$.trim($("#total_aves").val())) {
	  			   $("#total_aves").addClass("alertaCombo");
	  			   error = true;
	          }
	          if(!$.trim($("#tipo_ave").val())){
		  			$("#tipo_ave").addClass("alertaCombo");
		  			error =  true;
		  		  }
		      if (!$.trim($("#promedio_aves").val())) {
		  			$("#promedio_aves").addClass("alertaCombo");
		  			error =  true;
		      }
		      if(!$.trim($("#lugar_procedencia").val())){
		  			$("#lugar_procedencia").addClass("alertaCombo");
		  			error =  true;
		  		  }
		    
		      if (!$.trim($("#num_csmi").val())) {
		  			$("#num_csmi").addClass("alertaCombo");
		  			error =  true;
		      }

			  if (!$.trim($("#num_descarte").val())) {
		  			$("#num_descarte").addClass("alertaCombo");
		  			$("#porcent_num_descarte").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#num_colibacilosis").val())) {
		  			$("#num_colibacilosis").addClass("alertaCombo");
		  			$("#porcent_num_colibacilosis").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#num_pododermatitis").val())) {
		  			$("#num_pododermatitis").addClass("alertaCombo");
		  			$("#porcent_num_pododermatitis").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#num_lesiones_piel").val())) {
		  			$("#num_lesiones_piel").addClass("alertaCombo");
		  			$("#porcent_num_lesiones_piel").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#num_mal_sangrado").val())) {
		  			$("#num_mal_sangrado").addClass("alertaCombo");
		  			$("#porcent_num_mal_sangrado").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#num_contusion_pierna").val())) {
		  			$("#num_contusion_pierna").addClass("alertaCombo");
		  			$("#porcent_num_contusion_pierna").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#num_contusion_ala").val())) {
		  			$("#num_contusion_ala").addClass("alertaCombo");
		  			$("#porcent_num_contusion_ala").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#num_contusion_pechuga").val())) {
		  			$("#num_contusion_pechuga").addClass("alertaCombo");
		  			$("#porcent_num_contusion_pechuga").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#num_alas_rotas").val())) {
		  			$("#num_alas_rotas").addClass("alertaCombo");
		  			$("#porcent_num_alas_rotas").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#num_piernas_rotas").val())) {
		  			$("#num_piernas_rotas").addClass("alertaCombo");
		  			$("#porcent_num_piernas_rotas").addClass("alertaCombo");
		  			error =  true;
		      }
		      
		    //*****manejo faenamiento*****

		      if (!$.trim($("#total_canales_aprobados").val())) {
		  			$("#total_canales_aprobados").addClass("alertaCombo");
		  			error =  true;
		      } 
		      if (!$.trim($("#peso_total_canales_aprobados_totalmente").val())) {
		  			$("#peso_total_canales_aprobados_totalmente").addClass("alertaCombo");
		  			error =  true;
		      } 
		      if (!$.trim($("#total_canales_aprobados_parcialmente").val())) {
		  			$("#total_canales_aprobados_parcialmente").addClass("alertaCombo");
		  			error =  true;
		      }
		       if (!$.trim($("#peso_total_canales_aprobados_parcialmente").val())) {
		  			$("#peso_total_canales_aprobados_parcialmente").addClass("alertaCombo");
		  			error =  true;
		      } 
			   if (!$.trim($("#canales_decomiso_total").val())) {
		  			$("#canales_decomiso_total").addClass("alertaCombo");
		  			error =  true;
		      } 
			   if (!$.trim($("#canales_decomiso_parcial").val())) {
		  			$("#canales_decomiso_parcial").addClass("alertaCombo");
		  			error =  true;
		      }
			   if (!$.trim($("#peso_promedio_canales").val())) {
		  			$("#peso_promedio_canales").addClass("alertaCombo");
		  			error =  true;
		      } 
		      if (!$.trim($("#total_carne_decomisada").val())) {
		  			$("#total_carne_decomisada").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#destino_decomisos").val())) {
		  			$("#destino_decomisos").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#lugar_disposicion_final").val())) {
		  			$("#lugar_disposicion_final").addClass("alertaCombo");
		  			error =  true;
		      }
		    //*****observacion***
		    if (!$.trim($("#observacion").val())) {
		  			$("#observacion").addClass("alertaCombo");
		  			error =  true;
		      }
		
		return error;
	    }

	//************previsualizar detalle formulario***********************
    function btnPrevisualizar(id){
    	$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioAnteMortem/detalleFormularioAvesPrevisualizar",{
    		    id_detalle_ante_aves : id,
    		    estadoRegistro : estadoRegistro
	      		},
	      		function (data) {
                    $('#modalDetalle').modal('show');
                    $("#divDetalle").html(data);
                });
    	
     }
    //*************verificarAvesMuertas*********************************
    function verificarAvesMuertas(){

			if ($.trim($("#aves_muertas").val()) != '') {
				if ($.trim($("#causa_probable").val()) != '') {
					return  false;
				}else{
			  		$("#causa_probable").addClass("alertaCombo");
					return  true;
				}
		      }else{
		    	  $("#causa_probable").val('');
		    	  return  false;
		    }
        }
  //*************verificarAvesMuertas*********************************
    function verificarAvesMuertasGrupo(){
    	if ($.trim($("#aves_muertas").val()) != '') {
			return  false;
		}else{
			return  true;
				}
        }
    //*************verificar caracteristicas*********************************
    function verificarCaracteristicas(){
			if ($.trim($("#decaidas").val()) != '') {
				return  false;
			}else if($.trim($("#num_traumas").val()) != ''){
				return  false;
			}else{
				return  true;
					}
        }
    //*************verificar problemas sistémicos*********************************
    function verificarProblemas(){
	    	if ($.trim($("#probl_respirat").val()) != '') {
				return  false;
			}else if($.trim($("#probl_nerviosos").val()) != ''){
				return  false;
			}else if($.trim($("#probl_digestivos").val()) != ''){
				return  false;
			}else{
				return  true;
			}
        }
    //*************verificar caracteristicas externas*********************************
    function verificarProblExter(){
	    	if ($.trim($("#cabeza_hinchada").val()) != '') {
				return  false;
			}else if($.trim($("#plumas_erizadas").val()) != ''){
				return  false;
			}else{
				return  true;
					}
        }
    //*********enviar a revision el formulario
    $("#enviarRevision").click(function() { 
    	if($("#id_formulario_post_mortem").val() != '' && $("#id_formulario_post_mortem").val() != null){
    	$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioPostMortem/enviarRevisionAves", 
				{
    		        id_formulario_post_mortem: $("#id_formulario_post_mortem").val(),
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
							    mostrarMensaje(data.mensaje, "FALLO");
						  }
	        	}, 'json'); 
    	}else{
    		mostrarMensaje("Debe guardar primero el formulario...!!", "FALLO");
        	}
	});

    //*********Aprobar el formulario
    $("#aprobar").click(function() {
    	if($("#id_formulario_post_mortem").val() != '' && $("#id_formulario_post_mortem").val() != null){
    	$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioPostMortem/aprobarFormularioAves", 
				{
    		        id_formulario_post_mortem: $("#id_formulario_post_mortem").val(),
    		        estado: 'Aprobado_PM'
				},
				function (data) {
					 if(data.estado == 'EXITO'){
						        if(idFormularioDetalle != '' && idFormularioDetalle != null){
							    	$('#tablaItems #'+idFormularioDetalle+' td:eq(1)').html('<b>Aprobado_PM</b>'); 
							    	$("#detalleItem").html("<div id='cargando'>Cargando...</div>").wait(170).html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
							    }else{ 
							    	abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#detalleItem",false);
								}
							    mostrarMensaje(data.mensaje, "EXITO")
							    $("#estado").html(data.mensaje).wait(170).html('');
						  }else{
							 mostrarMensaje(data.mensaje, "FALLO");
						  }
	        	}, 'json'); 
    	}else{
    		mostrarMensaje("Debe guardar primero el formulario...!!", "FALLO");
        	}
	});
    //*********Aprobar el formulario
    $("#generar").click(function() {
    	if($("#id_formulario_post_mortem").val() != '' && $("#id_formulario_post_mortem").val() != null){
    	$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioPostMortem/generarFormularioAves", 
				{
    		        id_formulario_ante_mortem: $("#id_formulario_post_mortem").val(),
    		        id_formulario_post_mortem: $("#id_formulario_post_mortem").val(),
    		        estado: 'Aprobado_AM',
    		        idFormularioDetalle: idFormularioDetalle
				},
				function (data) {
					 if(data.estado == 'EXITO'){
							 mostrarMensaje(data.mensaje, "EXITO");
							 $("#formularioCreado").attr("src", data.ruta);
						  }else{
							  alert(data.mensaje);
							 mostrarMensaje(data.mensaje, "FALLO");
						  }
	        	}, 'json'); 
    	}else{
    		mostrarMensaje("Debe guardar primero el formulario...!!", "FALLO");
        	}
	});

</script>


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
	data-destino="detalleItem"
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
			<label for="total_aves">Nro. de Aves(TOTAL): </label> <input
				type="text" id="total_aves" name="total_aves" value=""
				placeholder="Total de aves" maxlength="8" />
		</div>
		<div data-linea="2">
			<label for="tipo_ave">Tipo de ave: </label> <select id="tipo_ave"
				name="tipo_ave">
            		<?php
														echo $this->comboEspecie;
														?>
        		</select>
		</div>
		<div data-linea="2">
			<label for="promedio_aves">Peso promedio de aves(kg): </label> <input
				type="text" id="promedio_aves" name="promedio_aves" value=""
				placeholder="Peso promedio de aves" maxlength="8" />
		</div>
		<div data-linea="3">
			<label for="lugar_procedencia">Lugar de procedencia(Granja): </label>
			<input type="text" id="lugar_procedencia" name="lugar_procedencia"
				value="" placeholder="Lugar de procedencia" maxlength="64" />
		</div>
		<div data-linea="3">
			<label for="hallazgos">Existen hallazgos: </label> <select
				id="hallazgos" name="hallazgos">
				<?php
				echo $this->comboSiNo();
				?>
			</select>
		</div>

		<div data-linea="4">
			<label for="num_csmi">Nro. de CSM: </label> <input type="text"
				id="num_csmi" name="num_csmi" value="" placeholder="Número de CSMI"
				maxlength="8" />
		</div>
	</fieldset>

	<fieldset id="avesMuertas">
		<legend>Aves Muertas</legend>

		<div data-linea="1">
			<label for="aves_muertas">Nro. de aves muertas(al arribo): </label> <input
				type="text" id="aves_muertas" name="aves_muertas" value=""
				placeholder="Número de aves muertas" maxlength="8" />
		</div>
		<div data-linea="1">
			<label for="porcent_aves_muertas">% de aves muertas: </label> <input
				type="text" id="porcent_aves_muertas" name="porcent_aves_muertas"
				value="" placeholder="Porcentaje de aves muertas" maxlength="8" />
		</div>
		<div data-linea="2">
			<label for="causa_probable">Causa probable: </label> <input
				type="text" id="causa_probable" name="causa_probable" value=""
				placeholder="Causa probable" maxlength="1024" />
		</div>

	</fieldset>

	<fieldset id="caracteristicas">
		<legend>Características</legend>

		<div data-linea="1">
			<label for="decaidas">Nro. de aves decaídas o moribundas: </label> <input
				type="text" id="decaidas" name="decaidas" value=""
				placeholder="Aves llegaron decaídas" maxlength="8" />
		</div>
		<div data-linea="1">
			<label for="porcent_decaidas">% de aves decaídas o moribundas: </label>
			<input type="text" id="porcent_decaidas" name="porcent_decaidas"
				value="" placeholder="Porcentaje de aves que llegaron decaídas"
				maxlength="8" />
		</div>
		<div data-linea="2">
			<label for="num_traumas">Nro. de aves con traumas: </label> <input
				type="text" id="num_traumas" name="num_traumas" value=""
				placeholder="Aves con traumas" maxlength="8" />
		</div>
		<div data-linea="2">
			<label for="porcent_traumas">% de aves traumas: </label> <input
				type="text" id="porcent_traumas" name="porcent_traumas" value=""
				placeholder="Porcentaje de aves que llegaron con traumas"
				maxlength="8" />
		</div>

	</fieldset>
	<fieldset id="problemasSistemicos">
		<legend>Problemas sistémicos</legend>

		<div data-linea="1">
			<label for="probl_respirat">Nro. de aves con problemas respiratorios:
			</label> <input type="text" id="probl_respirat" name="probl_respirat"
				value="" placeholder="Aves con problemas respiratorios"
				maxlength="8" />
		</div>
		<div data-linea="1">
			<label for="porcent_probl_respirat">% de aves con problemas
				respiratorios: </label> <input type="text"
				id="porcent_probl_respirat" name="porcent_probl_respirat" value=""
				placeholder="Porcentaje de aves con problemas respiratorios"
				maxlength="8" />
		</div>
		<div data-linea="2">
			<label for="probl_nerviosos">Nro. de aves con problemas nerviosos: </label>
			<input type="text" id="probl_nerviosos" name="probl_nerviosos"
				value="" placeholder="Aves con problemas nerviosos" maxlength="8" />
		</div>
		<div data-linea="2">
			<label for="porcent_proble_nerviosos">% de aves con problemas
				nerviosos: </label> <input type="text" id="porcent_proble_nerviosos"
				name="porcent_proble_nerviosos" value=""
				placeholder="Porcentaje de aves con problemas nerviosos" />
		</div>
		<div data-linea="3">
			<label for="probl_digestivos">Nro. de aves con problemas digestivos:
			</label> <input type="text" id="probl_digestivos"
				name="probl_digestivos" value=""
				placeholder="Aves con problemas digestivos" maxlength="8" />
		</div>
		<div data-linea="3">
			<label for="porcent_probl_digestivos">% de aves con problemas
				digestivos: </label> <input type="text"
				id="porcent_probl_digestivos" name="porcent_probl_digestivos"
				value="" placeholder="Porcentaje de aves con problemas digestivos"
				maxlength="8" />
		</div>

	</fieldset>
	<fieldset id="caracteristicasExternas">
		<legend>Características externas</legend>
		<div data-linea="1">
			<label for="cabeza_hinchada">Nro. de aves con cabeza hinchada: </label>
			<input type="text" id="cabeza_hinchada" name="cabeza_hinchada"
				value="" placeholder="Aves con cabeza hinchada" maxlength="8" />
		</div>
		<div data-linea="1">
			<label for="porcent_cabeza_hinchada">% de aves con con cabeza
				hinchada: </label> <input type="text" id="porcent_cabeza_hinchada"
				name="porcent_cabeza_hinchada" value=""
				placeholder="Porcentaje de aves con cabeza hinchada" maxlength="8" />
		</div>
		<div data-linea="2">
			<label for="plumas_erizadas">Nro. de aves con plumas erizadas: </label>
			<input type="text" id="plumas_erizadas" name="plumas_erizadas"
				value="" placeholder="Aves con plumas erizadas" maxlength="8" />
		</div>
		<div data-linea="2">
			<label for="porcent_plumas_erizadas">% de aves con plumas erizadas: </label>
			<input type="text" id="porcent_plumas_erizadas"
				name="porcent_plumas_erizadas" value=""
				placeholder="Porcentaje de aves con plumas erizadas" maxlength="8" />
		</div>

	</fieldset>



	<fieldset id="dictamen">
		<legend>Dictamen</legend>

		<div data-linea="1">
			<label for="faenamiento_normal">Faenamiento normal(Nro. de aves): </label>
			<input type="text" id="faenamiento_normal" name="faenamiento_normal"
				value="" placeholder="Aves recibirán fenamiento normal"
				maxlength="8" />
		</div>
		<div data-linea="1">
			<label for="procent_faenamiento_normal">Faenamiento normal(% de
				aves): </label> <input type="text" id="procent_faenamiento_normal"
				name="procent_faenamiento_normal"
				placeholder="Porcentaje aves recibirán fenamiento normal"
				maxlength="8" />
		</div>

		<div data-linea="2">
			<label for="faenamiento_especial">Faenamiento bajo precauciones
				especiales(Nro. de aves): </label> <input type="text"
				id="faenamiento_especial" name="faenamiento_especial"
				placeholder="Aves recibirán fenamiento bajo precauciones especiales"
				maxlength="8" />
		</div>

		<div data-linea="2">
			<label for="porcent_faenamiento_especial">Faenamiento bajo
				precauciones especiales(% de aves): </label> <input type="text"
				id="porcent_faenamiento_especial"
				name="porcent_faenamiento_especial"
				placeholder="Porcentaje aves recibirán fenamiento bajo precauciones especiales"
				maxlength="8" />
		</div>

		<div data-linea="3">
			<label for="faenamiento_emergencia">Faenamiento de emergencia(Nro. de
				aves): </label> <input type="text" id="faenamiento_emergencia"
				name="faenamiento_emergencia"
				placeholder="Aves recibirán faenmaiento de emergencia" maxlength="8" />
		</div>

		<div data-linea="3">
			<label for="porcent_emergencia">Faenamiento de emergencia(% de aves):
			</label> <input type="text" id="porcent_emergencia"
				name="porcent_emergencia"
				placeholder="Porcentaje aves recibirán faenmaiento de emergencia"
				maxlength="8" />
		</div>

		<div data-linea="4">
			<label for="aplazamiento_faenamiento">Aplazamiento de
				faenamiento(Nro. de aves): </label> <input type="text"
				id="aplazamiento_faenamiento" name="aplazamiento_faenamiento"
				placeholder="Aplazamiento del faenamiento" maxlength="8" />
		</div>

		<div data-linea="4">
			<label for="porcent_aplazamiento_faenamiento">Aplazamiento de
				faenamiento(% de aves) </label> <input type="text"
				id="porcent_aplazamiento_faenamiento"
				name="porcent_aplazamiento_faenamiento"
				placeholder="Porcentaje del aplazamiento del faenamiento"
				maxlength="8" />
		</div>

		<div data-linea="5">
			<label for="total_faenamiento">TOTAL </label> <input type="text"
				id="total_faenamiento" name="total_faenamiento"
				placeholder="Suma total del dictamen" readonly maxlength="8" />
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
					<th>Nro. GUIA</th>
					<th>Fecha</th>
					<th>Tipo de ave</th>
					<th>Previsualizar</th>
				</tr>
			
			
			<tbody id="bodyTbl">
			<?php echo $this->datosDetalleFormulario;?>
			</tbody>
		</table>

	</fieldset>

	<div data-linea="1">

		<button type="button" id="enviarRevision" class="">Enviar a revisión</button>
		<button type="button" id="aprobar" class="">Aprobar</button>
		<button type="button" id="generar" class="">Generar</button>
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
    var fechaActual = <?php echo json_encode(date("Y-m-d"));?>;
    var idFormularioDetalle = <?php echo json_encode($this->idFormularioEditar);?>;
    var perfilUsuario = <?php echo json_encode($this->perfilUsuario); ?>;
    var arreglo = [];
	$(document).ready(function() {
		construirValidador();
		establecerFechas('fecha_formulario',fechaActual);
		setearVariablesIniciales();
		mostrarMensaje("", "FALLO");
		$("#avesMuertas").hide();
	    $("#caracteristicas").hide();
	    $("#problemasSistemicos").hide();
	    $("#caracteristicasExternas").hide();
	    
	    if($("#id_formulario_ante_mortem").val() == ''){
	    	$("#enviarRevision").hide();
        	$("#aprobar").hide();
		 }else{
			 if(perfilUsuario == "PFL_APM_CF_OP"){
		        	$("#aprobar").show();
		        	$("#enviarRevision").hide();
				 }else{
					$("#enviarRevision").show();
					$("#aprobar").hide();
					 if(estadoRegistro == 'Por revisar'){
				        	$("#generalidades").hide();
				        	$("#dictamen").hide();
				        	$("#observaciones").hide();
				        	$("#agregarFormulario").hide();
				        	$("#enviarRevision").hide();
				        	$("#enviarRevision").hide();
				           }
				 }
		}
	    if(estadoRegistro == 'Aprobado_AM'){
        	$("#generalidades").hide();
        	$("#dictamen").hide();
        	$("#observaciones").hide();
        	$("#agregarFormulario").hide();
        	$("#enviarRevision").hide();
        	$("#aprobar").hide();
           }else{
        	$("#generar").hide();
           }
	    distribuirLineas();
			 
	 });

	//verificar campo total
    $("#total_aves").change(function () {
    	if(!$.trim($(this).val())){
    		setearCamposCambioTotal();
    	}
    });
    //verificar que campo esta vacio
    $( "#total_aves" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCamposCambioTotal();
    	}
    });
//*****************************************************************************************************
 //validar el ingreso de información de aves en cantidad
    $("#aves_muertas").change(function () {
    	validarIngresoInfo("aves_muertas","porcent_aves_muertas","total_aves",1);
    });
    //verificar que campo esta vacio
    $( "#aves_muertas" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_aves_muertas", 2);
    	}
    });
    //validar el ingreso de información de aves en porcentaje
    $("#porcent_aves_muertas").change(function () {
    	validarIngresoInfo("aves_muertas","porcent_aves_muertas","total_aves",2);
    });
    //verificar que campo esta vacio
    $( "#porcent_aves_muertas" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("aves_muertas", 2);
    	}
    });
//*****************************************************************************************************
//validar el ingreso de información de aves decaidas en cantidad
    $("#decaidas").change(function () {
    	validarIngresoInfo("decaidas","porcent_decaidas","total_aves",1);
    });
    //verificar que campo esta vacio
    $( "#decaidas" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_decaidas", 2);
    	}
    });
    //validar el ingreso de información de aves decaidas en porcentaje
    $("#porcent_decaidas").change(function () {
    	validarIngresoInfo("decaidas","porcent_decaidas","total_aves",2);
    });
    //verificar que campo esta vacio
    $( "#porcent_decaidas" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("decaidas", 2);
    	}
    });
//*****************************************************************************************************    
 //validar el ingreso de información de aves con traumas en cantidad
    $("#num_traumas").change(function () {
    	validarIngresoInfo("num_traumas","porcent_traumas","total_aves",1);
    });
    //verificar que campo esta vacio
    $( "#num_traumas" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_traumas", 2);
    	}
    });
    //validar el ingreso de información de aves con traumas en porcentaje
    $("#porcent_traumas").change(function () {
    	validarIngresoInfo("num_traumas","porcent_traumas","total_aves",2);
    });
    //verificar que campo esta vacio
    $( "#porcent_traumas" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("num_traumas", 2);
    	}
    });
//*****************************************************************************************************	
//validar el ingreso de información de aves con problemas respiratorios en cantidad
    $("#probl_respirat").change(function () {
    	validarIngresoInfo("probl_respirat","porcent_probl_respirat","total_aves",1);
    });
    //verificar que campo esta vacio
    $( "#probl_respirat" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_probl_respirat", 2);
    	}
    });
    //validar el ingreso de información de aves con problemas respiratorios en porcentaje
    $("#porcent_probl_respirat").change(function () {
    	validarIngresoInfo("probl_respirat","porcent_probl_respirat","total_aves",2);
    });
    //verificar que campo esta vacio
    $( "#porcent_probl_respirat" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("probl_respirat", 2);
    	}
    });
//*****************************************************************************************************
//validar el ingreso de información de aves con problemas nerviosos en cantidad
    $("#probl_nerviosos").change(function () {
    	validarIngresoInfo("probl_nerviosos","porcent_proble_nerviosos","total_aves",1);
    });
    //verificar que campo esta vacio
    $( "#probl_nerviosos" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_proble_nerviosos", 2);
    	}
    });
    //validar el ingreso de información de aves con problemas nerviosos en porcentaje
    $("#porcent_proble_nerviosos").change(function () {
    	validarIngresoInfo("probl_nerviosos","porcent_proble_nerviosos","total_aves",2);
    });
    //verificar que campo esta vacio
    $( "#porcent_proble_nerviosos" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("probl_nerviosos", 2);
    	}
    });	
//*****************************************************************************************************
//validar el ingreso de información de aves con problemas digestivos en cantidad
    $("#probl_digestivos").change(function () {
    	validarIngresoInfo("probl_digestivos","porcent_probl_digestivos","total_aves",1);
    });
    //verificar que campo esta vacio
    $( "#probl_digestivos" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_probl_digestivos", 2);
    	}
    });
    //validar el ingreso de información de aves con problemas digestivos en porcentaje
    $("#porcent_probl_digestivos").change(function () {
    	validarIngresoInfo("probl_digestivos","porcent_probl_digestivos","total_aves",2);
    });
    //verificar que campo esta vacio
    $( "#porcent_probl_digestivos" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("probl_digestivos", 2);
    	}
    });	
//*****************************************************************************************************
//validar el ingreso de información de aves con cabeza hinchada en cantidad
    $("#cabeza_hinchada").change(function () {
    	validarIngresoInfo("cabeza_hinchada","porcent_cabeza_hinchada","total_aves",1);
    });
    //verificar que campo esta vacio
    $( "#cabeza_hinchada" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_cabeza_hinchada", 2);
    	}
    });
    //validar el ingreso de información de aves con cabeza hinchada en porcentaje
    $("#porcent_cabeza_hinchada").change(function () {
    	validarIngresoInfo("cabeza_hinchada","porcent_cabeza_hinchada","total_aves",2);
    });
    //verificar que campo esta vacio
    $( "#porcent_cabeza_hinchada" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("cabeza_hinchada", 2);
    	}
    });
//*****************************************************************************************************
    //validar el ingreso de información de aves con plumas erizadas en cantidad
    $("#plumas_erizadas").change(function () {
    	validarIngresoInfo("plumas_erizadas","porcent_plumas_erizadas","total_aves",1);
    });
    //verificar que campo esta vacio
    $( "#plumas_erizadas" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_plumas_erizadas", 2);
    	}
    });
    //validar el ingreso de información de aves con plumas erizadas en porcentaje
    $("#porcent_plumas_erizadas").change(function () {
    	validarIngresoInfo("plumas_erizadas","porcent_plumas_erizadas","total_aves",2);
    });
    //verificar que campo esta vacio
    $( "#porcent_plumas_erizadas" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("plumas_erizadas", 2);
    	}
    });
//*****************************************************************************************************    
  //validar el ingreso de información de aves faenamiento normal en cantidad
    $("#faenamiento_normal").change(function () {
    	validarIngresoInfo("faenamiento_normal","procent_faenamiento_normal","total_aves",1);
    	sumarDictamen("faenamiento_normal");
    });
    //verificar que campo esta vacio
    $( "#faenamiento_normal" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("procent_faenamiento_normal", 2);
    		sumarDictamen("faenamiento_normal");
    	}
    });
    //validar el ingreso de información de aves faenamiento normal en porcentaje
    $("#procent_faenamiento_normal").change(function () {
    	validarIngresoInfo("faenamiento_normal","procent_faenamiento_normal","total_aves",2);
    	sumarDictamen("procent_faenamiento_normal");
    });
    //verificar que campo esta vacio
    $( "#procent_faenamiento_normal" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("faenamiento_normal", 2);
    		sumarDictamen("procent_faenamiento_normal");
    	}
    });
//*****************************************************************************************************
//validar el ingreso de información de aves faenamiento bajo precauciones especiales en cantidad
    $("#faenamiento_especial").change(function () {
    	validarIngresoInfo("faenamiento_especial","porcent_faenamiento_especial","total_aves",1);
    	sumarDictamen("faenamiento_especial");
    });
    //verificar que campo esta vacio
    $( "#faenamiento_especial" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_faenamiento_especial", 2);
    		sumarDictamen("faenamiento_especial");
    	}
    });
    //validar el ingreso de información de aves faenamiento bajo precauciones especiales en porcentaje
    $("#porcent_faenamiento_especial").change(function () {
    	validarIngresoInfo("faenamiento_especial","porcent_faenamiento_especial","total_aves",2);
    	sumarDictamen("porcent_faenamiento_especial");
    });
    //verificar que campo esta vacio
    $( "#porcent_faenamiento_especial" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("faenamiento_especial", 2);
    		sumarDictamen("porcent_faenamiento_especial");
    	}
    });
//*****************************************************************************************************
//validar el ingreso de información de aves faenamiento de emergencia en cantidad
    $("#faenamiento_emergencia").change(function () {
    	validarIngresoInfo("faenamiento_emergencia","porcent_emergencia","total_aves",1);
    	sumarDictamen("faenamiento_emergencia");
    });
    //verificar que campo esta vacio
    $( "#faenamiento_emergencia" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_emergencia", 2);
    		sumarDictamen("faenamiento_emergencia");
    	}
    });
    //validar el ingreso de información de aves faenamiento de emergencia en porcentaje
    $("#porcent_emergencia").change(function () {
    	validarIngresoInfo("faenamiento_emergencia","porcent_emergencia","total_aves",2);
    	sumarDictamen("porcent_emergencia");
    });
    //verificar que campo esta vacio
    $( "#porcent_emergencia" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("faenamiento_emergencia", 2);
    		sumarDictamen("porcent_emergencia");
    	}
    });
//*****************************************************************************************************
//validar el ingreso de información de aves aplazamiento de faenamiento en cantidad
    $("#aplazamiento_faenamiento").change(function () {
    	validarIngresoInfo("aplazamiento_faenamiento","porcent_aplazamiento_faenamiento","total_aves",1);
    	sumarDictamen("aplazamiento_faenamiento");
    });
    //verificar que campo esta vacio
    $( "#aplazamiento_faenamiento" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("porcent_aplazamiento_faenamiento", 2);
    		sumarDictamen("aplazamiento_faenamiento");
    	}
    });
    //validar el ingreso de información de aves aplazamiento de faenamiento en porcentaje
    $("#porcent_aplazamiento_faenamiento").change(function () {
    	validarIngresoInfo("aplazamiento_faenamiento","porcent_aplazamiento_faenamiento","total_aves",2);
    	sumarDictamen("porcent_aplazamiento_faenamiento");
    });
    //verificar que campo esta vacio
    $( "#porcent_aplazamiento_faenamiento" ).keyup(function() {
    	if(!$.trim($(this).val())){
    		setearCampo("aplazamiento_faenamiento", 2);
    		sumarDictamen("porcent_aplazamiento_faenamiento");
    	}
    });
//*****************************************************************************************************
	$("#agregarFormulario").click(function () {
        $(".alertaCombo").removeClass("alertaCombo");
      	var error = false;
      	if($("#hallazgos").val() == 'Si'){
      		error = verificarCamposObligatorios(1);
      	}else{
      		error = verificarCamposObligatorios(2);
      	}
        if(!error){
        	var totalAves = (isNaN(parseFloat($("#total_aves").val()))) ? "0" : parseFloat($("#total_aves").val());
          	var totalDictamen = (isNaN(parseFloat($("#total_faenamiento").val()))) ? "0" : parseFloat($("#total_faenamiento").val());
        	if(totalDictamen == totalAves){
        		$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioAnteMortem/agregarFormularioAves", 
                        {
        			        //*****cabecera*******
        			        id_formulario_ante_mortem: $("#id_formulario_ante_mortem").val(),
        			        idCentroFaenamiento: idCentroFaenamiento,
							//*****generalidades*****
			      		    fecha_formulario: $("#fecha_formulario").val(),
        		            total_aves: $("#total_aves").val(),
        		            promedio_aves: $("#promedio_aves").val(),
        		            tipo_ave: $("#tipo_ave option:selected").text(),
        		            lugar_procedencia: $("#lugar_procedencia").val(),
			      		    hallazgos: $("#hallazgos").val(),
			      		    num_csmi: $("#num_csmi").val(),
                	        //*****aves muertas*****
                			aves_muertas: $("#aves_muertas").val(),
			      		    porcent_aves_muertas: $("#porcent_aves_muertas").val(),
			      		    causa_probable: $("#causa_probable").val(),
                			//*****Características*****
                			decaidas: $("#decaidas").val(),
			      		    porcent_decaidas: $("#porcent_decaidas").val(),
        		            num_traumas: $("#num_traumas").val(),
        		            porcent_traumas: $("#porcent_traumas").val(),
                			//*****Problemas sistémicos*****
                			probl_respirat: $("#probl_respirat").val(),
        		            porcent_probl_respirat: $("#porcent_probl_respirat").val(),
        		            probl_nerviosos: $("#probl_nerviosos").val(),
        		            porcent_proble_nerviosos: $("#porcent_proble_nerviosos").val(),
        		            probl_digestivos: $("#probl_digestivos").val(),
        		            porcent_probl_digestivos: $("#porcent_probl_digestivos").val(),
                			//*****Características externas*****
                			cabeza_hinchada: $("#cabeza_hinchada").val(),
        		            porcent_cabeza_hinchada: $("#porcent_cabeza_hinchada").val(),
        		            plumas_erizadas: $("#plumas_erizadas").val(),
        		            porcent_plumas_erizadas: $("#porcent_plumas_erizadas").val(),
                			//*****dictamen*****
                			faenamiento_normal: $("#faenamiento_normal").val(),
        		            procent_faenamiento_normal: $("#procent_faenamiento_normal").val(),
        		            faenamiento_especial: $("#faenamiento_especial").val(),
        		            porcent_faenamiento_especial: $("#porcent_faenamiento_especial").val(),
        		            faenamiento_emergencia: $("#faenamiento_emergencia").val(),
        		            porcent_emergencia: $("#porcent_emergencia").val(),
        		            aplazamiento_faenamiento: $("#aplazamiento_faenamiento").val(),
        		            porcent_aplazamiento_faenamiento: $("#porcent_aplazamiento_faenamiento").val(),
        		            total_faenamiento: $("#total_faenamiento").val(),
        		            //********observacion*****
        		            observacion: $("#observacion").val()
                        	
     					},
     					function (data) {
     						  if(data.estado === 'EXITO'){
     							 $("#bodyTbl").html(data.contenido);
     							 $("#id_formulario_ante_mortem").val(data.id);
     							 setearVariablesRegistro();
     							 if(perfilUsuario == "PFL_APM_CF_OP"){
     					        	$("#aprobar").show();
     							 }else{
     								$("#enviarRevision").show();
     							 }
     							 mostrarMensaje(data.mensaje, "EXITO");
     						  }else{
     							  mostrarMensaje(data.mensaje, "FALLO");
     						  }
     		        	}, 'json');  
        	           
    		}else{
    			error = true;
          		mostrarMensaje("El total de DICTAMEN debe ser igual al total de AVES.", "FALLO");
          		$("#total_faenamiento").addClass("alertaCombo");
          		$("#total_aves").addClass("alertaCombo");

        		}
  		}else{
  			mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
  		}
	});
//*****************************************************************************************************
//validar si tiene hallazgos o no el formulario******
    $("#hallazgos").change(function () {

    	if($("#hallazgos").val() == 'Si'){
    		    $("#avesMuertas").show();
    	        $("#caracteristicas").show();
    	        $("#problemasSistemicos").show();
    	        $("#caracteristicasExternas").show();
    	        distribuirLineas();
    	}else{
    		    $("#avesMuertas").hide();
    	        $("#caracteristicas").hide();
    	        $("#problemasSistemicos").hide();
    	        $("#caracteristicasExternas").hide();
    	}
       
        });
    //************setear los campos**********
	function setearVariablesIniciales(){
		//*****generalidades*****
		$("#total_aves").numeric();
		$("#promedio_aves").numeric();
		$("#num_csmi").numeric();
		//*****aves muertas*****
		$("#aves_muertas").numeric();
		$("#porcent_aves_muertas").numeric();
		//*****Características*****
		$("#decaidas").numeric();
		$("#porcent_decaidas").numeric();
		$("#num_traumas").numeric();
		$("#porcent_traumas").numeric();
		//*****Problemas sistémicos*****
		$("#probl_respirat").numeric();
		$("#porcent_probl_respirat").numeric();
		$("#probl_nerviosos").numeric();
		$("#porcent_proble_nerviosos").numeric();
		$("#probl_digestivos").numeric();
		$("#porcent_probl_digestivos").numeric();
		//*****Características externas*****
		$("#cabeza_hinchada").numeric();
		$("#porcent_cabeza_hinchada").numeric();
		$("#plumas_erizadas").numeric();
		$("#porcent_plumas_erizadas").numeric();
		//*****dictamen*****
		$("#faenamiento_normal").numeric();
		$("#procent_faenamiento_normal").numeric();
		$("#faenamiento_especial").numeric();
		$("#porcent_faenamiento_especial").numeric();
		$("#faenamiento_emergencia").numeric();
		$("#porcent_emergencia").numeric();
		$("#aplazamiento_faenamiento").numeric();
		$("#porcent_aplazamiento_faenamiento").numeric();
		//**********ocultar funciones
		$("#avesMuertas").hide();
        $("#caracteristicas").hide();
        $("#problemasSistemicos").hide();
        $("#caracteristicasExternas").hide(); 
	}
	 //************setear los campos vaciar cuando se cambie el total**********
	function setearCamposCambioTotal(){
		 $("#fecha_formulario").val(fechaActual),
         $("#total_aves").val(''),
         $("#promedio_aves").val(''),
         $("#tipo_ave").val(''),
         $("#lugar_procedencia").val(''),
		 $("#hallazgos").val(''),
		 $("#num_csmi").val(''),
		//*****aves muertas*****
		$("#aves_muertas").val('');
		$("#porcent_aves_muertas").val('');
		$("#causa_probable").val('');
		//*****Características*****
		$("#decaidas").val('');
		$("#porcent_decaidas").val('');
		$("#num_traumas").val('');
		$("#porcent_traumas").val('');
		//*****Problemas sistémicos*****
		$("#probl_respirat").val('');
		$("#porcent_probl_respirat").val('');
		$("#probl_nerviosos").val('');
		$("#porcent_proble_nerviosos").val('');
		$("#probl_digestivos").val('');
		$("#porcent_probl_digestivos").val('');
		//*****Características externas*****
		$("#cabeza_hinchada").val('');
		$("#porcent_cabeza_hinchada").val('');
		$("#plumas_erizadas").val('');
		$("#porcent_plumas_erizadas").val('');
		//*****dictamen*****
		$("#faenamiento_normal").val('');
		$("#procent_faenamiento_normal").val('');
		$("#faenamiento_especial").val('');
		$("#porcent_faenamiento_especial").val('');
		$("#faenamiento_emergencia").val('');
		$("#porcent_emergencia").val('');
		$("#aplazamiento_faenamiento").val('');
		$("#porcent_aplazamiento_faenamiento").val('');
		
	}
	 //************setear los campos cuando se guarde un registro**********
	function setearVariablesRegistro(){
		//*******generalidades************************
		$("#fecha_formulario").val(fechaActual);
		$("#total_aves").val('');
		$("#tipo_ave").val('');
		$("#promedio_aves").val('');
		$("#lugar_procedencia").val('');
		$("#hallazgos").val('');
		$("#num_csmi").val('');
		
		//*****aves muertas*****
		$("#aves_muertas").val('');
		$("#porcent_aves_muertas").val('');
		$("#causa_probable").val('');
		//*****Características*****
		$("#decaidas").val('');
		$("#porcent_decaidas").val('');
		$("#num_traumas").val('');
		$("#porcent_traumas").val('');
		//*****Problemas sistémicos*****
		$("#probl_respirat").val('');
		$("#porcent_probl_respirat").val('');
		$("#probl_nerviosos").val('');
		$("#porcent_proble_nerviosos").val('');
		$("#probl_digestivos").val('');
		$("#porcent_probl_digestivos").val('');
		//*****Características externas*****
		$("#cabeza_hinchada").val('');
		$("#porcent_cabeza_hinchada").val('');
		$("#plumas_erizadas").val('');
		$("#porcent_plumas_erizadas").val('');
		//*****dictamen*****
		$("#faenamiento_normal").val('');
		$("#procent_faenamiento_normal").val('');
		$("#faenamiento_especial").val('');
		$("#porcent_faenamiento_especial").val('');
		$("#faenamiento_emergencia").val('');
		$("#porcent_emergencia").val('');
		$("#aplazamiento_faenamiento").val('');
		$("#porcent_aplazamiento_faenamiento").val('');
		$("#total_faenamiento").val('');
		//***********+observacion****************
		$("#observacion").val('');
		
	}
//***********************************************************************************
//********sumar el total del dictamen****
	function sumarDictamen(id){
		var total = 0;
		//*****dictamen*****
		var normal = (isNaN(parseFloat($("#faenamiento_normal").val()))) ? "0" : parseFloat($("#faenamiento_normal").val());
		var especial = (isNaN(parseFloat($("#faenamiento_especial").val()))) ? "0" : parseFloat($("#faenamiento_especial").val());
		var emergencia = (isNaN(parseFloat($("#faenamiento_emergencia").val()))) ? "0" : parseFloat($("#faenamiento_emergencia").val());
		var aplazamiento = (isNaN(parseFloat($("#aplazamiento_faenamiento").val()))) ? "0" : parseFloat($("#aplazamiento_faenamiento").val());
		var totalDictamen = parseFloat(normal)+parseFloat(especial)+parseFloat(emergencia)+parseFloat(aplazamiento);
		var totalAves = (isNaN(parseFloat($("#total_aves").val()))) ? "0" : parseFloat($("#total_aves").val());
		if(totalDictamen <= totalAves){
			 $("#total_faenamiento").val(totalDictamen.toFixed(3));
			 $("#total_faenamiento").removeClass("alertaCombo");
			 $("#agregarFormulario").removeAttr('disabled');
		}else{
			mostrarMensaje("El total del DICTAMEN no puede ser mayor al total de AVES...!!", "FALLO");
			setearCampo(id, 4);
			setearCampo("total_faenamiento", 4);
			$("#total_faenamiento").val(totalDictamen);
			$("#agregarFormulario").attr('disabled','disabled');
		}
		
	}

//*******************************************************************************************
//************verificar campos obligatorios*******
	function verificarCamposObligatorios(opt){

		var error = false;
		switch (opt) { 
		case 1: 
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
		      if (!$.trim($("#hallazgos").val())) {
		  			$("#hallazgos").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#num_csmi").val())) {
		  			$("#num_csmi").addClass("alertaCombo");
		  			error =  true;
		      }

			//*****aves muertas*****
			error = verificarAvesMuertas();
			//*****Características*****
			//*****Problemas sistémicos*****
			//*****Características externas*****
			if(verificarAvesMuertasGrupo() == true && verificarCaracteristicas() == true && verificarProblemas() == true && verificarProblExter() == true ){
                error = true;
              //*****aves muertas*****
        		$("#aves_muertas").addClass("alertaCombo");
        		$("#porcent_aves_muertas").addClass("alertaCombo");
        		//*****Características*****
        		$("#decaidas").addClass("alertaCombo");
        		$("#porcent_decaidas").addClass("alertaCombo");
        		$("#num_traumas").addClass("alertaCombo");
        		$("#porcent_traumas").addClass("alertaCombo");
        		//*****Problemas sistémicos*****
        		$("#probl_respirat").addClass("alertaCombo");
        		$("#porcent_probl_respirat").addClass("alertaCombo");
        		$("#probl_nerviosos").addClass("alertaCombo");
        		$("#porcent_proble_nerviosos").addClass("alertaCombo");
        		$("#probl_digestivos").addClass("alertaCombo");
        		$("#porcent_probl_digestivos").addClass("alertaCombo");
        		//*****Características externas*****
        		$("#cabeza_hinchada").addClass("alertaCombo");
        		$("#porcent_cabeza_hinchada").addClass("alertaCombo");
        		$("#plumas_erizadas").addClass("alertaCombo");
        		$("#porcent_plumas_erizadas").addClass("alertaCombo");
        		
				}
			
			//*****dictamen*****
			if (!$.trim($("#faenamiento_normal").val())) {
		  			$("#faenamiento_normal").addClass("alertaCombo");
		  			$("#procent_faenamiento_normal").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#faenamiento_especial").val())) {
		  			$("#faenamiento_especial").addClass("alertaCombo");
		  			$("#porcent_faenamiento_especial").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#faenamiento_emergencia").val())) {
		  			$("#faenamiento_emergencia").addClass("alertaCombo");
		  			$("#porcent_emergencia").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#aplazamiento_faenamiento").val())) {
		  			$("#aplazamiento_faenamiento").addClass("alertaCombo");
		  			$("#porcent_aplazamiento_faenamiento").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#total_faenamiento").val())) {
		  			$("#total_faenamiento").addClass("alertaCombo");
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
		      if (!$.trim($("#hallazgos").val())) {
		  			$("#hallazgos").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#num_csmi").val())) {
		  			$("#num_csmi").addClass("alertaCombo");
		  			error =  true;
		      }
			
			//*****dictamen*****
			if (!$.trim($("#faenamiento_normal").val())) {
		  			$("#faenamiento_normalmodeloFormularioAnteMortem").addClass("alertaCombo");
		  			$("#procent_faenamiento_normal").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#faenamiento_especial").val())) {
		  			$("#faenamiento_especial").addClass("alertaCombo");
		  			$("#porcent_faenamiento_especial").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#faenamiento_emergencia").val())) {
		  			$("#faenamiento_emergencia").addClass("alertaCombo");
		  			$("#porcent_emergencia").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#aplazamiento_faenamiento").val())) {
		  			$("#aplazamiento_faenamiento").addClass("alertaCombo");
		  			$("#porcent_aplazamiento_faenamiento").addClass("alertaCombo");
		  			error =  true;
		      }
		      if (!$.trim($("#total_faenamiento").val())) {
		  			$("#total_faenamiento").addClass("alertaCombo");
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
    	if($("#id_formulario_ante_mortem").val() != ''){
    	$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioAnteMortem/enviarRevisionAves", 
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
    	$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioAnteMortem/aprobarFormularioAves", 
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
    	$.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioAnteMortem/generarFormularioAves", 
				{
    		        id_formulario_ante_mortem: $("#id_formulario_ante_mortem").val(),
    		        estado: 'Aprobado_AM',
    		        idFormularioDetalle: idFormularioDetalle
				},
				function (data) {
					 if(data.estado == 'EXITO'){
							 mostrarMensaje(data.mensaje, "EXITO");
							 $("#formularioCreado").attr("src", data.ruta);
						  }else{
							 mostrarMensaje(data.mensaje, "FALLO");
						  }
	        	}, 'json'); 
    	}else{
    		mostrarMensaje("Debe existir el id del formulario.", "FALLO");
        	}
	});

</script>

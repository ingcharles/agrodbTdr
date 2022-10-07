<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formularioCultivo' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>PlagasLaboratorio' data-opcion='cultivos/editar' data-destino="detalleItem">
	<input type="hidden" name="id" value="<?php echo $this->modeloPlagas->getIdCultivo(); ?>"/>
		<button class="regresar">Regresar a plaga</button>
</form>


<form id='formularioPlagas' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>PlagasLaboratorio' data-opcion='plagas/actualizar' data-destino="detalleItem">
	<fieldset>
		<legend>Plaga</legend>
		
		<input type="hidden" name="id_plaga" value="<?php echo $this->modeloPlagas->getIdPlaga(); ?>" />
		
		<div data-linea="3">
			<label for="familia">Familia</label>
			<input type="text" id="familia" name="familia" value="<?php echo $this->modeloPlagas->getFamilia(); ?>" placeholder="Nombre de la familia a la cual pertenece la plaga" maxlength="256" class="validacion" disabled="disabled"/>
		</div>				

		<div data-linea="4">
			<label for="nombre_cientifico">Nombre científico</label>
			<input type="text" name="nombre_cientifico" value="<?php echo $this->modeloPlagas->getNombreCientifico(); ?>" placeholder="Nombre científico de la plaga" maxlength="256" class="validacion" disabled="disabled"/>
		</div>				

		<div data-linea="5">
			<label for="identificado_por">Identificado por</label>
			<input type="text" name="identificado_por" value="<?php echo $this->modeloPlagas->getIdentificadoPor(); ?>" placeholder="Nombre del técnico que identifica por primera vez la plaga" maxlength="256" disabled="disabled"/>
		</div>				

		<div data-linea="6">
			<label for="numero_primer_informe">Número del primer informe </label>
			<input type="text" id="numero_primer_informe" name="numero_primer_informe" value="<?php echo $this->modeloPlagas->getNumeroPrimerInforme(); ?>" placeholder="Número del primer informe en donde se detectó la plaga" maxlength="128" class="validacion" disabled="disabled"/>
		</div>
		
		<div data-linea="7">
			<label for="id_provincia_plaga">Provincia</label>
			<select id="id_provincia_plaga" name="id_provincia_plaga" class="validacion" disabled="disabled">
                <option value="">Seleccionar....</option>
                <?php
                	echo $this->comboProvinciasEc($this->modeloPlagas->getIdProvinciaPlaga());
                ?>
            </select>
			<input type="hidden" id="nombre_provincia_plaga" name="nombre_provincia_plaga" value="<?php echo $this->modeloPlagas->getNombreProvinciaPlaga(); ?>"/>
		</div>
		
		<hr/><label>DATOS DE ESPECIMEN</label><hr/>
		
		<div data-linea="8">
			<label for="especimen">Dispone de espécimen</label>
			<select id="especimen" name="especimen" disabled="disabled" class="validacion">
	            <?php
	            	echo $this->comboSiNo($this->modeloPlagas->getEspecimen());
	            ?>
	        </select>
		</div>				

		<div data-linea="9">
			<label for="ubicacion_especimen">Ubicación de espécimen</label>
			<input type="text" id="ubicacion_especimen" name="ubicacion_especimen" value="<?php echo $this->modeloPlagas->getUbicacionEspecimen(); ?>" placeholder="Lugar en donde se encuentra el espécimen." maxlength="256" disabled="disabled"/>
		</div>				

		<div data-linea="10">
			<label for="confirmacion_diagnostico">Confirmación de diagnóstico por</label>
			<input type="text" id="confirmacion_diagnostico" name="confirmacion_diagnostico" value="<?php echo $this->modeloPlagas->getConfirmacionDiagnostico(); ?>" placeholder="Se ingresará el nombre del taxónomo que confirma el diagnóstico del espécimen" maxlength="256" disabled="disabled"/>
		</div>				

		<div data-linea="11">
			<label for="observacion">Observación</label>
			<input type="text" id="observacion" name="observacion" value="<?php echo $this->modeloPlagas->getObservacion(); ?>" placeholder="Campo para ingresar una observación" maxlength="512" class="validacion" disabled="disabled"/>
		</div>
		
		
		<div data-linea="12">
			<button id="modificar" type="button" class="editar">Editar </button>
			<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
		</div>
	</fieldset>
</form>

<form id='formularioDetallePlaga' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>PlagasLaboratorio' data-opcion='PlagasDetalle/guardar' data-destino="detalleItem">
	<fieldset>
		<legend>Ingresar resultados</legend>				

		<input type="hidden" name="id_plaga" value="<?php echo $this->modeloPlagas->getIdPlaga(); ?>" />	

		<div data-linea="3">
			<label for="numero_reporte">Número de reporte</label>
			<input type="text" id="numero_reporte" name="numero_reporte" placeholder="Número de reportes en los cuales se detectó la plaga" maxlength="32" class="validacion"/>
		</div>				

		<div data-linea="4">
			<label for="id_provincia">Provincia</label>
			<select id="id_provincia" name="id_provincia" class="validacion">
                <option value="">Seleccionar....</option>
                <?php
                    echo $this->comboProvinciasEc('');
                ?>
            </select>
			<input type="hidden" id="nombre_provincia" name="nombre_provincia"/>
		</div>			

		<div data-linea="6">
			<label for="identificado_por">Identificado por</label>
			<input type="text" name="identificado_por" placeholder="Nombre del técnico que identifica la plaga" maxlength="256" />
		</div>				

		<div data-linea="7">
			<label for="fecha_ingreso">Fecha de ingreso de registro</label>
			<input type="text" id="fecha_ingreso" name="fecha_ingreso" class="validacion" />
		</div>
		
		<div data-linea="7">
			<label for="hora_ingreso">Hora de registro</label>
			<input type="text" id="hora_ingreso" name="hora_ingreso" class="validacion" placeholder="10:30"  data-inputmask="'mask': '99:99'"/>
		</div>

		<div data-linea="15">
			<button type="submit" class="mas">Agregar resultado</button>
		</div>
	</fieldset>
</form>

<fieldset>
	<legend>Resultados ingresados</legend>
		<table id="detallePlagas" style="width: 100%;">
			<thead>
				<tr>
					<th>Identificado por</th>
					<th>Provincia</th>
					<th>Número de reporte</th>
					<th>Fecha de ingreso</th>
				</tr>
			</thead>
			<?php echo $this->registroDetallePlagas; ?>
		</table>
</fieldset>

<script type="text/javascript">

	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		verificarValorDictamen($("#especimen").val());
		mostrarMensaje("","EXITO");
	 });

	$("#formularioPlagas").submit(function (event) {
		event.preventDefault();
		var error = false;
		$(".alertaCombo").removeClass("alertaCombo");

		$('#formularioPlagas .validacion').each(function(i, obj) {
			if(!$.trim($(this).val())){
				error = true;
				$(this).addClass("alertaCombo");
			}
		});

		if (!error) {
				JSON.parse(ejecutarJson($("#formularioPlagas")).responseText);
				filtrarFormulario('noRefrescar');
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#especimen").change(function() {
		verificarValorDictamen($("#especimen").val());
	});

	function verificarValorDictamen(valorDigamen) {
		if(valorDigamen=='Si'){
			$("#ubicacion_especimen").addClass("validacion");
			$("#confirmacion_diagnostico").addClass("validacion");
		}else{
			$("#ubicacion_especimen").removeClass("validacion");
			$("#confirmacion_diagnostico").removeClass("validacion");
		}
	}

	$("#formularioDetallePlaga").submit(function (event) {
		event.preventDefault();
		var error = false;
		$(".alertaCombo").removeClass("alertaCombo");

		error = verificarHora();
		
    	$('#formularioDetallePlaga .validacion').each(function(i, obj) {
			if(!$.trim($(this).val())){
				error = true;
				$(this).addClass("alertaCombo");
			}
		});

		if (!error) {
				var respuesta = JSON.parse(ejecutarJson($("#formularioDetallePlaga")).responseText);
				filtrarFormulario('noRefrescar');
				if(respuesta.estado == 'exito'){
	           		$("#detallePlagas").append(respuesta.linea);
	           		$("#formularioDetallePlaga select").each(function() { this.selectedIndex = 0 });
	                $("#formularioDetallePlaga input[type=text]").each(function() { this.value = '' });
	            }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

	//Cuando seleccionamos una provincia, llenamos el combo de cantones
    $("#id_provincia").change(function () {
        if ($(this).val !== "") {
        	$("#nombre_provincia").val($("#id_provincia option:selected").text());
        }
    });

  //Cuando seleccionamos una provincia, llenamos el combo de cantones
    $("#id_provincia_plaga").change(function () {
        if ($(this).val !== "") {
        	$("#nombre_provincia_plaga").val($("#id_provincia_plaga option:selected").text());
        }
    });

    $("#formularioCultivo").submit(function(event){
		event.stopImmediatePropagation();
		abrir($(this),event,false);
	});

    $("#fecha_ingreso").datepicker({
    	changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
	});

    $("#hora_ingreso").change(function(){
    	verificarHora();
    });

    function verificarHora() {

    	var validacion = false;
    	
		$("#hora_ingreso").removeClass('alertaCombo');
    	
    	var horaNueva = $("#hora_ingreso").val().replace(/\_/g, "0");
    	$("#hora_ingreso").val(horaNueva);

    	var hora = $("#hora_ingreso").val().substring(0,2);
    	var minuto = $("#hora_ingreso").val().substring(3,5);

    	if(parseInt(hora)>=1 && parseInt(hora)<25){
    		if(parseInt(minuto)>=0 && parseInt(minuto)<60){
    			if(parseInt(hora)==24){
    				minuto = '00';
    				$("#hora_ingreso").val('24:00');
    			}
    		}else{
    			$("#hora_ingreso").addClass('alertaCombo');
    			$("#estado").html("Los minutos ingresados están incorrecto, por favor actualice la información").addClass('alerta');
    			validacion = true;
    		}
    	}else{
    		$("#hora_ingreso").addClass('alertaCombo');
    		$("#estado").html("La hora ingresada está fuera de rango").addClass('alerta');
    		validacion = true;
    	}

    	return validacion;
	}

</script>

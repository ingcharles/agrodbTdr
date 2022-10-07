<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formularioCultivo' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>PlagasLaboratorio' data-opcion='cultivos/actualizar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<fieldset>
		<legend>Cultivo</legend>
		
			<input type="hidden" name="id_cultivo" value="<?php echo $this->modeloCultivos->getIdCultivo(); ?>" />				

		<div data-linea="2">
			<label for="nombre_comun">Nombre común</label> 
			<input type="text" id="nombre_comun" name="nombre_comun" value="<?php echo $this->modeloCultivos->getNombreComun(); ?>" placeholder="Nombre común del cultivo" maxlength="256" class="validacion" disabled="disabled"/>
		</div>

		<div data-linea="3">
			<label for="nombre_cientifico">Nombre científico</label> 
			<input type="text" name="nombre_cientifico" value="<?php echo $this->modeloCultivos->getNombreCientifico(); ?>" placeholder="Nombre científico del cultivo" maxlength="256" class="validacion" disabled="disabled"/>
		</div>
		
		<div data-linea="4">
			<button id="modificar" type="button" class="editar">Editar </button>
			<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
		</div>
	</fieldset>
	
	
	
</form>

<form id='formularioPlagas' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>PlagasLaboratorio' data-opcion='plagas/guardar' data-destino="detalleItem">
	<fieldset>
		<legend>Plaga</legend>			

			<input type="hidden" name="id_cultivo" value="<?php echo $this->modeloCultivos->getIdCultivo(); ?>" />
			

		<div data-linea="3">
			<label for="familia">Familia</label>
			<input type="text" id="familia" name="familia" placeholder="Nombre de la familia a la cual pertenece la plaga" maxlength="256" class="validacion"/>
		</div>				

		<div data-linea="4">
			<label for="nombre_cientifico">Nombre científico</label>
			<input type="text" name="nombre_cientifico" placeholder="Nombre científico de la plaga" maxlength="256" class="validacion"/>
		</div>				

		<div data-linea="5">
			<label for="identificado_por">Identificado por</label>
			<input type="text" id="identificado_por" name="identificado_por" placeholder="Nombre del técnico que identifica por primera vez la plaga" maxlength="256" />
		</div>				

		<div data-linea="6">
			<label for="numero_primer_informe">Número del primer informe </label>
			<input type="text" id="numero_primer_informe" name="numero_primer_informe" placeholder="Número del primer informe en donde se detectó la plaga" maxlength="128" class="validacion"/>
		</div>
		
		<div data-linea="7">
			<label for="id_provincia_plaga">Provincia</label>
			<select id="id_provincia_plaga" name="id_provincia_plaga" class="validacion">
                <option value="">Seleccionar....</option>
                <?php
                    echo $this->comboProvinciasEc('');
                ?>
            </select>
			<input type="hidden" id="nombre_provincia_plaga" name="nombre_provincia_plaga"/>
		</div>
		
		<hr/><label>DATOS DE ESPECIMEN</label><hr/>
		
		<div data-linea="8">
			<label for="especimen">Dispone de espécimen</label>
			<select id="especimen" name="especimen" class="validacion">
	            <?php
	                echo $this->comboSiNo('');
	            ?>
	        </select>
		</div>				

		<div data-linea="9">
			<label for="ubicacion_especimen">Ubicación de espécimen</label>
			<input type="text" id="ubicacion_especimen" name="ubicacion_especimen" placeholder="Lugar en donde se encuentra el espécimen." maxlength="256" />
		</div>				

		<div data-linea="10">
			<label for="confirmacion_diagnostico">Confirmación de diagnóstico por</label>
			<input type="text" id="confirmacion_diagnostico" name="confirmacion_diagnostico" placeholder="Se ingresará el nombre del taxónomo que confirma el diagnóstico del espécimen" maxlength="256" />
		</div>				

		<div data-linea="11">
			<label for="observacion">Observación</label>
			<input type="text" id="observacion" name="observacion" placeholder="Campo para ingresar una observación" maxlength="512" class="validacion"/>
		</div>

		<div data-linea="12">
			<button type="submit" class="mas">Agregar plaga</button>
		</div>
	</fieldset>
</form>

<fieldset>
	<legend>Detalle de plagas</legend>
		<table id="plagas">
			<?php echo $this->registroPlagas; ?>
		</table>
</fieldset>

<script type="text/javascript">
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		mostrarMensaje("","EXITO");
	 });

	$("#formularioCultivo").submit(function (event) {
		event.preventDefault();
		var error = false;
		$(".alertaCombo").removeClass("alertaCombo");

		$('#formularioCultivo .validacion').each(function(i, obj) {
			if(!$.trim($(this).val())){
				error = true;
				$(this).addClass("alertaCombo");
			}
		});

		if (!error) {
				JSON.parse(ejecutarJson($("#formularioCultivo")).responseText);
				filtrarFormulario('noRefrescar');
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#especimen").change(function() {
		if($("#especimen").val()=='Si'){
			$("#ubicacion_especimen").addClass("validacion");
			$("#confirmacion_diagnostico").addClass("validacion");
		}else{
			$("#ubicacion_especimen").removeClass("validacion");
			$("#confirmacion_diagnostico").removeClass("validacion");
		}
		
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
				var respuesta = JSON.parse(ejecutarJson($("#formularioPlagas")).responseText);
				filtrarFormulario('noRefrescar');
				if(respuesta.estado == 'exito'){
	           		$("#plagas").append(respuesta.linea);
	           		$("#formularioPlagas select").each(function() { this.selectedIndex = 0 });
	                $("#formularioPlagas input[type=text]").each(function() { this.value = '' });
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

	$("#plagas").on("submit","form.abrir",function(event){
		event.stopImmediatePropagation();
		abrir($(this),event,false);
	});

	//Cuando seleccionamos una provincia, llenamos el combo de cantones
    $("#id_provincia_plaga").change(function () {
        if ($(this).val !== "") {
        	$("#nombre_provincia_plaga").val($("#id_provincia_plaga option:selected").text());
        }
    });

</script>

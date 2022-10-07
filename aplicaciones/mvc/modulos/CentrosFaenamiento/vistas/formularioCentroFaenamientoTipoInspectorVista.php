<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='centroFaenamientoTipoInspector'>
	<input type="hidden" id="id_tipo_inspector" name="id_tipo_inspector" value="<?php echo $this->modeloCentroFaenamientoTipoInspector->getIdTipoInspector(); ?>" />
	<input type="hidden" name="tipo_inspector" id="tipo_inspector" value="<?php echo $this->modeloCentroFaenamientoTipoInspector->getTipoInspector() ?>">
	<input type="hidden" name="contador" id="contador" value="<?php echo $this->modeloCentroFaenamientoTipoInspector->getContador() ?>">
	<input type="hidden" name="identificador_inspector" id="identificador_inspector" value="<?php echo $this->modeloCentroFaenamientoTipoInspector->getIdentificadorOperador() ?>">

	<fieldset>
		<legend>Datos veterinario/auxiliar</legend>
		<div data-linea="1">
			<label>RUC/C.I:</label> <span><?php echo $this->modeloCentroFaenamientoTipoInspector->getIdentificadorOperador() ?></span>
		</div>
		<div data-linea="2">
			<label>Nombre:</label> <span><?php echo $this->modeloCentroFaenamientoTipoInspector->getRazonSocial() ?></span>
		</div>
		<div data-linea="3">
			<label>Tipo operación:</label> <span><?php echo $this->modeloCentroFaenamientoTipoInspector->getTipoInspector() ?></span>
		</div>
		<div data-linea="4">
			<label>#C.F asignados:</label> <span><?php echo $this->modeloCentroFaenamientoTipoInspector->getContador() ?></span>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Selección Centro de Faenamiento</legend>

		<div data-linea="1">
			<label for="identificador_operador">RUC </label> 
				<input type="text" id="identificador_operador" name="identificador_operador" placeholder="Identificador del operador" required="required"	maxlength="13" />
		</div>

		<div data-linea="2">
			<label for="id_centro_faenamiento">Sitio </label> 
				<select id="id_centro_faenamiento" name="id_centro_faenamiento" disabled>
					<option value="">Seleccionar....</option>
			</select>

		</div>
		
		<div id="agregar">
			<button type="button" class="mas" onclick="agregarCentroFaenamientoTipoInspector()">Agregar</button>
		</div>

	</fieldset>

</form>


<fieldset>
		<legend>Datos veterinario/auxiliar - Centro faenamiento</legend>
            <table class="lista" id="lista_veterinario_centro" style="text-align: center; width: 100%">
            	<thead>
            
            		<tr>
            			<th>#</th>
            			<th>Nombre vet/aux</th>
            			<th>Tipo</th>
            			<th>Centro<br>Faenamiento
            			</th>
            			<th>Sitio</th>
            			<th>Especie</th>
            			<th>Eliminar</th>
            		</tr>
            	</thead>
            	<tbody>
					<?php echo $this->veterinariosAuxiliares;?>
				</tbody>
			</table>
		</fieldset>
		
<script type="text/javascript">



	$(document).ready(function() {
		distribuirLineas();
		fn_numerar();
	 });
	
	 //Cuando ingresamos RUC llena el combo sitio
    $("#identificador_operador").change(function () {
        $.post("<?php echo URL ?>CentrosFaenamiento/CentroFaenamientoTipoInspector/comboSitioFaenamiento", 
			{
				identificadorOperador: $(this).val(),
				tipoInspector: $("#tipo_inspector").val()
			},
			function (data) {
            	$("#id_centro_faenamiento").html(data);
            	$("#id_centro_faenamiento").removeAttr("disabled");
        	});
    });

    // Función llamada al Agregar item
    function agregarCentroFaenamientoTipoInspector() {

    	$(".alertaCombo").removeClass("alertaCombo");
    	var error = false;

    	if(!$.trim($("#identificador_operador").val())){
			error = true;
			$("#identificador_operador").addClass("alertaCombo");
		}

        if (!$.trim($("#id_centro_faenamiento").val())) {
        	error = true;
			$("#id_centro_faenamiento").addClass("alertaCombo");
        }

        if(!error){
        	$(".id_centro").each(function () {
                if ($('#id_centro_faenamiento').val() === $(this).val()) {
                	error = true;
                	mostrarMensaje("El centro de faenamiento seleccionado ha sido ingresado previamente.", "FALLO");
                	return false;
                }
            });

        	if(!error){
                    fn_agregarFila();
			}
		}else{
			mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
		}
    }

    //Función que agrega una fila en la lista
    function fn_agregarFila() {
        var identificador = $('#identificador_operador').val();
    	$.post("<?php echo URL ?>CentrosFaenamiento/CentroFaenamientoTipoInspector/guardarDatosCentroFaenamientoTipoInspector", 
                {
            		id_tipo_inspector: $('#id_tipo_inspector').val(),
        	        id_centro_faenamiento: $('#id_centro_faenamiento').val(),
        	        identificador_operador: $('#identificador_inspector').val()
                }, function (data) {
                	if (data.estado === 'ERROR') {
                        mostrarMensaje(data.mensaje, "FALLO");
                    } else {
                    	$("#lista_veterinario_centro tbody").html(data.contenido);
                    	mostrarMensaje(data.mensaje, data.estado);
                        fn_numerar();
                        fn_filtrar();
                    }
        }, 'json');
    }
 
 
  //enumera las filas cuando se agrega y se elimina una fila
    function fn_numerar() {
        var total = $('#lista_veterinario_centro >tbody >tr').length;
        for (var i = 1; i <= total; i++) {
            document.getElementById("lista_veterinario_centro").rows[i ].cells[0].innerText = i;
        }
    }

	$("#lista_veterinario_centro").on("submit","form.borrar",function(event){
        event.preventDefault();
        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
        if (respuesta.estado == 'exito'){
        	$("#lista_veterinario_centro tr").eq($($("#id_centro_faenamiento_tipo_inspector").val()).index()).remove();
        	fn_numerar();
            fn_filtrar();
        }
    });
</script>

<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>PasaporteEquino' data-opcion='equinos/guardar' data-destino="detalleItem" method="post">
	<input type="hidden" id="id_organizacion_ecuestre" name="id_organizacion_ecuestre" value="<?php echo $this->idAsociacion; ?>" />
			
	<fieldset>
		<legend>Selección de Predio</legend>				

		<div data-linea="1">
			<label>Organización Ecuestre: </label> <?php echo $this->modeloOrganizacionEcuestre->current()->nombre_asociacion; ?>
		</div>		
		
		<hr />		

		<div data-linea="2">
			<label for="id_provincia">Provincia: </label>
			<select id="id_provincia" name="id_provincia"> 
            	<?php echo $this->comboProvinciasXMiembro(); ?>
            </select>
		</div>				

		<div data-linea="3">
			<label for="id_miembro">Miembros: </label>
			<select id="id_miembro" name="id_miembro"> 
            </select>
		</div>				

		<div data-linea="4">
			<label for="id_catastro_predio_equidos">Predio: </label>
			<select id="id_catastro_predio_equidos" name="id_catastro_predio_equidos"> 
            </select>
		</div>				

	</fieldset >

	<fieldset class="detalleEspeciePredio">
		<legend>Especie y cantidad existente</legend>

		<div data-linea="5">
			<label for="id_especie">Especie: </label>
			<select id="id_especie" name="id_especie"> 
            </select>
		</div>				

		<div data-linea="6">
			<label for="id_raza">Raza: </label>
			<select id="id_raza" name="id_raza"> 
            </select>
		</div>
		
		<div data-linea="7">
			<label for="id_categoria">Categoría: </label>
			<select id="id_categoria" name="id_categoria"> 
            </select>
		</div>
		
		<hr  class="numeroAnimales"/>	
		
		<div data-linea="8" class="numeroAnimales">
			<label for="numero_total">Número total de animales en el predio: </label>
			<input type="text" id="numero_total" name="numero_total" disabled> 
		</div>
		
		<div data-linea="10" class="numeroAnimales">
			<label for="numero_pasaportes">Ingresados: </label>
			<input type="text" id="numero_pasaportes" name="numero_pasaportes" disabled> 
		</div>	

		<div data-linea="10" class="numeroAnimales">
			<label for="numero_disponibles">Por ingresar: </label>
			<input type="text" id="numero_disponibles" name="numero_disponibles" disabled> 
		</div>
		
	</fieldset >
	
	<fieldset class="ingresoEquino">
		<legend>Asignación de número de pasaporte equino</legend>				

		<div data-linea="9">
			<label for="nombre_equino">Nombre del Equino: </label>
			<input type="text" id="nombre_equino" name="nombre_equino" maxlength="128" /> 
		</div>	
		
		<div data-linea="20">
			<button type="submit" class="guardar">Añadir</button>
		</div>			

	</fieldset >
</form >	
	<div id="tablaEquinos" class="numeroAnimales">
        <fieldset>
        	<legend>Equinos registrados</legend>
            	<div data-linea="5">
        			<table id="tbItemsEquinos" style="width:100%">
        				<thead>
        					<tr>
        						<th style="width: 5%;">#</th>
        						<th style="width: 10%;">Tipo</th>
        						<th style="width: 30%;">Nombre Equino</th>
        						<th style="width: 20%;">Pasaporte</th>
        						<th style="width: 20%;">Especie</th>
        						<th style="width: 10%;">Estado</th>
                                <th style="width: 5%;"></th>
        					</tr>
        				</thead>
        				<tbody>
        				</tbody>
        			</table>
        		</div>		
    	</fieldset>
    </div>


<script type ="text/javascript">
var bandera = <?php echo json_encode($this->formulario); ?>;
var combo = "<option>Seleccione....</option>";

	$(document).ready(function() {
		construirValidador();
		distribuirLineas();

		$(".detalleEspeciePredio").hide();
		$(".ingresoEquino").hide();
		$(".numeroAnimales").hide();
	 });

	$("#id_provincia").change(function () {
		$("#id_miembro").html(combo);
        $("#id_catastro_predio_equidos").html(combo);
        $("#id_especie").html(combo);
        $("#id_raza").html(combo);
        $("#id_categoria").html(combo);
        $("#numero_total").val('');
    	$("#numero_disponibles").val('');
		$("#numero_pasaportes").val('');
		$(".detalleEspeciePredio").hide();
		$(".ingresoEquino").hide();
		$(".numeroAnimales").hide();
		
		if ($("#id_provincia option:selected").val() != '' ) {
			fn_buscarMiembrosXProvincia();		
        }else{
			alert('Debe seleccionar una provincia');
			$("#id_miembro").html(combo);
            $("#id_catastro_predio_equidos").html(combo);
            $("#id_especie").html(combo);
            $("#id_raza").html(combo);
            $("#id_categoria").html(combo);
            $("#numero_total").val('');
	    	$("#numero_disponibles").val('');
			$("#numero_pasaportes").val('');
			$(".detalleEspeciePredio").hide();
			$(".ingresoEquino").hide();
			$(".numeroAnimales").hide();
        }
    });

	//Función para mostrar las provincias donde el operador tiene predios en el módulo de programas de control oficial
    function fn_buscarMiembrosXProvincia() {
    	var idProvincia = $("#id_provincia option:selected").val();
        
        if (idProvincia != "" ){
        	$.post("<?php echo URL ?>PasaporteEquino/Equinos/comboMiembrosXProvincia",
               {
        		idProvincia : idProvincia
               }, function (data) {
            	   $("#id_miembro").html(data);
            });
        }else{
            $("#id_miembro").html(combo);
            $("#id_catastro_predio_equidos").html(combo);
            $("#id_especie").html(combo);
            $("#id_raza").html(combo);
            $("#id_categoria").html(combo);
            $("#numero_total").val('');
	    	$("#numero_disponibles").val('');
			$("#numero_pasaportes").val('');
			$(".detalleEspeciePredio").hide();
			$(".ingresoEquino").hide();
			$(".numeroAnimales").hide();
        	
        	if(!$.trim($("#id_provincia").val())){
    			$("#id_provincia").addClass("alertaCombo");
    		}

            $("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
    	}     
    }

    $("#id_miembro").change(function () {
    	$("#id_catastro_predio_equidos").html(combo);
        $("#id_especie").html(combo);
        $("#id_raza").html(combo);
        $("#id_categoria").html(combo);
        $("#numero_total").val('');
    	$("#numero_disponibles").val('');
		$("#numero_pasaportes").val('');
		$(".detalleEspeciePredio").hide();
		$(".ingresoEquino").hide();
		$(".numeroAnimales").hide();
		
		if ($("#id_miembro option:selected").val() != '' ) {
			fn_buscarPrediosXMiembrosXProvincia();		
        }else{
			alert('Debe seleccionar una provincia');
			$("#id_catastro_predio_equidos").html(combo);
            $("#id_especie").html(combo);
            $("#id_raza").html(combo);
            $("#id_categoria").html(combo);
            $("#numero_total").val('');
	    	$("#numero_disponibles").val('');
			$("#numero_pasaportes").val('');
			$(".detalleEspeciePredio").hide();
			$(".ingresoEquino").hide();
			$(".numeroAnimales").hide();
        }
    });

	//Función para mostrar los predios del operador
    function fn_buscarPrediosXMiembrosXProvincia() {
    	var idProvincia = $("#id_provincia option:selected").val();
    	var idMiembro = $("#id_miembro option:selected").val();
        
        if (idProvincia != "" && idMiembro != ""){
        	$.post("<?php echo URL ?>PasaporteEquino/Equinos/comboPrediosXMiembrosXProvincia",
               {
        		idProvinciaFiltro : idProvincia,
        		idMiembroFiltro : idMiembro
               }, function (data) {
            	   $("#id_catastro_predio_equidos").html(data);
            });
        }else{
            $("#id_catastro_predio_equidos").html(combo);
            $("#id_especie").html(combo);
            $("#id_raza").html(combo);
            $("#id_categoria").html(combo);
            $("#numero_total").val('');
	    	$("#numero_disponibles").val('');
			$("#numero_pasaportes").val('');
			$(".detalleEspeciePredio").hide();
			$(".ingresoEquino").hide();
			$(".numeroAnimales").hide();
        	
        	if(!$.trim($("#id_provincia").val())){
    			$("#id_provincia").addClass("alertaCombo");
    		}

            $("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
    	}     
    }

    $("#id_catastro_predio_equidos").change(function () {
    	$("#id_especie").html(combo);
        $("#id_raza").html(combo);
        $("#id_categoria").html(combo);
        $("#numero_total").val('');
    	$("#numero_disponibles").val('');
		$("#numero_pasaportes").val('');
		$(".detalleEspeciePredio").hide();
		$(".ingresoEquino").hide();
		$(".numeroAnimales").hide();
		
		if ($("#id_catastro_predio_equidos option:selected").val() != '' ) {
			fn_buscarEspeciesXPredio();
			$(".detalleEspeciePredio").show();			
        }else{
			alert('Debe seleccionar un predio');
			$("#id_especie").html(combo);
            $("#id_raza").html(combo);
            $("#id_categoria").html(combo);
            $("#numero_total").val('');
	    	$("#numero_disponibles").val('');
			$("#numero_pasaportes").val('');
			$(".detalleEspeciePredio").hide();
			$(".ingresoEquino").hide();
			$(".numeroAnimales").hide();
        }
    });

	//Función para mostrar las provincias donde el operador tiene predios en el módulo de programas de control oficial
    function fn_buscarEspeciesXPredio() {
    	var idPredio = $("#id_catastro_predio_equidos option:selected").val();
        
        if (idPredio != ""){
        	$.post("<?php echo URL ?>PasaporteEquino/Equinos/comboEspeciesXPredio",
               {
        		idPredio : idPredio
               }, function (data) {
            	   $("#id_especie").html(data);
            });
        }else{
            $("#id_especie").html(combo);
            $("#id_raza").html(combo);
            $("#id_categoria").html(combo);
            $("#numero_total").val('');
	    	$("#numero_disponibles").val('');
			$("#numero_pasaportes").val('');
			$(".ingresoEquino").hide();
			$(".numeroAnimales").hide();
        	
        	if(!$.trim($("#id_catastro_predio_equidos").val())){
    			$("#id_catastro_predio_equidos").addClass("alertaCombo");
    		}

            $("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
    	}     
    }

    $("#id_especie").change(function () {
    	$("#id_raza").html(combo);
        $("#id_categoria").html(combo);
        $("#numero_total").val('');
    	$("#numero_disponibles").val('');
		$("#numero_pasaportes").val('');
		$(".numeroAnimales").hide();
		$(".ingresoEquino").hide();
		
		if ($("#id_especie option:selected").val() != '' ) {
			fn_buscarRazasXEspecieXPredio();		
        }else{
			alert('Debe seleccionar una especie');
			$("#id_raza").html(combo);
            $("#id_categoria").html(combo);
            $("#numero_total").val('');
	    	$("#numero_disponibles").val('');
			$("#numero_pasaportes").val('');
			$(".numeroAnimales").hide();
			$(".ingresoEquino").hide();
        }
    });

	//Función para mostrar las provincias donde el operador tiene predios en el módulo de programas de control oficial
    function fn_buscarRazasXEspecieXPredio() {
    	var idPredio = $("#id_catastro_predio_equidos option:selected").val();
    	var idEspecie = $("#id_especie option:selected").val();
        
        if (idPredio != "" && idEspecie != ""){
        	$.post("<?php echo URL ?>PasaporteEquino/Equinos/comboRazasXEspecieXPredio",
               {
        		idEspecie : idEspecie,
        		idPredio : idPredio
               }, function (data) {
            	   $("#id_raza").html(data);
            });
        }else{
            $("#id_raza").html(combo);
            $("#id_categoria").html(combo);
            $("#numero_total").val('');
	    	$("#numero_disponibles").val('');
			$("#numero_pasaportes").val('');
			$(".numeroAnimales").hide();
			$(".ingresoEquino").hide();
        	
        	if(!$.trim($("#id_catastro_predio_equidos").val())){
    			$("#id_catastro_predio_equidos").addClass("alertaCombo");
    		}

        	if(!$.trim($("#id_especie").val())){
    			$("#id_especie").addClass("alertaCombo");
    		}

            $("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
    	}     
    }

    $("#id_raza").change(function () {
    	$("#id_categoria").html(combo);
        $("#numero_total").val('');
    	$("#numero_disponibles").val('');
		$("#numero_pasaportes").val('');
		$(".numeroAnimales").hide();
		$(".ingresoEquino").hide();
		
		if ($("#id_raza option:selected").val() != '' ) {
			fn_buscarCategoriasXEspecieXPredio();	
        }else{
			alert('Debe seleccionar una especie y raza');
			$("#id_categoria").html(combo);
            $("#numero_total").val('');
	    	$("#numero_disponibles").val('');
			$("#numero_pasaportes").val('');
			$(".numeroAnimales").hide();
			$(".ingresoEquino").hide();
        }
    });

	//Función para mostrar las provincias donde el operador tiene predios en el módulo de programas de control oficial
    function fn_buscarCategoriasXEspecieXPredio() {
    	var idPredio = $("#id_catastro_predio_equidos option:selected").val();
    	var idEspecie = $("#id_especie option:selected").val();
    	var idRaza = $("#id_raza option:selected").val();
        
        if (idPredio != "" && idEspecie != "" && idRaza != ""){
        	$.post("<?php echo URL ?>PasaporteEquino/Equinos/comboCategoriasXEspecieXPredio",
               {
        		idEspecie : idEspecie,
        		idPredio : idPredio,
        		idRaza : idRaza
               }, function (data) {
            	   $("#id_categoria").html(data);
            });
        }else{
            $("#id_categoria").html(combo);
            $("#numero_total").val('');
	    	$("#numero_disponibles").val('');
			$("#numero_pasaportes").val('');
			$(".numeroAnimales").hide();
			$(".ingresoEquino").hide();
        	
        	if(!$.trim($("#id_catastro_predio_equidos").val())){
    			$("#id_catastro_predio_equidos").addClass("alertaCombo");
    		}

        	if(!$.trim($("#id_especie").val())){
    			$("#id_especie").addClass("alertaCombo");
    		}

        	if(!$.trim($("#id_raza").val())){
    			$("#id_raza").addClass("alertaCombo");
    		}

            $("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
    	}     
    }

    $("#id_categoria").change(function () {
    	$("#numero_total").val('');
    	$("#numero_disponibles").val('');
		$("#numero_pasaportes").val('');
		$(".numeroAnimales").hide();
		$(".ingresoEquino").hide();
		
		if ($("#id_categoria option:selected").val() != '' ) {
			fn_buscarNumeroEquinosXCategoriasXEspecieXPredio();		
        }else{
			alert('Debe seleccionar una especie, raza y categoría');
			
			$("#numero_total").val('');
	    	$("#numero_disponibles").val('');
			$("#numero_pasaportes").val('');
			$(".numeroAnimales").hide();
			$(".ingresoEquino").hide();
        }
    });
    
	//Función para mostrar la cantidad de equinos y número de pendientes y con pasaporte generado
    function fn_buscarNumeroEquinosXCategoriasXEspecieXPredio() {
    	var idPredio = $("#id_catastro_predio_equidos option:selected").val();
    	var idEspecie = $("#id_especie option:selected").val();
    	var idRaza = $("#id_raza option:selected").val();
    	var idCategoria = $("#id_categoria option:selected").val();
        
        if (idPredio != "" && idEspecie != "" && idRaza != "" && idCategoria != ""){
        	$.post("<?php echo URL ?>PasaporteEquino/Equinos/obtenerNumeroEquinosXCategoriasXEspecieXPredio",
            	{
            		id_catastro_predio_equidos : idPredio,
            		id_especie : idEspecie,            		
            		id_raza : idRaza,
            		id_categoria : idCategoria,
            		accion : 'validar'
                }, function (data) {
     				if(data.estado == "Fallo"){
     	        		mostrarMensaje(data.mensaje,"FALLO");    
     	        		$("#numero_total").val('');
         	           	$("#numero_disponibles").val('');
         	       		$("#numero_pasaportes").val('');  
            			$(".ingresoEquino").hide();		
     				}else if(data.estado == "Completo"){
     					mostrarMensaje(data.mensaje,"FALLO");
     					$("#numero_total").val(data.numero_total);
     					$("#numero_pasaportes").val(data.numero_pasaportes);
     					$("#numero_disponibles").val(data.numero_disponibles);
     					$(".numeroAnimales").show();
     					//$(".ingresoEquino").show();
     					fn_mostrarDetalleEquinos();
     				}else{
     					mostrarMensaje(data.mensaje,"EXITO");
     					$("#numero_total").val(data.numero_total);
     					$("#numero_pasaportes").val(data.numero_pasaportes);
     					$("#numero_disponibles").val(data.numero_disponibles);
     					$(".numeroAnimales").show();
     					$(".ingresoEquino").show();
     					fn_mostrarDetalleEquinos();
     				}
                 }, 'json');
        }else{
            $("#id_categoria").html(combo);
        	
            $("#numero_total").val('');
        	$("#numero_disponibles").val('');
    		$("#numero_pasaportes").val('');
    		$(".ingresoEquino").hide();	

            $("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
    	}     
    }

    function fn_limpiarDatos() {
    	$("#id_miembro").html(combo);
    	$("#id_catastro_predio_equidos").html(combo);
    	$("#id_especie").html(combo);
    	$("#id_raza").html(combo);
    	$("#id_categoria").html(combo);
    	$("#numero_total").val('');
    	$("#numero_disponibles").val('');
		$("#numero_pasaportes").val('');
    } 

    function fn_limpiar() {
    	$(".alertaCombo").removeClass("alertaCombo");
    	$('#estado').html('');
    }

    //Para guardar equinos y poner en grid
    $("#formulario").submit(function (event) {
		fn_limpiar();
		event.preventDefault();
		var error = false;
		
		if(!$.trim($("#id_provincia").val())){
        	error = true;
        	$("#id_provincia").addClass("alertaCombo");
		}

        if(!$.trim($("#id_miembro").val())){
        	error = true;
        	$("#id_miembro").addClass("alertaCombo");
		}

        if(!$.trim($("#id_catastro_predio_equidos").val())){
        	error = true;
        	$("#id_catastro_predio_equidos").addClass("alertaCombo");
		}

        if(!$.trim($("#id_especie").val())){
        	error = true;
        	$("#id_especie").addClass("alertaCombo");
		}

        if(!$.trim($("#id_raza").val())){
        	error = true;
        	$("#id_raza").addClass("alertaCombo");
		}

        if(!$.trim($("#id_categoria").val())){
        	error = true;
        	$("#id_categoria").addClass("alertaCombo");
		}

        if(!$.trim($("#numero_total").val())){
        	error = true;
        	$("#numero_total").addClass("alertaCombo");
		}

        if(!$.trim($("#numero_disponibles").val())){
        	error = true;
        	$("#numero_disponibles").addClass("alertaCombo");
		}

        if(!$.trim($("#numero_pasaportes").val())){
        	error = true;
        	$("#numero_pasaportes").addClass("alertaCombo");
		}
		
        if(!$.trim($("#nombre_equino").val())){
        	error = true;
        	$("#nombre_equino").addClass("alertaCombo");
		}

		if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
	       		fn_mostrarDetalleEquinos();
	       		fn_limpiarFormularioEquino();
	        }else{
				mostrarMensaje(respuesta.mensaje,"FALLO");
	        	fn_mostrarDetalleEquinos();
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
	
    //Para cargar el detalle de equinos registrados
    function fn_mostrarDetalleEquinos() {
    	var idMiembro = $("#id_miembro option:selected").val();
    	var idPredio = $("#id_catastro_predio_equidos option:selected").val();
    	var idEspecie = $("#id_especie option:selected").val();
    	var idRaza = $("#id_raza option:selected").val();
    	var idCategoria = $("#id_categoria option:selected").val();
    	
    	$.post("<?php echo URL ?>PasaporteEquino/Equinos/construirDetalleEquinos/",
    	{
    		idMiembro : idMiembro,            		
    		idPredio : idPredio,
    		idEspecie : idEspecie,
    		idRaza : idRaza,
    		idCategoria : idCategoria
		}, function (data) {
            $("#tbItemsEquinos tbody").html(data);
        });
    }

  	//Funcion para abrir detalle de equinos
    function fn_abrirEquino(idEquino) {  
        var url = "<?php echo URL ?>PasaporteEquino/Equinos/editar";
        var data = {id: idEquino+',emisionPasaporte'};
			
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "text",
            success: function (html) {
                $("#detalleItem").html(html);
            },
            complete: function () {
            }
        });
    }

  //Funcion para abrir detalle de equinos
    function fn_limpiarFormularioEquino() {  
    	$("#nombre_equino").val('');
    	fn_buscarNumeroEquinosXCategoriasXEspecieXPredio();
    }

</script>
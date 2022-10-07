<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formularioAsociacion' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>CertificacionBPA' data-opcion='Asociaciones/guardar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
	<input type="hidden" id="id_asociacion" name="id_asociacion" value="<?php echo $this->modeloAsociaciones->getIdAsociacion(); ?>" />
	<input type="hidden" id="id" name="id" />

	<fieldset>
		<legend>Datos de la Asociación</legend>				

		<div data-linea="1">
			<label for="identificador">RUC: </label>
			<input type="text" id="identificador" name="identificador" value="<?php echo $this->modeloAsociaciones->getIdentificador(); //$_SESSION['usuario']; ?>"
				placeholder="Identificador del operador que será el responsable de la asociación (cédula/RUC)" required maxlength="13" 
				data-er="^[0-9]+$" <?php echo ($this->modeloAsociaciones->getIdentificador()!=''?'readonly="readonly"':''); ?>/> <!-- readonly="readonly" data-inputmask="'mask': '9999999999999'" -->
		</div>				

		<div data-linea="2">
			<label for="razon_social">Razón Social: </label>
			<input type="text" id="razon_social" name="razon_social" value="<?php echo $this->modeloAsociaciones->getRazonSocial(); ?>"
				placeholder="Nombre de la razón social de la asociación" required maxlength="512" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
		</div>				

		<div data-linea="3">
			<label for="correo">Email: </label>
			<input type="text" id="correo" name="correo" value="<?php echo $this->modeloAsociaciones->getCorreo(); ?>"
				placeholder="Correo electrónico de la asociación" maxlength="128" data-er="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$"/>
		</div>				

		<div data-linea="3">
			<label for="telefono">Teléfono: </label>
			<input type="text" id="telefono" name="telefono" value="<?php echo $this->modeloAsociaciones->getTelefono(); ?>"
				placeholder="Número de teléfono de contacto de la asociación" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{4}-[0-9]{4}" 
				data-inputmask="'mask': '(99) 9999-9999'"/>
		</div>				

		<div data-linea="4">
			<label for="id_provincia">Provincia </label>
			<select id="id_provincia" name="id_provincia" required >
                <option value="">Seleccionar....</option>
                <?php
                    echo $this->comboProvinciasEc($this->modeloAsociaciones->getIdProvincia());
                ?>
            </select>
		
			<input type="hidden" id="provincia" name="provincia" value="<?php echo $this->modeloAsociaciones->getProvincia(); ?>" />
		</div>				

		<div data-linea="4">
			<label for="id_canton">Cantón: </label>
			<select id="id_canton" name="id_canton" required disabled>
                <option value="">Seleccionar....</option>
            </select>
			
			<input type="hidden" id="canton" name="canton" value="<?php echo $this->modeloAsociaciones->getCanton(); ?>"/>
		</div>				

		<div data-linea="5">
			<label for="id_parroquia">Parroquia: </label>
			<select id="id_parroquia" name="id_parroquia" required disabled>
                <option value="">Seleccionar....</option>
            </select>
            
			<input type="hidden" id="parroquia" name="parroquia" value="<?php echo $this->modeloAsociaciones->getParroquia(); ?>" />
		</div>			
		
		<div data-linea="6">
			<label for="direccion">Dirección: </label>
			<input type="text" id="direccion" name="direccion" value="<?php echo $this->modeloAsociaciones->getDireccion(); ?>"
				placeholder="Dirección de la asociación" required maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü -\/]+$"/>
		</div>	

		<hr />
		
		<div data-linea="7">
			<label for="identificador_representante_legal">Identificación Representante Legal: </label>
			<input type="number" id="identificador_representante_legal" name="identificador_representante_legal" value="<?php echo $this->modeloAsociaciones->getIdentificadorRepresentanteLegal(); ?>"
				placeholder="Identificador del representante legal de la asociación" required maxlength="13" data-er="^[0-9]+$"/>
		</div>				

		<div data-linea="8">
			<label for="nombre_representante_legal">Nombres Representante Legal: </label>
			<input type="text" id="nombre_representante_legal" name="nombre_representante_legal" value="<?php echo $this->modeloAsociaciones->getNombreRepresentanteLegal(); ?>"
				placeholder="Nombre del representante legal" required maxlength="128" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$"/>
		</div>		
		
		<hr />		

		<div data-linea="9">
			<label for="identificador_representante_tecnico">Identificación Representante Técnico: </label>
			<input type="number" id="identificador_representante_tecnico" name="identificador_representante_tecnico" value="<?php echo $this->modeloAsociaciones->getIdentificadorRepresentanteTecnico(); ?>"
				placeholder="Identificador del representate técnico de la asociación" required maxlength="13" data-er="^[0-9]+$"/>
		</div>				

		<div data-linea="10">
			<label for="nombre_representante_tecnico">Nombre Representante Técnico: </label>
			<input type="text" id="nombre_representante_tecnico" name="nombre_representante_tecnico" value="<?php echo $this->modeloAsociaciones->getNombreRepresentanteTecnico(); ?>"
				placeholder="Nombre del representante técnico de la asociación" required maxlength="128" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$"/>
		</div>
		
		<div data-linea="11">
			<label for="correo_representante_tecnico">Email: </label>
			<input type="text" id="correo_representante_tecnico" name="correo_representante_tecnico" value="<?php echo $this->modeloAsociaciones->getCorreoRepresentanteTecnico(); ?>"
				placeholder="Correo electrónico del representante técnico" maxlength="128" data-er="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$"/>
		</div>				

		<div data-linea="12">
			<label for="telefono_representante_tecnico">Teléfono: </label>
			<input type="text" id="telefono_representante_tecnico" name="telefono_representante_tecnico" value="<?php echo $this->modeloAsociaciones->getTelefonoRepresentanteTecnico(); ?>"
				placeholder="Número de teléfono de contacto del representante técnico" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{4}-[0-9]{4}" 
				data-inputmask="'mask': '(99) 9999-9999'"/>
		</div>
		
		<div data-linea="13">
			<label>Productos: </label>		
		</div>
		
		<div data-linea="14">
			<?php echo $this->productosMiembrosAsociacion; ?>			
		</div>

		<div data-linea="15">
    		<button type="submit" class="guardar">Guardar</button>
    	</div>
	</fieldset >
</form >

 <header id="labelMiembros">
	<h1>Miembros de la Asociación</h1>
</header>
	
<form id='formularioMiembros' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>CertificacionBPA' data-opcion='MiembrosAsociaciones/guardar' data-destino="detalleItem" method="post">
	<input type="hidden" id="id_asociacion" name="id_asociacion" value="<?php echo $this->modeloAsociaciones->getIdAsociacion(); ?>" />

	<fieldset>
		<legend>Operador</legend>				

		<div data-linea="12">
			<label for="identificador_miembro">RUC: </label>
			<input type="text" id="identificador_miembro" name="identificador_miembro" placeholder="Identificador del operador" required maxlength="13" data-er="^[0-9]+$" />
		</div>				

		<div data-linea="13">
			<label for="nombre_miembro">Nombre Completo: </label>
			<input type="text" id="nombre_miembro" name="nombre_miembro" placeholder="Nombre del operador" required maxlength="512" readonly="readonly"/>
		</div>				

		<div data-linea="14">
    		<button type="submit" class="guardar">Guardar</button>
    	</div>
    	
    	<div data-linea="14">
    		<input type="checkbox" id=buscador name="buscador"  />
    		<label for="buscador">Buscar un miembro registrado:</label>
    	</div>
  
	</fieldset >
</form >


			
<fieldset id="filtroMiembros">
	<legend>Buscar Operador</legend>
	
	<input type="hidden" id="id_asociacion_buscador" name="id_asociacion_buscador" value="<?php echo $this->modeloAsociaciones->getIdAsociacion(); ?>" />
	
	<div data-linea="12">
		<label for="identificador_miembro_buscador">Identificación: </label> 
		<input type="text" id="identificador_miembro_buscador" name="identificador_miembro_buscador" required maxlength="13" />
	</div>

	<div data-linea="13">
		<label for="nombre_miembro_buscador">Nombre Operador:</label> 
		<input type="text" id="nombre_miembro_buscador" name="nombre_miembro_buscador" required maxlength="128" />
	</div>
	
	<div data-linea="14">
		<button type="button" class="buscar" onclick="buscarMiembroAsociacion()" >Buscar</button>
	</div>

</fieldset>

<div id="tablaMiembros">
    <fieldset>
    	<legend>Operadores Ingresados</legend>
        	<div data-linea="28">
    			<table id="tbItems" style="width:100%">
    				<thead>
    					<tr>
    						<th style="width: 5%;">Nº</th>
    						<th style="width: 25%;">Identificación</th>
                            <th style="width: 65%;">Nombre Completo</th>
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
var asociacion = <?php echo json_encode($this->asociacion); ?>;
var bandera = <?php echo json_encode($this->formulario); ?>;
var combo = "<option>Seleccione....</option>";

	$(document).ready(function() {
		construirValidador();
		distribuirLineas();

		if(bandera == 'nuevo'){
			if(asociacion == null){
    			$("#formularioAsociacion").show();
    			$("#labelMiembros").hide();
    			$("#formularioMiembros").hide();
    			$("#checkBuscador").hide();
    			$("#filtroMiembros").hide();
    			$("#tablaMiembros").hide();
			}else{
				alert('El usuario ya dispone de una asociación registrada y no puede realizar nuevos registros.');
				$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');
	        	abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
			}
		}else{
			fn_mostrarDetalleMiembros();
			fn_cargarCantonesParroquiasXDefecto();
			
			$("#formularioAsociacion").show();
			$("#labelMiembros").show();
			$("#formularioMiembros").show();
			$("#checkBuscador").show();
			$("#filtroMiembros").hide();
			$("#tablaMiembros").show();
		}
	 });

	$("#identificador").change(function () {
		if (($(this).val !== "")  ) {
			fn_validarIdentificador();
        }
    });

	$("#razon_social").change(function () {
		if (($(this).val !== "")  ) {
			fn_validarNombre();
        }
    });

	$("#id_provincia").change(function () {
    	$("#id_canton").html(combo);
    	$("#id_parroquia").html(combo);
    	
        if ($(this).val !== "") {
            fn_cargarCantones();
            $("#provincia").val($("#id_provincia option:selected").text());
        }
    }); 

    $("#id_canton").change(function () {
    	$("#id_parroquia").html(combo);
    	
        if ($(this).val !== "") {
            fn_cargarParroquias();
            $("#canton").val($("#id_canton option:selected").text());
        }
    }); 

	$("#id_parroquia").change(function () {
        if ($(this).val !== "") {
            $("#parroquia").val($("#id_parroquia option:selected").text());
        }
    });	

	$("#formularioAsociacion").submit(function (event) {
		fn_limpiar();
		event.preventDefault();
		var error = false;
		
		if(!$.trim($("#identificador").val()) || !esCampoValido("#identificador") || $("#identificador").val().length < 10 || ($("#identificador").val().length > 10 && $("#identificador").val().length < 13) || $("#identificador").val().length > 13){
			error = true;
			$("#identificador").addClass("alertaCombo");
		}
		
        if(!$.trim($("#razon_social").val()) || !esCampoValido("#razon_social")){
        	error = true;
        	$("#razon_social").addClass("alertaCombo");
		}
		
        if (!$.trim($("#correo").val()) || !esCampoValido("#correo")) {
        	error = true;
        	$("#correo").addClass("alertaCombo");
        }

        if(!$.trim($("#telefono").val()) || !esCampoValido("#telefono")){
        	error = true;
			$("#telefono").addClass("alertaCombo");
		}
		
        if(!$.trim($("#id_provincia").val())){
        	error = true;
			$("#id_provincia").addClass("alertaCombo");
		}
		
        if(!$.trim($("#id_canton").val())){
        	error = true;
        	$("#id_canton").addClass("alertaCombo");
		}
		
        if (!$.trim($("#id_parroquia").val())) {
        	error = true;
        	$("#id_parroquia").addClass("alertaCombo");
        }

        if(!$.trim($("#direccion").val()) || !esCampoValido("#direccion")){
        	error = true;
			$("#direccion").addClass("alertaCombo");
		}
		
        if(!$.trim($("#identificador_representante_legal").val()) || !esCampoValido("#identificador_representante_legal"|| $("#identificador_representante_legal").val().length < 10 || ($("#identificador_representante_legal").val().length > 10 && $("#identificador_representante_legal").val().length < 13) || $("#identificador_representante_legal").val().length > 13)){
        	error = true;
			$("#identificador_representante_legal").addClass("alertaCombo");
		}
		
        if(!$.trim($("#nombre_representante_legal").val()) || !esCampoValido("#nombre_representante_legal")){
        	error = true;
        	$("#nombre_representante_legal").addClass("alertaCombo");
		}
		
        if (!$.trim($("#identificador_representante_tecnico").val()) || !esCampoValido("#identificador_representante_tecnico" || $("#identificador_representante_tecnico").val().length < 10 || ($("#identificador_representante_tecnico").val().length > 10 && $("#identificador_representante_tecnico").val().length < 13) || $("#identificador_representante_tecnico").val().length > 13)){
        	error = true;
        	$("#identificador_representante_tecnico").addClass("alertaCombo");
        }

        if(!$.trim($("#nombre_representante_tecnico").val()) || !esCampoValido("#nombre_representante_tecnico")){
        	error = true;
			$("#nombre_representante_tecnico").addClass("alertaCombo");
		}

        if (!$.trim($("#correo_representante_tecnico").val()) || !esCampoValido("#correo_representante_tecnico")) {
        	error = true;
        	$("#correo_representante_tecnico").addClass("alertaCombo");
        }

        if(!$.trim($("#telefono_representante_tecnico").val()) || !esCampoValido("#telefono_representante_tecnico")){
        	error = true;
			$("#telefono_representante_tecnico").addClass("alertaCombo");
		}
		
		if (!error) {
			var respuesta = JSON.parse(ejecutarJson($(this)).responseText);		
			
	        if (respuesta.estado == 'exito'){
	        	$("#estado").html("Se han guardado los datos con éxito.").addClass("exito");

	        	if(bandera === 'nuevo'){
    	        	$("#id").val(respuesta.contenido);
    	       		$("#formularioAsociacion").attr('data-opcion', 'Asociaciones/editar');
					abrir($("#formularioAsociacion"),event,false);
	        	}else{
	        		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');
		        	abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
	        	}
	        }
			
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#identificador_miembro").change(function () {
		if (($(this).val !== "")  ) {
			fn_validarIdentificadorMiembro();
        }
    });

	$('#buscador').change(function() {
        if($(this).is(":checked")) {
        	$('#filtroMiembros').fadeIn();
        }else{
        	$('#filtroMiembros').hide();
        }        
    });

	$("#formularioMiembros").submit(function (event) {
		fn_limpiar();
		event.preventDefault();
		var error = false;

		if(!$.trim($("#identificador_miembro").val()) || !esCampoValido("#identificador_miembro") || $("#identificador_miembro").val().length < 10 || ($("#identificador_miembro").val().length > 10 && $("#identificador_miembro").val().length < 13) || $("#identificador_miembro").val().length > 13){
			error = true;
			$("#identificador_miembro").addClass("alertaCombo");
		}
		
        if(!$.trim($("#nombre_miembro").val())){
        	error = true;
        	$("#nombre_miembro").addClass("alertaCombo");
		}
		
		if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
	       		fn_mostrarDetalleMiembros();
		       	fn_limpiarDetalleMiembros();
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	//Funciones
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	//Para buscar si existe una asociación con el número de identificación ingresado
    function fn_validarIdentificador() {
        var identificador = $("#identificador").val();
        
        if (identificador !== "") {
        	mostrarMensaje("","EXITO");
        	$.post("<?php echo URL ?>CertificacionBPA/Asociaciones/validarIdentificadorAsociacion/" + identificador, function (data) {
				if(data.validacion == "Exito"){
	        		mostrarMensaje(data.nombre,"FALLO");
	        		$("#identificador").val("");
				}
            }, 'json');
        }
    } 

  //Para buscar si existe una asociación con el nombre ingresado
    function fn_validarNombre() {
        var razonSocial = $("#razon_social").val();
        
        if (razonSocial !== "") {
        	mostrarMensaje("","EXITO");
        	$.post("<?php echo URL ?>CertificacionBPA/Asociaciones/validarNombreAsociacion/" + razonSocial, function (data) {
				if(data.validacion == "Exito"){
	        		mostrarMensaje(data.nombre,"FALLO");
	        		$("#razon_social").val("");
				}else{
					mostrarMensaje(data.nombre,"EXITO");
				}
            }, 'json');
        }
    }

	//Lista de cantones por provincia
    function fn_cargarCantones() {
        var idProvincia = $("#id_provincia option:selected").val();
        
        if (idProvincia !== "") {
        	$.post("<?php echo URL ?>CertificacionBPA/Asociaciones/comboCantones/" + idProvincia, function (data) {
                $("#id_canton").removeAttr("disabled");
                $("#id_canton").html(data);               
            });
        }
    }

    //Lista de parroquias por cantón
	function fn_cargarParroquias() {
        var idCanton = $("#id_canton option:selected").val();
        
        if (idCanton !== "") {
        	$.post("<?php echo URL ?>CertificacionBPA/Asociaciones/comboParroquias/" + idCanton, function (data) {
                $("#id_parroquia").removeAttr("disabled");
                $("#id_parroquia").html(data);               
            });
        }
    }

	//Lista de parroquias por cantón
	function fn_cargarCantonesParroquiasXDefecto() {
		var idCanton = <?php echo json_encode($this->modeloAsociaciones->getIdCanton()); ?>;
		var canton = <?php echo json_encode($this->modeloAsociaciones->getCanton()); ?>;

		var idParroquia = <?php echo json_encode($this->modeloAsociaciones->getIdParroquia()); ?>;
		var parroquia = <?php echo json_encode($this->modeloAsociaciones->getParroquia()); ?>;
        
        $("#id_canton").html('<option value="'+idCanton+'">'+canton+'</option>');
        $("#id_parroquia").html('<option value="'+idParroquia+'">'+parroquia+'</option>');
    }

	//Para cargar el detalle de miembros de la asociación registrados
    function fn_mostrarDetalleMiembros() {
        var idAsociacion = $("#id_asociacion").val();
        
    	$.post("<?php echo URL ?>CertificacionBPA/MiembrosAsociaciones/construirDetalleMiembros/" + idAsociacion, function (data) {
            $("#tbItems tbody").html(data);
        });
    }

  	//Para buscar si existe un miembro con el número de identificación ingresado
    function fn_validarIdentificadorMiembro() {
        var identificador = $("#identificador_miembro").val();
        
        if (identificador !== "") {
        	mostrarMensaje("","EXITO");
        	$.post("<?php echo URL ?>CertificacionBPA/MiembrosAsociaciones/validarIdentificadorMiembro/" + identificador, function (data) {
				if(data.validacion == "Exito"){
	        		mostrarMensaje(data.nombre,"FALLO");
	        		$("#identificador_miembro").val("");
				}else{
					mostrarMensaje(data.nombre,"EXITO");
					fn_obtenerNombreMiembro();
				}
            }, 'json');
        }
    }

  	//Para buscar el nombre de un miembro en la tabla operadores con el número de identificación ingresado
    function fn_obtenerNombreMiembro() {
        var identificador = $("#identificador_miembro").val();
        
        if (identificador !== "") {
        	mostrarMensaje("","EXITO");
        	$.post("<?php echo URL ?>CertificacionBPA/MiembrosAsociaciones/obtenerNombreOperador/" + identificador, function (data) {
				if(data.validacion == "Fallo"){
	        		mostrarMensaje(data.nombre,"FALLO");
	        		$("#identificador_miembro").val("");
				}else{
					$("#nombre_miembro").val(data.nombre);
				}
            }, 'json');
        }
    }

  	//Funcion que elimina una fila de la lista 
    function fn_eliminarDetalle(idDetalleMiembro) { 
        $.post("<?php echo URL ?>CertificacionBPA/MiembrosAsociaciones/borrar",
        {                
            elementos: idDetalleMiembro
        },
        function (data) {
        	fn_mostrarDetalleMiembros();
        });
    }

  	//Función para buscar un miembro registrado en la asociación
    function buscarMiembroAsociacion() {
    	fn_limpiar();
    	$("#tbItems tbody").html("");
    	
        var idAsociacion = $("#id_asociacion_buscador").val();
        var identificador = $("#identificador_miembro_buscador").val();
        var nombre = $("#nombre_miembro_buscador").val();

        if ((idAsociacion !== "") && ((identificador !== "")||(nombre !== ""))) {
        	$.post("<?php echo URL ?>CertificacionBPA/MiembrosAsociaciones/buscarMiembroAsociacion", 
    			{
            		idAsociacion : $("#id_asociacion_buscador").val(),
            		identificador : $("#identificador_miembro_buscador").val(),
            		nombre : $("#nombre_miembro_buscador").val()
    			},
                function (data) {
                    $("#tbItems tbody").html(data);
                    fn_limpiarDetalleBuscador();
                });
        }else{
        	fn_mostrarDetalleMiembros();
    		
        	if(!$.trim($("#identificador_miembro_buscador").val())){
    			if (!$.trim($("#nombre_miembro_buscador").val())) {
    				$("#identificador_miembro_buscador").addClass("alertaCombo");
        			$("#nombre_miembro_buscador").addClass("alertaCombo");
                }
    		}
    		
            if (!$.trim($("#nombre_miembro_buscador").val())) {
            	if(!$.trim($("#identificador_miembro_buscador").val())){
            		$("#identificador_miembro_buscador").addClass("alertaCombo");
    				$("#nombre_miembro_buscador").addClass("alertaCombo");
            	}
            }

            $("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
        }
    }

    function fn_limpiarDetalleMiembros(){
		$("#identificador_miembro").val("");
    	$("#nombre_miembro").val("");
	}

    function fn_limpiarDetalleBuscador(){
		$("#identificador_miembro_buscador").val("");
    	$("#nombre_miembro_buscador").val("");
	}

	function fn_limpiar() {
		$(".alertaCombo").removeClass("alertaCombo");
		$('#estado').html('');
	}
</script>
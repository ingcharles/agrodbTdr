<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>DossierPecuario' data-opcion='solicitud/guardar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
	<input type="hidden" id="id_provincia_operador" name="id_provincia_operador" />
	<input type="hidden" id="id_provincia_revision" name="id_provincia_revision" />
	<input type="hidden" id="tipo_solicitud" name="tipo_solicitud" value="Registro"/>
	<input type="hidden" id="id" name="id" />
	
	<fieldset>
		<legend>Información del Operador</legend>				

		<div data-linea="1">
			<label for="razon_social">Razón Social: </label>
			<input type="text" id="razon_social" readonly="readonly" />
		</div>				

		<div data-linea="2">
			<label for="identificador">RUC / RISE: </label>
			<input type="text" id="identificador" name="identificador" readonly="readonly" />
		</div>
		
		<div data-linea="3">
			<label for="direccion">Dirección: </label>
			<input type="text" id="direccion" readonly="readonly" />
		</div>
		
		<div data-linea="4">
			<label for="provincia">Provincia: </label>
			<input type="text" id="provincia" readonly="readonly" />
		</div>
		
		<div data-linea="5">
			<label for="canton">Cantón: </label>
			<input type="text" id="canton" readonly="readonly" />
		</div>
		
		<div data-linea="5">
			<label for="parroquia">Parroquia: </label>
			<input type="text" id="parroquia" readonly="readonly" />
		</div>
		
		<div data-linea="6">
			<label for="telefono">Teléfono: </label>
			<input type="text" id="telefono" readonly="readonly" />
		</div>
		
		<div data-linea="6">
			<label for="celular">Celular: </label>
			<input type="text" id="celular" readonly="readonly" />
		</div>
		
		<div data-linea="7">
			<label for="correo">Correo electrónico: </label>
			<input type="text" id="correo" readonly="readonly" />
		</div>
		
		<div data-linea="8">
			<label for="representante_legal">Representante legal: </label>
			<input type="text" id="representante_legal" readonly="readonly" />
		</div>
		
	</fieldset >
	
	<fieldset>
		<legend>Información del Producto</legend>				

		<div data-linea="1">
			<label for="id_grupo_producto">Grupo de Producto: </label>
			<select id="id_grupo_producto" name="id_grupo_producto" required >
                <option value="">Seleccionar....</option>
                <?php
                    echo $this->comboGrupoProducto($this->modeloSolicitud->getIdGrupoProducto());
                ?>
            </select>
            
            <input type="hidden" id="grupo_producto" name="grupo_producto" value="<?php echo $this->modeloSolicitud->getGrupoProducto(); ?>" required maxlength="32" />
		</div>				

		<div data-linea="2">
			<label for="id_subtipo_producto">Tipo de Producto: </label>
			<select id="id_subtipo_producto" name="id_subtipo_producto" required disabled>
                <option value="">Seleccionar....</option>
            </select>
			
			<input type="hidden" id="codificacion_subtipo_producto" name="codificacion_subtipo_producto" value="<?php echo $this->modeloSolicitud->getCodificacionSubtipoProducto(); ?>" required maxlength="32" />
		</div>		
		
		<div data-linea="3">
			<label for="nombre_producto">Nombre: </label>
			<input type="text" id="nombre_producto" name="nombre_producto" value="<?php echo $this->modeloSolicitud->getNombreProducto(); ?>" required maxlength="512" />
		</div>						

	</fieldset >
	
	<div data-linea="4">
		<button type="submit" class="guardar">Guardar</button>
	</div>
</form >

<script type ="text/javascript">
var identificadorUsuario = <?php echo json_encode($_SESSION['usuario']); ?>;
var bandera = <?php echo json_encode($this->formulario); ?>;
var combo = "<option>Seleccione....</option>";

	$(document).ready(function() {
		//Obtiene la información del usuario de su registro de operador
		fn_buscarDatosOperador();
		
		construirValidador();
		distribuirLineas();
	 });

	//Función para mostrar los datos del operador
    function fn_buscarDatosOperador() {
    	var identificador = identificadorUsuario;
        
        if (identificador !== "" ){
        	$.post("<?php echo URL ?>DossierPecuario/Solicitud/obtenerDatosOperador",
               {
                identificador : identificadorUsuario
               }, function (data) {
				if(data.validacion == "Fallo"){
	        		mostrarMensaje(data.resultado,"FALLO");       		
				}else{
					fn_cargarDatosOperador(data);
				}
            }, 'json');
        }
    } 

  //Función para mostrar los datos obtenidos del operador/asociación
    function fn_cargarDatosOperador(data) {
    	$("#razon_social").val(data.razon_social);
    	$("#identificador").val(data.id);
    	$("#direccion").val(data.direccion);
    	$("#id_provincia_operador").val(data.id_provincia);
    	$("#id_provincia_revision").val(data.id_provincia);
    	$("#provincia").val(data.provincia);
    	$("#canton").val(data.canton);
    	$("#parroquia").val(data.parroquia);
    	$("#telefono").val(data.telefono);
    	$("#celular").val(data.celular);
    	$("#correo").val(data.correo);
		$("#representante_legal").val(data.nombre_representante);		
    } 

  //Combo de Subtipo de Producto
    $("#id_grupo_producto").change(function () {
    	$("#id_subtipo_producto").html(combo);
    	$("#codificacion_subtipo_producto").val("");
    	$("#grupo_producto").val("");
    	
        if ($(this).val !== "") {
            fn_cargarSubtipoProducto();
            $("#grupo_producto").val($("#id_grupo_producto option:selected").attr('data-codigo'));
        }
    });
  //Lista de Subtipo de Producto por grupo de producto
    function fn_cargarSubtipoProducto() {
        var idGrupo = $("#id_grupo_producto option:selected").val();
        
        if (idGrupo !== "") {
        	$.post("<?php echo URL ?>DossierPecuario/Solicitud/comboSubtipoProductoXGrupo", 
        			{
                		idGrupo : idGrupo
        			},
                    function (data) {
        				$("#id_subtipo_producto").removeAttr("disabled");
                        $("#id_subtipo_producto").html(data);
                    });
        }
    }


    $("#id_subtipo_producto").change(function () {
    	$("#codificacion_subtipo_producto").val("");
    	
        if ($(this).val !== "") {
            $("#codificacion_subtipo_producto").val($("#id_subtipo_producto option:selected").attr('data-codigo'));
        }

        if($("#codificacion_subtipo_producto").val() == 'FM'){
        	$("#nombre_producto").removeAttr('required'); 
        	$("#nombre_producto").attr('disabled', 'disabled'); 
        }else{
        	$("#nombre_producto").attr('required', 'required'); 
        	$("#nombre_producto").removeAttr('disabled');
        }
    }); 

    $("#nombre_producto").change(function () {
		if (($(this).val !== "")  ) {
			fn_validarNombre();
        }
    });
    
    //Función para buscar si existe un producto con el nombre ingresado
    function fn_validarNombre() {
        var nombreProducto = $("#nombre_producto").val();
        
        /*if (nombreProducto !== "") {
        	mostrarMensaje("","EXITO");
        	$.post("< ?php echo U RL ?>DossierPecuario/Solicitud/validarNombreProducto/" + nombreProducto, function (data) {
				if(data.validacion == "Exito"){
					mostrarMensaje(data.nombre,"EXITO");
				}else{					
					mostrarMensaje(data.nombre,"FALLO");
	        		$("#nombre_producto").val("");
	        		alert('Debe seleccionar otro nombre para el producto.');
				}
            }, 'json');
        }*/

        $.post("<?php echo URL ?>DossierPecuario/Solicitud/validarNombreProducto",
                {
                 nombre : nombreProducto
                }, function (data) {
                	if(data.validacion == "Exito"){
    					mostrarMensaje(data.nombre,"EXITO");
    				}else{					
    					mostrarMensaje(data.nombre,"FALLO");
    	        		$("#nombre_producto").val("");
    	        		alert('Debe seleccionar otro nombre para el producto.');
    				}
             }, 'json');
    }

	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;

		if(!$.trim($("#id_grupo_producto").val())){
			error = true;
			$("#id_grupo_producto").addClass("alerta");
		}

		if(!$.trim($("#id_subtipo_producto").val())){
			error = true;
			$("#id_subtipo_producto").addClass("alerta");
		}
		
		if($("#id_subtipo_producto option:selected").attr('data-codigo') != 'FM'){
    		if(!$.trim($("#nombre_producto").val()) || !esCampoValido("#nombre_producto")){
    			error = true;
    			$("#nombre_producto").addClass("alerta");
    			$("#estado").text('El nombre del producto no es correcto.').addClass("alerta");
    		}
		}
		
		if (!error) {
			var respuesta = JSON.parse(ejecutarJson($(this)).responseText);		
			
	        if (respuesta.estado == 'exito'){
	        	$("#estado").html("Se han guardado los datos con éxito.").addClass("exito");

	        	if(bandera === 'nuevo'){
    	        	$("#id").val(respuesta.contenido);
    	       		$("#formulario").attr('data-opcion', 'Solicitud/editar');
					abrir($("#formulario"),event,false);
	        	}else{
	        		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');
		        	abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
	        	}
	        }
			
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>
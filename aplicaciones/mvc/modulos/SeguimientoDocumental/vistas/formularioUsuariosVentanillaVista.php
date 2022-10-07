<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>SeguimientoDocumental' data-opcion='usuariosVentanilla/guardar' data-destino="detalleItem" method="post">

<input type="hidden" id="id_usuario_ventanilla" name="id_usuario_ventanilla" value="<?php echo $this->modeloUsuariosVentanilla->getIdUsuarioVentanilla(); ?>" />

	<fieldset>
		<legend>Usuarios ventanilla</legend>				

		<div data-linea="1">
			<label for="id_ventanilla">Ventanilla: </label>
			<select id="id_ventanilla" name="id_ventanilla" required>
                <option value="">Seleccionar....</option>
                    <?php
                    echo $this->comboVentanillasSeguimientoDocumental($this->modeloUsuariosVentanilla->getIdVentanilla());
                    ?>
            </select>
		</div>	
		
		<div data-linea="2">
			<label for="unidad">Unidad asignada: </label>
			<input type="text" id="unidad" name="unidad" readonly="readonly" />
		</div>
		
		<div data-linea="3">
			<label for="identificador">CI: </label>
			<input type="text" id="identificador" name="identificador" value="<?php echo $this->modeloUsuariosVentanilla->getIdentificador(); ?>"
			placeholder="Identificador del usuario asignado a la ventanilla" required maxlength="13" <?php echo $this->formulario=="abrir"?'readonly="readonly"':""; ?> />
		</div>				

		<div data-linea="3">
			<label for="nombres">Nombres y Apellidos: </label>
			<input type="text" id="nombres" name="nombres" value="" readonly="readonly" />
		</div>			

		<div data-linea="4">
			<label for="id_perfil">Perfil </label>
			<select id="id_perfil" name="id_perfil" required="required">
                <option value="">Seleccionar....</option>
                    <?php
                    echo $this->comboPerfilesAplicacion($this->modeloUsuariosVentanilla->getIdPerfil());
                    ?>
            </select>
            <input type="hidden" id="codificacion_perfil" name="codificacion_perfil" />
            <input type="hidden" id="id_perfil_antiguo" name="id_perfil_antiguo" value="<?php echo $this->modeloUsuariosVentanilla->getIdPerfil(); ?>" />
		</div>				

		<div data-linea="4">
			<label for="estado_usuarios_ventanilla">Estado: </label>
			<select id="estado_usuarios_ventanilla" name="estado_usuarios_ventanilla" required="required">
            	<?php
                    echo $this->comboActivoInactivo($this->modeloUsuariosVentanilla->getEstadoUsuariosVentanilla());
                ?>
            </select>
		</div>
		
		<div data-linea="5">
			<button type="submit" class="guardar">Guardar</button>
		</div>
	</fieldset >
</form >
<script type ="text/javascript">
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		fn_cargarNombre();
		
		$("#unidad").val($("#id_ventanilla  option:selected").attr("data-unidad"));
		$("#codificacion_perfil").val($("#id_perfil option:selected").attr('data-codificacion'));
	 });

	$("#id_ventanilla").change(function () {
        if ($(this).val !== "") {
        	$("#unidad").val($("#id_ventanilla  option:selected").attr("data-unidad"));
        }
    });

	$("#identificador").change(function () {
		if (($(this).val !== "")  ) {
			fn_cargarNombre()
        }
    });

  //Para cargar los nombres del usuario
    function fn_cargarNombre() {
        var identificador = $("#identificador").val();
        
        if (identificador !== "") {
        	mostrarMensaje("","EXITO");
        	$.post("<?php echo URL ?>SeguimientoDocumental/UsuariosVentanilla/obtenerNombreUsuarioTecnico/" + identificador, function (data) {
				if(data.validacion == "Fallo"){
	        		mostrarMensaje(data.nombre,"FALLO");
	        		$("#nombres").val("");
				}else{
					$("#nombres").val(data.nombre);
				}
            }, 'json');
        }
    } 

	$("#formulario").submit(function (event) {
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#nombres").val())){
			error = true;
			$("#nombres").addClass("alertaCombo");
		}
		
		if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	        if (respuesta.estado == 'exito'){
	            fn_filtrar();
	        }else{
	        	mostrarMensaje(respuesta.mensaje,"FALLO");
			}
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#id_perfil").change(function(event){
		$("#codificacion_perfil").val($("#id_perfil option:selected").attr('data-codificacion'));
	});	

</script>

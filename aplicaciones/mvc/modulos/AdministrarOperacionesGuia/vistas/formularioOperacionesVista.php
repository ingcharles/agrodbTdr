<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario'
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>AdministrarOperacionesGuia'
	data-opcion='operaciones/guardarResultado' data-destino="detalleItem"
	data-accionEnExito="ACTUALIZAR" method="post">
	 <?php echo $this->sitiosAreas;?>
	 <?php echo $this->transporte;?>
	 <fieldset>
		<legend>Datos de la operación </legend>
		<div id="listaOperaciones" style="width: 100%">
    	<?php echo  $this->datosOperacion;?>	
    	</div>

	</fieldset>
	<fieldset>
		<legend>Resultado de inspección</legend>

		<div data-linea="1">
			<label for="resultado">Resultado:</label> <select
				style="width: 185px;" id="resultado" name="resultado">
    			<?php echo $this->comboResultado($this->estadoOperacion);?>
			</select>
		</div>

		<div data-linea="2">
			<label for="observacion">Observación:</label> <input type="text"
				id="observacion" name="observacion" value=""
				placeholder="Observación" maxlength="256" />
		</div>

	</fieldset>

	<div id="cargarMensajeTemporal"></div>
	<div data-linea="3">
		<button type="submit" class="guardar">Guardar</button>
	</div>
	<input type="hidden" id="id_operacion" name="id_operacion"
		value="<?php echo $this->idOperacion;?>">
</form>
<script type="text/javascript">
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		$("#estado").html("");
	 });

	$("#formulario").submit(function (event) {
		event.preventDefault();
		mostrarMensaje("", "FALLO");
	    $(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		var resultado =  $("input[name='resultado[]']").map(function(){ if($(this).prop("checked")){return $(this).val();}}).get();
		var check =  $("input[name='check[]']").map(function(){ if($(this).prop("checked")){return 1;}}).get();
		var texto = "";
		
		if($("#resultado").val() == ""){	
			error = true;		
			$("#resultado").addClass("alertaCombo");
		}

		if($("#observacion").val() == ""){	
			error = true;		
			$("#observacion").addClass("alertaCombo");
		}
		if(resultado == '' || check == ''){ alert("vacio");
			error = true;
			$("#listaOperaciones").addClass("alertaCombo");
			texto = "Debe seleccionar los Items de las operaciones..!!";
		}
		
		if (!error) {
			$("#cargarMensajeTemporal").html("<div id='cargando' style='position :fixed'>Cargando...</div>").fadeIn();
			setTimeout(function(){
				var respuesta = JSON.parse(ejecutarJson($("#formulario")).responseText);
				if (respuesta.estado == 'exito'){
					$("#cargarMensajeTemporal").html('');
        			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
    			}else{
    				$("#cargarMensajeTemporal").html('');
        		}
			}, 500);
		} else {
			$("#estado").html("Por favor revise los campos obligatorios." + " " + texto).addClass("alerta");
		}
	});


	function verificarOpcion(id){
	    mostrarMensaje("", "FALLO");
	    $(".alertaCombo").removeClass("alertaCombo");
	    var seleccion = []; 
	   	if(id == 'total'){
	   		$("input[name='check[]']").map(function(){ $(this).prop("checked",true)}).get();
	   	}
	}

	function limpiarResultado(id){		
		var resultado =  $("input[name='resultado[]']").map(function(){ if($(this).prop("checked")){return $(this).val();}}).get();
		if(resultado != 'parcial'){
    		$("input[name='check[]']").map(function(){ $(this).prop("checked",false)}).get();
    		$("input[name='resultado[]']").map(function(){ $(this).prop("checked",false)}).get();
		}		
	}
	
</script>

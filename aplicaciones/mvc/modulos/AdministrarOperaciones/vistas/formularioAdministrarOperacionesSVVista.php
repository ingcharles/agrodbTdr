<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<div class="pestania">

	<form id="formularioSitioArea" data-rutaAplicacion="<?php echo URL_MVC_FOLDER;?>AdministrarOperaciones" data-opcion="AdministrarOperaciones/guardarSitioArea" data-destino="detalleItem" method="post">
    	
	    <fieldset>
    	    <legend>Datos del sitio de operación</legend>
    	    
    	    <input type="hidden" name="id_sitio" id="id_sitio" value="<?php echo $this->datosSitios->current()->id_sitio; ?>">
           
            <div data-linea="5">
	       		<label>Nombre del sitio: </label> 
	       		<input type="text" name="nombre_lugar" id="nombre_lugar" value="<?php echo $this->datosSitios->current()->nombre_lugar; ?>" required="required" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$" maxlength="256">
	        </div>
	       
            <div data-linea="5">
           		<label>Provincia: </label> <?php echo $this->datosSitios->current()->provincia; ?>
            </div>
           
            <div data-linea="6">
           		<label>Cantón: </label> <?php echo $this->datosSitios->current()->canton; ?>
            </div>
           		
            <div data-linea="6">
           		<label>Parroquia: </label> 
           		<select id="parroquiaF" name="parroquiaF" class="noEditable">
           			<?php echo $this->comboParroquiasXNombreProvinciaCanton($this->datosSitios->current()->provincia, $this->datosSitios->current()->canton, $this->datosSitios->current()->parroquia); ?>
           		</select>
           		<input type="hidden" name="parroquia" id="parroquia" value="<?php echo $this->datosSitios->current()->parroquia; ?>" required="required" >
           	</div>
           	
            <div data-linea="7">
           		<label>Dirección: </label>
           		<input type="text" name="direccion" id="direccion" value="<?php echo $this->datosSitios->current()->direccion; ?>" required="required" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$" maxlength="256" class="noEditable" >
           	</div>
           	
            <div data-linea="8">
           		<label>Referencia: </label>
           		<input type="text" name="referencia" id="referencia" value="<?php echo $this->datosSitios->current()->referencia; ?>" required="required" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$" maxlength="1024" class="noEditable" >
           	</div>
           	
           	<div data-linea="9">
           		<label>Latitud: </label>
           		<input type="text" name="latitud" id="latitud" value="<?php echo $this->datosSitios->current()->latitud; ?>" required="required" data-er="^[0-9]+(\.[0-9]{1,})?$" maxlength="128" class="noEditable" >
           	</div>
           	
            <div data-linea="9">
           		<label>Longitud: </label>
           		<input type="text" name="longitud" id="longitud" value="<?php echo $this->datosSitios->current()->longitud; ?>" required="required" data-er="^[0-9]+(\.[0-9]{1,})?$" maxlength="128" class="noEditable" >
           	</div>
           	
           	<div data-linea="10">
           		<label>Zona: </label>
           		<input type="text" name="zona" id="zona" value="<?php echo $this->datosSitios->current()->zona; ?>" required="required" data-er="^[0-9]+(\.[0-9]{1,})?$" maxlength="128" class="noEditable" >
           	</div>
           	
           	<div data-linea="10">
           		<label>Superficie total: </label>
           		<input type="text" name="superficie_total" id="superficie_total" value="<?php echo $this->datosSitios->current()->superficie_total; ?>" required="required" data-er="^[0-9]+(\.[0-9]{1,2})?$" class="noEditable" >
           	</div>
	       
		</fieldset>
		
		<div id="areas" name="areas">
			<?php echo $this->datosAreas;?>
		</div>
		
		<div id="transporte" name="transporte">
			<?php echo $this->transporte;?>
		</div>		
		
		<div data-linea="14">
    		<button type="submit" class="guardar">Guardar</button>
    	</div>
		
	</form>
	
        	 
</div>
<!-- AdministrarOperaciones/validarActualizacionOperacion -->
<div class="pestania">
    <form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER;?>AdministrarOperaciones' data-opcion='AdministrarOperaciones/guardar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
    	 <input type="hidden" id="productosDeclarados" name="productosDeclarados" value="<?php echo $this->productosDeclarados; ?>" />
    	 
    	 <?php echo  $this->datosOperacion;?>
    	 
    	<fieldset>
    		<legend>Resultado de inspección</legend>
    
    		<div data-linea="1">
    			<label for="resultado">Resultado:</label> <select
    				style="width: 185px;" id="resultado" name="resultado">
        			<?php echo $this->comboNuevoEstadoOperacion($this->estadoActualOperacion, $this->estadoAnteriorOperacion);?>
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
</div>

<script type="text/javascript">
	var estadoSolicitud = <?php echo json_encode($this->modeloOperaciones->getEstado()); ?>;
	var estadoAnteriorSolicitud = <?php echo json_encode($this->modeloOperaciones->getEstadoAnterior()); ?>;
	var productosDeclarados = <?php echo json_encode($this->productosDeclarados); ?>;
	var operacionCuarentena = <?php echo json_encode($this->operacionCuarentena); ?>;

	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		construirAnimacion($(".pestania"));
		$("#estado").html("");
		//alert(estadoSolicitud + '-' + estadoAnteriorSolicitud + '-' + productosDeclarados);

		if(operacionCuarentena == 'Si'){//exite una operacion de cuarentena en el sitio
    		$(".noEditable").attr("disabled","disabled");
		}else{
			$(".noEditable").removeAttr("disabled");
		}
	 });

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	//--PESTAÑA 1 --
	
	$("#parroquiaF").change(function () {
        if ($("#parroquiaF option:selected").val() != "") {
            $("#parroquia").val($("#parroquiaF option:selected").text());
        }else{
        	$("#parroquia").val("");
        }
    });
	
	$("#formularioSitioArea").submit(function (event) {
		error = false;
		event.preventDefault();
		mostrarMensaje("", "FALLO");
	    $(".alertaCombo").removeClass("alertaCombo");

	    if($("#nombre_lugar").val() == "" || !esCampoValido("#nombre_lugar")){	
			error = true;		
			$("#nombre_lugar").addClass("alertaCombo");
		}
		
	    if($("#parroquia").val() == "" || !esCampoValido("#parroquia")){	
			error = true;		
			$("#parroquia").addClass("alertaCombo");
		}

	    if($("#parroquiaF").val() == "" || !esCampoValido("#parroquiaF")){	
			error = true;		
			$("#parroquiaF").addClass("alertaCombo");
		}

	    if($("#direccion").val() == "" || !esCampoValido("#direccion")){	
			error = true;		
			$("#direccion").addClass("alertaCombo");
		}

	    if($("#referencia").val() == "" || !esCampoValido("#referencia")){	
			error = true;		
			$("#referencia").addClass("alertaCombo");
		}

	    if($("#latitud").val() == "" || !esCampoValido("#latitud")){	
			error = true;		
			$("#latitud").addClass("alertaCombo");
		}

	    if($("#longitud").val() == "" || !esCampoValido("#longitud")){	
			error = true;		
			$("#longitud").addClass("alertaCombo");
		}

	    if($("#zona").val() == "" || !esCampoValido("#zona")){	
			error = true;		
			$("#zona").addClass("alertaCombo");
		}

		if($("#superficie_total").val() == "" || !esCampoValido("#superficie_total")){	
			error = true;		
			$("#superficie_total").addClass("alertaCombo");
		}

		if (!error) {
				var respuesta = JSON.parse(ejecutarJson($("#formularioSitioArea")).responseText);
				if (respuesta.estado == 'exito'){
					$("#estado").html(respuesta.mensaje);
    			}else{
    				$("#estado").html(respuesta.mensaje);
        		}
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
	
	
	
	
	
	
	
	//--PESTAÑA 2 --
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

		if(productosDeclarados == 'Si'){//if(estadoSolicitud != 'cargarProducto' && estadoAnteriorSolicitud != 'cargarProducto' ){
    		if(resultado == '' || check == ''){ 
    			alert("vacio");
    			error = true;
    			$("#listaOperaciones").addClass("alertaCombo");
    			texto = "Debe seleccionar los Items de las operaciones..!!";
    		}
		}else{
			alert("La solicitud no tiene productos declarados.");
		}
		
		if (!error) {
			$("#cargarMensajeTemporal").html("<div id='cargando' style='position :fixed'>Cargando...</div>").fadeIn();
			setTimeout(function(){
				var respuesta = JSON.parse(ejecutarJson($("#formulario")).responseText);
				if (respuesta.estado == 'exito'){
					$("#cargarMensajeTemporal").html('');
					$("#estado").html(respuesta.mensaje);
        			//abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
    			}else{
    				$("#cargarMensajeTemporal").html('');
    				$("#estado").html(respuesta.mensaje);
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
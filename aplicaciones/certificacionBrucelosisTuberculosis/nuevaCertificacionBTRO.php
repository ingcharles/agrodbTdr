<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	//require_once '../../clases/ControladorCatastroProducto.php';
	require_once '../../clases/ControladorBrucelosisTuberculosis.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCatalogos();
	//$cp = new ControladorCatastroProducto();
	$cbt = new ControladorBrucelosisTuberculosis();
	
	$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
	$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');
	
?>

<header>
	<h1>Predios para Certificación como Libres de Brucelosis y Tuberculosis Bovina</h1>
</header>

<div id="estado"></div>

<form id="nuevaCertificacionBT" data-rutaAplicacion="certificacionBrucelosisTuberculosis" data-opcion="guardarCertificacionBT" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">

	<fieldset>
		<legend>Búsqueda del Operador</legend>
		
			<div data-linea="0">
				<label>Identificación Operador: </label> 
				<input type="text" id="identificadorOperador" name="identificadorOperador" value=""  maxlength="13" />
				
				<input type="hidden" id="opcion" name="opcion" value="">
			</div>
			
			<div data-linea="0">
				<button type="button" id="buscarOperador" name="buscarOperador" >Buscar </button>
			</div>
			
			<p class="nota">Recuerde que solamente puede realizar el proceso de Certificación para Operadores Registrados en GUIA.</p>

	</fieldset>
		
	<div id="formulario">
	
	</div>
	
	<div id="localizacion">
	
	</div>
	
	
</form>


<script type="text/javascript">

var array_canton= <?php echo json_encode($cantones); ?>;
var array_parroquia= <?php echo json_encode($parroquias); ?>;

var array_canton2= <?php echo json_encode($cantones); ?>;
var array_parroquia2= <?php echo json_encode($parroquias); ?>;

	$("document").ready(function(){
		distribuirLineas();	
		construirValidador();

		$("#fecha").datepicker({
		      changeMonth: true,
		      changeYear: true
		});
		
	});
	
	//Validación y Guardado
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
	
	$("#nuevaCertificacionBT").submit(function(event){
		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#fecha").val())){
			error = true;
			$("#fecha").addClass("alertaCombo");
		}

		if(!$.trim($("#nombreEncuestado").val()) || !esCampoValido("#nombreEncuestado")){
			error = true;
			$("#nombreEncuestado").addClass("alertaCombo");
		}

		/*if(!$.trim($("#nombrePredio").val()) || !esCampoValido("#nombrePredio")){
			error = true;
			$("#nombrePredio").addClass("alertaCombo");
		}*/

		if(!$.trim($("#numCertFiebreAftosa").val()) || !esCampoValido("#numCertFiebreAftosa")){
			error = true;
			$("#numCertFiebreAftosa").addClass("alertaCombo");
		}

		if(!$.trim($("#certificacion").val())){
			error = true;
			$("#certificacion").addClass("alertaCombo");
		}

		/*if(!$.trim($("#nombrePropietario").val()) || !esCampoValido("#nombrePropietario")){
			error = true;
			$("#nombrePropietario").addClass("alertaCombo");
		}

		if(!$.trim($("#cedulaPropietario").val()) || !esCampoValido("#cedulaPropietario")){
			error = true;
			$("#cedulaPropietario").addClass("alertaCombo");
		}

		if(!$.trim($("#telefonoPropietario").val()) || !esCampoValido("#telefonoPropietario")){
			error = true;
			$("#telefonoPropietario").addClass("alertaCombo");
		}

		if(!$.trim($("#celularPropietario").val()) || !esCampoValido("#celularPropietario")){
			error = true;
			$("#celularPropietario").addClass("alertaCombo");
		}

		if(!$.trim($("#correoElectronicoPropietario").val()) || !esCampoValido("#correoElectronicoPropietario")){
			error = true;
			$("#correoElectronicoPropietario").addClass("alertaCombo");
		}

		if(!$.trim($("#provincia").val())){
			error = true;
			$("#provincia").addClass("alertaCombo");
		}

		if(!$.trim($("#canton").val())){
			error = true;
			$("#canton").addClass("alertaCombo");
		}

		if(!$.trim($("#parroquia").val())){
			error = true;
			$("#parroquia").addClass("alertaCombo");
		}*/

		if(!$.trim($("#x").val()) || !esCampoValido("#x")){
			error = true;
			$("#x").addClass("alertaCombo");
		}

		if(!$.trim($("#y").val()) || !esCampoValido("#y")){
			error = true;
			$("#y").addClass("alertaCombo");
		}

		if(!$.trim($("#z").val()) || !esCampoValido("#z")){
			error = true;
			$("#z").addClass("alertaCombo");
		}

		if(!$.trim($("#huso").val()) || !esCampoValido("#huso")){
			error = true;
			$("#huso").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			$('#nuevaCertificacionBT').attr('data-destino','detalleItem');
			$('#nuevaCertificacionBT').attr('data-opcion','guardarCertificacionBT');
			abrir($(this),event,false);
		}
	});
	
	
	//Mapa

	
	//Ubicación Provincia, Cantón, Parroquia, Oficina
	$("#provincia").change(function(event){
    	scanton ='0';
		scanton = '<option value="">Seleccione...</option>';
	    for(var i=0;i<array_canton.length;i++){
		    if ($("#provincia").val()==array_canton[i]['padre']){
		    	scanton += '<option data-latitud="'+array_canton[i]['latitud']+'"data-longitud="'+array_canton[i]['longitud']+'"data-zona="'+array_canton[i]['zona']+'" value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
			}
	   	}
	    $('#canton').html(scanton);
	    $("#canton").removeAttr("disabled");
	    $("#nombreProvincia").val($("#provincia option:selected").text());	
	});

    $("#canton").change(function(){
    	$("#nombreCanton").val($("#canton option:selected").text());
        
		sparroquia ='0';
		sparroquia = '<option value="">Seleccione...</option>';
	    for(var i=0;i<array_parroquia.length;i++){
		    if ($("#canton").val()==array_parroquia[i]['padre']){
		    	sparroquia += '<option value="'+array_parroquia[i]['codigo']+'" data-codigo="'+array_parroquia[i]['codigoProvincia']+'">'+array_parroquia[i]['nombre']+'</option>';
			    } 
	    	}

	    $('#parroquia').html(sparroquia);
		$("#parroquia").removeAttr("disabled");
	});

    $("#parroquia").change(function(){
    	$("#nombreParroquia").val($("#parroquia option:selected").text());
    	$("#codigoParroquia").val($("#parroquia option:selected").attr('data-codigo'));
	});

	//Validación de Información de Operador
	$("#buscarOperador").click(function(event){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#identificadorSolicitante").val()==""){	
			 error = true;		
			$("#identificadorSolicitante").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese el número de cédula para realizar la búsqueda.").addClass('alerta');
		}

		if (!error){
			$("#estado").html("").removeClass('alerta');
			$('#nuevaCertificacionBT').attr('data-destino','formulario');
			$('#nuevaCertificacionBT').attr('data-opcion','combosOperador');
		    $('#opcion').val('buscarOperador');		
			abrir($("#nuevaCertificacionBT"),event,false); 
		}
	});
</script>
<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();

$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');

?>
<header>
	<h1>Nuevo control de areteo</h1>
</header>

	<form id='nuevoControlAreteo' data-rutaAplicacion='vacunacionAnimal' data-opcion='guardarNuevoControlAreteo' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	
	<div id="estado"></div>

	<input type="hidden" id="identificador" name="identificador" value="<?php echo $_SESSION['usuario']?>" />
	<input type=hidden id="fecha" name="fecha" value="<?php echo $fecha;?>" />
		<fieldset>
			<legend>Control de areteo</legend>
				<div data-linea="1">
					<label>Ingrese el lugar (provincia - cantón) que no se va a aretear, para la vacunación animal</label>								
			    </div>
			    <div data-linea="2">
					<label>Provincia</label>				
					<select id="provincia" name="provincia">
						<option value="0">Seleccione....</option>
						<?php 
							$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
							foreach ($provincias as $provinciaArray){
								echo '<option value="' . $provinciaArray['codigo'] . '">' . $provinciaArray['nombre'] . '</option>';							
							}
						?>
					</select>
					<input type="hidden" id="nombreProvincia" name="nombreProvincia" />	 
			   </div>
			   <div data-linea="3">				
					<label>Cantón</label>
					<select id="canton" name="canton" disabled="disabled">
					</select>
					<input type="hidden" id="nombreCanton" name="nombreCanton" />	
			   </div>			   			   
			   <div data-linea="4">
				     <label>Observación</label> 
					 <input type="text" id="observacion" name="observacion" placeholder="Ej: Observación" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$" />
			   </div> 			   			   			 
		</fieldset>	
		<button type="submit" class="guardar">Guardar vacunador</button>

  </form>

<script type="text/javascript">	
			
    var array_canton= <?php echo json_encode($cantones); ?>;

    $(document).ready(function(){			
		distribuirLineas();		
	});

    $("#nuevoControlAreteo").submit(function(event){		
		event.preventDefault();
		abrir($(this),event,false);
	});
	
	$("#provincia").change(function(){
    	scanton ='0';
		scanton = '<option value="">Cantón...</option>';
	    for(var i=0;i<array_canton.length;i++){
		    if ($("#provincia").val()==array_canton[i]['padre']){
		    	scanton += '<option data-latitud="'+array_canton[i]['latitud']+'"data-longitud="'+array_canton[i]['longitud']+'"data-zona="'+array_canton[i]['zona']+'" value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
			}
	   	}
	    $('#canton').html(scanton);
	    $("#canton").removeAttr("disabled");

	    if ($("#provincia").val() != ''){
			$("#nombreProvincia").val($('#provincia option:selected').text());				
		}else{
			alert("Debe elegir la provincia de origen.");	
		}
	});
	
	$("#canton").change(function(){
	 	if ($("#canton").val() != ''){
			$("#nombreCanton").val($('#canton option:selected').text());				
		}else{
			alert("Debe elegir el cantón de origen.");	
		}
	});
	
	function chequearCamposGuardar(form){
		$("#estado").html("").addClass('correcto');
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false; 
        //campos 
      	
		if(!$.trim($("#provinciaTitular").val())){
			error = true;
			$("#provinciaTitular").addClass("alertaCombo");
		}

		if(!$.trim($("#apellidoTitular").val()) || !esCampoValido("#apellidoTitular")){
			error = true;
			$("#apellidoTitular").addClass("alertaCombo");
		}
		
		if (!error){
			return true;		
		}else{			
			$("#estado").html("Por favor revise el formato de la información ingresada").addClass('alerta');
			return false;
		}
		
	}
	
</script>
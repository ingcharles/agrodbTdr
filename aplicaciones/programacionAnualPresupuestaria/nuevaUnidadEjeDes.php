<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCatalogos();
	
	$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
?>

<header>
	<h1>Unidad Ejecutora - Desconcentrada</h1>
</header>

<form id="nuevaUnidadEjeDes" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="guardarNuevaUnidadEjeDes" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<div id="estado"></div>

	<fieldset>
		<legend>Unidad Ejecutora - Desconcentrada</legend>

		<div data-linea="1">
			<label>Nombre:</label>
			<input type="text" id="nombreUnidadEjeDes" name="nombreUnidadEjeDes" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
		</div>
		
		<div data-linea="2">
			<label>Código:</label>
			<input type="text" id="codigoUnidadEjeDes" name="codigoUnidadEjeDes" maxlength="8" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
		</div>
		
		<div data-linea="2">
			<label>Tipo:</label>
				<select id=tipo name="tipo" required="required" >
					<option value="">Seleccione....</option>
					<option value="ejecutora">Unidad Ejecutora</option>
					<option value="desconcentrada">Unidad Desconcentrada</option>
				</select>
		</div>
		
		<div data-linea="3">
			<label>Provincia:</label>
				<select id=provincia name="provincia" required="required">
					<option value="">Seleccione....</option>
					<?php 
						$provincias = $cc->listarLocalizacion($conexion, 'PROVINCIAS');
						
						while($fila = pg_fetch_assoc($provincias)){
							echo '<option value="' . $fila['id_localizacion'] . '" data-geografico="' . $fila['geografico_mfin'] . '">' . $fila['nombre'].' </option>';
						}
					?>
				</select>
				
				<input type='hidden' id='idLocalizacion' name='idLocalizacion' />
				<input type='hidden' id='codigoGeografico' name='codigoGeografico' />
		</div>
		
		<!-- 
		<div data-linea="3">
			<label>Provincia:</label>
				<select id=provincia name="provincia" required="required" >
					<option value="">Seleccione....</option>
					< ?php 
						$provincias = $cc->listarLocalizacion($conexion, 'PROVINCIAS');
						
						while($fila = pg_fetch_assoc($provincias)){
							echo '<option value="' . $fila['id_localizacion'] . '" data-codigo-geografico="' . $fila['geografico_mfin'] . '">' . $fila['nombre'].' </option>';
						}
					?>
				</select>
		</div>
		
		<div data-linea="3">
			<label>Cantón:</label>
				<select id=localizacion name="localizacion" required="required" disabled="disabled">
					<option value="">Seleccione....</option>
				</select>
				
				<input type='hidden' id='idLocalizacion' name='idLocalizacion' value='< ?php echo $objetivoEspecifico['id_area'];?>' />
				<input type='hidden' id='codigoGeografico' name='codigoGeografico' value='< ?php echo $objetivoEspecifico['nombre_area'];?>' />
		</div>
		
		 -->
		

	</fieldset>

	<button type="submit" class="guardar">Guardar</button>

</form>
<script type="text/javascript">
	var array_canton= <?php echo json_encode($cantones); ?>;
	var array_parroquia= <?php echo json_encode($parroquias); ?>;

	$(document).ready(function(){
		distribuirLineas();
		construirValidador();
	});	

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCampos(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#nombreUnidadEjeDes").val()) || !esCampoValido("#nombreUnidadEjeDes")){
			error = true;
			$("#nombreUnidadEjeDes").addClass("alertaCombo");
		}

		if(!$.trim($("#codigoUnidadEjeDes").val()) || !esCampoValido("#codigoUnidadEjeDes")){
			error = true;
			$("#codigoUnidadEjeDes").addClass("alertaCombo");
		}

		if(!$.trim($("#tipo").val())){
			error = true;
			$("#tipo").addClass("alertaCombo");
		}

		if(!$.trim($("#provincia").val())){
			error = true;
			$("#provincia").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
			$("#_actualizar").click();
		}
	}

	$("#nuevaUnidadEjeDes").submit(function(event){
		 event.preventDefault();
		 chequearCampos(this);
	});

	$("#provincia").change(function(event){
		$('#idLocalizacion').val($("#provincia option:selected").val());
    	$('#codigoGeografico').val($("#provincia option:selected ").attr('data-geografico'));
		
    	/*scanton ='0';
		scanton = '<option value="">Seleccione...</option>';
	    for(var i=0;i<array_canton.length;i++){
		    if ($("#provincia").val()==array_canton[i]['padre']){
		    	scanton += '<option value="'+array_canton[i]['codigo']+'" data-geografico="'+array_canton[i]['geografico']+'" >'+array_canton[i]['nombre']+'</option>';
			}
	   	}
	    $('#localizacion').html(scanton);
	    $("#localizacion").removeAttr("disabled");*/
	});
	/*
	$("#localizacion").change(function(event){
    	$('#idLocalizacion').val($("#localizacion option:selected").val());
    	$('#codigoGeografico').val($("#localizacion option:selected ").attr('data-geografico'));
	});*/
	
</script>
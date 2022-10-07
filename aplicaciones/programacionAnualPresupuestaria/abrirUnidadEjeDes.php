<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorProgramacionPresupuestaria.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCatalogos();
	$cpp = new ControladorProgramacionPresupuestaria();
	
	$unidadEjeDes = pg_fetch_assoc($cpp->abrirUnidadEjeDes($conexion, $_POST['id']));
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">	
	</head>
	
	<body>
		<header>
			<h1>Unidad Ejecutora - Desconcentrada</h1>
		</header>
		
		<form id="unidadEjeDes" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="modificarUnidadEjeDes" data-accionEnExito="ACTUALIZAR">
			<input type="hidden" id="idUnidadEjeDes" name="idUnidadEjeDes" value="<?php echo $unidadEjeDes['id_unidad_ejedes'];?>" />
			
			<p>
				<button id="modificar" type="button" class="editar">Modificar</button>
				<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
			</p>
			<div id="estado"></div>
			
			<fieldset>
				<legend>Unidad Ejecutora - Desconcentrada</legend>

				<div data-linea="1">
					<label>Nombre:</label>
					<input type="text" id="nombreUnidadEjeDes" name="nombreUnidadEjeDes" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $unidadEjeDes['nombre'];?>" disabled="disabled"/>
				</div>
				
				<div data-linea="2">
					<label>Código:</label>
					<input type="text" id="codigoUnidadEjeDes" name="codigoUnidadEjeDes" maxlength="8" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $unidadEjeDes['codigo'];?>" disabled="disabled"/>
				</div>
				<div data-linea="2">
					<label>Tipo:</label>
						<select id=tipo name="tipo" required="required" disabled="disabled">
							<option value="">Seleccione....</option>
							<option value="ejecutora">Unidad Ejecutora</option>
							<option value="desconcentrada">Unidad Desconcentrada</option>
						</select>
				</div>
				
				<div data-linea="3">
					<label>Provincia:</label>
						<select id=provincia name="provincia" required="required" disabled="disabled">
							<option value="">Seleccione....</option>
							<?php 
								$provincias = $cc->listarLocalizacion($conexion, 'PROVINCIAS');
								
								while($fila = pg_fetch_assoc($provincias)){
									echo '<option value="' . $fila['id_localizacion'] . '" data-geografico="' . $fila['geografico_mfin'] . '">' . $fila['nombre'].' </option>';
								}
							?>
						</select>
						
						<input type='hidden' id='idLocalizacion' name='idLocalizacion' value="<?php echo $unidadEjeDes['id_localizacion'];?>"/>
						<input type='hidden' id='codigoGeografico' name='codigoGeografico' value="<?php echo $unidadEjeDes['codigo_geografico'];?>"/>
				</div>
				
			</fieldset>
		
		</form>
	
	</body>

	<script type="text/javascript">
		
		$(document).ready(function(){
			cargarValorDefecto("tipo","<?php echo $unidadEjeDes['tipo'];?>");
			cargarValorDefecto("provincia","<?php echo $unidadEjeDes['id_localizacion'];?>");
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
			}
		}
	
		$("#modificar").click(function(){
			$("input").removeAttr("disabled");
			$("select").removeAttr("disabled");
			$("#actualizar").removeAttr("disabled");
			$(this).attr("disabled","disabled");			
		});
		
		$("#unidadEjeDes").submit(function(event){
			event.preventDefault();
		    chequearCampos(this);  	
		});

		$("#provincia").change(function(event){
			$('#idLocalizacion').val($("#provincia option:selected").val());
	    	$('#codigoGeografico').val($("#provincia option:selected ").attr('data-geografico'));
		});
	
	</script>
</html>
<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorProgramacionPresupuestaria.php';
	
	$conexion = new Conexion();
	$cpp = new ControladorProgramacionPresupuestaria();
	
	$unidadMedida = pg_fetch_assoc($cpp->abrirUnidadMedida($conexion, $_POST['id']));
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">	
	</head>
	
	<body>
		<header>
			<h1>Parámetros</h1>
		</header>
		
		<form id="unidadMedida" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="modificarUnidadMedida" data-accionEnExito="ACTUALIZAR">
			<input type="hidden" id="idUnidadMedida" name="idUnidadMedida" value="<?php echo $unidadMedida['id_unidad_medida'];?>" />
			
			<p>
				<button id="modificar" type="button" class="editar">Modificar</button>
				<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
			</p>
			<div id="estado"></div>
			
			<fieldset>
				<legend>Unidad Medida</legend>

				<div data-linea="1">
					<label>Nombre:</label>
					<input type="text" id="nombreUnidadMedida" name="nombreUnidadMedida" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $unidadMedida['nombre'];?>" disabled="disabled" />
				</div>
				
				<div data-linea="2">
					<label>Nomenclatura:</label>
					<input type="text" id="codigoUnidadMedida" name="codigoUnidadMedida" maxlength="8" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $unidadMedida['codigo'];?>" disabled="disabled" />
				</div>
				
			</fieldset>
		
		</form>
	
	</body>

	<script type="text/javascript">
		
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

			if(!$.trim($("#nombreUnidadMedida").val()) || !esCampoValido("#nombreUnidadMedida")){
				error = true;
				$("#nombreUnidadMedida").addClass("alertaCombo");
			}

			if(!$.trim($("#codigoUnidadMedida").val()) || !esCampoValido("#codigoUnidadMedida")){
				error = true;
				$("#codigoUnidadMedida").addClass("alertaCombo");
			}
			
			if (error){
				$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
			}else{
				ejecutarJson(form);
			}
		}
	
		$("#modificar").click(function(){
			$("input").removeAttr("disabled");
			$("#actualizar").removeAttr("disabled");
			$(this).attr("disabled","disabled");			
		});
		
		$("#unidadMedida").submit(function(event){
			event.preventDefault();
		    chequearCampos(this);  	
		});
	
	</script>
</html>
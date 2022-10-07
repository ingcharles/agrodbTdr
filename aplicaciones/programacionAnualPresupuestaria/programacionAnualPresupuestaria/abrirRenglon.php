<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorProgramacionPresupuestaria.php';
	
	$conexion = new Conexion();
	$cpp = new ControladorProgramacionPresupuestaria();
	
	$renglon = pg_fetch_assoc($cpp->abrirRenglon($conexion, $_POST['id']));
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">	
	</head>
	
	<body>
		<header>
			<h1>Renglón - Partida Presupuestaria</h1>
		</header>
		
		<form id="renglon" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="modificarRenglon" data-accionEnExito="ACTUALIZAR">
			<input type="hidden" id="idRenglon" name="idRenglon" value="<?php echo $renglon['id_renglon'];?>" />
			
			<p>
				<button id="modificar" type="button" class="editar">Modificar</button>
				<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
			</p>
			<div id="estado"></div>
			
			<fieldset>
				<legend>Renglo</legend>

				<div data-linea="1">
					<label>Nombre:</label>
					<input type="text" id="nombreRenglon" name="nombreRenglon" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $renglon['nombre'];?>" disabled="disabled" />
				</div>
				
				<div data-linea="2">
					<label>Ítem Presupuestario:</label>
					<input type="text" id="codigoRenglon" name="codigoRenglon" maxlength="16" data-er="^[0-9]+$" value="<?php echo $renglon['codigo'];?>" disabled="disabled" />
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

			if(!$.trim($("#nombreRenglon").val()) || !esCampoValido("#nombreRenglon")){
				error = true;
				$("#nombreRenglon").addClass("alertaCombo");
			}

			if(!$.trim($("#codigoRenglon").val()) || !esCampoValido("#codigoRenglon")){
				error = true;
				$("#codigoRenglon").addClass("alertaCombo");
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
		
		$("#renglon").submit(function(event){
			event.preventDefault();
		    chequearCampos(this);  	
		});
	
	</script>
</html>